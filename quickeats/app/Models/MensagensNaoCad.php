<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MensagensNaoCad extends Model
{
    use HasFactory;
    
    // Define a tabela associada
    protected $table = 'mensagens_nao_cad';

    protected $primaryKey = 'id_mensagem';

    protected $fillable = [
        'nome',
        'email',
        'mensagem',
        'data_envio',
        'ativo',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}
