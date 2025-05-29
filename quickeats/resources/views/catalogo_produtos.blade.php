@extends('template_cliente')

@section('title', 'Catálogo de produtos')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="accordion" id="catalogoAccordion">
        @foreach($categorias as $categoria)
            @php
                $produtosCategoria = collect($produtos)->where('id_categoria', $categoria->id_categoria);
            @endphp

            <div class="accordion-item" id="categoria-{{ $categoria->id_categoria }}">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $categoria->id_categoria }}" aria-expanded="true"
                        aria-controls="collapse-{{ $categoria->id_categoria }}">
                        {{ $categoria->descricao }}
                    </button>
                </h2>

                <div id="collapse-{{ $categoria->id_categoria }}" class="accordion-collapse collapse"
                    aria-labelledby="heading-{{ $categoria->id_categoria }}" data-bs-parent="#catalogoAccordion">
                    <div class="accordion-body">
                        <div class="row mx-auto">
                            @if($produtosCategoria->isNotEmpty())
                                @foreach($produtosCategoria as $p)
                                @php
                                    $favoritado = in_array($p->id_produto, $favoritos);
                                @endphp

                                    <div class="col-md-4 mb-4">
                                        <form action="{{ route('adicionar_carrinho') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                                            <input type="hidden" name="data_adicao" value="{{ now() }}">
                                            <input type="hidden" name="id_estab" value="{{ $p->id_estab }}">

                                            <div class="card shadow rounded-4">
                                                <img class="card-img-top" src="{{ asset('imagem_produto/' . ($p->imagem ?? 'sem_foto.png')) }}" 
                                                    alt="Imagem do produto"
                                                    style="width: 100%; height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $p->nome_produto }}</h5>
                                                    <p class="card-text">{{ $p->descricao ?? 'sem descrição' }}<br>
                                                    <p class="card-text">R$ {{ $p->valor }}<br>
                                                    {{ $p->estab }}</p>
                                                    
                                                    <label for="qtd_produto_{{ $p->id_produto }}" class="form-label">Quantidade:</label>
                                                    <input type="number" name="qtd_produto" id="qtd_produto_{{ $p->id_produto }}"
                                                    class="form-control-sm mb-2" value="1" min="1" step="1" style="width: 50px;" required><br>
                                                    @if($p->estab_fechado)
                                                        <button type="button" class="btn btn-secondary" disabled>
                                                            Estabelecimento fechado
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-custom3 me-5">Adicionar ao carrinho</button>
                                                    @endif
                                                    <input type="hidden" name="id_produto" value="{{ $p->id_produto }}">
                                                    @if ($favoritado)
                                                        <button type="button" class="heart-icon favoritado btn btn-link p-0 m-0 align-center"
                                                        onclick="window.location.href='{{ route('desfavoritar_produto', $p->id_produto) }}'">
                                                            <i class="fas fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="heart-icon btn btn-link p-0 m-0 align-center" 
                                                        onclick="window.location.href='{{ route('favoritar_produto', $p->id_produto) }}'">
                                                            <i class="far fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                                        </a>
                                                    @endif

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Nenhum produto disponível nesta categoria.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;
        if (hash) {
            const el = document.querySelector(hash);
            if (el) {
                // Tenta encontrar o conteúdo do accordion (a parte colapsável)
                const collapseElement = el.querySelector(".accordion-collapse");
                const button = el.querySelector(".accordion-button");

                if (collapseElement && button) {
                    // Abre o accordion (adiciona a classe 'show')
                    collapseElement.classList.add("show");

                    // Remove o 'collapsed' do botão
                    button.classList.remove("collapsed");

                    // Scroll centralizado
                    setTimeout(() => {
                        el.scrollIntoView({ behavior: "smooth", block: "center" });
                    }, 300);
                }
            }
        }
    });
</script>

@endsection