<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importando Authenticatable para autenticação
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Administrador extends Authenticatable
{
    use HasFactory;

    // Defina a chave primária, se não for 'id'
    protected $primaryKey = 'id_admin';

    // Define a tabela associada
    protected $table = 'administradores';

    // Desativa os timestamps automáticos
    public $timestamps = false;

    // Ocultar a senha ao recuperar os dados
    protected $hidden = [
        'senha',
    ];

    // Adiciona a função getAuthPassword para autenticação
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
