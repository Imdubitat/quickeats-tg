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
// Exibir página de perguntas frequentes
Route::get('/faqs', [Controller::class, 'exibirFaqs'])->name('faqs');
Route::get('/sobre', [Controller::class, 'exibirSobre'])->name('sobre');
Route::get('/contato', [Controller::class, 'exibirContato'])->name('contato');

// Enviar mensagem para o suporte
Route::post('/abrir-chamado', [Controller::class, 'abrirChamado'])->name('abrir_chamado');

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

    // Catálogo de restaurantes
    Route::get('/catalogo-restaurantes', [ClienteController::class, 'exibirRestaurantesDisponiveis'])->name('catalogo_restaurantes');

    // Cardápio do restaurante
    Route::get('/restaurante/{id}', [ClienteController::class, 'exibirCardapio'])->name('cardapio_restaurante');

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

    // Exibir mensagens de suporte
    Route::get('/clientes/lista-chamados', [ClienteController::class, 'exibirChamados'])->name('listar_chamados_cliente');

    // Enviar mensagem para o suporte
    Route::post('/clientes/abrir-chamado', [ClienteController::class, 'abrirChamado'])->name('abrir_chamado_cliente');

    Route::get('/cliente/chamados/{id_chat}/mensagens', [ClienteController::class, 'buscarMensagens']);

    // Responder chamados
    Route::post('/cliente/responder-chamado', [ClienteController::class, 'responderChamado'])->name('cliente_responder_chamado');

    // Favoritar produto
    Route::get('/favoritar/{id}', [ClienteController::class, 'favoritarProduto'])->name('favoritar_produto');

    // Desfavoritar produto
    Route::get('/desfavoritar/{id}', [ClienteController::class, 'desfavoritarProduto'])->name('desfavoritar_produto');

    // Exibir página de favoritos
    Route::get('/favoritos', [ClienteController::class, 'exibirFavoritos'])->name('exibir_favoritos');

    Route::post('/pesquisa', [ClienteController::class, 'pesquisar'])->name('pesquisa');
    Route::post('/autocomplete', [ClienteController::class, 'autocomplete'])->name('autocomplete');

    // Página de alteração de senha
    Route::get('/alt-senha', [ClienteController::class, 'alterarSenha'])->name('alterar_senha');

    // Confirmar alteração de senha
    Route::post('/confirmarSenha', [ClienteController::class, 'confirmarSenha'])->name('confirmar_senha');
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

    // Atualizar produto
    Route::post('/produto/atualizar', [EstabelecimentoController::class, 'atualizarProduto'])->name('atualizar_produto');

    // Página de estoque
    Route::get('/estoque-restaurante', [EstabelecimentoController::class, 'exibirEstoqueRestaurante'])->name('estoque_restaurante');

    // Atualizar estoque
    Route::post('/estoque/{id}/atualizar-estoque', [EstabelecimentoController::class, 'atualizarEstoque'])->name('atualizar_estoque');

    // Página de métricas
    Route::get('/dashboard-restaurante', [EstabelecimentoController::class, 'exibirDashboardRestaurante'])->name('dashboard_restaurante');

    // Página de planos
    Route::get('/planos-restaurante', [EstabelecimentoController::class, 'exibirPlanosdRestaurante'])->name('planos_restaurante');

    // Rota para escolher plano
    Route::post('/escolher-plano', [EstabelecimentoController::class, 'escolherPlano'])->name('escolher_plano');

    // Exibir mensagens de suporte
    Route::get('/estab/lista-chamados', [EstabelecimentoController::class, 'exibirChamados'])->name('listar_chamados_estab');

    // Enviar mensagem para o suporte
    Route::post('/estab/abrir-chamado', [EstabelecimentoController::class, 'abrirChamado'])->name('abrir_chamado_estab');

    Route::get('/estab/chamados/{id_chat}/mensagens', [EstabelecimentoController::class, 'buscarMensagens']);

    // Responder chamados
    Route::post('/estab/responder-chamado', [EstabelecimentoController::class, 'responderChamado'])->name('estab_responder_chamado');

    // Rota para upload de imagem
    Route::post('/perfil/upload', [EstabelecimentoController::class, 'uploadImagemPerfil'])->name('imagem_upload');

    Route::get('/grade-horario', [EstabelecimentoController::class, 'exibirGradeHorario'])->name('grade_horario');

    // Rota para deletar um horário com o método 'deletarHorario'
    Route::post('deleta-grade/{id}', [EstabelecimentoController::class, 'deletarHorario'])->name('deletarHorario');

    // Rota para salvar um horário com o método 'salvarGrade'
    Route::post('salvar-grade', [EstabelecimentoController::class, 'salvarGrade'])->name('salvarGrade');

    // Página de alteração de senha
    Route::get('/alt-senhaEstab', [EstabelecimentoController::class, 'alterarSenha'])->name('alterar_senhaEstab');

    // Confirmar alteração de senha
    Route::post('/confirmarSenhaEstab', [EstabelecimentoController::class, 'confirmarSenha'])->name('confirmar_senhaEstab');
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

    // Página com todos os restaurantes
    Route::get('/admin/restaurantes', [AdministradorController::class, 'exibirRestaurantes'])->name('admin_restaurantes');

    // Página com todos os clientes
    Route::get('/admin/clientes', [AdministradorController::class, 'exibirClientes'])->name('admin_clientes');

    // Ativar perfil de clientes
    Route::put('/admin//clientes/{id}/ativar', [AdministradorController::class, 'ativarCliente'])->name('ativar_cliente');

    // Desativar perfil de clientes
    Route::put('/admin//clientes/{id}/desativar', [AdministradorController::class, 'desativarCliente'])->name('desativar_cliente');

    // Ativar perfil de restaurantes
    Route::put('/admin//restaurantes/{id}/ativar', [AdministradorController::class, 'ativarRestaurantes'])->name('ativar_restaurantes');

    // Desativar perfil de restaurantes
    Route::put('/admin//restaurantes/{id}/desativar', [AdministradorController::class, 'desativarRestaurantes'])->name('desativar_restaurantes');

    // Exibir chamados
    Route::get('/admin/chamados', [AdministradorController::class, 'exibirChamados'])->name('chamados_admin');

    // Responder chamados
    Route::post('/admin/responder-chamado/cliente', [AdministradorController::class, 'responderChamadoCliente'])->name('responder_chamado_cliente');
    Route::post('/admin/responder-chamado/estab', [AdministradorController::class, 'responderChamadoEstab'])->name('responder_chamado_estab');
    Route::post('/mensagens/nao-cadastrado/resolver', [AdministradorController::class, 'marcarComoRespondido'])->name('mensagem_resolver');

    Route::get('/chamados/{id_chat}/mensagens', [AdministradorController::class, 'buscarMensagens']);

    // Exibir planos ativos
    Route::get('/admin/planos', [AdministradorController::class, 'planosAtivos'])->name('planos_ativos');
});