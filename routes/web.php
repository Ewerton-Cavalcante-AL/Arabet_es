<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return Inertia::render('Home');
});


Route::get('/cadastro', [AuthController::class, 'create'])->name('cadastro.index');
Route::post('/cadastro', [AuthController::class, 'store'])->name('cadastro.store');

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/perfil', function () {
    return Inertia::render('perfil'); // Renderiza o componente perfil.jsx
});
Route::get('/admin', function () {
    return Inertia::render('AdminPanel');
})->name('admin.panel');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
