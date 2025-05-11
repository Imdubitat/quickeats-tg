<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{
    use HasFactory;

    // Defina a chave primária, se não for 'id'
    protected $primaryKey = 'id_pedido';

    // Define a tabela associada
    protected $table = 'pedidos';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'valor_total',
        'forma_pagamento',
        'data_compra',
        'status_entrega',
        'endereco',
        'payment_intent_id',
    ];

    // Desativa os timestamps automáticos
    public $timestamps = false;

    public static function realizarPedido($id_cliente, $id_endereco, $id_pagamento, $payment_intent_id)
    {
        try {
            return DB::select('CALL realizar_pedido(?, ?, ?, ?)', [
                $id_cliente,
                $id_endereco,
                $id_pagamento,
                $payment_intent_id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro na procedure realizar_pedido: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            throw $e; // Repassa a exceção para a controller capturar
        }
    }

}
