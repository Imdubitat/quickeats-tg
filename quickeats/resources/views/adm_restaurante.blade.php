@extends('template_restaurante')

@section('title', 'Adm Restaurante')

@section('nav-buttons')
@endsection

@section('content')
<section class="container px-5 mx-auto" style="margin-top: 13rem;">
    <div class="mb-5 ps-3 border-bottom border-start border-danger border-3 rounded-start" 
         style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Meu perfil</h3>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center">
        <div class="col">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Informações cadastrais</p>
                <p class="text-muted">Atualize ou confira seus dados.</p>
                <a href="{{ route('info_restaurante') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Segurança e privacidade</p>
                <p class="text-muted">Altere sua senha.</p>
                <a href="{{ route('alterar_senhaEstab') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Histórico de pedidos</p>
                <p class="text-muted">Consulte seus pedidos anteriores.</p>
                <a href="{{ route('pedidos_restaurante') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Ajuda</p>
                <p class="text-muted">Obtenha suporte e resposta para as suas dúvidas.</p>
                <a href="{{ route('listar_chamados_estab') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
    </div>
</section>
@endsection
