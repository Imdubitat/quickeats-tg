<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetSenha extends Model
{
    use HasFactory;

    // Define a tabela associada
    protected $table = 'resets_senhas';

    protected $fillable = [
        'id_usuario',
        'tipo_usuario',
        'email',
        'criado_em',
        'token',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}
