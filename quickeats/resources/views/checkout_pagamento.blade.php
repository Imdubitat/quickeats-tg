@extends('template_cliente')

@section('title', 'Pagamento')

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
        <form id="payment-form">
            @csrf
            <div class="mb-4">
                <label for="card-element" class="form-label">Informações do cartão</label>
                <div id="card-element" class="form-control p-3">
                    <!-- Stripe vai injetar o campo aqui -->
                </div>
                <div id="card-errors" class="text-danger mt-2"></div>
            </div>

            <div class="text-center mt-4">
                <button id="submit" class="btn btn-custom4 w-50">Pagar agora</button>
            </div>
        </form>
    </div>
</section>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card = elements.create("card");
    card.mount("#card-element");

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const {error, paymentIntent} = await stripe.confirmCardPayment(
            "{{ $clientSecret }}",
            {
                payment_method: {
                    card: card
                }
            }
        );

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else if (paymentIntent.status === 'succeeded') {
            // Envia os dados para o back-end via POST
            fetch("{{ route('realizar_pedido') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntent.id,
                    forma_pagamento_id: 1, // exemplo
                    valor_total: 99.90     // exemplo
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Erro ao salvar pedido");
                return response.json();
            })
            .then(data => {
                // Redireciona para uma página de sucesso
                window.location.href = "carrinho";
            })
            .catch(error => {
                console.error(error);
                document.getElementById('card-errors').textContent = "Erro ao finalizar o pedido.";
            });
        }
    });
</script>
@endsection
