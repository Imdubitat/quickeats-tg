<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    public function exibirIndexRestaurante()
    {
        return view('index_restaurante');
    }

    public function logout(Request $request)
    {
        // Remover todas as variáveis de sessão
        Session::flush();

        // Redirecionar para a página de login
        return redirect('/quickeats')->with('success', 'Logout realizado com sucesso.');
    }
}
