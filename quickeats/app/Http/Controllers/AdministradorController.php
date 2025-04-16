<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\MensagensCliente;
use App\Models\MensagensEstab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    public function realizarLogin(Request $request)
    {
        // Validação dos campos de entrada
        $validatedData = $request->validate([
            'emailLogin' => 'required|string|email|max:255',
            'senhaLogin' => 'required|string|min:8',
        ]);

        // Tentar autenticar o cliente usando o guard 'cliente'
        if (Auth::guard('administrador')->attempt(['email' => $request->input('emailLogin'), 'password' => $request->input('senhaLogin')])) {
            // Login bem-sucedido, redirecionar para a página inicial do profissional
            return redirect()->route('home_admin')->with('success', 'Login realizado com sucesso!');
        } else {
            // Login falhou, redirecionar de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Email ou senha inválidos');
        }
    }

    public function exibirPaginaInicial()
    {
        // Retorna a view 'home-cliente' com os dados dos estabelecimentos populares
        return view('home_admin');
    }

    public function realizarLogout(Request $request)
    {
        // Remover todas as variáveis de sessão
        Session::flush();

        // Redirecionar para a página de login
        return redirect('/administrador')->with('success', 'Logout realizado com sucesso.');
    }

    public function exibirRestaurantes()
    {
        $restaurantes = DB::table('estabelecimentos')->paginate(10);

        // Consulta para obter os horários de expediente de cada estabelecimento
        $horarios = DB::table('grades_horario')
                        ->whereIn('id_estab', $restaurantes->pluck('id_estab'))
                        ->get()
                        ->groupBy('id_estab'); // Agrupar por id_estab

        return view('admin_restaurantes', compact('restaurantes', 'horarios'));
    }


    public function exibirClientes()
    {
        $clientes = DB::table('clientes')->paginate(10);
        
        foreach ($clientes as $cliente) {
            $cliente->enderecos = DB::select("CALL exibir_enderecos_cliente(?)", [$cliente->id_cliente]);
        }

        return view('admin_clientes', compact('clientes'));
    }

    public function ativarCliente($id)
    {
        DB::table('clientes')->where('id_cliente', $id)->update(['perfil_ativo' => 1]);
        return redirect()->back()->with('success', 'Cliente ativado com sucesso!');
    }

    public function desativarCliente($id)
    {
        DB::table('clientes')->where('id_cliente', $id)->update(['perfil_ativo' => 0]);
        return redirect()->back()->with('success', 'Cliente desativado com sucesso!');
    }

    public function ativarRestaurantes($id)
    {
        DB::table('estabelecimentos')->where('id_estab', $id)->update(['perfil_ativo' => 1]);
        return redirect()->back()->with('success', 'Restaurante ativado com sucesso!');
    }

    public function desativarRestaurantes($id)
    {
        DB::table('estabelecimentos')->where('id_estab', $id)->update(['perfil_ativo' => 0]);
        return redirect()->back()->with('success', 'Restaurante desativado com sucesso!');
    }

    public function exibirChamados()
    {
        $idAdmin = Auth::guard('administrador')->id();

        // Seleciona as últimas mensagens de cada chat, agrupando pelo id_chat (mensagens_cliente)
        $mensagensCliente = DB::table('mensagens_cliente')
            ->where('id_remetente', $idAdmin)
            ->orWhere('id_destinatario', $idAdmin)
            ->orderBy('data_envio', 'desc')
            ->get()
            ->groupBy('id_chat')
            ->map(function ($mensagensChat) {
                return $mensagensChat->first();
            });

        // Seleciona as últimas mensagens de cada chat, agrupando pelo id_chat (mensagens_estab)
        $mensagensEstab = DB::table('mensagens_estab')
            ->where('id_remetente', $idAdmin)
            ->orWhere('id_destinatario', $idAdmin)
            ->orderBy('data_envio', 'desc')
            ->get()
            ->groupBy('id_chat')
            ->map(function ($mensagensChat) {
                return $mensagensChat->first();
            });

        // Seleciona mensagens ativas de usuários não cadastrados
        $mensagensNaoCad = DB::table('mensagens_nao_cad')
            ->where('ativo', 1) 
            ->orderBy('data_envio', 'desc')
            ->get();

        $categorias = DB::table('categorias_chamado')
            ->get();

        // Passar ambas as coleções separadamente para a view
        return view('chamados_admin', compact('mensagensCliente', 'mensagensEstab', 'mensagensNaoCad', 'categorias', 'idAdmin'));
    }

    public function buscarMensagens($idChat)
    {
        $idAdmin = Auth::guard('administrador')->id();

        // Busca todas as mensagens do chat específico (mensagens_cliente)
        $mensagensCliente = DB::table('mensagens_cliente')
            ->where('id_chat', $idChat)
            ->orderBy('data_envio', 'asc')
            ->get();

        // Busca todas as mensagens do chat específico (mensagens_estab)
        $mensagensEstab = DB::table('mensagens_estab')
            ->where('id_chat', $idChat)
            ->orderBy('data_envio', 'asc')
            ->get();

        // Combinar as coleções de mensagens
        $mensagens = $mensagensCliente->merge($mensagensEstab);

        return response()->json($mensagens);
    }

    public function responderChamadoCliente(Request $request)
    {
        $idAdmin = auth()->guard('administrador')->id();
        $chatId = $request->input('id_chat');
        $resposta = $request->input('resposta_cliente');
    
        // Busca a última mensagem do chat para descobrir o destinatário
        $ultimaMensagem = MensagensCliente::where('id_chat', $chatId)
            ->orderBy('data_envio', 'desc')
            ->first();
    
        // Se não houver mensagens no chat, atribui o próprio admin como destinatário
        if (!$ultimaMensagem) {
            $idDestinatario = $idAdmin;  // Caso não exista histórico, o destinatário pode ser o admin ou qualquer outra lógica
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
        $novaMensagem->id_remetente = $idAdmin;  // O remetente é o usuário logado
        $novaMensagem->id_destinatario = $idDestinatario;  // O destinatário é o oposto da última mensagem
        $novaMensagem->categoria = $categoria;
        $novaMensagem->mensagem = $resposta;
        $novaMensagem->data_envio = now();  // A data de envio é a hora atual
        $novaMensagem->ativo = 1;
        $novaMensagem->save();
    
        // Redireciona ou retorna uma resposta para o usuário
        return redirect()->back()->with('success', 'Resposta enviada com sucesso!');
    }

    public function responderChamadoEstab(Request $request)
    {
        $idAdmin = auth()->guard('administrador')->id();
        $chatId = $request->input('id_chat');
        $resposta = $request->input('resposta_estab');
    
        // Busca a última mensagem do chat para descobrir o destinatário
        $ultimaMensagem = MensagensEstab::where('id_chat', $chatId)
            ->orderBy('data_envio', 'desc')
            ->first();
    
        // Se não houver mensagens no chat, atribui o próprio admin como destinatário
        if (!$ultimaMensagem) {
            $idDestinatario = $idAdmin;  // Caso não exista histórico, o destinatário pode ser o admin ou qualquer outra lógica
        } else {
            // Verifica quem é o destinatário da última mensagem
            $idDestinatario = ($ultimaMensagem->id_remetente == Auth::id()) 
                ? $ultimaMensagem->id_destinatario 
                : $ultimaMensagem->id_remetente;

            $categoria = $ultimaMensagem->categoria;
        }
    
        // Cria uma nova mensagem no banco de dados
        $novaMensagem = new MensagensEstab();
        $novaMensagem->id_chat = $chatId;
        $novaMensagem->id_remetente = $idAdmin;  // O remetente é o usuário logado
        $novaMensagem->id_destinatario = $idDestinatario;  // O destinatário é o oposto da última mensagem
        $novaMensagem->categoria = $categoria;
        $novaMensagem->mensagem = $resposta;
        $novaMensagem->data_envio = now();  // A data de envio é a hora atual
        $novaMensagem->ativo = 1;
        $novaMensagem->save();
    
        // Redireciona ou retorna uma resposta para o usuário
        return redirect()->back()->with('success', 'Resposta enviada com sucesso!');
    }

    public function marcarComoRespondido(Request $request)
    {
        DB::table('mensagens_nao_cad')
            ->where('id_mensagem', $request->id_mensagem)
            ->update(['ativo' => 0]);

        return back()->with('success', 'Mensagem marcada como resolvida!');
    }

    public function planosAtivos() 
    {
        $restaurantes = DB::select('CALL exibir_planos()');
        
        return view('planos_admin', compact('restaurantes'));
    }
}