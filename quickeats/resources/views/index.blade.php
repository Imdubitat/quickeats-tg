@extends('template')

@section('title', 'Página inicial')

@section('nav-buttons')
    <ul class="nav d-flex flex-wrap justify-content-start">
        <li class="nav-item">
            <a href="{{ route('index_cliente') }}" id="cliente" class="btn btn-custom ms-4">Sou cliente</a>
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
                <p class="opacity-75 fs-5">Descubra restaurantes incríveis perto de você e peça sua comida favorita em poucos cliques.</p>
                <p><a class="btn btn-lg btn-custom3 rounded-5" href="">Tô com fome</a></p>
            </div>
        </div>
    </section>

    <section class="bg-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                        <img src="https://plus.unsplash.com/premium_photo-1701090939615-1794bbac5c06?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Z3JheSUyMGJhY2tncm91bmR8ZW58MHx8MHx8fDA%3D" class="card-img-top" alt="">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3"></div>
                            <h3 class="text-primary">Restaurante 1</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                    <img src="https://plus.unsplash.com/premium_photo-1701090939615-1794bbac5c06?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Z3JheSUyMGJhY2tncm91bmR8ZW58MHx8MHx8fDA%3D" class="card-img-top" alt="">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3"></div>
                            <h3 class="text-primary">Restaurante 2</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg rounded-3">
                    <img src="https://plus.unsplash.com/premium_photo-1701090939615-1794bbac5c06?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Z3JheSUyMGJhY2tncm91bmR8ZW58MHx8MHx8fDA%3D" class="card-img-top" alt="">
                        <div class="card-body">
                            <div class="bg-light rounded mb-3"></div>
                            <h3 class="text-primary">Restaurante 3</h3>
                            <p class="text-secondary">Comidas deliciosas e entrega rápida.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection