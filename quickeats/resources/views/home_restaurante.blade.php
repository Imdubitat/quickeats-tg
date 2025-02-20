@extends('template_restaurante')

@section('title', 'Home | Restaurante')

@section('nav-buttons')

@endsection

@section('content')
<section style="margin-top: 14rem;">
    <div class="container mt-4">
        <h2 class="mb-4">📊 Dashboard Resumido</h2>
        
        <div class="row">
            <!-- Total de Pedidos -->
            <div class="col-md-4">
                <a href="{{ route('pedidos_restaurante') }}" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">📦 Total de Pedidos</h5>
                            <h2>{{ $totalPedidos }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pedidos Pendentes -->
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">⏳ Pedidos Aguardando Aprovação</h5>
                        <h2>{{ $pendentes }}</h2>
                    </div>
                </div>
            </div>

            <!-- Pedidos em Preparação -->
            <div class="col-md-4">
                <div class="card bg-info text-white">
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
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">🚚 Em Rota</h5>
                        <h2>{{ $emRota }}</h2>
                    </div>
                </div>
            </div>

            <!-- Pedidos Finalizados -->
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">✅ Finalizados</h5>
                        <h2>{{ $finalizados }}</h2>
                    </div>
                </div>
            </div>

            <!-- Estoque Baixo -->
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">📉 Estoque Baixo</h5>
                        <h2>3 Itens</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection