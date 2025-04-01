@extends('template_restaurante')

@section('title', 'Home | Restaurante')

@section('nav-buttons')

@endsection

@section('content')
<section style="margin-top: 14rem;">
    <div class="container mt-4">
        <div class="row align-items-center mb-4">
            <div class="col-md-4">
                <h2>📊 Dashboard Resumido</h2>
            </div>
            <div class="col-md-8">
                <h6 class="fw-bold d-inline">Avaliação: {{ $avaliacao[0]->media_avaliacao }} </h6>
                <h6 class="text-warning d-inline">&#9733;</h6>
            </div>

        </div>

        <div class="row">
            <!-- Total de Pedidos -->
            <div class="col-md-4">
                <a href="{{ route('pedidos_restaurante') }}" class="text-decoration-none">
                    <div class="card text-white" style="background-color: #0000CD;">
                        <div class="card-body">
                            <h5 class="card-title">📦 Total de Pedidos</h5>
                            <h2>{{ $totalPedidos }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pedidos Pendentes -->
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #ff6347;">
                    <div class="card-body">
                        <h5 class="card-title">⏳ Pedidos Aguardando Aprovação</h5>
                        <h2>{{ $pendentes }}</h2>
                    </div>
                </div>
            </div>

            <!-- Pedidos em Preparação -->
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #48D1CC;">
                    <div class="card-body">
                        <h5 class="card-title">🍳 Em Preparação</h5>
                        <h2>{{ $preparacao }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Pedidos em Rota -->
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #5F9EA0;">
                    <div class="card-body">
                        <h5 class="card-title">🚚 Em Rota</h5>
                        <h2>{{ $emRota }}</h2>
                    </div>
                </div>
            </div>

            <!-- Pedidos Finalizados -->
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #2E8B57;">
                    <div class="card-body">
                        <h5 class="card-title">✅ Finalizados</h5>
                        <h2>{{ $finalizados }}</h2>
                    </div>
                </div>
            </div>

            <!-- Estoque Baixo -->
            <div class="col-md-4">
                <a href="{{ route('estoque_restaurante') }}" class="text-decoration-none">
                    <div class="card text-white" style="background-color: #FF0000;">
                        <div class="card-body">
                            <h5 class="card-title">📉 Estoque Baixo</h5>
                            <h2>{{ $estoqueBaixo }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection