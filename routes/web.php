<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Página principal (redirige según si está logueado o no)
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas (solo si NO está logueado)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas (solo si ESTÁ logueado)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
