<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\Apostador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransacaoController extends Controller
{
    public function depositar(Request $request)
    {
        $request->validate([
            'id_apostador' => 'required|integer',
            'valor' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::transaction(function () use ($request) {
               
                Transacao::create([
                    'id_apostador' => $request->id_apostador,
                    'tipo' => 'DEPOSITO',
                    'valor' => $request->valor,
                    'data_hora' => now(),
                ]);

            
                Apostador::where('id_usuario', $request->id_apostador)
                    ->increment('saldo', $request->valor);
            });

            return response()->json(['message' => 'Depósito realizado com sucesso!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar o depósito.', 'details' => $e->getMessage()], 500);
        }
    }
}
