<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estabelecimento;
use App\Models\ConfirmacaoEmail;
use App\Mail\ConfirmaEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB; // Para interagir com o banco de dados
use App\Http\Controllers\Controller; // Para estender a classe base do Laravel
use App\Rules\validaCelular;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetSenhaEmail; 
use App\Models\ResetSenha; 
use App\Models\LogsToken;   
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EstabelecimentoController extends Controller
{
    public function cadastrarEstabelecimento(Request $request)
    {
        try {
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

            // Chama o método para criar o estabelecimento no model
            $estabelecimento = Estabelecimento::cadastrarEstabelecimento($validatedData);

            if (!$estabelecimento) {
                return redirect()->back()->with('error', 'Erro ao cadastrar estabelecimento. Tente novamente.');
            }

            // Gerar o token de confirmação
            $token = Str::random(60);

            // Inserir o token no banco de dados
            $confirmacao = ConfirmacaoEmail::create([
                'email' => $estabelecimento->email,
                'token' => $token,
                'criado_em' => now(),
                'id_usuario' => $estabelecimento->id_estab,
                'tipo_usuario' => 'estabelecimento',
            ]);

            if (!$confirmacao) {
                return redirect()->back()->with('error', 'Erro ao cadastrar estabelecimento. Tente novamente.');
            }

            // Envio do e-mail de confirmação
            try {
                Mail::to($estabelecimento->email)->send(new ConfirmaEmail($token, $estabelecimento->email, 'estabelecimento'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao enviar e-mail de confirmação.');
            }

            return redirect()->route('index_restaurante')->with('success', 'Estabelecimento cadastrado com sucesso!');
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

        $email_verificado = Estabelecimento::where('email', $validatedData['emailLogin'])->where('email_verificado', 1)->first();

        // Tentar autenticar o cliente usando o guard 'cliente'
        if (Auth::guard('estabelecimento')->attempt(['email' => $request->input('emailLogin'), 'password' => $request->input('senhaLogin')])) {
            if($email_verificado){
                // Login bem-sucedido, redirecionar para a página inicial do profissional
                return redirect()->route('home_restaurante')->with('success', 'Login realizado com sucesso!');
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
        // Obtendo o estabelecimento autenticado
        $estabelecimento = Auth::guard('estabelecimento')->user();

        // Pegando o ID do estabelecimento logado
        $id_estabelecimento = $estabelecimento->id_estab; 

        // Executando o procedure e obtendo os pedidos
        $pedidos = DB::select("CALL exibir_pedidos_estabelecimento(?)", [$id_estabelecimento]);

        // Contadores para os diferentes status
        $totalPedidos = count($pedidos);
        $pendentes = collect($pedidos)->where('status_entrega', 2)->count();
        $preparacao = collect($pedidos)->where('status_entrega', 3)->count();
        $emRota = collect($pedidos)->where('status_entrega', 4)->count();
        $finalizados = collect($pedidos)->where('status_entrega', 5)->count();

        $estoqueBaixo = DB::table('produtos')
                            ->where('id_estab', $id_estabelecimento)
                            ->where('qtd_estoque', '<', 10)
                            ->count();

        return view('home_restaurante', compact('totalPedidos', 'pendentes', 'preparacao', 'emRota', 'finalizados', 'estoqueBaixo'));
    }

    public function exibirPaginaPedidos()
    {
        // Obtendo o estabelecimento autenticado
        $id_estabelecimento = Auth::guard('estabelecimento')->id();

        // Executando o procedure e obtendo os pedidos
        $pedidos = DB::select("CALL exibir_pedidos_estabelecimento(?)", [$id_estabelecimento]);

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

        return view('pedidos_restaurante', compact('pedidos'));
    }

    public function alterarStatus(Request $request, $id)
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
    public function exibirAdmRestaurante() 
    {
        return view('adm_restaurante');
    }

    public function exibirInfoRestaurante() 
    {
        $id_res = Auth::guard('estabelecimento')->id();

        $cadastro = DB::table('estabelecimentos')
        ->where('id_estab', $id_res)->get();

        return view('info_restaurante', compact('cadastro'));
    }

    public function alteraCadastro(Request $request) 
    {
        $id_res = Auth::guard('estabelecimento')->id();

        // Validação dos dados
        $request->validate([
            'telefone' => ['required', 'string', 'max:11'],
            'email' => ['required', 'email', 'max:100', 'unique:estabelecimentos,email,' . $id_res . ',id_estab',],
        ]);

        // Capturando os dados validados
        $telefone = $request->input('telefone');
        $email = $request->input('email');

        // Atualizando os dados no modelo
        Estabelecimento::atualizarEstabelecimento($id_res, $telefone, $email);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
    }

    public function exibirProdutosRestaurante() 
    {
        $id_res = Auth::guard('estabelecimento')->id();

        $produtos = DB::table('produtos')
        ->join('categorias_produtos', 'produtos.id_categoria', '=', 'categorias_produtos.id_categoria')
        ->where('produtos.id_estab', $id_res)
        ->select('produtos.*', 'categorias_produtos.descricao as categoria_descricao') 
        ->get();

        // Obter as categorias dos produtos
        $categorias = DB::table('categorias_produtos')->get();

        return view('produtos_restaurante', compact('produtos', 'categorias'));
    }

    public function cadastrarProduto(Request $request)
    {
        // Validação dos dados do formulário
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'id_categoria' => 'required|exists:categorias_produtos,id_categoria',
            'qtd_estoque' => 'required|integer|min:0',
        ]);

        // Inserção do produto na tabela 'produtos'
        DB::table('produtos')->insert([
            'nome' => $validated['nome'],
            'valor' => $validated['valor'],
            'id_categoria' => $validated['id_categoria'],
            'qtd_estoque' => $validated['qtd_estoque'],
            'id_estab' => Auth::guard('estabelecimento')->id(), // Estabelecimento do usuário logado
        ]);

        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->route('produtos_restaurante')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function exibirEstoqueRestaurante()
    {
        $id_res = Auth::guard('estabelecimento')->id();

        $produtos = DB::table('produtos')
        ->where('id_estab', $id_res)
        ->get();

        return view('estoque_restaurante', compact('produtos'));
    }

    public function atualizarEstoque(Request $request, $id)
    {
        $request->validate([
            'qtd_estoque' => 'required|integer|min:0'
        ]);

        // Atualiza diretamente no banco sem precisar recuperar o objeto
        DB::table('produtos')
            ->where('id_produto', $id)
            ->update(['qtd_estoque' => $request->qtd_estoque]);

        return redirect()->back()->with('success', 'Estoque atualizado com sucesso!');
    }

    public function exibirDashboardRestaurante()
    {
        // Supondo que você tenha o ID do estabelecimento armazenado na sessão ou no contexto do usuário logado.
        $idEstab = Auth::guard('estabelecimento')->id(); // Substitua pelo método que você usa para obter o id do estabelecimento

        // Obter o total de clientes
        $clientes = DB::select('CALL clientes_por_estabelecimento(?)', [$idEstab]);
        $totalClientes = $clientes ? count($clientes) : 0;

        // Obter o total de pedidos do estabelecimento
        $pedidos = DB::select('CALL exibir_pedidos_f_estabelecimento(?)', [$idEstab]);
        $totalPedidos = $pedidos ? count($pedidos) : 0;

        // Obter o prato mais vendido do estabelecimento (usando a view pedidos_estabelecimento)
        $produtosMaisPopulares = DB::select('CALL exibir_produtos_mais_populares_por_estabelecimento(?)', [$idEstab]);
        $pratoMaisVendido = $produtosMaisPopulares ? $produtosMaisPopulares[0] : null;

        // Obter o faturamento mensal do estabelecimento (soma dos valores dos pedidos no mês atual)
        $faturamentoMensalData = DB::select('CALL faturamento_estabelecimento(?)', [$idEstab]);
        $faturamentoMensal = collect($faturamentoMensalData)->firstWhere('mes', now()->month);

        // Se não houver dados para o mês atual, o faturamento será 0
        $faturamentoMensal = $faturamentoMensal ? $faturamentoMensal->faturamento : 0;

        // Obter pedidos finalizados por mês do estabelecimento
        $pedidosPorMes = DB::select('CALL contagem_pedidos_f_mes(?)', [$idEstab]);

        // Obter pedidos cancelados por mês do estabelecimento
        $canceladosPorMes = DB::select('CALL contagem_pedidos_c_mes(?)', [$idEstab]);

        // Obter categorias populares
        $categoriasPopulares = DB::select('CALL exibir_categorias_mais_populares_por_estabelecimento(?)', [$idEstab]);

        // Preparar os dados para a view, sem alterar as variáveis existentes
        $data = [
            'total_clientes' => $totalClientes,
            'total_pedidos' => $totalPedidos,
            'prato_mais_vendido' => $pratoMaisVendido ? $pratoMaisVendido->produto : 'Nenhum prato vendido',
            'faturamento_mensal' => number_format($faturamentoMensal, 2, ',', '.'),
            'pratos_labels' => [$pratoMaisVendido->produto], // Usando apenas o prato mais vendido
            'pratos_vendas' => [$pratoMaisVendido->total_vendas],
            
            // Novos dados para os gráficos (sem map())
            'pedidos_por_mes' => [],
            'produtos_populares' => [],
            'categorias_populares' => [],
        ];

        // Preencher os dados de pedidos finalizados por mês
        foreach ($pedidosPorMes as $item) {
            $data['pedidos_por_mes'][] = [
                'mes' => $item->mes,
                'ano' => $item->ano, // Certifique-se de que "ano" está presente
                'total_pedidos' => $item->total_pedidos,
            ];
        }

        // Preencher os dados de pedidos cancelados por mês
        foreach ($canceladosPorMes as $item) {
            $data['cancelados_por_mes'][] = [
                'mes' => $item->mes,
                'ano' => $item->ano, // Certifique-se de que "ano" está presente
                'total_pedidos' => $item->total_pedidos,
            ];
        }

        // Preencher os dados de produtos populares
        foreach ($produtosMaisPopulares as $produto) {
            $data['produtos_populares'][] = [
                'nome_produto' => $produto->produto,
                'total_vendas' => $produto->total_vendas,
            ];
        }

        // Preencher os dados de categorias populares
        foreach ($categoriasPopulares as $categoria) {
            $data['categorias_populares'][] = [
                'nome_categoria' => $categoria->descricao,
                'total_vendas' => $categoria->total_vendas,
            ];
        }

        foreach ($faturamentoMensalData as $faturamento) {
            $data['faturamento'][] = [
                'ano' => $faturamento->ano,
                'mes' => $faturamento->mes,
                'faturamento' => $faturamento->faturamento
            ];
        }

        // Retornar a view com os dados
        return view('dashboard_restaurante', compact('data'));
    }

    public function esqueceuSenhaEstabelecimento(Request $request){
        // Corrigido para corresponder ao campo correto
        $email = $request->input('emailResetSenhaEstab'); 
    
        // Buscar o cliente pelo email no banco de dados
        $estabelecimento = Estabelecimento::where('email', $email)->first();
    
        // Verificar se o cliente foi encontrado
        if ($estabelecimento) {
            // Gerar um token para redefinição de senha
            $token = Str::random(60);
            
            // Inserir o token no banco de dados para esse email
            ResetSenha::create([
                'id_usuario' => $estabelecimento->id_estab,
                'tipo_usuario' => 'estabelecimento',
                'email' => $estabelecimento->email,
                'criado_em' => now(),
                'token' => $token,
            ]);
    
            // Enviar o email de redefinição de senha
            Mail::to($estabelecimento->email)->send(new ResetSenhaEmail($estabelecimento, $token, 'estabelecimento'));
    
            return redirect()->back()->with('status', 'Email de redefinição de senha enviado!');
        } else {
            return redirect()->back()->with('error', 'Email não encontrado');
        }
    }
    

    public function resetSenhaEstabelecimento(Request $request){
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('index')->with('error', 'Acesso inválido.');
        }
    
        $resetRecord = ResetSenha::where('email', $email)->where('token', $token)->first();
    
        if (!$resetRecord) {
            return redirect()->route('index_restaurante')->with('error', 'Link de redefinição de senha inválido ou expirado.');
        }

        $estabelecimento = Estabelecimento::where('email', $email)->first();
    
        if (Carbon::parse($resetRecord->criado_em)->addMinutes(1)->isPast()) {
            LogsToken::create([
                'id_usuario' => $estabelecimento->id_estab,
                'email' => $resetRecord->email,
                'motivo' => 'token expirado - redefinição de senha',
                'tipo_usuario' => 'estabelecimento',
                'token' => $resetRecord->token,
                'criado_em' => $resetRecord->criado_em,
                'usado_em' => now(),
            ]);
    
            ResetSenha::where('email', $email)->where('tipo_usuario', 'estabelecimento')->delete();
    
            return redirect()->route('index_restaurante')->with('error', 'O link de redefinição de senha expirou.');
        }
    
        session(['email' => $email, 'token' => $token]);
    
        return view('nova_senhaEstab', compact('token', 'email'));
    }

    public function definirNovaSenhaEstabelecimento(Request $request){
        // Valida a entrada
        $request->validate([
            'new_password' => 'required|min:8', // Adicione outras regras de validação conforme necessário
        ]);

        /// Obtém o email da sessão
        $email = session('email');

        // Verifique se o token é válido e se o email existe na tabela resets_senha_clientes
        $resetRecord = ResetSenha::where('email', $email)->first();
    
        if (!$resetRecord) {
            return redirect()->route('index_restaurante')->with('error', 'Link de redefinição de senha inválido ou expirado.');
        }else {

            // Busca o cliente pelo ID
            $estabelecimento = Estabelecimento::where('email', $email)->first();

            LogsToken::create([
                'id_usuario' => $estabelecimento->id_estab,
                'email' => $resetRecord->email,
                'motivo' => 'redefinição de senha',
                'tipo_usuario' => 'estabelecimento',
                'token' => $resetRecord->token,
                'criado_em' => $resetRecord->criado_em,
                'usado_em' => now(),
            ]);

            ResetSenha::where('email', $email)->where('tipo_usuario', 'estabelecimento')->delete();

            // Atualiza a senha
            $estabelecimento->senha = Hash::make($request->input('new_password'));
            $estabelecimento->save();
            
            return redirect()->route('index_restaurante')->with('success', 'Senha redefinida com sucesso');
        }
    }
}
