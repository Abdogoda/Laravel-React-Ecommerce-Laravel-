<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/front-homepage', [FrontendController::class, 'homePage']);
Route::get('/front-get-categories', [FrontendController::class, 'categories']);
Route::get('/front-get-products', [FrontendController::class, 'products']);
Route::get('/front-get-category-products/{categorySlug}', [FrontendController::class, 'categoryProducts']);
Route::get('/front-get-product/{categorySlug}/{productSlug}', [FrontendController::class, 'product']);
Route::post('/front-send-message', [FrontendController::class, 'sendMessage']);


// UserRoutes
Route::middleware(['auth:sanctum'])->group(function(){
    
    // cart routes
    Route::get('/front-get-cart-products', [CartController::class, 'index']);
    Route::post('/front-add-to-cart', [CartController::class, 'addToCart']);
    Route::put('/front-update-cart-product-quantity/{id}/{scope}', [CartController::class, 'updateProductQuantity']);
    Route::delete('/front-delete-cart-product/{id}', [CartController::class, 'deleteCartProduct']);
    
    // place order route
    Route::post('front-place-order', [CheckoutController::class, 'placeOrder']);
    Route::post('front-validate-order', [CheckoutController::class, 'validateOrder']);
    
    // profile route
    Route::get('get-user-info', [AuthController::class, 'getUserInfo']);

    // logout route
    Route::post('logout', [AuthController::class, 'logout']);
});


// AdminRoutes
Route::middleware(['auth:sanctum','isAdmin'])->group(function(){

    // check admin auth route
    Route::get('/checkAuth', function(){
        return response()->json(['message'=>'You are in', 'status'=>200],200);
    });

    
    // category routes
    Route::get('/get-categories', [CategoryController::class, 'index']);
    Route::post('/add-category', [CategoryController::class, 'store']);
    Route::get('/get-category/{id}', [CategoryController::class, 'edit']);
    Route::post('/update-category/{id}', [CategoryController::class, 'update']);
    Route::post('/delete-category/{id}', [CategoryController::class, 'delete']);
    
    // products routes
    Route::get('/get-products', [ProductController::class, 'index']);
    Route::get('/get-search-products/{search}', [ProductController::class, 'search']);
    Route::get('/get-product/{id}', [ProductController::class, 'edit']);
    Route::post('/add-product', [ProductController::class, 'store']);
    Route::post('/update-product/{id}', [ProductController::class, 'update']);
    Route::post('/delete-product/{id}', [ProductController::class, 'delete']);
    
    // orders routes
    Route::get('/get-orders', [OrderController::class, 'getOrders']);
    Route::get('/get-order-details/{id}', [OrderController::class, 'getOrderDetails']);
    
    // dashboard routes
    Route::get('/get-dashboard-data', [DashboardController::class, 'index']);
});



Route::post('/add-test-image', function(Request $request){
    if($request->has('image')){
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('/uploads/test/'), $filename);
        return response()->json([
            'status' => 200,
            'image_path' => 'http://localhost:8000/uploads/test/'.$filename,
            'message' => 'Image Uploaded Successfully'
        ],200);
    }else{
        return response()->json([
            'status' => 404,
            'message' => 'No Image Uploaded!'
        ]);
    }
});