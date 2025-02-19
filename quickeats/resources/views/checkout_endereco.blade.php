@extends('template_cliente')

@section('title', 'Entrega')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    <div class="container">
        <form action="{{ route('exibir_pagamentos') }}" method="POST">
            @csrf
            @php $i = 0; @endphp
            @foreach($enderecos as $e)
            <div class="row border p-4 rounded-4 align-items-center mb-3">
                <div class="col-md-1 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="endereco" id="endereco{{ $e->id_endereco }}" value="{{ $e->id_endereco }}" required>
                </div>
                <label class="col-md-11" for="endereco{{ $e->id_endereco }}">
                    <h5>Endereço {{ $i + 1 }}</h5>
                    <h6>Logradouro: {{ $e->logradouro }}, {{ $e->numero }}</h6>
                    <h6>Bairro: {{ $e->bairro }}, {{ $e->cep }}</h6>
                    <h6>Cidade: {{ $e->cidade }} - {{ $e->estado }}</h6>
                </label>
            </div>
            @php $i++; @endphp
            @endforeach

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom4 w-50">Realizar pagamento</button>
            </div>
        </form>
    </div>
</section>
@endsection
