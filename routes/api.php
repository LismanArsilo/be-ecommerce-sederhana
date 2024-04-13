<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'checkTokenValidity', 'checkRolePermission'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/v1/user', 'getAllUser');
        Route::get('/v1/user/{id}', 'getOneUser');
        Route::put('/v1/user/{id}', 'updateUser');
        Route::delete('/v1/user/{id}', 'deleteUser');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/v1/category', 'getAllCategory');
        Route::get('/v1/category/{id}', 'getOneCategory');
        Route::post('/v1/category/', 'createCategory');
        Route::delete('/v1/category/{id}', 'deleteCategory');
    });
    Route::controller(ProductController::class)->group(
        function () {
            Route::get('/v1/product', 'getAllProduct');
            Route::get('/v1/product/{id}', 'getOneProduct');
            Route::post('/v1/product', 'createProduct');
            Route::delete('/v1/product/{id}', 'deleteProduct');
        }
    );
    Route::controller(SizeController::class)->group(function () {
        Route::get('/v1/size', 'getAllSize');
        Route::get('/v1/size/{id}', 'getOneSize');
        Route::post('/v1/size', 'createSize');
        Route::delete('/v1/size/{id}', 'deleteSize');
        Route::get('/v1/list/size', 'getListSize');
    });
    Route::controller(ColorController::class)->group(function () {
        Route::get('/v1/color', 'getAllColor');
        Route::get('/v1/color/{id}', 'getOneColor');
        Route::post('/v1/color', 'createColor');
        Route::delete('/v1/color/{id}', 'deleteColor');
        Route::get('/v1/list/color', 'getListColor');
    });
    Route::controller(ImageController::class)->group(function () {
        Route::post('/v1/upload/images/{id}', 'createImageProduct');
    });
});

Route::middleware(['auth:sanctum', 'checkTokenValidity'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/v1/auth/logout', 'logoutUser');
    });
    Route::controller(CartController::class)->group(function () {
        Route::get('/user/v1/cart/{userId}', 'getOneCart');
        Route::get('/user/v1/cart/items/count/{userId}', 'countCartItems');
        Route::post('/user/v1/cart/items/{userId}', 'addProductToCart');
        Route::get('/user/v1/cart/items/{userId}', 'getAllCartItems');
        Route::delete('/user/v1/cart/items/{id}', 'deleteProductCart');
        Route::put('/user/v1/cart/items/qty/{id}', 'updateQtyOrderItem');
        Route::put('/user/v1/cart/items/status/{id}', 'updateStatusOrderItem');
    });
});

Route::middleware(['checkTokenValidity'])->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('/user/v1/product', 'getAllProductUser');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/user/v1/category', 'getAllCategoryUser');
    });
    Route::controller(AuthController::class)->group(function () {
        Route::post('/v1/auth/register', 'registerUser');
        Route::post('/v1/auth/login', 'loginUser');
        Route::get('/v1/auth/check-token', 'checkTokenSession');
    });
});


// Middleware fallback untuk menangani rute yang tidak ditemukan
Route::get('/Unauthorized', function () {
    return response()->json(['status' => false, 'message' => 'Unauthenticated cuy'], Response::HTTP_UNAUTHORIZED);
})->name('unauthenticated');
Route::get('/validate', function () {
    return response()->json(['status' => false, 'message' => 'Your Token Not Falid Please Login !!!'], Response::HTTP_UNAUTHORIZED);
})->name('validate');
