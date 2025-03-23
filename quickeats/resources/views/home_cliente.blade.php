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
</section>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5">
                <h3>ENTRE EM CONTATO</h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Seu email">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="message" rows="5" placeholder="Sua mensagem"></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Enviar</button>
                </form>
            </div>
            <div class="col-md-6">
                <h3 style="color: #1E3A8A">INFORMAÇÕES</h3>
                <p>Email: contato@quickeats.com</p>
                <p>Telefone: (11) 1234-5678</p>
                <p>Redes Sociais:</p>
                <ul class="list-unstyled">
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection