<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ProdutoFavorito extends Model
{
    use HasFactory;

    // Define a tabela associada
    protected $table = 'produtos_favoritos';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_produto',
        'id_cliente',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;
}
