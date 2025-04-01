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

    <div class="row mx-auto">
        @foreach($restaurantes as $r)
            <div class="col-md-4 mb-4">
                <input type="hidden" name="id_estab" value="{{ $r->id_estab }}">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                    <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($r->imagem ?? 'sem_foto.png')) }}" 
                            alt="Imagem do Estabelecimento"
                            style="width: 100%; height: 200px; object-fit: cover;">
                        <h5 class="card-title">{{ $r->nome_fantasia }}</h5>
                        <p class="card-text">{{ $r->logradouro }}, {{ $r->numero }}<br>{{ $r->bairro }}, {{ $r->cidade }} - {{ $r->estado }}</p>
                        <button type="button" class="btn btn-custom3" onclick="window.location.href='{{ route('cardapio_restaurante', $r->id_estab) }}'">
                            Ver cardápio
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection