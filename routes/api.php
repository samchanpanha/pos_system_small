<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //PRODUCTS

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::get('/search/{name}', [ProductController::class, 'search']);
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/upload', [ProductController::class, 'upload']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::post('/show-products',[ProductController::class,'getProductsByCategory']);
    });

    //CATEGORY
    Route::group(['prefix' => 'categories'],function (){
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::get('/search/{name}', [CategoryController::class, 'search']);
    });


    //RATE
    Route::group(['prefix' => 'rates'],function (){
        Route::get('/', [RateController::class,'index']);
    });

    //INVOICE
    Route::group(['prefix' => 'invoices'],function (){
        Route::get('/', [InvoiceController::class,'index']);
        Route::get('/{id}', [InvoiceController::class, 'show']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::put('/{id}', [InvoiceController::class, 'update']);
        Route::delete('/{id}', [InvoiceController::class, 'destroy']);
        Route::get('/search/{name}', [InvoiceController::class, 'search']);
    });

    //INVOICE
    Route::group(['prefix' => 'invoice-details'],function (){
        Route::get('/', [InvoiceDetailsController::class,'index']);
        Route::get('/{id}', [InvoiceDetailsController::class, 'show']);
        Route::post('/', [InvoiceDetailsController::class, 'store']);
        Route::put('/{id}', [InvoiceDetailsController::class, 'update']);
        Route::delete('/{id}', [InvoiceDetailsController::class, 'destroy']);
        Route::get('/search/{name}', [InvoiceDetailsController::class, 'search']);
    });



    //STAFF
    Route::group(['prefix' => 'staff'],function () {
        Route::get('/',[\App\Http\Controllers\StaffController::class,'index']);
        Route::get('/{id}', [AuthController::class, 'show']);
        Route::post('/{id}', [AuthController::class, 'update_user']);
        Route::delete('/{id}', [AuthController::class, 'destroy']);
    });


});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
