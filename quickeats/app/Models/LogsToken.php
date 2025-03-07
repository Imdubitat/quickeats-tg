<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsToken extends Model
{
    use HasFactory;
 
    // Define a tabela associada
    protected $table = 'logs_tokens';

    protected $primaryKey = 'id_token';

    protected $fillable = [
        'id_usuario',
        'email',
        'motivo',
        'tipo_usuario',
        'token',
        'criado_em',
        'usado_em',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}