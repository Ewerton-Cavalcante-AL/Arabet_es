<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
    use HasFactory;
    protected $table = 'arabetdb.aposta';
    protected $primaryKey = 'id_aposta';
    public $timestamps = false;

    protected $fillable = [
        'id_apostador', 'id_partida', 'valor', 'palpite', 
        'odd_momento', 'status', 'data_aposta'
    ];
}
