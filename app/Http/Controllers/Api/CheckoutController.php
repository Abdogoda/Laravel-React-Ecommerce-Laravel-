<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller{
    public function placeOrder(Request $request){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cart_products = Cart::where('user_id', $user_id)->get();
            if($cart_products){
                $validator = Validator::make($request->all(),[
                    'first_name' => 'required|min:3|max:50',
                    'last_name' => 'required|min:3|max:50',
                    'email' => 'required|email|min:3|max:50',
                    'phone' => 'required|min:10|max:50',
                    'address' => 'required|min:3|max:50',
                    'state' => 'required|min:3|max:50',
                    'city' => 'required|min:3|max:50',
                    'zipcode' => 'required|min:4|max:50',
                ]);
                if ($validator->fails()){
                    return response()->json([
                        'status' => 422,
                        'errors' => $validator->messages()
                    ]);
                }else{
                    DB::beginTransaction();
                    try {
                        $order = new Order();
                        $order->user_id = $user_id; 
                        $order->first_name = $request->first_name; 
                        $order->last_name = $request->last_name; 
                        $order->phone = $request->phone; 
                        $order->email = $request->email; 
                        $order->address = $request->address; 
                        $order->city = $request->city; 
                        $order->state = $request->state; 
                        $order->zipcode = $request->zipcode; 
                        $order->payment_mode = $request->payment_mode; 
                        $order->tracking_no = 'ecommerce'.rand(1111,9999);
                        $order->save(); 

                        $cartItems = [];
                        foreach ($cart_products as $item) {
                            $cartItems[] = [
                                'product_id' => $item->product_id,
                                'qty' => $item->product_qty > $item->product->qty ? $item->product->qty : $item->product_qty,
                                'price' => $item->product->selling_price,
                            ];
                            $item->product->update([
                                'qty' => $item->product_qty > $item->product->qty ? 0 : $item->product->qty - $item->product_qty 
                            ]);
                        }
                        $order->orderItems()->createMany($cartItems);
                        Cart::destroy($cart_products);
                        DB::commit();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Your Order has been placed successfully✅'
                        ]);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 404,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'No Products Found In Cart!',
                ]);
            }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'You Have To Log In First!',
            ]);
        }
    }
    public function validateOrder(Request $request){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cart_products = Cart::where('user_id', $user_id)->get();
            if($cart_products){
                $validator = Validator::make($request->all(),[
                    'first_name' => 'required|min:3|max:50',
                    'last_name' => 'required|min:3|max:50',
                    'email' => 'required|email|min:3|max:50',
                    'phone' => 'required|min:10|max:50',
                    'address' => 'required|min:3|max:50',
                    'state' => 'required|min:3|max:50',
                    'city' => 'required|min:3|max:50',
                    'zipcode' => 'required|min:4|max:50',
                ]);
                if ($validator->fails()){
                    return response()->json([
                        'status' => 422,
                        'errors' => $validator->messages()
                    ]);
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' => 'Your Order has been validated successfully✅'
                    ],200);
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'No Products Found In Cart!',
                ]);
            }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'You Have To Log In First!',
            ]);
        }
    }
}