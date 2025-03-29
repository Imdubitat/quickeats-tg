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
                <img src="{{ asset('imagem_produto/' . ($produto->imagem_produto ?? 'sem_foto.png')) }}" 
                     alt="Imagem do produto" class="card-img-top" 
                     style="width: 100%; height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $produto->nome }}</h5>
                    <h5 class="card-title">{{ $produto->descricao }}</h5>
                    <p class="card-text">R$ {{ number_format($produto->valor, 2, ',', '.') }}</p>
                    <p class="card-text">Categoria: {{ $produto->categoria_descricao }}</p>
                    <p class="card-text">Estoque: {{ $produto->qtd_estoque }}</p>
                    <button class="btn btn-warning btn-editar" 
                        data-id="{{ $produto->id_produto }}" 
                        data-nome="{{ $produto->nome }}"
                        data-descricao="{{ $produto->descricao }}"  
                        data-valor="{{ $produto->valor }}" 
                        data-categoria="{{ $produto->id_categoria }}" 
                        data-estoque="{{ $produto->qtd_estoque }}" 
                        data-imagem="{{ $produto->imagem_produto }}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#cadastroProdutoModal">
                        Editar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- Modal de Cadastro/Edição de Produto -->
    <div class="modal fade" id="cadastroProdutoModal" tabindex="-1" aria-labelledby="cadastroProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroProdutoModalLabel">Cadastrar Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="produtoForm" action="{{ route('cadastar_produto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_produto" name="id_produto"> <!-- Campo oculto para edição -->
                        
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="qtd_estoque" class="form-label">Quantidade em Estoque</label>
                            <input type="number" class="form-control" id="qtd_estoque" name="qtd_estoque" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem_produto" class="form-label">Imagem do Produto</label>
                            <br>
                            <img id="imagemPreview" src="" alt="Imagem do produto" class="img-thumbnail mb-2" style="max-width: 200px;">
                            <input type="file" id="imagem_produto" name="imagem_produto" accept="image/*" class="form-control">
                            <input type="hidden" id="imagem_atual" name="imagem_atual">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script para preencher o modal ao editar -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("cadastroProdutoModal");
        const form = document.getElementById("produtoForm");
        const tituloModal = document.getElementById("cadastroProdutoModalLabel");

        document.querySelectorAll(".btn-editar").forEach(button => {
            button.addEventListener("click", function() {
                // Pegando os atributos do botão clicado
                const id = this.getAttribute("data-id");
                const nome = this.getAttribute("data-nome");
                const descricao = this.getAttribute("data-descricao");
                const valor = this.getAttribute("data-valor");
                const categoria = this.getAttribute("data-categoria");
                const estoque = this.getAttribute("data-estoque");
                const imagem = this.getAttribute("data-imagem");

                // Preenchendo os campos do modal
                document.getElementById("id_produto").value = id;
                document.getElementById("nome").value = nome;
                document.getElementById("descricao").value = descricao;
                document.getElementById("valor").value = valor;
                document.getElementById("id_categoria").value = categoria;
                document.getElementById("qtd_estoque").value = estoque;
                document.getElementById("imagem_atual").value = imagem;

                // Atualizando a exibição da imagem
                let imagemPreview = document.getElementById("imagemPreview");
                if (imagem) {
                    imagemPreview.src = "/imagem_produto/" + imagem;
                    imagemPreview.style.display = "block";
                } else {
                    imagemPreview.style.display = "none";
                }

                // Alterando título do modal
                tituloModal.textContent = "Editar Produto";

                // Alterando a action do formulário para atualizar o produto
                form.action = "{{ route('atualizar_produto') }}";
            });
        });

        // Resetar modal ao fechar (para novo cadastro)
        modal.addEventListener("hidden.bs.modal", function() {
            form.reset();
            document.getElementById("id_produto").value = "";
            tituloModal.textContent = "Cadastrar Novo Produto";
            form.action = "{{ route('cadastar_produto') }}";
            document.getElementById("imagemPreview").style.display = "none";
        });

    });
</script>
@endsection