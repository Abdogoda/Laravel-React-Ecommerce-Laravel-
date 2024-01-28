<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products, 
            'message' => 'All Available Products'
        ],200);
    }
    public function search($search){
        $products = Product::where('slug','LIKE','%'.$search."%")->where('name','LIKE','%'.$search."%")->get();
        return response()->json([
            'status' => 200,
            'products' => $products, 
            'message' => 'All Available Products'
        ],200);
    }
    
    public function store(Request $request){
        $validated = Validator::make($request->all(),[
            'category_id' => 'required',
            'slug' => 'required|min:3|max:191',
            'name' => 'required|min:3|max:191',
            'meta_title' => 'required|min:3|max:191',
            'qty' => 'required|integer|min:1',
            'selling_price' => 'required',
            'original_price' => 'required',
            'brand' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png'
        ]);
        if($validated->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validated->messages(),
            ]);
        }else{
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->brand = $request->brand;
            $product->description = $request->description;
            $product->selling_price = $request->selling_price;
            $product->original_price = $request->original_price;
            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_keywords = $request->meta_keywords;
            $product->qty = $request->qty;
            $product->featured = $request->featured;
            $product->status = $request->status;
            $product->popular = $request->popular;
            if($request->has('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('/uploads/products/'), $filename);
                $product->image = '/uploads/products/'. $filename;
            }
            $product->save();
            return response()->json([
                'status' => 200,
                'message' => 'Product Added Successfully'
            ],200);
        }
    }
    public function edit(int $id) {
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status' => 200,
                'product' => $product, 
                'message' => 'Product Found'
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found!'
            ],404);
        }
    }

    public function update(Request $request, int $id) {
        $product = Product::find($id);
        if($product){
            $validated = Validator::make($request->all(),[
                'category_id' => 'required',
                'slug' => 'required|min:3|max:191',
                'name' => 'required|min:3|max:191',
                'meta_title' => 'required|min:3|max:191',
                'qty' => 'required|integer',
                'selling_price' => 'required',
                'original_price' => 'required',
                'brand' => 'required',
                'image' => 'nullable|image|mimes:jpeg,jpg,png'
            ]);
            if($validated->fails()){
                return response()->json([
                    'status' => 422,
                    'errors' => $validated->messages(),
                ]);
            }else{
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->slug = $request->slug;
                $product->brand = $request->brand;
                $product->selling_price = $request->selling_price;
                $product->original_price = $request->original_price;
                $product->meta_title = $request->meta_title;
                $product->meta_description = $request->meta_description;
                $product->meta_keywords = $request->meta_keywords;
                $product->qty = $request->qty;
                $product->featured = $request->featured;
                $product->status = $request->status;
                $product->popular = $request->popular;
                if($request->hasFile('image')){
                    if($product->image){
                        if(File::exists($product->image)) {
                            File::delete($product->image);
                        }
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move(public_path('/uploads/products/'), $filename);
                    $product->image = '/uploads/products/'. $filename;
                }
                $product->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Updated Successfully'
                ],200);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found!'
            ],404);
        }
    }

    public function delete(int $id) {
        $product = Product::find($id);
        if($product){
            if(File::exists($product->image)) {
                File::delete($product->image);
            }
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product Deleted Successfully'
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found!'
            ],404);
        }
    }
}