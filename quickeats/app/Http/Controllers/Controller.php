<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ConfirmacaoEmail;
use App\Models\LogsToken;
use App\Models\ResetSenha;
use App\Models\Cliente;
use App\Models\Estabelecimento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

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

    // Função para confirmar o e-mail
    public function confirmaEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        $tipo_usuario = $request->query('tipo_usuario');

        $resetRecord = ConfirmacaoEmail::where('email', $email)->where('token', $token)->where('tipo_usuario', $tipo_usuario)->first();

        if(!$token || !$email) {
            return redirect()->route('index')->with('error', 'Acesso inválido.');
        }


        if($tipo_usuario){
            if($tipo_usuario == 'cliente'){
                // Verifica se o e-mail está correto
                $cliente = Cliente::where('email', $email)->first();

                if ($cliente) {
                    $cliente->email_verificado = true;
                    $cliente->save();

                    LogsToken::create([
                        'email' => $email,
                        'token' => $token,
                        'criado_em' => $resetRecord->criado_em,
                        'usado_em' => now(),
                        'motivo' => 'confirmação de email',
                        'id_usuario' => $cliente->id_cliente,
                        'tipo_usuario' => 'cliente',
                    ]);
            
                    ConfirmacaoEmail::where('email', $email)->where('tipo_usuario', 'cliente')->delete();

                    return redirect()->route('index_cliente')->with('success', 'E-mail confirmado com sucesso!');
                }
            } else if($tipo_usuario == 'estabelecimento') {
                // Verifica se o e-mail está correto
                $estabelecimento = Estabelecimento::where('email', $email)->first();

                if ($estabelecimento) {
                    $estabelecimento->email_verificado = true;
                    $estabelecimento->save();

                    LogsToken::create([
                        'email' => $email,
                        'token' => $token,
                        'criado_em' => $resetRecord->criado_em,
                        'usado_em' => now(),
                        'motivo' => 'confirmação de email',
                        'id_usuario' => $estabelecimento->id_estab,
                        'tipo_usuario' => 'estabelecimento',
                    ]);
            
                    ConfirmacaoEmail::where('email', $email)->where('tipo_usuario', 'estabelecimento')->delete();

                    return redirect()->route('index_restaurante')->with('success', 'E-mail confirmado com sucesso!');
                }
            }
        } else {
            return redirect()->route('index')->with('error', 'Token inválido ou expirado.');
        }
    }

}
