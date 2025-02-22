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
            <button class="nav-link" id="aguardando-cancelamento-tab" data-bs-toggle="tab" data-bs-target="#aguardando-cancelamento" type="button" role="tab">
                Aguardando Cancelamento
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
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cancelado-tab" data-bs-toggle="tab" data-bs-target="#cancelado" type="button" role="tab">
                Cancelados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="recusado-tab" data-bs-toggle="tab" data-bs-target="#recusado" type="button" role="tab">
                Recusados
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="pedidosTabsContent">
    @foreach(['pendentes' => 2, 'preparacao' => 3, 'rota' => 4, 'finalizados' => 5, 'aguardando-cancelamento' => 6, 'cancelado' => 7, 'recusado' => 8] as $tab => $status)
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
                                @if($pedido->status_entrega == 2) 
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('pedidos_status', $pedido->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="3">
                                            <button type="submit" class="btn btn-success">Aceitar Pedido</button>
                                        </form>
                                        <form action="{{ route('pedidos_status', $pedido->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="8">
                                            <button type="submit" class="btn btn-danger">Recusar Pedido</button>
                                        </form>
                                    </div>
                                @elseif($pedido->status_entrega == 3) 
                                    <form action="{{ route('pedidos_status', $pedido->id_pedido) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="novo_status" value="4">
                                        <button type="submit" class="btn btn-primary">Marcar como Pronto</button>
                                    </form>
                                @elseif($pedido->status_entrega == 6) 
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('pedidos_status', $pedido->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="7">
                                            <button type="submit" class="btn btn-danger">Aprovar Cancelamento</button>
                                        </form>
                                        <form action="{{ route('pedidos_status', $pedido->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="3">
                                            <button type="submit" class="btn btn-warning">Recusar Cancelamento</button>
                                        </form>
                                    </div>
                                @endif
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
