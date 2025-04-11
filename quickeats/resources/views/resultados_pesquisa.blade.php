@extends('template_cliente')

@section('title', 'Resultados da Pesquisa')

@section('content')
<section style="margin-top: 13rem;">
    <div class="container mt-5 pt-5">
        <h2 class="text-primary">Resultados para: "{{ $termo }}"</h2>

        @if($produtos->isEmpty() && $estabelecimentos->isEmpty())
            <p class="mt-3">Nenhum resultado encontrado.</p>
        @endif

        @if(!$estabelecimentos->isEmpty())
            <h4 class="mt-4">Estabelecimentos encontrados:</h4>
            <div class="row">
                @foreach($estabelecimentos as $estab)
                    <div class="col-md-4 mb-4">
                        <input type="hidden" name="id_estab" value="{{ $estab->id_estab }}">
                        <div class="card shadow rounded-4">
                            <div class="card-body">
                                <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($estab->imagem_perfil ?? 'sem_foto.png')) }}" 
                                alt="Imagem do Estabelecimento"
                                style="width: 100%; height: 200px; object-fit: cover;">
                            <h5 class="card-title">{{ $estab->nome_fantasia }}</h5>
                            <p class="card-text">{{ $estab->logradouro }}, {{ $estab->numero }}<br>{{ $estab->bairro }}, {{ $estab->cidade }} - {{ $estab->estado }}</p>
                            <button type="button" class="btn btn-custom3" onclick="window.location.href='{{ route('cardapio_restaurante', $estab->id_estab) }}'">
                                Ver cardápio
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        @if(!$produtos->isEmpty())
            <h4 class="mt-4">Produtos encontrados:</h4>
            <div class="row">
            @foreach($produtos as $produto)
                <div class="col-md-4 mb-4">
                    <form action="{{ route('adicionar_carrinho') }}" method="POST" onsubmit="console.log('Formulário enviado para adicionar ao carrinho');">
                        @csrf
                        <input type="hidden" name="produto" value="{{ $produto->id_produto }}">
                        <input type="hidden" name="data_adicao" value="{{ now() }}">
                        <input type="hidden" name="id_estab" value="{{ $produto->id_estab }}">

                        <div class="card shadow rounded-4">
                            <img class="card-img-top" src="{{ asset('imagem_produto/' . ($produto->imagem_produto ?? 'sem_foto.png')) }}" 
                                alt="Imagem do produto"
                                style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $produto->nome }}</h5>
                                <p class="card-text">{{ $produto->descricao ?? 'sem descrição' }}<br>
                                <p class="card-text">R$ {{ $produto->valor }}<br>
                                {{ $produto->id_estab }}</p>
                                
                                <label for="qtd_produto_{{ $produto->id_produto }}" class="form-label">Quantidade:</label>
                                <input type="text" name="qtd_produto" id="qtd_produto_{{ $produto->id_produto }}"
                                    class="form-control-sm mb-2" value="1" min="1" style="width: 30px; height: 10px;" required><br>
                                
                                <button type="submit" class="btn btn-custom3 me-5" onclick="console.log('Botão Adicionar clicado');">Adicionar ao carrinho</button>
                                <input type="hidden" name="id_produto" value="{{ $produto->id_produto }}">
                                @if (in_array($produto->id_produto, $favoritos))
                                    <button type="button" class="heart-icon favoritado btn btn-link p-0 m-0 align-center"
                                        onclick="window.location.href='{{ route('desfavoritar_produto', $produto->id_produto) }}'">
                                        <i class="fas fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                    </button>
                                @else
                                    <button type="button" class="heart-icon btn btn-link p-0 m-0 align-center" 
                                        onclick="window.location.href='{{ route('favoritar_produto', $produto->id_produto) }}'">
                                        <i class="far fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
