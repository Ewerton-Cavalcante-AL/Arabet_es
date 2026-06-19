<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $table = 'arabetdb.transacao';
    protected $primaryKey = 'id_transacao';
    public $timestamps = false;

    protected $fillable = [
        'id_apostador', 'tipo', 'valor', 'data_hora'
    ];
}
