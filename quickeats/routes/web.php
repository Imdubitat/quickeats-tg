<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstabelecimentoController;
use App\Http\Controllers\AdministradorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quickeats', [Controller::class, 'exibirIndex'])->name('index');
Route::get('/clientes', [Controller::class, 'exibirIndexCliente'])->name('index_cliente');
Route::get('/restaurantes', [Controller::class, 'exibirIndexRestaurante'])->name('index_restaurante');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');
Route::get('/confirma-email', [Controller::class, 'confirmaEmail'])->name('confirma_email');

// -------------------------------------------- Rotas do cliente ---------------------------------------------------------
Route::post('cliente/login', [ClienteController::class, 'realizarLogin'])->name('login_cliente');
Route::post('cliente/cadastro', [ClienteController::class, 'cadastrarCliente'])->name('cadastro_cliente');
Route::get('carrinho', [ClienteController::class, 'exibirCarrinho'])->name('carrinho');

// Rotas privadas (requer autenticação do cliente)
Route::middleware('auth:cliente')->group(function () {
    // Página inicial do cliente
    Route::get('/home-cliente', [ClienteController::class, 'exibirPaginaInicial'])->name('home_cliente');

    // Catálogo de produtos
    Route::get('/catalogo-produtos', [ClienteController::class, 'exibirProdutosDisponiveis'])->name('catalogo_produtos');

    // Adicionar produto ao carrinho
    Route::post('/adiciona-carrinho', [ClienteController::class, 'adicionarProdutoCarrinho'])->name('adicionar_carrinho');

    // Remover produto do carrinho
    Route::post('/remove-carrinho', [ClienteController::class, 'removerProdutoCarrinho'])->name('remover_carrinho');

    // Diminuir quantidade de produto no carrinho
    Route::post('/diminuir-carrinho', [ClienteController::class, 'diminuirQuantidadeCarrinho'])->name('diminuir_carrinho');

    // Aumentar quantidade de produto no carrinho
    Route::post('/aumenta-carrinho', [ClienteController::class, 'aumentarQuantidadeCarrinho'])->name('aumentar_carrinho');

    // Exibir endereços cadastrados
    Route::post('/enderecos', [ClienteController::class, 'exibirEnderecos'])->name('exibir_enderecos');

    // Exibir formas de pagamento
    Route::post('/pagamento', [ClienteController::class, 'exibirFormasPagamento'])->name('exibir_pagamentos');

    // Realizar pedido
    Route::post('/realizar-pedido', [ClienteController::class, 'realizarPedido'])->name('realizar_pedido');

    // Página com os pedidos realizados
    Route::get('/pedidos-cliente', [ClienteController::class, 'exibirPaginaPedidos'])->name('pedidos_cliente');

    // Solicitar cancelamento do pedido
    Route::post('/cancelar-pedido/{id}', [ClienteController::class, 'cancelarPedido'])->name('cancelar_pedido');

    // Página de alteração cadastral
    Route::get('/adm-cliente', [ClienteController::class, 'exibirAdmCliente'])->name('adm_cliente');

    // Página de alteração cadastral
    Route::get('/info-cliente', [ClienteController::class, 'exibirInfoCliente'])->name('info_cliente');

    // Alterar cadastro
    Route::post('/alterar-cadastro/cliente', [ClienteController::class, 'alterarCadastro'])->name('altera_cadastro');

    // Avaliar pedido
    Route::post('/avaliar-pedido/{id}', [ClienteController::class, 'avaliarPedido'])->name('avaliar_pedido');

    // Receber pedido
    Route::post('/receber-pedido/{id}', [ClienteController::class, 'receberPedido'])->name('receber_pedido');

    // Página de cadastro e visualização de endereços
    Route::get('/enderecos', [ClienteController::class, 'controleEnderecos'])->name('enderecos');

    // Cadastrar Endereço
    Route::post('/cadastrar-endereco', [ClienteController::class, 'cadastrarEndereco'])->name('cadastrar_endereco');

    // Editar endereço
    Route::post('/enderecos/editar/{id}', [ClienteController::class, 'editarEndereco'])->name('editar_endereco');

    // Excluir endereco
    Route::post('/enderecos/excluir/{id}', [ClienteController::class, 'excluirEndereco'])->name('excluir_endereco');
});

//Rota para solicitação de link para redefinição de senha via email
Route::post('esqueceu-senha/cliente', [ClienteController::class, 'esqueceuSenhaCliente'])->name('esqueceuSenhaCliente');

// Rota para o envio do email com o link para redefinição de senha
Route::get('/reset-senha/cliente/', [ClienteController::class, 'resetSenhaCliente'])->name('resetSenhaCliente');

// Rota para a definição de uma nova senha
Route::post('nova-senha/cliente', [ClienteController::class, 'definirNovaSenhaCliente'])->name('definirNovaSenhaCliente');

//Rota adicional para evitar o acesso indevido a rota de nova senha (Vou verificar a viabilidade de fazer um middleware para isso)
Route::get('nova-senha/cliente', function () {
    return redirect()->route('Index')->with('error', 'Acesso inválido!');
});

// -------------------------------------------- Rotas do estabelecimento ---------------------------------------------------------
Route::post('restaurante/cadastro', [EstabelecimentoController::class, 'cadastrarEstabelecimento'])->name('cadastro_restaurante');
Route::post('restaurante/login', [EstabelecimentoController::class, 'realizarLogin'])->name('login_estabelecimento');

// Rotas privadas (requer autenticação do estabelecimento)
Route::middleware('auth:estabelecimento')->group(function () {
    // Página inicial do estabelecimento
    Route::get('/home-restaurante', [EstabelecimentoController::class, 'exibirPaginaInicial'])->name('home_restaurante');

    // Página gerenciar pedidos estabelecimento
    Route::get('/pedidos-restaurante', [EstabelecimentoController::class, 'exibirPaginaPedidos'])->name('pedidos_restaurante');

    // Alterar status pedido
    Route::post('/pedidos-status/{id}', [EstabelecimentoController::class, 'alterarStatus'])->name('pedidos_status');

    // Página de alteração cadastral
    Route::get('/adm-restaurante', [EstabelecimentoController::class, 'exibirAdmRestaurante'])->name('adm_restaurante');

    // Página de alteração cadastral
    Route::get('/info-restaurante', [EstabelecimentoController::class, 'exibirInfoRestaurante'])->name('info_restaurante');

    // Alterar cadastro
    Route::post('/mudar-cadastro/restaurante', [EstabelecimentoController::class, 'alteraCadastro'])->name('altera_cadastro_res');

    // Página de produtos
    Route::get('/produtos-restaurante', [EstabelecimentoController::class, 'exibirProdutosRestaurante'])->name('produtos_restaurante');

    // Cadastrar produto
    Route::post('/cadastrar-produto', [EstabelecimentoController::class, 'cadastrarProduto'])->name('cadastar_produto');

    // Página de estoque
    Route::get('/estoque-restaurante', [EstabelecimentoController::class, 'exibirEstoqueRestaurante'])->name('estoque_restaurante');

    // Atualizar estoque
    Route::post('/estoque/{id}/atualizar-estoque', [EstabelecimentoController::class, 'atualizarEstoque'])->name('atualizar_estoque');

    // Página de métricas
    Route::get('/dashboard-restaurante', [EstabelecimentoController::class, 'exibirDashboardRestaurante'])->name('dashboard_restaurante');
});

// Rota para solicitação de link para redefinição de senha via email
Route::post('esqueceu-senha/estabelecimento', [EstabelecimentoController::class, 'esqueceuSenhaEstabelecimento'])->name('esqueceuSenhaEstabelecimento');

// Rota para o envio do email com o link para redefinição de senha
Route::get('/reset-senha/estabelecimento', [EstabelecimentoController::class, 'resetSenhaEstabelecimento'])->name('resetSenhaEstabelecimento');

// Rota para a definição de uma nova senha
Route::post('nova-senha/estabelecimento', [EstabelecimentoController::class, 'definirNovaSenhaEstabelecimento'])->name('definirNovaSenhaEstabelecimento');

// Rota adicional para evitar o acesso indevido à rota de nova senha (Vou verificar a viabilidade de fazer um middleware para isso)
Route::get('nova-senha/estabelecimento', function () {
    return redirect()->route('Index')->with('error', 'Acesso inválido!');
});

// -------------------------------------------- Rotas do administrador ---------------------------------------------------------

Route::get('/administrador', function () { return view('index_admin'); });
Route::get('/logout-admin', [AdministradorController::class, 'realizarLogout'])->name('logout_admin');

// Rota para realização de login dos administradores
Route::post('administrador/login', [AdministradorController::class, 'realizarLogin'])->name('login_admin');

// Rotas privadas (requer autenticação do cliente)
Route::middleware('auth:administrador')->group(function () {
    // Página inicial do cliente
    Route::get('/home-admin', [AdministradorController::class, 'exibirPaginaInicial'])->name('home_admin');
});