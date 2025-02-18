<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estabelecimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB; // Para interagir com o banco de dados
use App\Http\Controllers\Controller; // Para estender a classe base do Laravel


class EstabelecimentoController extends Controller
{
    public function cadastrarEstabelecimento(Request $request)
    {

        // Valida apenas os campos obrigatórios para o cadastro inicial
        $validatedData = $request->validate([
            'nomeFantasiaSignup' => 'required|string|max:55',
            'cnpjSignup' => 'required|string|max:18',
            'telefoneSignup' => 'required|string|max:15',
            'logradouroSignup' => 'required|string|max:100',
            'numeroSignup' => 'required|numeric',
            'bairroSignup' => 'required|string|max:100',
            'cidadeSignup' => 'required|string|max:100',
            'estadoSignup' => 'required|string|max:2',
            'cepSignup' => 'required|string|max:9',
            'inicioExpedienteSignup' => 'required|string|max:255',
            'terminoExpedienteSignup' => 'required|string|max:255',
            'emailSignup' => 'required|string|email|max:255|unique:estabelecimentos,email',
            'senhaSignup' => 'required|string|min:8',
        ]);

        try {
            // Chama o método para criar o estabelecimento no model
            $estabelecimento = Estabelecimento::cadastrarEstabelecimento($validatedData);

            return redirect()->route('index_restaurante')->with('success', 'Estabelecimento cadastrado com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao cadastrar o estabelecimento. Tente novamente.');
        }
    }

    public function realizarLogin(Request $request)
    {
        // Validação dos campos de entrada
        $validatedData = $request->validate([
            'emailLogin' => 'required|string|email|max:255',
            'senhaLogin' => 'required|string|min:8',
        ]);

        // $email_verificado = Estabelecimento::where('email', $validatedData['emailLogin'])->where('email_verificado', 1)->first();

        // Tentar autenticar o cliente usando o guard 'cliente'
        if (Auth::guard('estabelecimento')->attempt(['email' => $request->input('emailLogin'), 'password' => $request->input('senhaLogin')])) {
            // if($email_verificado){
                // Login bem-sucedido, redirecionar para a página inicial do profissional
                return redirect()->route('home_restaurante')->with('success', 'Login realizado com sucesso!');
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
        // Retorna a view 'home-restaurante' com os dados dos estabelecimentos populares
        return view('home_restaurante');
    }

    public function exibirPaginaPedidos()
    {
        // Obtendo o estabelecimento autenticado
        $estabelecimento = Auth::guard('estabelecimento')->user();

        // Pegando o ID do estabelecimento logado
        $id_estabelecimento = $estabelecimento->id_estab; 

        // Executando o procedure e obtendo os pedidos
        $pedidos = DB::select("CALL exibir_pedidos_estabelecimento(?)", [$id_estabelecimento]);

        return view('pedidos_restaurante', compact('pedidos'));
    }
}
