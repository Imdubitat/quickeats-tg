@extends('template_cliente')

@section('title', 'Pagamento')

@section('nav-buttons')
@endsection

@section('content')
<section class="container  mb-5" style="margin-top: 13rem;">
    <!-- Botão Voltar -->
    <div class="mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>

    <div class="row g-5">        
        <!-- Coluna Direita: Pagamento -->
        <div class="col-lg-7">
            <div class="border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-semibold text-primary mb-3">Valor Total</h5>
                @php
                $valorTotal = 0;
                foreach ($produtos as $p) {
                    $subtotal = floatval($p->valor) * $p->qtd_produto;
                    $valorTotal += $subtotal;
                }
                @endphp
                <h3 class="text-success fw-bold">R$ {{ number_format($valorTotal, 2, ',', '.') }}</h3>
                <p class="text-muted mb-0">Entrega: Grátis</p>
            </div>
            
            <!-- Pagamento -->
            <div class="border rounded-4 p-4 shadow-sm bg-white">
                <h5 class="fw-semibold text-primary mb-3">Pagamento</h5>
                <form id="payment-form">
                    @csrf
                    <label for="card-element" class="form-label">Informações do cartão</label>
                    <div id="card-element" class="form-control p-3 shadow-sm">
                        <!-- Stripe vai injetar o campo aqui -->
                    </div>
                    <div id="card-errors" class="text-danger mt-2"></div>
                    
                    <button id="submit" class="btn btn-success w-100 mt-4 fw-semibold d-flex justify-content-center align-items-center" type="submit">
                        <span id="submit-text">Finalizar Pedido</span>
                        <span id="submit-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Coluna Esquerda: Endereço + Produtos -->
        <div class="col-lg-5">
            <!-- Endereço -->
            <div class="border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-semibold text-primary mb-3">Endereço de Entrega</h5>
                <p class="mb-1">{{ $endereco->logradouro }}, {{ $endereco->numero }}</p>
                <p class="mb-1 text-muted">{{ $endereco->bairro }} - {{ $endereco->cidade }}/{{ $endereco->estado }}</p>
                <p class="mb-0 text-muted">CEP: {{ $endereco->cep }}</p>
            </div>

            <!-- Produtos -->
            <div class="border rounded-4 p-4 shadow-sm bg-white">
                <h5 class="fw-semibold text-primary mb-3">Resumo dos Produtos</h5>
                @foreach($produtos as $p)
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <!-- Lado esquerdo: Imagem + nome -->
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('imagem_produto/' . ($p->imagem ?? 'sem_foto.png')) }}" 
                                alt="Imagem do Produto" 
                                class="me-3 rounded"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <strong>{{ $p->qtd_produto }}x {{ $p->nome_produto }}</strong>
                            </div>
                        </div>

                        <!-- Lado direito: Valor -->
                        <div class="text-success fw-semibold">
                            R$ {{ $p->valor }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card = elements.create("card", {
        hidePostalCode: true
    });
    card.mount("#card-element");

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Mostra spinner e desativa botão
        submitButton.disabled = true;
        submitSpinner.classList.remove('d-none');
        submitText.classList.add('d-none');
        cardErrors.textContent = '';

        try {
            const { error, paymentIntent } = await stripe.confirmCardPayment(
                "{{ $clientSecret }}",
                {
                    payment_method: {
                        card: card
                    }
                }
            );

            if (error) {
                throw error;
            }

            if (paymentIntent.status === 'succeeded') {
                await fetch("{{ route('realizar_pedido') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                        payment_method_id: paymentIntent.payment_method,
                        valor_total: {{ $valorTotal }}
                    })
                });

                sessionStorage.setItem('mensagem', 'Pedido realizado com sucesso!');
                window.location.href = "carrinho";
            } else {
                throw new Error("Pagamento não foi concluído com sucesso.");
            }
        } catch (error) {
            console.error(error);
            cardErrors.textContent = error.message || "Erro ao finalizar o pedido.";
            
            // Reativa botão e esconde spinner
            submitButton.disabled = false;
            submitSpinner.classList.add('d-none');
            submitText.classList.remove('d-none');
        }
    });
</script>


@endsection
