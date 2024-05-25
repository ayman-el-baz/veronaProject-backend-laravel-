<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// route Client Auth
Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'delete']);
Route::post('/login', [ClientController::class, 'login']);
Route::post('/logout', [ClientController::class, 'logout']);
Route::post('/forgetPassword',[ClientController::class, 'ForgetPassword']);
Route::post('/clients/{id}/upload-photo', [ClientController::class, 'uploadPhoto']);



// route category crud
Route::get('allCategory',[CategoryController::class, 'AllCategory']);
Route::get('category/{id}',[CategoryController::class, 'show']);
Route::post('addCategory',[CategoryController::class, 'AddCategory']);
Route::delete('deleteCategory/{id}',[CategoryController::class, 'DeleteCategory']);


// route products crud
Route::get('allProduct', [ProductController::class, 'AllProduct']);
Route::post('addProduct', [ProductController::class, 'AddProduct']); 
Route::get('products/{param}', [ProductController::class, 'show']); 
Route::get('promotionalProducts', [ProductController::class, 'PromotionalProducts']);
// http://127.0.0.1:8000/products/1684160658.jpg
Route::delete('deleteProduct/{id}',[ProductController::class, 'DeleteProduct']);
Route::get('editProduct/{id}',[ProductController::class, 'EditProduct']);
Route::post('updateProduct/{id}',[ProductController::class, 'UpdateProduct']);
Route::get('productCount', [ProductController::class, 'ProductCount']);



// route Client Auth
// Route::post('register',[ClientController::class, 'Register']);
// Route::post('login',[ClientController::class, 'Login']);
// Route::post('forgetPassword',[ClientController::class, 'ForgetPassword']);
// Route::post('verifyCode',[ClientController::class, 'VerifyCode']);


Route::get('/admin', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
