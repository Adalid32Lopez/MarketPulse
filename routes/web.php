<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth', 'belongs.to.business')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //CRUD de categoria
    Route::resource('businesses.categories', CategoryController::class);
    //CRUD de clientes
    Route::resource('businesses.customers', CustomerController::class);
    //
    Route::resource('businesses.sales', SaleController::class);
    // ReporController
    Route::resource('businesses.reports', ReportController::class)->only([
    'index', 'create', 'store', 'show', 'destroy'
    ]);

    //
    Route::resource('businesses.alerts', AlertController::class)->only([
    'index', 'store', 'destroy'
    ]);
    // Ruta especial para marcar como leída
    Route::patch('businesses/{business}/alerts/{alert}/read', [AlertController::class, 'markAsRead'])
    ->name('businesses.alerts.read');
    

    // ruta para crear un nuevo usuario como vendedor
    Route::resource('businesses.users', UserController::class)->only([
    'index', 'create', 'store', 'destroy'
    ]);
    

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Negocios
    Route::resource('businesses', BusinessController::class);

    // Productos (anidados bajo un negocio)
    Route::resource('businesses.products', ProductController::class);

});

Route::get('/test', function () {
    return view('test');
});

Route::get('/test-products', function () {
    $business = \App\Models\Business::find(1);
    $categories = $business->categories;
    return view('products.create', compact('business', 'categories'));
});

require __DIR__.'/auth.php';
