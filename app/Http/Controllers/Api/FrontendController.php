<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller{
    public function categories(){
        $categories = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'categories' => $categories,
            'message' => 'All Available Categories'
        ],200);
    }
    public function products(){
        $products = Product::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'products' => $products,
            'message' => 'All Available Products'
        ],200);
    }
    public function categoryProducts($categorySlug){
        $category = Category::where('slug', $categorySlug)->where('status','0')->first();
        if($category){
            $products = Product::where('category_id', $category->id)->get();
            return response()->json([
                'status' => 200,
                'category' => $category,
                'products' => $products,
                'message' => "All Available Products For Category $category->name"
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found'
            ]);

        }
    }
    public function product($categorySlug,$productSlug){
        $category = Category::where('slug', $categorySlug)->where('status','0')->first();
        $product = Product::where('category_id', $category->id)->where('slug', $productSlug)->where('status','0')->first();
        if($category){
            if($product){
                return response()->json([
                    'status' => 200,
                    'category' => $category,
                    'product' => $product,
                    'message' => "Product Found"
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found'
                ]);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found'
            ]);

        }
    }

    public function homePage() {
        $data = [];
        $data['collections'] = Category::where('status', '0')->get();
        $data['products'] = Product::where('status', '0')->get()->take(15);
        $data['featured'] = Product::where('status', '0')->where('featured','1')->get()->take(3);
        $data['popular'] = Product::where('status', '0')->where('popular','1')->get()->take(3);
        return response()->json([
            'status' => 200,
            'data' => $data,
            'message' => 'All Available Data For Homepage'
        ],200);
    }


    public function sendMessage(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'email'=>'required|email|max:191',
            'phone'=>'required|max:11',
            'message'=>'required'
        ]);
        if ($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }else{
            $message = new Message();
            $message->name = $request->name;
            $message->email = $request->email;
            $message->phone = $request->phone;
            $message->message = $request->message;
            $message->save();
            return response()->json([
                'status' => 200,
                'message' => 'Your Message Sent Successfully'
            ], 200); 
        }
    }
}