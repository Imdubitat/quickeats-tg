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
                            <h5 class="card-title">📉 Produtos com estoque baixo</h5>
                            <h2>{{ $estoqueBaixo }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="dadosComplementaresModal" tabindex="-1" aria-labelledby="dadosComplementaresModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('salvar_dados_complementares') }}" method="POST">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="dadosComplementaresModalLabel">Complete seus dados</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="razao_social" class="form-label">Razão Social</label>
                        <input type="text" class="form-control" id="razao_social" name="razao_social" required>
                    </div>

                    <div class="mb-3">
                        <label for="cpf_titular" class="form-label">CPF do Titular</label>
                        <input type="text" class="form-control" id="cpf_titular" name="cpf_titular" required>
                    </div>

                    <div class="mb-3">
                        <label for="rg_titular" class="form-label">RG do Titular</label>
                        <input type="text" class="form-control" id="rg_titular" name="rg_titular" required>
                    </div>

                    <div class="mb-3">
                        <label for="cnae" class="form-label">CNAE</label>
                        <input type="text" class="form-control" id="cnae" name="cnae" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custom4">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/imask"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($exibirModal)
            var dadosModal = new bootstrap.Modal(document.getElementById('dadosComplementaresModal'), {
                backdrop: 'static',
                keyboard: false
            });
            dadosModal.show();
        @endif


        // Máscaras
        const cpfElement = document.getElementById('cpf_titular');
        if (cpfElement) {
            IMask(cpfElement, { mask: '000.000.000-00' });
        }

        const rgElement = document.getElementById('rg_titular');
        if (rgElement) {
            IMask(rgElement, { mask: '00.000.000-0' });
        }

        const cnaeElement = document.getElementById('cnae');
        if (cnaeElement) {
            IMask(cnaeElement, { mask: '0000-0/00' });
        }
    });
</script>

@endsection