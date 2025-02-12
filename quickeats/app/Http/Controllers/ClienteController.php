<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClienteController extends Controller
{
    public function realizarLogin(Request $request)
    {
        // Validação dos campos de entrada
        $validatedData = $request->validate([
            'emailLogin' => 'required|string|email|max:255',
            'senhaLogin' => 'required|string|min:8',
        ]);

        $email_verificado = Cliente::where('email', $validatedData['emailLogin'])->where('email_verificado', 1)->first();

        // Tentar autenticar o cliente usando o guard 'cliente'
        if (Auth::guard('cliente')->attempt(['email' => $request->input('emailLogin'), 'password' => $request->input('senhaLogin')])) {
            if($email_verificado){
                // Login bem-sucedido, redirecionar para a página inicial do profissional
                return redirect()->route('home_cliente')->with('success', 'Login realizado com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Email não verificado!');
            }
        } else {
            // Login falhou, redirecionar de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Email ou senha inválidos');
        }
    }

    public function exibirPaginaInicial()
    {
        // Retorna a view 'home-cliente' com os dados dos estabelecimentos populares
        return view('home_cliente');
    }
}
