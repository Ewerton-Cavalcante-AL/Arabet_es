<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\ApostaController;

Route::get('/jogos', [PartidaController::class, 'index']);

Route::post('/depositar', [TransacaoController::class, 'depositar']);

Route::post('/apostar', [ApostaController::class, 'apostar']);