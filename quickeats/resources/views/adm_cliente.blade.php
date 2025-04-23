@extends('template_cliente')

@section('title', 'Adm Cliente')

@section('nav-buttons')
@endsection

@section('content')
<section class="container px-5 mx-auto" style="margin-top: 13rem;">
    <div class="mb-5 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Meu perfil</h3>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Informações cadastrais</p>
                <p class="text-muted">Atualize ou confira seus dados.</p>
                <a href="{{ route('info_cliente') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Endereços</p>
                <p class="text-muted">Cadastre ou confira seus endereços cadastrados.</p>
                <a href="{{ route('enderecos') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Segurança e privacidade</p>
                <p class="text-muted">Altere sua senha.</p>
                <a href="{{ route('alterar_senha') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Histórico de pedidos</p>
                <p class="text-muted">Consulte seus pedidos anteriores.</p>
                <a href="{{ route('pedidos_cliente') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Métodos de pagamento</p>
                <p class="text-muted">Gerencie seus formas de pagamento.</p>
                <a href="" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center p-4 shadow-sm hover-effect">
                <p class="fw-bold fs-4">Ajuda</p>
                <p class="text-muted">Obtenha suporte e resposta para a suas dúvidas.</p>
                <a href="{{ route('listar_chamados_cliente') }}" class="btn btn-custom4">Acessar</a>
            </div>
        </div>
    </div>
</section>
@endsection
