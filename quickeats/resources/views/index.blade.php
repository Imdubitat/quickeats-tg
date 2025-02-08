@extends('template')

@section('title', 'Página inicial')

@section('nav-buttons')
    <ul class="nav d-flex flex-wrap justify-content-start">
        <li class="nav-item">
            <a href="" id="cliente" class="btn btn-custom ms-4">Sou cliente</a>
        </li>

        <li class="nav-item">
            <a href="" id="restaurante" class="btn btn-custom2 ms-4">Tenho um restaurante</a>
        </li>
    </ul>
@endsection

@section('content')
    <section style="margin-top: 10rem;">
        <div class="container-fluid" style="background: #F3F4F6">
            <div class="py-5 text-center">
                <h1 class="fw-bold" style="color: #1E3A8A">Encontre sua próxima refeição com rapidez e facilidade</h1>
                <p class="opacity-75">Descubra restaurantes incríveis perto de você e peça sua comida favorita em poucos cliques.</p>
                <p><a class="btn btn-lg btn-custom rounded-5" href="">Tô com fome</a></p>
            </div>
        </div>
    </section>

    <section class="bg-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3" style="height: 150px;"></div>
                            <h3 class="text-primary">Restaurante 1</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3" style="height: 150px;"></div>
                            <h3 class="text-primary">Restaurante 2</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3" style="height: 150px;"></div>
                            <h3 class="text-primary">Restaurante 3</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection