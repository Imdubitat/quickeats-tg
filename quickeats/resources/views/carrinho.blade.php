@extends('template_cliente')

@section('title', 'Carrinho')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(empty($produtos))
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5">
            <i id="carrinho" class='fas fa-shopping-cart fa-3x' style="color: #1E3A8A;"></i>
            <p class="display-6 fw-bold text-secondary mt-4">Seu carrinho está vazio!</p>
            <p class="text-muted">Adicione alguns produtos para aproveitar as melhores ofertas.</p>
            <a href="{{ route('catalogo_produtos') }}" class="btn btn-custom3 mt-3">Explorar Produtos</a>
        </div>
    @endif

    <div class="container">
        @foreach($produtos as $p)
            <div class="row">
                <div class="col-md-12">
                    <div class="row border p-4 rounded-4 align-items-center">
                        <div class="col-md-4">
                            <h5 class="fw-bold text-primary-emphasis">{{ $p->nome_produto }}</h5>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <form action="{{ route('diminuir_carrinho') }}" method="POST" class="me-2">
                                @csrf
                                <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                                <button type="submit" class="btn btn-custom3">-</button>
                            </form>
                            <h6 class="mb-0">{{ $p->qtd_produto }}</h6>
                            <form action="{{ route('aumentar_carrinho') }}" method="POST" class="ms-2">
                                @csrf
                                <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                                <button type="submit" class="btn btn-custom3">+</button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <h5>R$ {{ $p->valor }}</h5>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('remover_carrinho') }}" method="POST">
                                @csrf
                                <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                                <button type="submit" class="btn btn-custom3">Remover</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if(count($produtos) > 0)
            <div class="text-center mt-4">
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-custom4 w-50">Finalizar Compra</button>
                </form>
            </div>
        @endif
    </div>
</section>
@endsection
