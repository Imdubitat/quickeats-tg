@extends('template_cliente')

@section('title', 'Catálogo de restaurantes')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    <div class="d-flex justify-content-start mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>

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
    <div class="container">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="card shadow-lg rounded-4 p-4" style="max-width: 700px; width: 100%;">
                <div class="card-body text-center">
                    <!-- Ícone de restaurante -->
                    <div class="mb-3">
                    <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($restaurante->imagem_perfil ?? 'sem_foto.png')) }}" 
                            alt="Imagem do Estabelecimento"
                            style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <h2 class="card-title fw-bold">{{ $restaurante->nome_fantasia }}</h2>
                    
                    <p class="card-text mt-3">
                        <i class="fas fa-map-marker-alt"></i> {{ $restaurante->logradouro }}, {{ $restaurante->numero }}<br>
                        {{ $restaurante->bairro }}, {{ $restaurante->cidade }} - {{ $restaurante->estado }}
                    </p>
                    
                    <p class="mt-2"><i class="fas fa-phone"></i> <strong>Contato:</strong> {{ $restaurante->telefone }}</p>
                </div>
            </div>
        </div>

        <h2 class="mt-4 ">Cardápio</h2>
        <div class="row">
            @if(empty($produtos))
                <p class="text-muted">Nenhum produto disponível no momento.</p>
            @else
                @foreach($produtos as $p)
                    <div class="col-md-4 mb-4">
                        <form action="{{ route('adicionar_carrinho') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                            <input type="hidden" name="data_adicao" value="{{ now() }}">
                            <input type="hidden" name="id_estab" value="{{ $p->id_estab }}">
                            <div class="card shadow rounded-4">
                                <div class="card-body">
                                    <img class="card-img-top" src="{{ asset('imagem_produto/' . ($p->imagem_produto ?? 'sem_foto.png')) }}" 
                                        alt="Imagem do produto"
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                    <h5 class="card-title">{{ $p->nome_produto }}</h5>
                                    <p class="card-text">{{ $p->descricao ?? 'sem descrição' }}<br>
                                    <p class="card-text">R$ {{ $p->valor }}<br>
                                    {{ $p->estab }}</p>
                                    <label for="qtd_produto_{{ $p->id_produto }}" class="form-label">Quantidade:</label>
                                    <input type="number" name="qtd_produto" id="qtd_produto_{{ $p->id_produto }}"
                                        class="form-control-sm mb-2" value="1" min="1" style="width: 50px;" required><br>
                                    
                                    <button type="submit" class="btn btn-custom3">Adicionar ao carrinho</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endsection