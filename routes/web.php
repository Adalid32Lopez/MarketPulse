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

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil (no necesita belongs.to.business)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Negocios (no necesita belongs.to.business, el usuario crea/edita los suyos)
    Route::resource('businesses', BusinessController::class);
});

// Rutas que requieren pertenecer al negocio — accesibles por admin Y vendedor
Route::middleware(['auth', 'belongs.to.business'])->group(function () {
    Route::resource('businesses.customers', CustomerController::class);
    Route::resource('businesses.sales', SaleController::class);
    Route::patch('businesses/{business}/alerts/{alert}/read', [AlertController::class, 'markAsRead'])
        ->name('businesses.alerts.read');
});

// Rutas solo para admin
Route::middleware(['auth', 'belongs.to.business', 'role:admin'])->group(function () {
    Route::resource('businesses.categories', CategoryController::class);
    Route::resource('businesses.products', ProductController::class);
    Route::resource('businesses.reports', ReportController::class)->only([
        'index', 'create', 'store', 'show', 'destroy'
    ]);
    Route::resource('businesses.alerts', AlertController::class)->only([
        'index', 'destroy'
    ]);
    Route::resource('businesses.users', UserController::class)->only([
        'index', 'create', 'store', 'destroy'
    ]);
});

require __DIR__.'/auth.php';