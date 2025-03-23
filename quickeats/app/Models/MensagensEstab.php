<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagensEstab extends Model
{
    use HasFactory;
 
    // Define a tabela associada
    protected $table = 'mensagens_estab';

    protected $primaryKey = 'id_mensagem';

    protected $fillable = [
        'id_chat',
        'id_remetente',
        'id_destinatario',
        'categoria',
        'mensagem',
        'data_envio',
        'ativo',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}