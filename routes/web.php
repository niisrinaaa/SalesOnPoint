<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RobuxPackageController;
use App\Http\Controllers\Admin\RobuxTransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;


// Route::get('/wel', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('guest.index');
});

Route::get('/buying', function () {
    return view('userpov/index');
// =======
// Route::get('/leyot', function () {
//     return view('layoutnotadmin');
// });

// Route::get('/', function () {
//     return view('tampilanawal/index');
// });

// Route::get('/buying', function () {
//     return view('tampilanbeli/index');
// >>>>>>> 4b21c3c0b7296814d3b5934581d35bfed87731a4
});


// Route::get('/userp', function () {
//     return view('userpov/index');
// });

Route::get('/index', function () {
    return view('guest.index');
})->middleware(['auth', 'verified'])->name('guest.index');

// Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('dashboard'); // Nama route ini jadi 'admin.dashboard'
    
//     Route::resource('category', CategoryController::class);
//     Route::resource('item', ItemController::class);
//     Route::resource('/transaction', TransactionController::class);
//     Route::resource('/detail', TransactionDetailController::class);
// });



// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Robux Packages
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
    
    // Robux Transactions
    Route::resource('robux/transactions', RobuxTransactionController::class)->only(['index', 'show'])
         ->names([
             'index' => 'robux.transactions.index',
             'show' => 'robux.transactions.show',
         ]);
         
    Route::patch('robux/transactions/{transaction}/payment', [RobuxTransactionController::class, 'updatePaymentStatus'])
         ->name('robux.transactions.update-payment');
    Route::patch('robux/transactions/{transaction}/delivery', [RobuxTransactionController::class, 'updateDeliveryStatus'])
         ->name('robux.transactions.update-delivery');
});

// // Frontend Routes
// Route::get('/', function () {
//     return view('frontend.topup');
// });

// API Routes (routes/api.php)
Route::prefix('robux')->group(function () {
    Route::get('/packages', [App\Http\Controllers\Api\RobuxController::class, 'getPackages']);
    Route::post('/create-transaction', [App\Http\Controllers\Api\RobuxController::class, 'createTransaction']);
    Route::get('/check-transaction/{code}', [App\Http\Controllers\Api\RobuxController::class, 'checkTransaction']);
});
require __DIR__.'/auth.php';
