<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeFutebol extends Model
{
    use HasFactory;

    protected $table = 'arabetdb.time_futebol';
    protected $primaryKey = 'id_time';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cidade',
        'estadio',
    ];

    // Um time pode jogar várias vezes como mandante
    public function partidasComoMandante()
    {
        return $this->hasMany(Partida::class, 'id_mandante', 'id_time');
    }

    // Um time pode jogar várias vezes como visitante
    public function partidasComoVisitante()
    {
        return $this->hasMany(Partida::class, 'id_visitante', 'id_time');
    }
}