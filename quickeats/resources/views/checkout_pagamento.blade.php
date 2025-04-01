@extends('template_cliente')

@section('title', 'Pagamento')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    <div class="d-flex justify-content-start mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>

    <div class="container">
        <form action="{{ route('realizar_pedido') }}" method="POST">
            @csrf
            @foreach($formas_pagamento as $fp)
            <div class="row border p-4 rounded-4 align-items-center mb-3">
                <div class="col-md-1 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="pagamento" id="pagamento{{ $fp->id_formapag }}" value="{{ $fp->id_formapag }}" required>
                </div>
                <label class="col-md-11" for="pagamento{{ $fp->id_formapag }}">
                    <h5>{{ $fp->descricao }}</h6>
                </label>
            </div>
            @endforeach

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom4 w-50">Realizar pagamento</button>
            </div>
        </form>
    </div>
</section>
@endsection
