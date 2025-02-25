@extends('template_cliente')

@section('title', 'Pedidos | Cliente')

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
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="aguarda_cancela-tab" data-bs-toggle="tab" data-bs-target="#aguarda_cancela" type="button" role="tab">
                Aguardando cancelamento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cancelados-tab" data-bs-toggle="tab" data-bs-target="#cancelados" type="button" role="tab">
                Cancelados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="recusados-tab" data-bs-toggle="tab" data-bs-target="#recusados" type="button" role="tab">
                Recusados
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="pedidosTabsContent">
    @foreach(['pendentes' => 2, 'preparacao' => 3, 'rota' => 4, 'finalizados' => 5, 'aguarda_cancela' => 6, 'cancelados' => 7, 'recusados' => 8] as $tab => $status)
        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $tab }}" role="tabpanel">
            <div class="row">
                @php
                    $filteredPedidos = array_filter($pedidos, function($p) use ($status) {
                        return $p->status_entrega == $status;
                    });
                @endphp

                @foreach($filteredPedidos as $p)
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pedido #{{ $p->id_pedido }}</h5>
                                <p class="card-text"><strong>Cliente:</strong> {{ $p->nome_cliente }}</p>
                                <p class="card-text">
                                    <strong>Valor:</strong> R$ {{ number_format($p->valor_total, 2, ',', '.') }}
                                </p>
                                <p class="card-text">
                                    <strong>Forma de Pagamento:</strong> {{ $p->forma_pagamento }}
                                </p>
                                <p class="card-text">
                                    <strong>Data:</strong> {{ \Carbon\Carbon::parse($p->data_compra)->format('d/m/Y H:i') }}
                                </p>
                                <p class="card-text">
                                    <strong>Endereço:</strong> {{ $p->endereco }}
                                </p>
                                <p class="card-text">
                                    <strong>Produtos:</strong> {{ $p->produtos }}
                                </p>
                                @if($p->status_entrega == 1 || $p->status_entrega == 2 
                                || $p->status_entrega == 3  || $p->status_entrega == 4) 
                                    {{-- Se o pedido estiver aguardando aprovação --}}
                                    <form action="{{ route('cancelar_pedido', $p->id_pedido) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="novo_status" value="6">
                                        <button type="submit" class="btn btn-outline-danger w-100">Cancelar pedido</button>
                                    </form>
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
