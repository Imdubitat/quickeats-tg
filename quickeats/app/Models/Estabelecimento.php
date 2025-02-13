<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Estabelecimento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estab';
    protected $table = 'estabelecimentos';

    protected $fillable = [
        'nome_fantasia',
        'cnpj',
        'telefone',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'inicio_expediente',
        'termino_expediente',
        'email',
        'senha',
    ];

    public $timestamps = false;

    protected $hidden = [
        'senha',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public static function cadastrarEstabelecimento($data)
    {
        return self::create([
            'nome_fantasia' => $data['nomeFantasiaSignup'],
            'cnpj' => $data['cnpjSignup'],
            'telefone' => $data['telefoneSignup'],
            'logradouro' => $data['logradouroSignup'],
            'numero' => $data['numeroSignup'],
            'bairro' => $data['bairroSignup'],
            'cidade' => $data['cidadeSignup'],
            'estado' => $data['estadoSignup'],
            'cep' => $data['cepSignup'],
            'inicio_expediente' => $data['inicioExpedienteSignup'],
            'termino_expediente' => $data['terminoExpedienteSignup'],
            'email' => $data['emailSignup'],
            'senha' => Hash::make($data['senhaSignup']),
        ]);
    }
}