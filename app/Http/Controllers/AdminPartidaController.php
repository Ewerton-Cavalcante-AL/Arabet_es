<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\Aposta;
use App\Models\Apostador;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPartidaController extends Controller
{

    public function listarTimes()
    {
        $times = DB::table('arabetdb.time_futebol')->select('id_time', 'nome')->get();
        return response()->json($times);
    }

    public function criarPartida(Request $request)
    {
        $request->validate([
            'id_mandante' => 'required|integer',
            'id_visitante' => 'required|integer',
            'data_hora' => 'required|date|after:now',
            'odd_mandante' => 'required|numeric|min:1.01',
            'odd_empate' => 'required|numeric|min:1.01',
            'odd_visitante' => 'required|numeric|min:1.01',
        ]);

        $partida = Partida::create([
            'id_mandante' => $request->id_mandante,
            'id_visitante' => $request->id_visitante,
            'data_hora' => $request->data_hora,
            'odd_mandante' => $request->odd_mandante,
            'odd_empate' => $request->odd_empate,
            'odd_visitante' => $request->odd_visitante,
            'status' => 'AGENDADA'
        ]);

        return response()->json(['message' => 'Partida criada com sucesso!', 'partida' => $partida]);
    }
   
    public function atualizarOdds(Request $request, $id)
    {
        $request->validate([
            'odd_mandante' => 'required|numeric|min:1.01',
            'odd_empate' => 'required|numeric|min:1.01',
            'odd_visitante' => 'required|numeric|min:1.01',
        ]);

        $partida = Partida::find($id);

        if (!$partida || $partida->status === 'FINALIZADA') {
            return response()->json(['error' => 'Partida não encontrada ou já finalizada.'], 400);
        }

        $partida->update([
            'odd_mandante' => $request->odd_mandante,
            'odd_empate' => $request->odd_empate,
            'odd_visitante' => $request->odd_visitante,
        ]);

        return response()->json(['message' => 'Odds atualizadas com sucesso!', 'partida' => $partida]);
    }

    public function finalizarPartida(Request $request, $id)
    {
        $request->validate([
            'placar_mandante' => 'required|integer|min:0',
            'placar_visitante' => 'required|integer|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $partida = Partida::lockForUpdate()->find($id);

                if (!$partida || $partida->status === 'FINALIZADA') {
                    return response()->json(['error' => 'Partida inválida ou já finalizada.'], 400);
                }

                $partida->update([
                    'placar_mandante' => $request->placar_mandante,
                    'placar_visitante' => $request->placar_visitante,
                    'status' => 'FINALIZADA'
                ]);

                $resultadoReal = 'EMPATE';
                if ($request->placar_mandante > $request->placar_visitante) {
                    $resultadoReal = 'MANDANTE';
                } elseif ($request->placar_visitante > $request->placar_mandante) {
                    $resultadoReal = 'VISITANTE';
                }

                $apostas = Aposta::where('id_partida', $id)
                                 ->where('status', 'PENDENTE')
                                 ->get();


                foreach ($apostas as $aposta) {
                    if ($aposta->palpite === $resultadoReal) {

                        $aposta->update(['status' => 'GANHA']);
                        

                        $valorPremio = $aposta->valor * $aposta->odd_momento;


                        $apostador = Apostador::lockForUpdate()->find($aposta->id_apostador);
                        $apostador->increment('saldo', $valorPremio);


                        Transacao::create([
                            'id_apostador' => $aposta->id_apostador,
                            'tipo' => 'DEPOSITO', 
                            'valor' => $valorPremio,
                            'data_hora' => now()
                        ]);
                    } else {
                     
                        $aposta->update(['status' => 'PERDIDA']);
                       
                    }
                }

                return response()->json([
                    'message' => 'Partida finalizada e prêmios distribuídos com sucesso!',
                    'resultado_oficial' => $resultadoReal,
                    'total_apostas_processadas' => $apostas->count()
                ]);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao liquidar apostas.', 'details' => $e->getMessage()], 500);
        }
    }
}
