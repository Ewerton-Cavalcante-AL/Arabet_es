<?php

namespace App\Http\Controllers;
use App\Models\Aposta;
use App\Models\Partida;
use App\Models\Apostador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApostaController extends Controller
{
    public function apostar(Request $request)
    {
        $request->validate([
            'id_apostador' => 'required|integer',
            'id_partida' => 'required|integer',
            'valor' => 'required|numeric|min:1',
            'palpite' => 'required|string|in:MANDANTE,EMPATE,VISITANTE'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
               
                $partida = Partida::find($request->id_partida);

                if (!$partida || $partida->status !== 'AGENDADA') {
                    return response()->json(['error' => 'Partida indisponível para apostas.'], 400);
                }

                
                $apostador = Apostador::lockForUpdate()->find($request->id_apostador);

                if (!$apostador || $apostador->saldo < $request->valor) {
                    return response()->json(['error' => 'Saldo insuficiente.'], 400);
                }

                
                $oddMomento = match ($request->palpite) {
                    'MANDANTE' => $partida->odd_mandante,
                    'EMPATE' => $partida->odd_empate,
                    'VISITANTE' => $partida->odd_visitante,
                };

                
                $apostador->decrement('saldo', $request->valor);

            
                Aposta::create([
                    // 👇 Nome exato da coluna no seu banco
                    'jogo_id' => $request->id_partida, 
                    'palpite' => $request->palpite,
                    'valor' => $request->valor,
                    // 👇 Nome exato da coluna no banco
                    'odd' => $oddMomento, 
                    // 👇 A sua tabela tem essa coluna, então já vamos preencher!
                    'retorno_potencial' => $request->valor * $oddMomento, 
                ]);

                return response()->json(['message' => 'Aposta registrada com sucesso!'], 201);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar a aposta.', 'details' => $e->getMessage()], 500);
        }
    }
}
