<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\ApostaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController; // 


Route::get('/jogos', [PartidaController::class, 'index']);
Route::get('/historico/{idUsuario}', [DashboardController::class, 'historicoUsuario']);
Route::post('/depositar', [TransacaoController::class, 'depositar']);
Route::post('/apostar', [ApostaController::class, 'apostar']);

// ---------------------------------------------------
// NOVAS ROTAS DO PERFIL DO USUÁRIO
// ---------------------------------------------------

// Buscar os dados atuais do usuário para preencher a tela
Route::get('/usuario/{id}', [UsuarioController::class, 'mostrar']);

// Atualizar os dados pessoais (Nome, Email, Telefone)
Route::put('/usuario/{id}', [UsuarioController::class, 'atualizarDados']);

// Atualizar a senha com segurança
Route::put('/usuario/{id}/senha', [UsuarioController::class, 'atualizarSenha']);