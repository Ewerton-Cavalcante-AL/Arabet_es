<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// ... (outros imports)

class User extends Authenticatable
{
    use Notifiable;

    // 1. Apontar para a tabela e schema corretos
    protected $table = 'arabetdb.usuario';

    // 2. Apontar a chave primária correta
    protected $primaryKey = 'id_usuario';

    // 3. Desativar os timestamps padrão do Laravel
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'senha',
        'tipo',
    ];

    // O Laravel espera que a senha seja guardada na coluna 'password'.
    // Como a sua coluna se chama 'senha', precisamos avisar o Laravel sobre isso:
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
