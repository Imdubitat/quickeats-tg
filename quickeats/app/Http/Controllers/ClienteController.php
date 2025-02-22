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

        //$email_verificado = Cliente::where('email', $validatedData['emailLogin'])->where('email_verificado', 1)->first();

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

    public function exibirCarrinho()
    {
        $id_cliente = auth()->guard('cliente')->id();

        $produtos = DB::select('CALL produtos_carrinho(?)', [$id_cliente]);

        // Retorna a view 'carrinho' com os dados dos estabelecimentos populares
        return view('carrinho', compact('produtos'));
    }

    public function adicionarProdutoCarrinho(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $id_produto = $request->input('produto');
        $qtd_produto = $request->input('qtd_produto');
        $data_adicao = now(); // Sempre define a data atual
        $id_res = $request->input('id_estab');

        // Verifica se já existe um produto no carrinho de outro estabelecimento
        $produtoOutroEstab = DB::table('carrinho')
            ->join('produtos', 'carrinho.id_produto', '=', 'produtos.id_produto')
            ->where('carrinho.id_cliente', $id_cliente)
            ->where('produtos.id_estab', '!=', $id_res)
            ->exists();

        if ($produtoOutroEstab) {
            return redirect()->back()->with('error', 'Você já tem produtos de outro estabelecimento no carrinho. Esvazie o carrinho antes de adicionar novos produtos.');
        }

        // Verifica se o produto já está no carrinho do cliente
        $produtoExistente = DB::table('carrinho')
            ->where('id_cliente', $id_cliente)
            ->where('id_produto', $id_produto)
            ->first();

        if ($produtoExistente) {
            // Se o produto já existe, apenas atualiza a quantidade
            DB::table('carrinho')
                ->where('id_cliente', $id_cliente)
                ->where('id_produto', $id_produto)
                ->update([
                    'qtd_produto' => $produtoExistente->qtd_produto + $qtd_produto,
                    'data_adicao' => $data_adicao
                ]);
        } else {
            // Se não existir, insere um novo registro
            DB::table('carrinho')->insert([
                'id_cliente' => $id_cliente,
                'id_produto' => $id_produto,
                'qtd_produto' => $qtd_produto,
                'data_adicao' => $data_adicao,
            ]);
        }

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function removerProdutoCarrinho(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $id_produto = $request->input('produto');

        DB::table('carrinho')
        ->where('id_cliente', $id_cliente)
        ->where('id_produto', $id_produto)
        ->delete();
            
        return redirect()->back()->with('success', 'Produto removido do carrinho!');
    }

    public function diminuirQuantidadeCarrinho(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $id_produto = $request->input('produto');

        // Buscar o produto no carrinho
        $produto = DB::table('carrinho')
        ->where('id_cliente', $id_cliente)
        ->where('id_produto', $id_produto)
        ->first();

        if ($produto) {
            // Atualizar a quantidade no carrinho
            $produto = DB::table('carrinho')
            ->where('id_cliente', $id_cliente)
            ->where('id_produto', $id_produto)
            ->update([
                'qtd_produto'=> $produto->qtd_produto - 1,
                'data_adicao' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Quantidade do produto reduzida!');
    }

    public function aumentarQuantidadeCarrinho(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $id_produto = $request->input('produto');

        // Buscar o produto no carrinho
        $produto = DB::table('carrinho')
        ->where('id_cliente', $id_cliente)
        ->where('id_produto', $id_produto)
        ->first();

        if ($produto) {
            // Atualizar a quantidade no carrinho
            DB::table('carrinho')
                ->where('id_cliente', $id_cliente)
                ->where('id_produto', $id_produto)
                ->update([
                    'qtd_produto' => $produto->qtd_produto + 1,
                    'data_adicao' => now()
                ]);
        }

        return redirect()->back()->with('success', 'Quantidade do produto aumentada!');
    }

    public function exibirEnderecos()
    {
        $id_cliente = auth()->guard('cliente')->id();

        $enderecos = DB::select('CALL exibir_enderecos_cliente(?)', [$id_cliente]);
    
        return view('checkout_endereco', compact('enderecos'));
    }

    public function exibirFormasPagamento(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $formas_pagamento = DB::table('formas_pagamentos')->get();

        session(['id_endereco' => $request->endereco]);

        return view('checkout_pagamento', compact('formas_pagamento'));
    }

    public function realizarPedido(Request $request)
    {
        $id_cliente = auth()->guard('cliente')->id();
        $id_pagamento = $request->input('pagamento');
        $id_endereco = session('id_endereco');

        if (!$id_endereco) {
            return redirect()->back()->with('error', 'Endereço não selecionado.');
        }

        DB::select('CALL realizar_pedido(?, ?, ?)', [$id_cliente, $id_endereco, $id_pagamento]);

        return redirect()->route('carrinho')->with('success', 'Pedido realizado!');
    }

    public function exibirPaginaPedidos()
    {
        $id_cliente = Auth::guard('cliente')->id();

        // Executando o procedure e obtendo os pedidos
        $pedidos = DB::select("CALL exibir_pedidos_cliente(?)", [$id_cliente]);

        return view('pedidos_cliente', compact('pedidos'));
    }

    public function cancelarPedido(Request $request, $id)
    {
        // Verificar se o pedido existe
        $pedido = DB::select("SELECT * FROM pedidos WHERE id_pedido = ?", [$id]);

        if (empty($pedido)) { // Como DB::select() retorna um array, verificamos se está vazio
            return redirect()->back()->with('error', 'Pedido não encontrado.');
        }

        // Atualizar o status do pedido
        DB::update("UPDATE pedidos SET status_entrega = ? WHERE id_pedido = ?", [$request->novo_status, $id]);

        return redirect()->back()->with('success', 'Status atualizado com sucesso.');
    }

    // Método para ir para a página de adm
    public function exibirAdmCliente() 
    {
        return view('adm_cliente');
    }

    public function exibirInfoCliente() 
    {
        $id_cliente = Auth::guard('cliente')->id();

        $cadastro = DB::table('clientes')
        ->where('id_cliente', $id_cliente)->get();

        return view('info_cliente', compact('cadastro'));
    }
    

    // Método para salvar alterações
    public function alterarCadastro(Request $request) 
    {
        // Captura o id do cliente da sessão
        $id_cliente = Auth::guard('cliente')->id();

        // Validação dos dados
        $request->validate([
            'telefone' => ['required', 'string', 'max:11'],
            'email' => ['required', 'email', 'max:100', 'unique:clientes,email,' . $id_cliente . ',id_cliente',],
        ]);

        // Capturando os dados validados
        $telefone = $request->input('telefone');
        $email = $request->input('email');

        // Atualizando os dados no modelo
        Cliente::atualizarCliente($id_cliente, $telefone, $email);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
    }
}