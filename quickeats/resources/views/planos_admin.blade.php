@extends('template_admin')

@section('title', 'Home | Admin')

@section('nav-buttons')
@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 15rem; max-width: 60%;">
<div class="mb-5 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Restaurantes com planos ativos</h3>
    </div>
    <table class="mx-auto table table-responsive table-striped table-hover table-bordered">
        <tr>
            <th>Nome fantasia</th>
            <th>Tipo de plano</th>
            <th>Valor do plano</th>
        </tr>
        @foreach($restaurantes as $r)
            <tr>
                <td>{{ $r->nome_fantasia }}</td>
                <td>{{ $r->nome_plano }}</td>
                <td>{{ $r->valor }}</td>
            </tr>
        @endforeach
    </table>
</section>
@endsection