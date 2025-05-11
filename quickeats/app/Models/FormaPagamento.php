<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    protected $table = 'formas_pagamentos';
    protected $fillable = ['descricao'];
}
