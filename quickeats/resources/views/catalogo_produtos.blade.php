@extends('template_cliente')

@section('title', 'Catálogo de produtos')

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
    <div class="row mx-auto">
        @foreach($produtos as $p)
            <div class="col-md-4 mb-4">
                <form action="{{ route ('adicionar_carrinho') }}" method="POST">
                    @csrf
                    <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                    <input type="hidden" name="data_adicao" value="{{ now() }}">
                    <div class="card shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->nome_produto }}</h5>
                            <p class="card-text">R$ {{ $p->valor }}<br>
                            {{ $p->estab }}</p>
                            <label for="qtd_produto_{{ $p->id_produto }}" class="form-label">Quantidade:</label>
                            <input type="text" name="qtd_produto" id="qtd_produto_{{ $p->id_produto }}"
                            class="form-control-sm mb-2" value="1" min="1" style="width: 30px; height: 10px;" required><br>
                            
                           <button type="submit" class="btn btn-custom3">Adicionar ao carrinho</button>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</section>
@endsection