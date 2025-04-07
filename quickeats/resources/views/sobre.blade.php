@extends('template')

@section('title', 'Sobre Nós | QuickEats')

@section('content')
<section style="margin-top: 13rem;">
    <div class="container">
        <div class="d-flex justify-content-start">
            <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Voltar
            </button>
        </div>
        <div class="row mb-5">
            <div class="col-md-6 d-flex justify-content-center">
                <img src="{{ asset('images/quick_logo.png') }}" alt="Sobre a QuickEats" class="img-fluid rounded-4 shadow"
                style="max-height: 400px;">
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <h1 class="fw-bold text-primary mb-5 ps-3 border-bottom border-start border-primary border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
                    Sobre a QuickEats
                </h1>
                <p class="fs-5">
                    A <strong>QuickEats</strong> nasceu com a missão de facilitar sua vida na hora de pedir comida. 
                    Nosso objetivo é conectar você aos melhores restaurantes da sua região com agilidade, praticidade e confiança.
                </p>
                <p class="fs-5">
                    Somos mais que uma plataforma de delivery — somos uma ponte entre pequenos empreendedores locais e clientes que valorizam sabor, qualidade e um bom atendimento.
                </p>
            </div>
        </div>

        <div class="row text-center mb-5 justify-content-between">
            <div class="col-md-4 card-sobre">
                <h3 class="text-primary">🚀 Rápido</h3>
                <p>Receba seu pedido em poucos minutos, com entregas otimizadas e eficientes.</p>
            </div>
            <div class="col-md-4 card-sobre">
                <h3 class="text-primary">🍔 Variedade</h3>
                <p>Milhares de opções para todos os gostos: lanches, comidas típicas, sobremesas e mais.</p>
            </div>
            <div class="col-md-4 card-sobre">
                <h3 class="text-primary">📍 Local</h3>
                <p>Valorizamos os restaurantes da sua região, incentivando o comércio local.</p>
            </div>
        </div>
    </div>

    <div class="py-3"></div>

    <div class="container mx-auto">
        <h2 class="fw-bold text-body-emphasis text-center mb-3">Conheça nossos compromissos</h2>
        <div class="row align-items-center rounded-4 shadow-lg py-5 px-5 mb-5">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold text-body-emphasis mb-3">Nós pensamos em você!</h1>
                <p class="lead">
                    Aqui cada estabelecimento passa por uma avaliação de sua capacidade e a partir desta análise, 
                    condições especiais e uma estratégia personalizada de negócio é desenvolvida para o mesmo. 
                    Taxas e planos personalizados. Aqui, o foco é você!
                </p>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/foco_cliente.png') }}" class="img-fluid rounded-4" alt="" style="max-width:500px;">
            </div>
        </div>
        <div class="row align-items-center rounded-4 shadow-lg py-5 px-5 mb-5">
            <div class="col-md-6">
                <img src="{{ asset('images/uniao_sustentabilidade.png') }}" class="img-fluid rounded-4" alt="" style="max-width:500px;">
            </div>
            <div class="col-md-6">
                <h1 class="display-5 fw-bold text-body-emphasis mb-3">Unidos somos mais fortes!</h1>
                <p class="lead">
                Fomentar o relacionamento com a comunidade e implementar políticas sustentáveis são 
                passos essenciais para construir um ecossistema de delivery mais consciente, colaborativo e responsável.
                </p>
            </div>
        </div>
        <div class="row align-items-center rounded-4 shadow-lg py-5 px-5 mb-5">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold text-body-emphasis mb-3">Todos são importantes!</h1>
                <p class="lead">
                Nosso compromisso é com você — oferecendo opções inclusivas para pessoas com 
                restrições alimentares, sejam intolerantes, veganas, vegetarianas ou com outros estilos de vida.
                </p>    
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/restricao_alimentar.png') }}" class="img-fluid rounded-4" alt="" style="max-width:500px;">
            </div>
        </div>
    </div>
</section>
@endsection
