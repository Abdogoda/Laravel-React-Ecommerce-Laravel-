<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $data = [];
        $users = User::where('role_as',0)->get();
        $admins = User::where('role_as',1)->get();
        $categories = Category::get();
        $products = Product::get();
        $orders = Order::get();
        $data['statistics'] = [
            'users' => $users->count(),
            'categories' => $categories->count(),
            'products' => $products->count(),
            'orders' => $orders->count()
        ];
        // $data['users'] ;
        // $data['admins'] ;
        // $data['categories'] ;
        // $data['products'] ;
        // $data['orders'];
        return response()->json([
            'status'=>200,
            'message'=>'Dashboard Data Loaded Successfully',
            'data'=>$data
        ],200);
    }
}