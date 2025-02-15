@extends('template_cliente')

@section('title', 'Home | Cliente')

@section('nav-buttons')

@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    <div class="row mx-5">
        @foreach($produtos as $p)
            <div class="col-md-4 mb-4">
                <form action="" method="GET">
                    @csrf
                    <input type="hidden" name="produto" value="{{ $p->id_produto }}">
                    <div class="card shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->nome_produto }}</h5>
                            <p class="card-text">R$ {{ $p->valor }}<br>
                            {{ $p->estab }}</p>
                            <a type="submit" href="" class="btn btn-custom3">Adicionar ao carrinho</a>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</section>
@endsection