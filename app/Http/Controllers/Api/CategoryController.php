<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller{
    
    public function index(){
        $categories = Category::all();
        return response()->json([
            'status' => 200, 
            'categories' => $categories,
            'message' => 'All Available Categories'
        ],200);
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|min:3|max:191',
            'name' => 'required|min:3|max:191',
            'slug' => 'required|min:3|max:191'
        ]);
        if ($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }else{
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->description = $request->description;
            $category->meta_description = $request->meta_description;
            $category->meta_keywords = $request->meta_keywords;
            $category->meta_title = $request->meta_title;
            $category->status = $request->status ? 1 : 0;
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfully'
            ], 200); 
        }
    }
    
    public function edit(int $id){
        $category = Category::find($id);
        if($category){
            return response()->json([
                'status' => 200, 
                'category' => $category,
                'message' => 'Category Found'
            ],200);
        }else{
            return response()->json([
                'status' => 404, 
                'message' => 'Category Not Found'
            ],404);

        }
    }
    
    public function update(Request $request, int $id){
        $category = Category::find($id);
        if($category){
            $validator = Validator::make($request->all(), [
                'meta_title' => 'required|min:3|max:191',
                'name' => 'required|min:3|max:191',
                'slug' => 'required|min:3|max:191'
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages()
                ]);
            }else{
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->description = $request->description;
                $category->meta_description = $request->meta_description;
                $category->meta_keywords = $request->meta_keywords;
                $category->meta_title = $request->meta_title;
                $category->status = $request->status ? 1 : 0;
                $category->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Category Updated Successfully'
                ], 200); 
            }
        }else{
            return response()->json([
                'status' => 404, 
                'message' => 'Category Not Found'
            ],404);

        }
    }
    
    public function delete(int $id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'status' => 200, 
                'message' => 'Category Deleted Successfully'
            ],200);
        }else{
            return response()->json([
                'status' => 404, 
                'message' => 'Category Not Found'
            ],404);
    
        }
    }
}