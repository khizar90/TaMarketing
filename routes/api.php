<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/verify' ,[ AuthController::class, 'verify']);
Route::post('otp/verify' ,[ AuthController::class, 'otpVerify']);
Route::post('user/register' ,[ AuthController::class, 'register']);
Route::post('user/login' ,[ AuthController::class, 'login']);
Route::post('user/recover' ,[ AuthController::class, 'recover']);
Route::post('user/new/password' ,[ AuthController::class, 'newPassword']);
Route::post('user/logout' ,[ AuthController::class, 'logout']);
Route::post('user/delete' ,[ AuthController::class, 'deleteAccount']);
Route::post('change/password' , [AuthController::class , 'changePassword']);
Route::post('edit/image' , [AuthController::class , 'editImage']);
Route::get('remove/image/{id}' , [AuthController::class , 'removeImage']);
Route::post('edit/profile', [AuthController::class, 'editProfile']);


Route::get('order/question', [OrderController::class, 'question']);
Route::post('order/create', [OrderController::class, 'create']);
Route::get('orders/{status}/{user_id}', [OrderController::class, 'list']);
Route::post('order/cancel/', [OrderController::class, 'cancel']);
Route::get('order/detail/{order_id}', [OrderController::class, 'detail']);
Route::get('order/complete/{order_id}', [OrderController::class, 'complete']);
Route::get('order/chat/{user_id}', [OrderController::class, 'ordersChat']);
Route::get('order/list/messages/{order_id}', [OrderController::class, 'conversation']);
Route::post('order/send/message', [OrderController::class, 'sendMessage']);

Route::get('home/{user_id}', [UserController::class, 'home']);
Route::get('counter/{user_id}', [UserController::class, 'counter']);
Route::get('user/notification/{user_id}', [UserController::class, 'notification']);


Route::post('create/payment', [PaymentController::class, 'create']);
Route::post('payment/intent' , [PaymentController::class , 'createIntent']);



Route::get('faqs' , [SettingController::class , 'faqs']);
