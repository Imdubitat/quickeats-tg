@extends('template_restaurante')

@section('title', 'Adm Restaurante')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Botão para abrir o modal -->
    <div class="mb-4"> 
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroProdutoModal">
            Cadastrar Novo Produto
        </button>
    </div>

    <!-- Exibindo os cards dos produtos -->
    <div class="row">
        <h1>Produtos Cadastrados</h1>
        @foreach ($produtos as $produto)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $produto->nome }}</h5>
                    <p class="card-text">R$ {{ number_format($produto->valor, 2, ',', '.') }}</p>
                    <p class="card-text">Categoria: {{ $produto->categoria_descricao }}</p> <!-- Exibindo a descrição da categoria -->
                    <p class="card-text">Estoque: {{ $produto->qtd_estoque }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal de Cadastro de Produto -->
    <div class="modal fade" id="cadastroProdutoModal" tabindex="-1" aria-labelledby="cadastroProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroProdutoModalLabel">Cadastrar Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cadastar_produto') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->descricao }}</option> <!-- Exibindo a descrição da categoria -->
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="qtd_estoque" class="form-label">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="qtd_estoque" name="qtd_estoque" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection