<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RobuxPackageController;
use App\Http\Controllers\Admin\RobuxTransactionController;
use App\Http\Controllers\UserGuest\DashboardGuestController;
use App\Http\Controllers\UserGuest\MbohController;
use App\Http\Controllers\Api\RobuxController;
use App\Http\Middleware\AdminMiddleware;

// Guest Routes (dapat diakses semua orang)
Route::get('/', [DashboardGuestController::class, 'index'])->name('guest.index');

// User Routes (harus login)
Route::middleware(['auth'])->group(function () {
    // Dashboard user setelah login
    Route::get('/dashboard', [DashboardGuestController::class, 'index'])->name('dashboard');
    
    // Robux Routes untuk user
    Route::prefix('robux')->name('robux.')->group(function () {
        Route::get('/', [MbohController::class, 'index'])->name('index');
        Route::post('/purchase', [MbohController::class, 'purchase'])->name('purchase');
        Route::get('/payment/{transaction:transaction_code}', [MbohController::class, 'payment'])->name('payment');
        Route::post('/confirm-payment/{transaction}', [MbohController::class, 'confirmPayment'])->name('confirm-payment');
        Route::get('/status/{transaction}', [MbohController::class, 'status'])->name('status');
        Route::get('/get-price', [MbohController::class, 'getPrice'])->name('get-price');
        Route::post('/robux/payment/{transaction:transaction_code}/confirm', [BuyRobuxController::class, 'confirmPayment'])->name('robux.confirm-payment');
    });
});

// Admin Routes (harus login + admin)
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Robux Package Management
    Route::resource('robux/packages', RobuxPackageController::class)->names([
        'index' => 'robux.packages.index',
        'create' => 'robux.packages.create',
        'store' => 'robux.packages.store',
        'show' => 'robux.packages.show',
        'edit' => 'robux.packages.edit',
        'update' => 'robux.packages.update',
        'destroy' => 'robux.packages.destroy',
    ]);
    
    // Update stock route
    Route::patch('robux/packages/{package}/update-stock', [RobuxPackageController::class, 'updateStock'])
         ->name('robux.packages.update-stock');
    
    // Transaction Management
    Route::prefix('robux/transactions')->name('robux.transactions.')->group(function () {
        Route::get('/', [RobuxTransactionController::class, 'index'])->name('index');
        Route::get('/{transaction:transaction_code}', [RobuxTransactionController::class, 'show'])->name('show');
        Route::post('/{transaction}/payment-status', [RobuxTransactionController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/{transaction}/delivery-status', [RobuxTransactionController::class, 'updateDeliveryStatus'])->name('update-delivery-status');
    });
});

// API Routes untuk frontend
Route::prefix('api/robux')->group(function () {
    Route::get('/packages', [RobuxController::class, 'getPackages']);
    Route::post('/create-transaction', [RobuxController::class, 'createTransaction']);
    Route::get('/check-transaction/{code}', [RobuxController::class, 'checkTransaction']);
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';