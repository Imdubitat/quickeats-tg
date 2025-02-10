<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function exibirIndex() 
    {
        return view('index');
    }

    public function exibirIndexCliente() 
    {
        return view('index_cliente');
    }
}
