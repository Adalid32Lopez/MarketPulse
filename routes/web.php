<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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
