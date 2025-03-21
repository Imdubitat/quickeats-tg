@extends('template_restaurante')

@section('title', 'Planos | Restaurante')

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    <div class="row mb-4">
        <h2>Meu Plano Atual</h2>
        @if ($planoAtivo)
            <div class="col-md-3"> <!-- Centraliza o card -->
                <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                    <div class="card-body">
                        <h3 class="card-title fw-bold text-primary">Plano {{ $planoAtivo->nome }}</h3>
                        <p class="card-text fs-5 text-success">Valor: <strong>R$ {{ number_format($planoAtivo->valor, 2, ',', '.') }}</strong></p>
                        <hr>
                        <p class="card-text text-muted">{!! nl2br(e($planoAtivo->beneficios)) !!}</p>
                    </div>
                </div>
            </div>
        @else
            <p class="text-muted">Você ainda não possui um plano ativo.</p>
        @endif
    </div>

    <div class="row">
        <h2>Planos Disponíveis</h2>

        @if ($planosDisponiveis->isNotEmpty())
            @foreach ($planosDisponiveis as $plano)
                <div class="col-md-3 mb-4"> <!-- Cada card em uma coluna separada -->
                    <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                        <div class="card-body">
                            <h3 class="card-title fw-bold text-primary">Plano {{ $plano->nome }}</h3>
                            <p class="card-text fs-5 text-success">Valor: <strong>R$ {{ number_format($plano->valor, 2, ',', '.') }}</strong></p>
                            <hr>
                            <p class="card-text text-muted">{!! nl2br(e($plano->beneficios)) !!}</p>

                            <!-- Formulário para escolher o plano -->
                            <form action="{{ route('escolher_plano') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_plano" value="{{ $plano->id_plano }}">
                                <button type="submit" class="btn btn-primary">Escolher Plano</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>Nenhum plano disponível no momento.</p>
        @endif
    </div>

</section>
@endsection
