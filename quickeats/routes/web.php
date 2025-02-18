<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstabelecimentoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quickeats', [Controller::class, 'exibirIndex'])->name('index');
Route::get('/clientes', [Controller::class, 'exibirIndexCliente'])->name('index_cliente');
Route::get('/restaurantes', [Controller::class, 'exibirIndexRestaurante'])->name('index_restaurante');

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
});