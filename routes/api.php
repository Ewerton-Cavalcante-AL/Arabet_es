<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\ApostaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminPartidaController;

Route::get('/jogos', [PartidaController::class, 'index']);

Route::get('/historico/{idUsuario}', [DashboardController::class, 'historicoUsuario']);

Route::post('/depositar', [TransacaoController::class, 'depositar']);

Route::post('/apostar', [ApostaController::class, 'apostar']);



Route::prefix('admin')->group(function () {
    Route::get('/times', [AdminPartidaController::class, 'listarTimes']);
    // Atualizar as odds de uma partida em andamento/agendada
    Route::put('/partidas/{id}/odds', [AdminPartidaController::class, 'atualizarOdds']);
    
    // Inserir placar final, finalizar o jogo e pagar as apostas
    Route::post('/partidas/{id}/finalizar', [AdminPartidaController::class, 'finalizarPartida']);

    Route::post('/partidas', [AdminPartidaController::class, 'criarPartida']);
});