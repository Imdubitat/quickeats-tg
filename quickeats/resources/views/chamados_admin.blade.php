@extends('template_admin')

@section('title', 'Chamados | Admin')

@section('nav-buttons')
@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 15rem; max-width: 60%;">
    @if($mensagensCliente->isEmpty() && $mensagensEstab->isEmpty())
        <p>Você não tem mensagens</p>
    @else
        <div class="accordion" id="accordionMessages">
            <!-- Accordion para mensagens de clientes -->
            @if(!$mensagensCliente->isEmpty())
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMessagesCliente">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMessagesCliente" aria-expanded="true" aria-controls="collapseMessagesCliente">
                        Chamados de Clientes
                    </button>
                </h2>
                <div id="collapseMessagesCliente" class="accordion-collapse collapse show" aria-labelledby="headingMessagesCliente" data-bs-parent="#accordionMessages">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach($mensagensCliente as $idChat => $mc)
                                <div class="col-12 mb-4">
                                    <div class="card pb-2">
                                        <div class="card-header">
                                            <strong>{{ $mc->id_remetente == $idAdmin ? 'Você' : 'Usuário ' . $mc->id_remetente }}</strong> - 
                                            <small>{{ \Carbon\Carbon::parse($mc->data_envio)->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $mc->mensagem }}</p>
                                            <p><strong>Categoria:</strong> {{ $mc->categoria }}</p>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-custom responder-chamado" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#respostaModalCliente-{{ $mc->id_chat }}">
                                                Responder
                                            </button>
                                            <button type="button" class="btn btn-custom4 detalhes-chamado" data-bs-toggle="modal" data-bs-target="#detalhesChamadoModal" data-chamado-id="{{ $mc->id_chat }}">
                                                Detalhes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="respostaModalCliente-{{ $mc->id_chat }}" tabindex="-1" aria-labelledby="respostaClienteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="respostaClienteModalLabel">Responder à Mensagem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('responder_chamado_cliente') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="resposta_cliente" class="form-label">Sua Resposta</label>
                                                        <textarea class="form-control" id="resposta_cliente" name="resposta_cliente" rows="4" required></textarea>
                                                    </div>
                                                    <input type="hidden" id="id_chat" name="id_chat" value="{{ $mc->id_chat }}">
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
                    </div>
                </div>
            </div>
            @endif

            <!-- Accordion para mensagens de estabelecimentos -->
            @if(!$mensagensEstab->isEmpty())
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMessagesEstab">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMessagesEstab" aria-expanded="false" aria-controls="collapseMessagesEstab">
                        Chamados de Estabelecimentos
                    </button>
                </h2>
                <div id="collapseMessagesEstab" class="accordion-collapse collapse" aria-labelledby="headingMessagesEstab" data-bs-parent="#accordionMessages">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach($mensagensEstab as $idChat => $me)
                                <div class="col-12 mb-4">
                                    <div class="card pb-2">
                                        <div class="card-header">
                                            <strong>{{ $me->id_remetente == $idAdmin ? 'Você' : 'Estabelecimento ' . $me->id_remetente }}</strong> - 
                                            <small>{{ \Carbon\Carbon::parse($me->data_envio)->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $me->mensagem }}</p>
                                            <p><strong>Categoria:</strong> {{ $me->categoria }}</p>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-custom responder-chamado" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#respostaModalEstab-{{ $me->id_chat }}">
                                                Responder
                                            </button>
                                            <button type="button" class="btn btn-custom4 detalhes-chamado" data-bs-toggle="modal" data-bs-target="#detalhesChamadoModal" data-chamado-id="{{ $me->id_chat }}">
                                                Detalhes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="respostaModalEstab-{{ $me->id_chat }}" tabindex="-1" aria-labelledby="respostaEstabModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="respostaEstabModalLabel">Responder à Mensagem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('responder_chamado_estab') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="resposta_estab" class="form-label">Sua Resposta</label>
                                                        <textarea class="form-control" id="resposta_estab" name="resposta_estab" rows="4" required></textarea>
                                                    </div>
                                                    <input type="hidden" id="id_chat" name="id_chat" value="{{ $me->id_chat }}">
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
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Accordion para mensagens de usuários não cadastrados -->
            @if(!$mensagensNaoCad->isEmpty())
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMessagesNaoCad">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMessagesNaoCad" aria-expanded="false" aria-controls="collapseMessagesNaoCad">
                        Chamados de Usuários Não Cadastrados
                    </button>
                </h2>
                <div id="collapseMessagesNaoCad" class="accordion-collapse collapse" aria-labelledby="headingMessagesNaoCad" data-bs-parent="#accordionMessages">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach($mensagensNaoCad as $mnc)
                                <div class="col-12 mb-4">
                                    <div class="card pb-2">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $mnc->nome }}</strong> - 
                                                <small>{{ \Carbon\Carbon::parse($mnc->data_envio)->format('d/m/Y H:i') }}</small><br>
                                                <span class="text-muted" id="email-{{ $mnc->id_mensagem }}">{{ $mnc->email }}</span>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary" onclick="copiarEmail('{{ $mnc->id_mensagem }}')">Copiar E-mail</button>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $mnc->mensagem }}</p>
                                        </div>
                                        <form action="{{ route('mensagem_resolver') }}" method="POST" class="text-center mt-2">
                                            @csrf
                                            <input type="hidden" name="id_mensagem" value="{{ $mnc->id_mensagem }}">
                                            <button type="submit" class="btn btn-success">Marcar como Respondido</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif
</section>
@endsection

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
                
                fetch(`/chamados/${chamadoId}/mensagens`)
                    .then(response => response.json())
                    .then(data => {
                        const mensagensContainer = document.getElementById("mensagensChamado");
                        mensagensContainer.innerHTML = "";
                        
                        data.forEach(mensagem => {
                            const mensagemDiv = document.createElement("div");
                            mensagemDiv.classList.add("mb-3", "p-2", "border", "rounded");
                            mensagemDiv.innerHTML = `
                                <strong>${mensagem.id_remetente == {{ $idAdmin }} ? 'Você' : `Usuário ${mensagem.id_remetente}`}</strong> -
                                <small>${new Date(mensagem.data_envio).toLocaleString("pt-BR")}</small>
                                <p class="mt-1">${mensagem.mensagem}</p>
                            `;
                            mensagensContainer.appendChild(mensagemDiv);
                        });
                    });
            });
        });
    });

    function copiarEmail(id) {
        const emailElement = document.getElementById('email-' + id);
        const email = emailElement.textContent || emailElement.innerText;

        navigator.clipboard.writeText(email).then(() => {
            alert('E-mail copiado: ' + email);
        }).catch(err => {
            console.error('Erro ao copiar e-mail: ', err);
        });
    }
</script>