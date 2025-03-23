@extends('template_cliente')

@section('title', 'Chamados | Cliente')

@section('nav-buttons')

@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 15rem; max-width: 60%;">

    <div class="mb-2 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Meus Chamados</h3>
    </div>

    @if($mensagens->isEmpty())
        <p>Você não tem chamados registrados.</p>
    @else
        <div class="row">
            @foreach($mensagens as $mensagem)
                <div class="col-12 mb-4">
                    <div class="card pb-2">
                        <div class="card-header">
                            <strong>{{ $mensagem->id_remetente == $id_cliente ? 'Você' : 'Suporte' }}</strong> - 
                            <small>{{ \Carbon\Carbon::parse($mensagem->data_envio)->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $mensagem->mensagem }}</p>
                            <p><strong>Categoria:</strong> {{ $mensagem->categoria }}</p>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-custom3" data-bs-toggle="modal" data-bs-target="#respostaModal-{{ $mensagem->id_chat }}">
                            Responder
                            </button>
                            <button type="button" class="btn btn-custom4 detalhes-chamado" data-bs-toggle="modal" data-bs-target="#detalhesChamadoModal" data-chamado-id="{{ $mensagem->id_chat }}">
                                Detalhes
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="respostaModal-{{ $mensagem->id_chat }}" tabindex="-1" aria-labelledby="respostaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="respostaModalLabel">Responder à Mensagem</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('cliente_responder_chamado') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="resposta" class="form-label">Sua Resposta</label>
                                        <textarea class="form-control" id="resposta" name="resposta" rows="4" required></textarea>
                                    </div>
                                    <input type="hidden" id="id_chat" name="id_chat" value="{{ $mensagem->id_chat }}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="container text-center mb-5">
        <button type="button" class="btn btn-lg btn-custom mb-5" data-bs-toggle="modal" data-bs-target="#abrirChamadoModal">
            Entre em Contato
        </button>
    </div>

    <div class="mb-2 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Perguntas Frequentes</h3>
    </div>
    <div class="accordion mb-5" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Como faço um pedido?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Para fazer um pedido, basta acessar nosso site ou aplicativo, escolher os itens desejados, adicionar ao carrinho e finalizar a compra informando endereço e forma de pagamento.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Quais são as formas de pagamento aceitas?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Aceitamos pagamentos via cartão de crédito, débito, PIX e carteira digital. Também é possível pagar na entrega com dinheiro ou maquininha.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Quanto tempo leva para meu pedido chegar?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    O tempo de entrega pode variar dependendo do estabelecimento e da sua localização. Em média, os pedidos são entregues entre 30 a 60 minutos.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Posso cancelar um pedido após a finalização?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Sim, você pode cancelar um pedido antes da preparação começar. Para isso, entre em contato com o suporte imediatamente pelo chat ou telefone.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<div class="modal fade" id="abrirChamadoModal" tabindex="-1" aria-labelledby="abrirChamadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Entre em Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('abrir_chamado_cliente') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione uma categoria</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->nome }}">{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="message" name="mensagem" rows="5" placeholder="Sua mensagem" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-custom">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detalhesChamadoModal" tabindex="-1" aria-labelledby="detalhesChamadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalhesChamadoModalLabel">Detalhes do Chamado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="mensagensChamado" class="overflow-auto" style="max-height: 400px;">
                    <!-- Mensagens serão inseridas aqui via JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const detalhesButtons = document.querySelectorAll("[data-bs-target='#detalhesChamadoModal']");
        
        detalhesButtons.forEach(button => {
            button.addEventListener("click", function () {
                const chamadoId = this.getAttribute("data-chamado-id");
                
                fetch(`/cliente/chamados/${chamadoId}/mensagens`)
                    .then(response => response.json())
                    .then(data => {
                        const mensagensContainer = document.getElementById("mensagensChamado");
                        mensagensContainer.innerHTML = "";
                        
                        data.forEach(mensagem => {
                            const mensagemDiv = document.createElement("div");
                            mensagemDiv.classList.add("mb-3", "p-2", "border", "rounded");
                            mensagemDiv.innerHTML = `
                                <strong>${mensagem.id_remetente == {{ $id_cliente }} ? 'Você' : 'Suporte'}</strong> -
                                <small>${new Date(mensagem.data_envio).toLocaleString("pt-BR")}</small>
                                <p class="mt-1">${mensagem.mensagem}</p>
                            `;
                            mensagensContainer.appendChild(mensagemDiv);
                        });
                    });
            });
        });
    });
</script>