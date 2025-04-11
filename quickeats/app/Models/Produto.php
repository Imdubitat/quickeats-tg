<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Produto extends Model
{
    use HasFactory;

    // Defina a chave primária, se não for 'id'
    protected $primaryKey = 'id_produto';

    // Define a tabela associada
    protected $table = 'produtos';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_produto',
        'nome',
        'descricao',
        'valor',
        'id_categoria',
        'id_estab',
        'qtd_estoque',
        'imagem_produto',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}
