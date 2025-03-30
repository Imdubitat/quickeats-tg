@extends('template_cliente')

@section('title', 'Catálogo de produtos')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
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
    @foreach($categorias as $categoria)
        <h3 class="mt-4">{{ $categoria->descricao }}</h3> <!-- Nome da categoria -->
        
        @php
            $produtosCategoria = collect($produtos)->where('id_categoria', $categoria->id_categoria);
        @endphp

        <div class="row mx-auto">
            @if($produtosCategoria->isNotEmpty()) <!-- Verifica se há produtos -->
                @foreach($produtosCategoria as $p)
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
                                    <input type="text" name="qtd_produto" id="qtd_produto_{{ $p->id_produto }}"
                                    class="form-control-sm mb-2" value="1" min="1" style="width: 30px; height: 10px;" required><br>
                                    
                                    <button type="submit" class="btn btn-custom3">Adicionar ao carrinho</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Nenhum produto disponível nesta categoria.</p>
            @endif
        </div>
    @endforeach

</section>
@endsection