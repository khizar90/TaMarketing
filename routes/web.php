<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/insert', function () {
    $user = new Admin();
    $user->name = 'Kevin Anderson';
    $user->email = 'admin@admin.com';
    $user->password = Hash::make('qweqwe');
    $user->save();
});
Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')->middleware(['auth'])->name('dashboard-')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('users', [AdminController::class, 'users'])->name('users');
    Route::get('users/export', [AdminController::class, 'exportCSV'])->name('users-export-csv');
    Route::get('verify-users', [AdminController::class, 'verifyUsers'])->name('verify-users');
    Route::get('get-verify/{id}', [AdminController::class, 'getVerify'])->name('get-verify');
    Route::get('users/delete/{id}', [AdminController::class, 'usersDelete'])->name('user-delete');

    Route::prefix('question')->name('question-')->group(function () {
        Route::get('/', [FormController::class, 'questions']);
        Route::post('add', [FormController::class, 'addQuestion'])->name('add');
        Route::post('edit/{id}', [FormController::class, 'editQuestion'])->name('edit');
        Route::get('delete-faq/{id}', [FormController::class, 'deleteQuestion'])->name('delete');
        Route::post('options/{id}', [FormController::class, 'addOptions'])->name('options');
    });

    Route::prefix('order')->name('order-')->group(function () {
        Route::get('/{status}', [AdminOrderController::class, 'list']);
        Route::post('change/status/{order_id}/{status}', [AdminOrderController::class, 'changeStatus'])->name('change-status');
        Route::get('detail/{status}/{order_id}', [AdminOrderController::class, 'detail'])->name('detail');
        Route::get('conversation/{order_id}', [AdminOrderController::class, 'conversation'])->name('conversation');
        Route::post('send/message', [AdminOrderController::class, 'sendMessage'])->name('send-message');
    });




    Route::prefix('faqs')->name('faqs-')->group(function () {
        Route::get('/', [AdminController::class, 'faqs']);
        Route::post('add', [AdminController::class, 'addFaq'])->name('add');
        Route::post('edit/{id}', [AdminController::class, 'editFaq'])->name('edit');
        Route::get('delete-faq/{id}', [AdminController::class, 'deleteFaq'])->name('delete');
    });

    Route::prefix('venmo')->name('venmo-')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'venmo']);
        Route::post('add', [AdminPaymentController::class, 'addVenmo'])->name('add');
        Route::post('edit/{id}', [AdminPaymentController::class, 'editVenmo'])->name('edit');
        Route::get('delete-venmo/{id}', [AdminPaymentController::class, 'deleteVenmo'])->name('delete');     
    });
   

    Route::prefix('zelle')->name('zelle-')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'zelle']);
        Route::post('add', [AdminPaymentController::class, 'addZelle'])->name('add');
        Route::post('edit/{id}', [AdminPaymentController::class, 'editZelle'])->name('edit');
        Route::get('delete-zelle/{id}', [AdminPaymentController::class, 'deleteZelle'])->name('delete'); 
        
    });


    // Route::prefix('ticket')->name('ticket-')->group(function () {
    //     Route::get('ticket/{status}', [AdminTicketController::class, 'ticket'])->name('ticket');
    //     Route::get('close-ticket/{id}', [AdminTicketController::class, 'closeTicket'])->name('close-ticket');
    //     Route::get('messages/{from_to}', [AdminTicketController::class, 'messages'])->name('messages');
    //     Route::post('send-message', [AdminTicketController::class, 'sendMessage'])->name('send-message');
    // });

    // Route::prefix('category')->name('category-')->group(function () {
    //     Route::get('/{type}', [CategoryController::class, 'list']);
    //     Route::post('/add', [CategoryController::class, 'add'])->name('add');
    //     Route::post('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    //     Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    // });

    Route::get('send-notification', [AdminController::class, 'createSendNotification'])->name('send-notification');
    Route::post('send-notification', [AdminController::class, 'sendNotification'])->name('create-notification');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
