@extends('template_cliente')

@section('title', 'Produtos favoritos')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    <div class="container mt-4">
        <h2 class="mb-4">Meus Produtos Favoritos</h2>

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

        @if(empty($favoritos))
            <p>Você ainda não adicionou produtos aos favoritos.</p>
        @else
            <div class="row">
                @foreach($favoritos as $produto)
                @php
                    $favoritado = in_array($produto->id_produto, $produtoFavorito);
                @endphp
                    <div class="col-md-4 mb-4">
                        <form action="{{ route('adicionar_carrinho') }}" method="POST">
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
                                    {{ $produto->estab }}</p>
                                
                                    <label for="qtd_produto_{{ $produto->id_produto }}" class="form-label">Quantidade:</label>
                                    <input type="text" name="qtd_produto" id="qtd_produto_{{ $produto->id_produto }}"
                                    class="form-control-sm mb-2" value="1" min="1" style="width: 30px; height: 10px;" required><br>
                                    
                                    <button type="submit" class="btn btn-custom3 me-5">Adicionar ao carrinho</button>
                                    <input type="hidden" name="id_produto" value="{{ $produto->id_produto }}">

                                    @if ($favoritado)
                                        <button type="button" class="heart-icon favoritado btn btn-link p-0 m-0 align-center"
                                        onclick="window.location.href='{{ route('desfavoritar_produto', $produto->id_produto) }}'">
                                            <i class="fas fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                        </button>
                                    @else
                                        <button type="button" class="heart-icon btn btn-link p-0 m-0 align-center" 
                                        onclick="window.location.href='{{ route('favoritar_produto', $produto->id_produto) }}'">
                                            <i class="far fa-heart" style="font-size: 1.5rem; color:red;"></i>
                                        </a>
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