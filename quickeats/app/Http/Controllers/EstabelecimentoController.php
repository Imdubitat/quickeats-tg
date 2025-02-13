<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estabelecimento;

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

    public function exibirPaginaInicial()
    {
        // Retorna a view 'home-restaurante' com os dados dos estabelecimentos populares
        return view('home_restaurante');
    }
}
