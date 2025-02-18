@extends('template_restaurante')

@section('title', 'Pedidos | Restaurante')

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    <ul class="nav nav-tabs" id="pedidosTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pendentes-tab" data-bs-toggle="tab" data-bs-target="#pendentes" type="button" role="tab">
                Aguardando Aprovação
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preparacao-tab" data-bs-toggle="tab" data-bs-target="#preparacao" type="button" role="tab">
                Em Preparação
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rota-tab" data-bs-toggle="tab" data-bs-target="#rota" type="button" role="tab">
                Em Rota de Entrega
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="finalizados-tab" data-bs-toggle="tab" data-bs-target="#finalizados" type="button" role="tab">
                Finalizados
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="pedidosTabsContent">
        @foreach(['pendentes' => 2, 'preparacao' => 3, 'rota' => 4, 'finalizados' => 5] as $tab => $status)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $tab }}" role="tabpanel">
                <div class="row">
                    @php
                        $filteredPedidos = array_filter($pedidos, function($pedido) use ($status) {
                            return $pedido->status_entrega == $status;
                        });
                    @endphp

                    @foreach($filteredPedidos as $pedido)
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Pedido #{{ $pedido->id_pedido }}</h5>
                                    <p class="card-text"><strong>Cliente:</strong> {{ $pedido->nome_cliente }}</p>
                                    <p class="card-text">
                                        <strong>Valor:</strong> R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Forma de Pagamento:</strong> {{ $pedido->forma_pagamento }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Data:</strong> {{ \Carbon\Carbon::parse($pedido->data_compra)->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Endereço:</strong> {{ $pedido->endereco }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Produtos:</strong> {{ $pedido->produtos }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Status:</strong> {{ $pedido->status_entrega }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(empty($filteredPedidos))
                    <p class="text-muted text-center">Nenhum pedido encontrado.</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endsection
