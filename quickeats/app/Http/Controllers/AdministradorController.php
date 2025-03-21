<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrador;
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
        $restaurantes = DB::table('estabelecimentos')->get();

        return view('admin_restaurantes', compact('restaurantes'));
    }

    public function exibirClientes()
    {
        $clientes = DB::table('clientes')->get();
        
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
}
