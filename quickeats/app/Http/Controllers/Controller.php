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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\MensagensNaoCad;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function exibirIndex() 
    {
        $estabPopulares = DB::select("SELECT * FROM estabelecimentos_populares");
        $prodPopulares = DB::select("SELECT * FROM produtos_populares");

        return view('index', compact('estabPopulares', 'prodPopulares'));
    }

    public function exibirIndexCliente() 
    {
        return view('index_cliente');
    }

    public function exibirIndexRestaurante()
    {
        return view('index_restaurante');
    }

    // Função para confirmar o e-mail
    public function confirmaEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        $tipo_usuario = $request->query('tipo_usuario');

        if (!$token || !$email) {
            return redirect()->route('index')->with('error', 'Acesso inválido.');
        }

        $resetRecord = ConfirmacaoEmail::where('email', $email)
            ->where('token', $token)
            ->where('tipo_usuario', $tipo_usuario)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('index')->with('error', 'Token inválido ou expirado.');
        }

        if ($tipo_usuario == 'cliente') {
            $cliente = Cliente::where('email', $email)->first();

            // Verificar se o token está expirado (válido por 1 minuto)
            if (Carbon::parse($resetRecord->criado_em)->addMinutes(1)->isPast()) {
                LogsToken::create([
                    'email' => $email,
                    'token' => $token,
                    'criado_em' => $resetRecord->criado_em,
                    'usado_em' => now(),
                    'motivo' => 'token expirado - confirmação de email',
                    'id_usuario' => $cliente->id_cliente,
                    'tipo_usuario' => 'cliente',
                ]);

                ConfirmacaoEmail::where('email', $email)->where('tipo_usuario', 'cliente')->delete();
                return redirect()->route('index_cliente')->with('error', 'Token expirado. Solicite uma nova confirmação de e-mail.');
            }

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
        } else if ($tipo_usuario == 'estabelecimento') {
            $estabelecimento = Estabelecimento::where('email', $email)->first();
            
            // Verificar se o token está expirado (válido por 1 minuto)
            if (Carbon::parse($resetRecord->criado_em)->addMinutes(1)->isPast()) {
                LogsToken::create([
                    'email' => $email,
                    'token' => $token,
                    'criado_em' => $resetRecord->criado_em,
                    'usado_em' => now(),
                    'motivo' => 'token expirado - confirmação de email',
                    'id_usuario' => $estabelecimento->id_estab,
                    'tipo_usuario' => 'estabelecimento',
                ]);

                ConfirmacaoEmail::where('email', $email)->where('tipo_usuario', 'estabelecimento')->delete();
                return redirect()->route('index_restaurante')->with('error', 'Token expirado. Solicite uma nova confirmação de e-mail.');
            }
            
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

        return redirect()->route('index')->with('error', 'Token inválido ou expirado.');
    }

    public function exibirFaqs()
    {
        return view('faqs');
    }

    public function exibirSobre()
    {
        return view('sobre');
    }

    public function exibirContato()
    {
        return view('contato');
    }

    public function abrirChamado(Request $request)
    {
        // Criando a mensagem
        $mensagens = MensagensNaoCad::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem,
            'data_envio' => now(),
            'ativo' => 1,
        ]);

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
    }
}