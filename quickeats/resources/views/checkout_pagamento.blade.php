@extends('template_cliente')

@section('title', 'Pagamento')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    <div class="container">
        <form action="{{ route('realizar_pedido') }}" method="POST">
            @csrf
            @php $i = 0; @endphp
            @foreach($formas_pagamento as $fp)
            <div class="row border p-4 rounded-4 align-items-center mb-3">
                <div class="col-md-1 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="pagamento" id="pagamento{{ $fp->id_formapag }}" value="{{ $fp->id_formapag }}" required>
                </div>
                <label class="col-md-11" for="pagamento{{ $fp->id_formapag }}">
                    <h5>Forma de pagamento {{ $i + 1 }}</h5>
                    <h6>Descrição: {{ $fp->descricao }}</h6>
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
