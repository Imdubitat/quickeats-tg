<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Avaliacao;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Estabelecimento;
use App\Models\ConfirmacaoEmail;
use App\Models\MensagensCliente;
use App\Mail\ConfirmaEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetSenhaEmail;
use App\Models\ResetSenha;
use App\Models\LogsToken; 
use App\Models\Endereco;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Rules\validaCPF;
use App\Rules\validaCelular;
use App\Rules\validaData;
use App\Models\ProdutoFavorito;

class ClienteController extends Controller
{
    public function cadastrarCliente(Request $request)
    {
        try {
            // Valida os dados enviados pelo modal
            $validatedData = $request->validate([
                'nomeSignup' => 'required|string|max:50',
                'cpfSignup' => ['required', new validaCPF],
                'dataNascSignup' => ['required', new validaData],
                'telefoneSignup' => ['required', new validaCelular],
                'emailSignup' => 'required|string|email|max:100|unique:clientes,email',
                'senhaSignup' => 'required|string|min:8',
            ]);

            // Chama o método para criar o cliente no model
            $cliente = Cliente::cadastrarCliente($validatedData);

            if (!$cliente) {
                return redirect()->back()->with('error', 'Erro ao cadastrar cliente. Tente novamente.');
            }

            // Gerar o token de confirmação
            $token = Str::random(60);

            // Inserir o token no banco de dados
            $confirmacao = ConfirmacaoEmail::create([
                'email' => $cliente->email,
                'token' => $token,
                'criado_em' => now(),
                'id_usuario' => $cliente->id_cliente,
                'tipo_usuario' => 'cliente',
            ]);

            if (!$confirmacao) {
                return redirect()->back()->with('error', 'Erro ao cadastrar cliente. Tente novamente.');
            }

            // Envio do e-mail de confirmação
            try {
                Mail::to($cliente->email)->send(new ConfirmaEmail($token, $cliente->email, 'cliente'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao enviar e-mail de confirmação.');
            }        

            // Redireciona para a página com uma mensagem de sucesso
            return redirect()->route('index_cliente')->with('success', 'Cliente cadastrado com sucesso. Por favor, verifique seu e-mail!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro inesperado. Tente novamente.');
        }
    }

    public function realizarLogin(Request $request)
    {
        // Validação dos campos de entrada
        $validatedData = $request->validate([
            'emailLogin' => 'required|string|email|max:255',
            'senhaLogin' => 'required|string|min:8',
        ]);

        $emailVerificado = Cliente::where('email', $validatedData['emailLogin'])->where('email_verificado', 1)->first();
        $perfilAtivo = Cliente::where('email', $validatedData['emailLogin'])->where('perfil_ativo', 1)->first();

        // Tentar autenticar o cliente usando o guard 'cliente'
        if (Auth::guard('cliente')->attempt(['email' => $request->input('emailLogin'), 'password' => $request->input('senhaLogin')])) {
            if($perfilAtivo){
                if($emailVerificado){
                    // Login bem-sucedido, redirecionar para a página inicial do profissional
                    return redirect()->route('home_cliente')->with('success', 'Login realizado com sucesso!');
                } else {
                    return redirect()->back()->with('error', 'Email não verificado!');
                }
            } else {
                return redirect()->back()->with('error', 'Seu perfil está desativado');
            }
        } else {
            // Login falhou, redirecionar de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Email ou senha inválidos');
        }
    }

    public function exibirPaginaInicial()
    {
        $estabPopulares = DB::select("SELECT * FROM estabelecimentos_populares");
        $prodPopulares = DB::select("SELECT * FROM produtos_populares");

        // Retorna a view 'home-cliente' com os dados dos estabelecimentos populares
        return view('home_cliente', compact('estabPopulares', 'prodPopulares'));
    }

    public function exibirProdutosDisponiveis()
    {
        $produtos = DB::select('CALL listar_produtos()');
        $categorias = DB::select('SELECT * FROM `categorias_produtos`');
        $favoritos = ProdutoFavorito::where('id_cliente', auth()->id())->pluck('id_produto')->toArray();

        $horaAtual = now()->format('H:i:s');
        $diaSemana = now()->dayOfWeekIso; // 1 (segunda) a 7 (domingo)

        foreach ($produtos as &$produto) {
            $horario = DB::table('grades_horario')
                ->where('id_estab', $produto->id_estab)
                ->where('dia_semana', $diaSemana)
                ->first();

            $produto->estab_fechado = true;

            if ($horario && $horario->inicio_expediente && $horario->termino_expediente) {
                if ($horaAtual >= $horario->inicio_expediente && $horaAtual <= $horario->termino_expediente) {
                    $produto->estab_fechado = false;
                }
            }
        }

        return view('catalogo_produtos', [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'favoritos' => $favoritos,
        ]);
    }


    public function exibirRestaurantesDisponiveis()
    {
        $restaurantes = DB::select('CALL listar_estab()');
        $agora = Carbon::now();
        $diaSemana = $agora->dayOfWeekIso; // 1 (segunda) a 7 (domingo)
        $horaAtual = $agora->format('H:i:s');

        foreach ($restaurantes as $r) {
            $grade = DB::table('grades_horario')
                ->where('id_estab', $r->id_estab)
                ->where('dia_semana', $diaSemana)
                ->whereTime('inicio_expediente', '<=', $horaAtual)
                ->whereTime('termino_expediente', '>=', $horaAtual)
                ->first();

            $r->aberto = $grade !== null;
        }

        return view('catalogo_restaurantes', compact('restaurantes'));
    }


    public function exibirCardapio($id)
    {
        $restaurante = DB::table('estabelecimentos')
            ->where('id_estab', $id)
            ->first();

        $produtos = DB::select('CALL exibir_produtos_estab(?)', [$id]);
        $favoritos = ProdutoFavorito::where('id_cliente', auth()->id())->pluck('id_produto')->toArray();

        $horaAtual = now()->format('H:i:s');
        $diaSemana = now()->dayOfWeekIso; // 1 (segunda) até 7 (domingo)

        // Buscar grade de horário do dia atual
        $horario = DB::table('grades_horario')
            ->where('id_estab', $id)
            ->where('dia_semana', $diaSemana)
            ->first();

        $estabFechado = true;

        if ($horario && $horario->inicio_expediente && $horario->termino_expediente) {
            if ($horaAtual >= $horario->inicio_expediente && $horaAtual <= $horario->termino_expediente) {
                $estabFechado = false;
            }
        }

        // Marcar cada produto conforme o status do estabelecimento
        foreach ($produtos as &$produto) {
            $produto->estab_fechado = $estabFechado;
        }

        return view('cardapio_restaurante', [
            'produtos' => $produtos,
            'restaurante' => $restaurante,
            'favoritos' => $favoritos,
        ]);
    }


    public function exibirCarrinho()
    {
        $idCliente = auth()->guard('cliente')->id();

        $produtos = DB::select('CALL produtos_carrinho(?)', [$idCliente]);

        // Retorna a view 'carrinho' com os dados dos estabelecimentos populares
        return view('carrinho', compact('produtos'));
    }

    public function adicionarProdutoCarrinho(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idProduto = $request->input('produto');
        $qtdProduto = $request->input('qtd_produto');
        $dataAdicao = now(); // Sempre define a data atual
        $idRes = $request->input('id_estab');

        // Verifica se já existe um produto no carrinho de outro estabelecimento
        $produtoOutroEstab = DB::table('carrinho')
            ->join('produtos', 'carrinho.id_produto', '=', 'produtos.id_produto')
            ->where('carrinho.id_cliente', $idCliente)
            ->where('produtos.id_estab', '!=', $idRes)
            ->exists();

        if ($produtoOutroEstab) {
            return redirect()->back()->with('error', 'Você já tem produtos de outro estabelecimento no carrinho. Esvazie o carrinho antes de adicionar novos produtos.');
        }

        // Verifica se o produto já está no carrinho do cliente
        $produtoExistente = DB::table('carrinho')
            ->where('id_cliente', $idCliente)
            ->where('id_produto', $idProduto)
            ->first();

        if ($produtoExistente) {
            // Se o produto já existe, apenas atualiza a quantidade
            DB::table('carrinho')
                ->where('id_cliente', $idCliente)
                ->where('id_produto', $idProduto)
                ->update([
                    'qtd_produto' => $produtoExistente->qtd_produto + $qtdProduto,
                    'data_adicao' => $dataAdicao
                ]);
        } else {
            // Se não existir, insere um novo registro
            DB::table('carrinho')->insert([
                'id_cliente' => $idCliente,
                'id_produto' => $idProduto,
                'qtd_produto' => $qtdProduto,
                'data_adicao' => $dataAdicao,
            ]);
        }

        return redirect()->route('carrinho')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function removerProdutoCarrinho(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idProduto = $request->input('produto');

        DB::table('carrinho')
        ->where('id_cliente', $idCliente)
        ->where('id_produto', $idProduto)
        ->delete();
            
        return redirect()->back()->with('success', 'Produto removido do carrinho!');
    }

    public function diminuirQuantidadeCarrinho(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idProduto = $request->input('produto');

        // Buscar o produto no carrinho
        $produto = DB::table('carrinho')
        ->where('id_cliente', $idCliente)
        ->where('id_produto', $idProduto)
        ->first();

        if ($produto) {
            // Atualizar a quantidade no carrinho
            $produto = DB::table('carrinho')
            ->where('id_cliente', $idCliente)
            ->where('id_produto', $idProduto)
            ->update([
                'qtd_produto'=> $produto->qtd_produto - 1,
                'data_adicao' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Quantidade do produto reduzida!');
    }

    public function aumentarQuantidadeCarrinho(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idProduto = $request->input('produto');

        // Buscar o produto no carrinho
        $produto = DB::table('carrinho')
        ->where('id_cliente', $idCliente)
        ->where('id_produto', $idProduto)
        ->first();

        if ($produto) {
            // Atualizar a quantidade no carrinho
            DB::table('carrinho')
                ->where('id_cliente', $idCliente)
                ->where('id_produto', $idProduto)
                ->update([
                    'qtd_produto' => $produto->qtd_produto + 1,
                    'data_adicao' => now()
                ]);
        }

        return redirect()->back()->with('success', 'Quantidade do produto aumentada!');
    }

    public function exibirEnderecos()
    {
        $idCliente = auth()->guard('cliente')->id();

        $enderecos = DB::select('CALL exibir_enderecos_cliente(?)', [$idCliente]);
    
        return view('checkout_endereco', compact('enderecos'));
    }

    public function exibirFormasPagamento(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $formasPagamento = DB::table('formas_pagamentos')->get();

        session(['id_endereco' => $request->endereco]);

        return view('checkout_pagamento', compact('formasPagamento'));
    }

    public function realizarPedido(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idPagamento = $request->input('pagamento');
        $idEndereco = session('id_endereco');

        if (!$idEndereco) {
            return redirect()->back()->with('error', 'Endereço não selecionado.');
        }

        Pedido::realizarPedido($idCliente, $idEndereco, $idPagamento);

        return redirect()->route('pedidos_cliente')->with('success', 'Pedido realizado!');
    }

    public function avaliarPedido(Request $request, $id)
    {
        $idCliente = Auth::guard('cliente')->id();
        $pedido = DB::select("SELECT * FROM pedidos WHERE id_pedido = ?", [$id]);

        if (empty($pedido)) { // Como DB::select() retorna um array, verificamos se está vazio
            return redirect()->back()->with('error', 'Pedido não encontrado.');
        }

        Avaliacao::avaliarPedido($idCliente, $id, $request->nota);

        return redirect()->back()->with('success', 'Avaliação realizada com sucesso!');
    }

    public function exibirPaginaPedidos()
    {
        $idCliente = Auth::guard('cliente')->id();

        // Executando a procedure e obtendo os pedidos
        $pedidos = DB::select("CALL exibir_pedidos_cliente(?)", [$idCliente]);

        // Verificar quais pedidos já foram avaliados e obter a nota
        foreach ($pedidos as $pedido) {
            $avaliacao = DB::table('avaliacoes')
                ->where('id_pedido', $pedido->id_pedido)
                ->select('nota')
                ->first(); // Retorna a primeira correspondência

            // Adiciona os dados de avaliação ao pedido
            $pedido->avaliado = !is_null($avaliacao);
            $pedido->nota = $avaliacao->nota ?? null;
        }

        return view('pedidos_cliente', compact('pedidos'));
    }

    public function receberPedido(Request $request, $id)
    {
        // Verificar se o pedido existe
        $pedido = DB::select("SELECT * FROM pedidos WHERE id_pedido = ?", [$id]);

        if (empty($pedido)) { // Como DB::select() retorna um array, verificamos se está vazio
            return redirect()->back()->with('error', 'Pedido não encontrado.');
        }

        // Atualizar o status do pedido
        DB::update("UPDATE pedidos SET status_entrega = ? WHERE id_pedido = ?", [$request->novo_status, $id]);

        return redirect()->back()->with('success', 'Pedido finalizado!');
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
        $idCliente = Auth::guard('cliente')->id();

        $cadastro = DB::table('clientes')
        ->where('id_cliente', $idCliente)->get();

        return view('info_cliente', compact('cadastro'));
    }

    // Método para salvar alterações
    public function alterarCadastro(Request $request) 
    {
        // Captura o id do cliente da sessão
        $idCliente = Auth::guard('cliente')->id();

        // Validação dos dados
        $request->validate([
            'telefone' => ['required', new validaCelular],
            'email' => ['required', 'email', 'max:100', 'unique:clientes,email,' . $idCliente . ',id_cliente',],
        ]);

        // Capturando os dados validados
        $telefone = $request->input('telefone');
        $email = $request->input('email');

        // Atualizando os dados no modelo
        Cliente::atualizarCliente($idCliente, $telefone, $email);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
    }

    public function esqueceuSenhaCliente(Request $request)
    {
        // Corrigido para corresponder ao campo correto
        $email = $request->input('emailResetSenha'); 
    
        // Buscar o cliente pelo email no banco de dados
        $cliente = Cliente::where('email', $email)->first();
    
        // Verificar se o cliente foi encontrado
        if ($cliente) {
            // Gerar um token para redefinição de senha
            $token = Str::random(60);
            
            // Inserir o token no banco de dados para esse email
            ResetSenha::create([
                'id_usuario' => $cliente->id_cliente,
                'tipo_usuario' => 'cliente',
                'email' => $cliente->email,
                'criado_em' => now(),
                'token' => $token,
            ]);
    
            // Enviar o email de redefinição de senha
            Mail::to($cliente->email)->send(new ResetSenhaEmail($cliente, $token, 'cliente'));
    
            return redirect()->back()->with('status', 'Email de redefinição de senha enviado!');
        } else {
            return redirect()->back()->with('error', 'Email não encontrado');
        }
    }

    public function resetSenhaCliente(Request $request) 
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('index')->with('error', 'Acesso inválido.');
        }
    
        $resetRecord = ResetSenha::where('email', $email)->where('token', $token)->first();
    
        if (!$resetRecord) {
            return redirect()->route('index_cliente')->with('error', 'Link de redefinição de senha inválido ou expirado.');
        }
    
        // Busca o cliente pelo ID
        $cliente = Cliente::where('email', $email)->first();

        if (Carbon::parse($resetRecord->criado_em)->addMinutes(1)->isPast()) {
            LogsToken::create([
                'id_usuario' => $cliente->id_cliente,
                'email' => $resetRecord->email,
                'motivo' => 'token expirado - redefinição de senha',
                'tipo_usuario' => 'cliente',
                'token' => $resetRecord->token,
                'criado_em' => $resetRecord->criado_em,
                'usado_em' => now(),
            ]);
    
            ResetSenha::where('email', $email)->where('tipo_usuario', 'cliente')->delete();
    
            return redirect()->route('index_cliente')->with('error', 'O link de redefinição de senha expirou.');
        }
    
        session(['email' => $email, 'token' => $token]);
    
        return view('nova_senhaCliente', compact('token', 'email'));
    }    

    public function definirNovaSenhaCliente(Request $request)
    {
        // Valida a entrada
        $request->validate([
            'new_password' => 'required|min:8', // Adicione outras regras de validação conforme necessário
        ]);

        // Obtém o email da sessão
        $email = session('email');

        // Verifique se o token é válido e se o email existe na tabela resets_senha_clientes
        $resetRecord = ResetSenha::where('email', $email)->first();
    
        if (!$resetRecord) {
            return redirect()->route('index_cliente')->with('error', 'Link de redefinição de senha inválido ou expirado.');
        }else {

            // Busca o cliente pelo ID
            $cliente = Cliente::where('email', $email)->first();

            LogsToken::create([
                'id_usuario' => $cliente->id_cliente,
                'email' => $resetRecord->email,
                'motivo' => 'redefinição de senha',
                'tipo_usuario' => 'cliente',
                'token' => $resetRecord->token,
                'criado_em' => $resetRecord->criado_em,
                'usado_em' => now(),
            ]);

            ResetSenha::where('email', $email)->where('tipo_usuario', 'cliente')->delete();
            
            // Atualiza a senha
            $cliente->senha = Hash::make($request->input('new_password'));
            $cliente->save();
            
            return redirect()->route('index_cliente')->with('success', 'Senha redefinida com sucesso');
        }
    }

    public function controleEnderecos() 
    {
        // Captura o id do cliente da sessão
        $idCliente = Auth::guard('cliente')->id();

        $enderecos =  DB::select('CALL exibir_enderecos_cliente(?)', [$idCliente]);

        return view('endereco_cliente', compact('enderecos'));
    }

    public function cadastrarEndereco(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'cep' => 'required|string|max:9', // 00000-000 formato
            'estado' => 'required|string|max:2',
            'cidade' => 'required|string|max:100',
            'bairro' => 'required|string|max:100',
            'logradouro' => 'required|string|max:150',
            'numero' => 'required|numeric'
        ]);

        // Captura o id do cliente da sessão
        $idCliente = Auth::guard('cliente')->id();

        try {
            // Chamada do método no Model
            Endereco::cadastrar(
                $idCliente, 
                $request->logradouro, 
                $request->numero, 
                $request->bairro, 
                $request->cidade, 
                $request->estado, 
                $request->cep
            );

            return redirect()->back()->with('success', 'Endereço cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar endereço: ' . $e->getMessage());
        }
    }

    public function editarEndereco(Request $request, $id)
    {
        // Buscar o endereço pelo ID
        $endereco = DB::select("SELECT * FROM enderecos WHERE id_endereco = ?", [$id]);

        if (!$endereco) {
            return redirect()->back()->with('error', 'Endereço não encontrado.');
        }

        // Atualizar os dados do endereço
        DB::update("UPDATE enderecos SET logradouro = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, cep = ? WHERE id_endereco = ?", [
            $request->logradouro,
            $request->numero,
            $request->bairro,
            $request->cidade,
            $request->estado,
            $request->cep,
            $id
        ]);

        return redirect()->back()->with('success', 'Endereço atualizado com sucesso!');
    }

    public function excluirEndereco(Request $request, $id)
    {
        // Captura o id do cliente da sessão
        $idCliente = Auth::guard('cliente')->id();

        DB::table('enderecos_clientes')
        ->where('id_endereco', $id)
        ->where('id_cliente', $idCliente)
        ->delete();
    
        return redirect()->back()->with('success', 'Endereço excluído com sucesso!');
    }

    public function exibirChamados()
    {
        $idCliente = Auth::guard('cliente')->id();

        // Seleciona as últimas mensagens de cada chat, agrupando pelo id_chat
        $mensagens = DB::table('mensagens_cliente')
        ->where('id_remetente', $idCliente)
        ->orWhere('id_destinatario', $idCliente)
        ->orderBy('data_envio', 'desc')  // Ordena por data de envio para pegar a última mensagem
        ->get()
        ->groupBy('id_chat')  // Agrupa as mensagens pelo id_chat
        ->map(function ($mensagensChat) {
            return $mensagensChat->first();  // Pega a primeira mensagem de cada grupo (última mensagem do chat)
        });

        $categorias = DB::table('categorias_chamado')
        ->get();

        return view('chamados_cliente', compact('mensagens', 'categorias', 'idCliente'));
    }

    public function buscarMensagens($idChat)
    {
        $idCliente = Auth::guard('cliente')->id();

        // Busca todas as mensagens do chat específico
        $mensagens = DB::table('mensagens_cliente')
            ->where('id_chat', $idChat)
            ->orderBy('data_envio', 'asc')
            ->get();

        return response()->json($mensagens);
    }

    public function abrirChamado(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $idChat = (string) Str::uuid();

        // Criando a mensagem
        $mensagens = MensagensCliente::create([
            'id_chat' => $idChat,
            'id_remetente' => $idCliente,
            'id_destinatario' => 1,
            'categoria' => $request->categoria,
            'mensagem' => $request->mensagem,
            'data_envio' => now(),
            'ativo' => 1,
        ]);

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
    }

    public function responderChamado(Request $request)
    {
        $idCliente = auth()->guard('cliente')->id();
        $chatId = $request->input('id_chat');
        $resposta = $request->input('resposta');
    
        // Busca a última mensagem do chat para descobrir o destinatário
        $ultimaMensagem = MensagensCliente::where('id_chat', $chatId)
            ->orderBy('data_envio', 'desc')
            ->first();
    
        // Se não houver mensagens no chat, atribui o próprio admin como destinatário
        if (!$ultimaMensagem) {
            $idDestinatario = 1;  // Caso não exista histórico, o destinatário pode ser o admin ou qualquer outra lógica
        } else {
            // Verifica quem é o destinatário da última mensagem
            $idDestinatario = ($ultimaMensagem->id_remetente == Auth::id()) 
                ? $ultimaMensagem->id_destinatario 
                : $ultimaMensagem->id_remetente;

            $categoria = $ultimaMensagem->categoria;
        }
    
        // Cria uma nova mensagem no banco de dados
        $novaMensagem = new MensagensCliente();
        $novaMensagem->id_chat = $chatId;
        $novaMensagem->id_remetente = $idCliente;  // O remetente é o usuário logado
        $novaMensagem->id_destinatario = $idDestinatario;  // O destinatário é o oposto da última mensagem
        $novaMensagem->categoria = $categoria;
        $novaMensagem->mensagem = $resposta;
        $novaMensagem->data_envio = now();  // A data de envio é a hora atual
        $novaMensagem->ativo = 1;
        $novaMensagem->save();
    
        // Redireciona ou retorna uma resposta para o usuário
        return redirect()->back()->with('success', 'Resposta enviada com sucesso!');
    }

    public function favoritarProduto($idProduto)
    {
        $idCliente = Auth::guard('cliente')->id();

        ProdutoFavorito::create([
            'id_produto' => $idProduto,
            'id_cliente' => $idCliente,
        ]);
    
        return back()->with('success', 'Produto adicionado aos favoritos!');
    }

    public function desfavoritarProduto($idProduto)
    {
        $idCliente = Auth::guard('cliente')->id();

        ProdutoFavorito::where('id_produto', $idProduto)
            ->where('id_cliente', $idCliente)
            ->delete();

        return back()->with('success', 'Produto removido dos favoritos.');
    }

    public function exibirFavoritos()
    {
        $idCliente = Auth::guard('cliente')->id();

        $favoritos = DB::select('CALL exibir_produtos_favoritos(?)', [$idCliente]);

        $produtoFavorito = ProdutoFavorito::where('id_cliente', auth()->id())->pluck('id_produto')->toArray();

        return view('favoritos', compact('favoritos', 'produtoFavorito'));
    }

    public function pesquisar(Request $request)
    {
        $termo = $request->input('termoPesquisa');

        // Busca por nome (ajuste os campos conforme seu banco)
        $produtos = Produto::with('estabelecimento')->where('nome', 'like', "%{$termo}%")->get();
        $estabelecimentos = Estabelecimento::where('nome_fantasia', 'like', "%{$termo}%")->get();
        $favoritos = ProdutoFavorito::where('id_cliente', auth()->id())->pluck('id_produto')->toArray();

        return view('resultados_pesquisa', compact('termo', 'produtos', 'estabelecimentos', 'favoritos'));
    }

    public function autocomplete(Request $request)
    {
        $termo = $request->input('termoPesquisa');

        // Busca por nome (ajuste os campos conforme seu banco)
        $produtos = Produto::where('nome', 'like', "%{$termo}%")
                        ->limit(5) // Limita o número de resultados
                        ->get(['nome']); // Apenas o campo necessário
        $estabelecimentos = Estabelecimento::where('nome_fantasia', 'like', "%{$termo}%")
                                ->limit(5) // Limita o número de resultados
                                ->get(['nome_fantasia']); // Apenas o campo necessário

        return response()->json([
            'produtos' => $produtos,
            'estabelecimentos' => $estabelecimentos
        ]);
    }

    public function alterarSenha(){
        $idCliente = Auth::guard('cliente')->id();

        $cadastro = DB::table('clientes')
        ->where('id_cliente', $idCliente)->get();

        return view('alterar_senhaCliente', compact('cadastro'));
    }

    public function confirmarSenha(Request $request)
    {
        $request->validate([
            'senhaAntiga' => 'required',
            'novaSenha' => 'required|min:8',
            'confirmarSenha' => 'required|same:novaSenha',
        ]);

        $idCliente = Auth::guard('cliente')->id();

        $cliente = DB::table('clientes')->where('id_cliente', $idCliente)->first();

        if (!$cliente) {
            return back()->with('error', 'Usuário não encontrado.');
        }

        if (!Hash::check($request->senhaAntiga, $cliente->senha)) {
            return back()->with('error', 'Senha atual incorreta.');
        }

        DB::table('clientes')->where('id_cliente', $idCliente)->update([
            'senha' => Hash::make($request->novaSenha)
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}