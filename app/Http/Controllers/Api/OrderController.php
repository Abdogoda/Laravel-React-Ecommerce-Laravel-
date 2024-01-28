<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders(){
        $orders = Order::all();
        if(!$orders){
            return response()->json([
                'status' => 404,
                'message' => 'Order not found'
            ]);
        }else{
            return response()->json([
                'status' => 200,
                'orders' => $orders,
                'message' => 'All Available Orders'
            ]);
        }
    }
    public function getOrderDetails($id){
        $order = Order::find($id);
        if(!$order){
            return response()->json([
                'status' => 404,
                'message' => 'Order not found'
            ]);
        }else{
            $data = $order;
            return response()->json([
                'status' => 200,
                'order' => $data,
                'message' => 'Order Found'
            ]);
        }
    }
}