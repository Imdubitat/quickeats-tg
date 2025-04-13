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
                            <div class="card shadow-lg">
                                <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($estab->imagem ?? 'sem_foto.png')) }}" 
                                alt="Imagem do Estabelecimento"
                                style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <p class="card-text fw-bold">{{ $estab->nome_fantasia }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="py-5"></div>

            <h1 class="fw-bold text-start mb-3">Os produtos mais populares</h1>
            <div class="row">
                @if($prodPopulares)
                    @foreach($prodPopulares as $prod)
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <img class="card-img-top" src="{{ asset('imagem_produto/' . ($prod->imagem ?? 'sem_foto.png')) }}" 
                                alt="Imagem do produto"
                                style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <p class="card-text fw-bold">{{ $prod->nome_produto }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="container border border-1 my-5 w-50"></div>

            <div class="row pt-5 align-items-center">
                <div class="col-md-4">
                    <h1 class="fw-bold">Seja parte da nossa comunidade!</h1>
                    <p class="fs-5">Junte-se à nossa comunidade e faça parte de algo verdadeiramente transformador. 
                        Aqui, cada pessoa importa e cada ação tem um propósito. 
                        Acreditamos no poder da união, da colaboração e da inovação para criar mudanças reais. 
                        Venha contribuir com suas ideias, sua energia e sua paixão — juntos, 
                        podemos construir um futuro melhor e deixar uma marca positiva no mundo.</p>

                        <a href="{{ route('sobre') }}" class="btn btn-lg btn-custom3">Saiba mais</a>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <img src="{{ asset('images/app-variedade.jpg') }}" class="card-img-top mb-1" alt="variedade de alimentos">
                        <h5 class="card-title fw-bold fs-3">Está com fome!?</h5>
                        <p>Crie sua conta e tenha acesso a milhares de produtos e estabelecimentos 
                            espalhados pelo Brasil todo!
                        </p>
                        <a href="{{ route('index_cliente') }}" class="text-start btn btn-custom3" style="width:120px;">Tamo junto!</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <img src="{{ asset('images/restaurante-dono.jpg') }}" class="card-img-top mb-1" alt="...">
                        <h5 class="card-title fw-bold fs-3">Quer mais lucro!?</h5>
                        <p>Cadastre seu estabelecimento e tenha o apoio que você sempre procurou!</p>
                        <a href="{{ route('index_restaurante') }}" class="text-start btn btn-custom4" style="width:80px;">Bora!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection