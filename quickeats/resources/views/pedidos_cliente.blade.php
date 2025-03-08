@extends('template_cliente')

@section('title', 'Pedidos | Cliente')

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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
                                    || $p->status_entrega == 3) 
                                    <form action="{{ route('cancelar_pedido', $p->id_pedido) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="novo_status" value="6">
                                        <button type="submit" class="btn btn-outline-danger w-100">Cancelar pedido</button>
                                    </form>
                                @elseif($p->status_entrega == 4)
                                    <div class="d-flex gap-2"> 
                                        <form action="{{ route('receber_pedido', $p->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="5">
                                            <button type="submit" class="btn btn-primary">Marcar como recebido</button>
                                        </form>
                                        <form action="{{ route('cancelar_pedido', $p->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="novo_status" value="6">
                                            <button type="submit" class="btn btn-outline-danger">Cancelar pedido</button>
                                        </form>
                                    </div>
                                @elseif($p->status_entrega == 5)
                                    @if(!$p->avaliado)
                                        {{-- Formulário de avaliação --}}
                                        <form action="{{ route('avaliar_pedido', $p->id_pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="nota" id="rating-value" value="">
                                            <div class="rating">
                                                <input type="radio" id="star5-{{ $p->id_pedido }}" name="nota" value="5"/>
                                                <label for="star5-{{ $p->id_pedido }}" title="5 estrelas">&#9733;</label>
                                                <input type="radio" id="star4-{{ $p->id_pedido }}" name="nota" value="4"/>
                                                <label for="star4-{{ $p->id_pedido }}" title="4 estrelas">&#9733;</label>
                                                <input type="radio" id="star3-{{ $p->id_pedido }}" name="nota" value="3"/>
                                                <label for="star3-{{ $p->id_pedido }}" title="3 estrelas">&#9733;</label>
                                                <input type="radio" id="star2-{{ $p->id_pedido }}" name="nota" value="2"/>
                                                <label for="star2-{{ $p->id_pedido }}" title="2 estrelas">&#9733;</label>
                                                <input type="radio" id="star1-{{ $p->id_pedido }}" name="nota" value="1"/>
                                                <label for="star1-{{ $p->id_pedido }}" title="1 estrela">&#9733;</label>
                                            </div>
                                            <button type="submit" class="btn btn-custom4 w-100">Avaliar pedido</button>
                                        </form>
                                        @else
                                            <p class="fs-5 fw-bold text-center">Avaliação:</p>
                                            <div class="rating">
                                                {{-- Estrela 5 --}}
                                                <input type="radio" id="star5-{{ $p->id_pedido }}" disabled {{ $p->nota == 5 ? 'checked' : '' }} />
                                                <label for="star5-{{ $p->id_pedido }}" title="5 estrelas">&#9733;</label>
                                                {{-- Estrela 4 --}}
                                                <input type="radio" id="star4-{{ $p->id_pedido }}" disabled {{ $p->nota == 4 ? 'checked' : '' }} />
                                                <label for="star4-{{ $p->id_pedido }}" title="4 estrelas">&#9733;</label>
                                                {{-- Estrela 3 --}}
                                                <input type="radio" id="star3-{{ $p->id_pedido }}" disabled {{ $p->nota == 3 ? 'checked' : '' }} />
                                                <label for="star3-{{ $p->id_pedido }}" title="3 estrelas">&#9733;</label>
                                                {{-- Estrela 2 --}}
                                                <input type="radio" id="star2-{{ $p->id_pedido }}" disabled {{ $p->nota == 2 ? 'checked' : '' }} />
                                                <label for="star2-{{ $p->id_pedido }}" title="2 estrelas">&#9733;</label>
                                                {{-- Estrela 1 --}}
                                                <input type="radio" id="star1-{{ $p->id_pedido }}" disabled {{ $p->nota == 1 ? 'checked' : '' }} />
                                                <label for="star1-{{ $p->id_pedido }}" title="1 estrela">&#9733;</label>
                                            </div>
                                        @endif
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