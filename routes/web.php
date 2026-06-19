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
Route::get('/dash', function () {
    return view('dash');
});
Route::get('/dashboard', [DashboardController::class, 'index']);
