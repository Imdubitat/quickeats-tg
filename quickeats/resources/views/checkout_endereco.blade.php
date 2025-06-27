@extends('template_cliente')

@section('title', 'Entrega')

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
        <form action="{{ route('exibir_pagamentos') }}" method="POST">
            @csrf
            @foreach($enderecos as $e)
            <div class="row border p-4 rounded-4 align-items-center mb-3">
                <div class="col-md-1 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="endereco" id="endereco{{ $e->id_endereco }}" value="{{ $e->id_endereco }}" required>
                </div>
                <label class="col-md-11" for="endereco{{ $e->id_endereco }}">
                    <h5>{{ $e->logradouro }}, {{ $e->numero }}</h5>
                    <h6>{{ $e->bairro }}, {{ $e->cidade }} - {{ $e->estado }}</h6>
                    <h6>CEP: {{ $e->cep }}</h6>
                </label>
            </div>
            @endforeach

            <div class="text-center mt-4">
                <button id="submit" class="btn btn-success w-50 mt-4 fw-semibold" type="submit">
                    <span id="submit-text">Realizar pagamento</span>
                    <span id="submit-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const submitButton = document.getElementById('submit');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');

        form.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitSpinner.classList.remove('d-none');
            submitText.classList.add('d-none');
        });
    });
</script>
@endsection