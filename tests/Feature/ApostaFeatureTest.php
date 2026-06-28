<?php


use App\Models\Apostador;
use App\Models\Partida;
use App\Models\Aposta;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

it('deve registrar uma aposta com sucesso e descontar o saldo', function () {
    
    $apostador = Apostador::factory()->create(['saldo' => 100.00]);
    $partida = Partida::factory()->create([
        'status' => 'AGENDADA',
        'odd_mandante' => 2.50
    ]);

    $payload = [
        'id_apostador' => $apostador->id_usuario,
        'id_partida' => $partida->id_partida,
        'valor' => 30.00,
        'palpite' => 'MANDANTE'
    ];

    
    $response = postJson('/api/apostar', $payload); 

    
    $response->assertStatus(201)
             ->assertJson(['message' => 'Aposta registrada com sucesso!']);
    
    
    expect($apostador->fresh()->saldo)->toBe('70.00');

   
    assertDatabaseHas((new Aposta())->getTable(), [
    'id_apostador' => $apostador->id_usuario,
    'id_partida' => $partida->id_partida,
    'valor' => 30.00,
    'palpite' => 'MANDANTE',
    'odd_momento' => 2.50,
    'status' => 'PENDENTE'
    ]);
});

it('deve bloquear a aposta se a partida nao estiver agendada', function () {
    $apostador = Apostador::factory()->create(['saldo' => 100.00]);
    $partida = Partida::factory()->create([
        'status' => 'FINALIZADA'
    ]);

    $response = postJson('/api/apostar', [
        'id_apostador' => $apostador->id_usuario,
        'id_partida' => $partida->id_partida,
        'valor' => 30.00,
        'palpite' => 'VISITANTE'
    ]);

    $response->assertStatus(400)
             ->assertJson(['error' => 'Partida indisponível para apostas.']);
});

it('deve bloquear a aposta por saldo insuficiente', function () {
    $apostador = Apostador::factory()->create(['saldo' => 10.00]); 
    $partida = Partida::factory()->create(['status' => 'AGENDADA']);

    $response = postJson('/api/apostar', [
        'id_apostador' => $apostador->id_usuario,
        'id_partida' => $partida->id_partida,
        'valor' => 50.00,
        'palpite' => 'EMPATE'
    ]);

    $response->assertStatus(400)
             ->assertJson(['error' => 'Saldo insuficiente.']);
});