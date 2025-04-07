@extends('template_cliente')

@section('title', 'Home | Cliente')

@section('nav-buttons')

@endsection

@section('content')
<section style="margin-top: 11rem;">
    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel" style="border-radius: 0 0 10px 10px; overflow: hidden;">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="bd-placeholder-img" width="100%" src="{{ asset('images/motoboy-banner.jpg') }}">
                <div class="container">
                <div class="carousel-caption text-start">
                    <h1 class="display-1 fw-bold">Rápido!</h1>
                    <p class="fs-5 text-white">Peça agora e receba seu pedido em minutos!</p>
                    <p><a class="btn btn-lg btn-custom3" href="">Peça agora</a></p>
                </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" src="{{ asset('images/variedade-banner.jpg') }}">
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-1 fw-bold">Milhares de opções no cardápio!</h1>
                        <p class="fs-5">Escolha o que quiser e receba em casa!</p>
                        <p><a class="btn btn-lg btn-custom3" href="">Ver restaurantes</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" src="{{ asset('images/entregas-banner.jpg') }}">
                <div class="container">
                    <div class="carousel-caption text-end">
                        <h1 class="display-1 fw-bold">Simples!</h1>
                        <p class="fs-5">Tudo rápido, seguro e com garantia.</p>
                        <p><a class="btn btn-lg btn-custom3" href="">Peça agora</a></p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>

    <div class="py-3"></div>

    <div class="container">
    <h1 class="fw-bold text-start mb-3">Os restaurantes mais populares</h1>
        <div class="row align-self-center align-items-center pb-5 mb-5">
        @if($estabPopulares)
            @foreach($estabPopulares as $estab)
                <div class="col-md-4">
                    <input type="hidden" name="estabelecimento" value="{{ $estab->id }}">
                    <div class="card shadow rounded-4">
                        <img class="card-img-top" src="{{ asset('imagem_perfil/' . ($estab->imagem ?? 'sem_foto.png')) }}" 
                        alt="Imagem do Estabelecimento"
                        style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="card-body text-center">
                            <p class="card-text">{{ $estab->nome_fantasia }}</p>
                            <button type="button" class="btn btn-custom3" onclick="window.location.href='{{ route('cardapio_restaurante', $estab->id) }}'">
                                Ver cardápio
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
        
        <h1 class="fw-bold text-start mb-3">Os produtos mais populares</h1>
        <div class="row align-self-center align-items-center pb-5 mb-5">
            @if($prodPopulares)
                @foreach($prodPopulares as $prod)
                    <div class="col-md-4">
                        <input type="hidden" name="produto" value="{{ $prod->id }}">
                        <input type="hidden" name="categoria" value="{{ $prod->categoria }}">
                        <div class="card" style="cursor: pointer;">
                            <img class="card-img-top" src="{{ asset('imagem_produto/' . ($prod->imagem ?? 'sem_foto.png')) }}" 
                            alt="Imagem do produto"
                            style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <p class="card-text">{{ $prod->nome_produto }}</p>
                            </div>
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-custom3" 
                                    onclick="window.location.href='{{ route('catalogo_produtos') }}#categoria-{{ $prod->categoria }}'">
                                    Ver categoria
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
</section>

<section class="py-5 mt-5">
    <div class="container newsletter pt-5 pb-5 rounded-4 text-white">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 class="mb-4">Receba novidades e promoções</h3>
                <p class="mb-4">Inscreva-se na nossa newsletter e fique por dentro das melhores ofertas, novos restaurantes e dicas exclusivas!</p>
                <form action="" method="post" class="d-flex flex-column flex-md-row justify-content-center gap-2">
                    @csrf
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Digite seu email" required>
                    <button type="submit" class="btn btn-custom btn-lg">Inscrever</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection