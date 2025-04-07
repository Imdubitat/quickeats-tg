@extends('template')

@section('title', 'FAQs')

@section('nav-buttons')

@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 13rem;">
    <div class="d-flex justify-content-start mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>
    
    <!-- FAQs - Clientes -->
    <div class="mb-2 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Perguntas Frequentes - Clientes</h3>
    </div>
    <div class="accordion mb-5" id="faqAccordionClientes">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingClientesOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClientesOne" aria-expanded="true" aria-controls="collapseClientesOne">
                    Como faço um pedido?
                </button>
            </h2>
            <div id="collapseClientesOne" class="accordion-collapse collapse show" aria-labelledby="headingClientesOne" data-bs-parent="#faqAccordionClientes">
                <div class="accordion-body">
                    Para fazer um pedido, basta acessar nosso site ou aplicativo, escolher os itens desejados, adicionar ao carrinho e finalizar a compra informando endereço e forma de pagamento.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingClientesTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClientesTwo" aria-expanded="false" aria-controls="collapseClientesTwo">
                    Quais são as formas de pagamento aceitas?
                </button>
            </h2>
            <div id="collapseClientesTwo" class="accordion-collapse collapse" aria-labelledby="headingClientesTwo" data-bs-parent="#faqAccordionClientes">
                <div class="accordion-body">
                    Aceitamos pagamentos via cartão de crédito, débito, PIX e carteira digital. Também é possível pagar na entrega com dinheiro ou maquininha.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingClientesThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClientesThree" aria-expanded="false" aria-controls="collapseClientesThree">
                    Quanto tempo leva para meu pedido chegar?
                </button>
            </h2>
            <div id="collapseClientesThree" class="accordion-collapse collapse" aria-labelledby="headingClientesThree" data-bs-parent="#faqAccordionClientes">
                <div class="accordion-body">
                    O tempo de entrega pode variar dependendo do estabelecimento e da sua localização. Em média, os pedidos são entregues entre 30 a 60 minutos.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingClientesFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClientesFour" aria-expanded="false" aria-controls="collapseClientesFour">
                    Posso cancelar um pedido após a finalização?
                </button>
            </h2>
            <div id="collapseClientesFour" class="accordion-collapse collapse" aria-labelledby="headingClientesFour" data-bs-parent="#faqAccordionClientes">
                <div class="accordion-body">
                    Sim, você pode cancelar um pedido antes da preparação começar. Para isso, entre em contato com o suporte imediatamente pelo chat ou telefone.
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- FAQs - Restaurantes -->
    <div class="mb-2 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Perguntas Frequentes - Restaurantes</h3>
    </div>
    <div class="accordion mb-5" id="faqAccordionRestaurantes">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRestaurantesOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRestaurantesOne" aria-expanded="true" aria-controls="collapseRestaurantesOne">
                    Como faço para cadastrar meu estabelecimento na plataforma?
                </button>
            </h2>
            <div id="collapseRestaurantesOne" class="accordion-collapse collapse show" aria-labelledby="headingRestaurantesOne" data-bs-parent="#faqAccordionRestaurantes">
                <div class="accordion-body">
                    Para cadastrar seu estabelecimento, basta acessar nossa plataforma, preencher os dados solicitados sobre o seu negócio e aguardar a aprovação para começar a receber pedidos.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRestaurantesTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRestaurantesTwo" aria-expanded="false" aria-controls="collapseRestaurantesTwo">
                    Quais são os requisitos para vender na plataforma?
                </button>
            </h2>
            <div id="collapseRestaurantesTwo" class="accordion-collapse collapse" aria-labelledby="headingRestaurantesTwo" data-bs-parent="#faqAccordionRestaurantes">
                <div class="accordion-body">
                    Os principais requisitos incluem ter uma operação legalizada, oferecer um cardápio de qualidade e garantir que as entregas sejam feitas no prazo. Também pedimos que os estabelecimentos estejam localizados em áreas atendidas pela nossa plataforma.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRestaurantesThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRestaurantesThree" aria-expanded="false" aria-controls="collapseRestaurantesThree">
                    Como posso gerenciar os pedidos recebidos?
                </button>
            </h2>
            <div id="collapseRestaurantesThree" class="accordion-collapse collapse" aria-labelledby="headingRestaurantesThree" data-bs-parent="#faqAccordionRestaurantes">
                <div class="accordion-body">
                    Após o cadastro, você terá acesso a um painel de administração onde poderá gerenciar os pedidos em tempo real, atualizar o status das entregas e fazer ajustes no seu cardápio conforme necessário.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRestaurantesFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRestaurantesFour" aria-expanded="false" aria-controls="collapseRestaurantesFour">
                    Como posso receber os pagamentos dos pedidos?
                </button>
            </h2>
            <div id="collapseRestaurantesFour" class="accordion-collapse collapse" aria-labelledby="headingRestaurantesFour" data-bs-parent="#faqAccordionRestaurantes">
                <div class="accordion-body">
                    Os pagamentos podem ser feitos através de plataformas de pagamento integradas, como cartões de crédito, débito, PIX, ou até mesmo na entrega. O valor dos pedidos é repassado semanalmente para a conta cadastrada no sistema.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRestaurantesFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRestaurantesFive" aria-expanded="false" aria-controls="collapseRestaurantesFive">
                    Como posso ajustar o horário de funcionamento do meu estabelecimento?
                </button>
            </h2>
            <div id="collapseRestaurantesFive" class="accordion-collapse collapse" aria-labelledby="headingRestaurantesFive" data-bs-parent="#faqAccordionRestaurantes">
                <div class="accordion-body">
                    No painel de administração, você pode configurar facilmente os horários de funcionamento do seu estabelecimento, podendo também ajustá-los conforme feriados ou eventos especiais.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
