<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller{
    public function getUserInfo(){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $user = User::find($user_id);
            if($user){
                $orders = Order::select('orders.id','orders.tracking_no','orders.payment_mode', 'orders.user_id', DB::raw('SUM(order_items.price * order_items.qty) as total_order_products'))
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.user_id', '=', $user_id)
                ->groupBy('orders.id','orders.tracking_no','orders.payment_mode', 'orders.user_id') 
                ->get();
                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'orders' => $orders,
                    'message' => 'User Found Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'User Not Found!',
                ]);
            }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'You Have To Log In First!',
            ]);
        }
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages()
            ]);
        }else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $token = $user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Registration successful'
            ]);

        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:191',
            'password' => 'required|min:6'
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages()
            ]);
        }else{
            $user = User::where('email',$request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message'=>'Invalid Email Or Password'
                ]);
            }else{
                if($user->role_as == 1){//admin
                    $token = $user->createToken($user->email.'_Token',['server:admin'])->plainTextToken;
                }else{//user
                    $token = $user->createToken($user->email.'_AdminToken', [''])->plainTextToken;
                }
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'token' => $token,
                    'role_as'=>$user->role_as,
                    'message' => 'Logged In successfully'
                ]);
            }
        }
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'You have been logged out successfully'
        ]);
    }
}