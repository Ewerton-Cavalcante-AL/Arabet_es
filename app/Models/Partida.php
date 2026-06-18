<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'arabetdb.partida';
    protected $primaryKey = 'id_partida';
    public $timestamps = false;

    protected $fillable = [
        'id_mandante', 'id_visitante', 'data_hora', 'status',
        'odd_mandante', 'odd_empate', 'odd_visitante',
        'placar_mandante', 'placar_visitante'
    ];
}
