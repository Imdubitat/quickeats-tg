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

    <!-- Exibindo os cards dos produtos -->
    <div class="row">
        <h1>Gerenciamento de estoque</h1>
        
        @php
            // Ordenando os produtos pela quantidade em estoque (menor para maior)
            $produtos = $produtos->sortBy('qtd_estoque');
        @endphp

        @foreach ($produtos as $produto)
            @php
                // Definir cor com base na quantidade em estoque
                $corEstoque = 'bg-light'; // Padrão (estoque suficiente)
                if ($produto->qtd_estoque == 0) {
                    $corEstoque = 'bg-danger text-white'; // Cinza para estoque zerado
                } elseif ($produto->qtd_estoque <= 5) {
                    $corEstoque = 'bg-warning text-dark'; // Vermelho para menos de 5
                } elseif ($produto->qtd_estoque <= 10) {
                    $corEstoque = 'bg-secondary text-white'; // Amarelo para menos de 10
                }
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card {{ $corEstoque }}">
                    <div class="card-body text-start">
                        <h5 class="card-title ms-2">{{ $produto->nome }}</h5>

                        <!-- Estoque Editável -->
                        <h6 class="card-text fw-bold fs-5 ms-2">
                            Estoque: 
                            <span id="estoque-text-{{ $produto->id_produto }}">{{ $produto->qtd_estoque }}</span>
                            <input type="number" id="estoque-input-{{ $produto->id_produto }}" 
                                class="form-control form-control-sm d-none w-50 ms-2" 
                                min="0" value="{{ $produto->qtd_estoque }}">
                        </h6>

                        <!-- Botão para atualizar -->
                        <button id="btn-estoque-{{ $produto->id_produto }}" 
                            class="btn btn-primary btn-sm ms-2 mt-2" 
                            onclick="editarEstoque({{ $produto->id_produto }})">
                            Atualizar Estoque
                        </button>

                        <!-- Formulário oculto para envio -->
                        <form id="form-estoque-{{ $produto->id_produto }}" action="{{ route('atualizar_estoque', $produto->id_produto) }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="qtd_estoque" id="input-hidden-{{ $produto->id_produto }}">
                        </form>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</section>

<script>
    function editarEstoque(produtoId) {
        let textoEstoque = document.getElementById(`estoque-text-${produtoId}`);
        let inputEstoque = document.getElementById(`estoque-input-${produtoId}`);
        let botao = document.getElementById(`btn-estoque-${produtoId}`);
        let formEstoque = document.getElementById(`form-estoque-${produtoId}`);
        let inputHidden = document.getElementById(`input-hidden-${produtoId}`);

        if (botao.innerText === "Atualizar Estoque") {
            // Mudar para edição
            textoEstoque.classList.add("d-none");
            inputEstoque.classList.remove("d-none");
            botao.innerText = "Salvar";
            botao.classList.remove("btn-primary");
            botao.classList.add("btn-success");
        } else {
            // Salvar novo valor e enviar o formulário
            inputHidden.value = inputEstoque.value;
            formEstoque.submit();
        }
    }
</script>
@endsection