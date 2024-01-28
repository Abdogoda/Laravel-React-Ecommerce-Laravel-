<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller{

    public function index(){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cart_products = Cart::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 200,
                'cart_products' => $cart_products,
                'message' => 'All Cart Products Available',
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'You Have To Log In First!',
            ]);
        }
    }

    public function addToCart(Request $request){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;
            $product = Product::find($product_id);
            if($product){
                $check_cart = Cart::where('product_id', $product_id)->where('user_id', $user_id)->first();
                if($check_cart){
                    if($check_cart->product_qty != $product_qty){
                        $check_cart->product_qty = $product_qty;
                        $check_cart->save();
                    }
                    return response()->json([
                        'status' => 409,
                        'message' => $product->name.' Already Exists In Cart!',
                    ]);
                    }else{
                    $cart = new Cart();
                    $cart->user_id = $user_id;
                    $cart->product_id = $product_id;
                    $cart->product_qty = $request->product_qty;
                    $cart->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Product Added To Cart Successfully',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found!',
                ]);
            }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'You Have To Log In First!',
            ]);
        }
    }

    public function updateProductQuantity($id, $scope){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $check_cart = Cart::where('id', $id)->where('user_id', $user_id)->first();
            if($check_cart){
                if($scope === 'inc'){
                    $check_cart->product_qty += 1;
                }else{
                    $check_cart->product_qty -= 1;
                }
                $check_cart->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Quantity Updated Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found!',
                ]);
            }
            }else{
                return response()->json([
                    'status' => 401,
                    'message' => 'You Have To Log In First!',
                ]);
            }
    }

    public function deleteCartProduct($id){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $check_cart = Cart::where('id', $id)->where('user_id', $user_id)->first();
            if($check_cart){
                $check_cart->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Deleted Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found!',
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