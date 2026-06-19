<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PainelJogo;

class PartidaController extends Controller
{
    public function index()
    {
        $jogos = PainelJogo::where('status', 'AGENDADA')
            ->orderBy('data_hora', 'asc')
            ->get();

        return response()->json($jogos);
    }
}
