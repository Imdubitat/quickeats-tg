<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // Função para salvar o cliente no banco de dados
    public function cadastrarCliente(Request $request)
    {
        // Valida os dados enviados pelo modal
        $validatedData = $request->validate([
            'nomeSignup' => 'required|string|max:50',
            'cpfSignup' => 'required|string|max:11',
            'dataNascSignup' => 'required|string|max:50',
            'telefoneSignup' => 'required|string|max:11',
            'emailSignup' => 'required|string|email|max:100|unique:clientes,email',
            'senhaSignup' => 'required|string|min:8',
        ]);

        try {
            // Chama o método para criar o cliente no model
            $cliente = Cliente::cadastrarCliente($validatedData);

            // Redireciona para a página com uma mensagem de sucesso
            return redirect()->route('index_cliente')->with('success', 'Cliente cadastrado com sucesso. Por favor, verifique seu e-mail!');
        } catch (\Exception $e) {
            // Se ocorrer um erro, redireciona com uma mensagem de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o cliente. Tente novamente.');
        }
    }

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
            // if($email_verificado){
            //     // Login bem-sucedido, redirecionar para a página inicial do profissional
                return redirect()->route('home_cliente')->with('success', 'Login realizado com sucesso!');
            // } else {
            //     return redirect()->back()->with('error', 'Email não verificado!');
            // }
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

    public function exibirProdutosDisponiveis()
    {
        $produtos = DB::select('SELECT * FROM `produtos_disponiveis`');

        return view('catalogo_produtos', compact('produtos'));
    }
}