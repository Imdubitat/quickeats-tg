@extends('template')

@section('title', 'Página inicial')

@section('nav-buttons')
    <ul class="nav d-flex flex-wrap justify-content-start">
        <li class="nav-item">
            <a href="{{ route('index_cliente') }}" id="cliente" class="btn btn-custom ms-4">Sou cliente</a>
        </li>

        <li class="nav-item">
            <a href="{{ route('index_restaurante') }}" id="restaurante" class="btn btn-custom2 ms-4">Tenho um restaurante</a>
        </li>
    </ul>
@endsection

@section('content')
    <section style="margin-top: 10rem;">
        <div class="container-fluid" style="background: #F3F4F6">
            <div class="py-5 text-center">
                <h1 class="fw-bold" style="color: #1E3A8A">Encontre sua próxima refeição com rapidez e facilidade</h1>
                <p class="opacity-75 fs-5">Descubra restaurantes incríveis perto de você e peça sua comida favorita em poucos cliques.</p>
                <p><a class="btn btn-lg btn-custom3 rounded-5" href="">Tô com fome</a></p>
            </div>
        </div>
    </section>

    <section class="bg-white py-4">
        <div class="container">
            <h1 class="fw-bold text-start mb-3">Os restaurantes mais populares</h1>
            <div class="row">
                @if($estabPopulares)
                    @foreach($estabPopulares as $estab)
                        <div class="col-md-4">
                            <form action="" method="">
                            @csrf
                                <input type="hidden" name="estabelecimento" value="{{ $estab->id }}">
                                <div class="card" style="cursor: pointer;">
                                    <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($estab->imagem ?? 'sem_foto.png')) }}" 
                                    alt="Imagem do Estabelecimento"
                                    style="width: 100%; height: 200px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $estab->nome_fantasia }}</p>
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