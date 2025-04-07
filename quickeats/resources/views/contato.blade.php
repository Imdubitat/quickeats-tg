@extends('template')

@section('title', 'Contato')

@section('content')
<section style="margin-top: 13rem;">
    <div class="container">
        <h1 class="text-center fw-bold mb-4 text-primary">Fale com a gente</h1>
        <p class="text-center mb-5 fs-5">Tem alguma dúvida, sugestão ou precisa de ajuda? Preencha o formulário ou entre em contato pelos nossos canais.</p>

        <div class="row">
            <div class="col-md-6 mb-4">
                <form action="" method="POST" class="p-4 rounded-4 shadow bg-white">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Seu nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Mensagem</label>
                        <textarea name="mensagem" id="mensagem" class="form-control" rows="5" placeholder="Escreva sua mensagem..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Enviar mensagem</button>
                </form>
            </div>

            <div class="col-md-6 d-flex flex-column justify-content-center">
                <div class="bg-light p-4 rounded-4 shadow">
                    <h4 class="text-primary mb-3 fw-bold">Nossos canais</h4>
                    <p><strong>Email:</strong> contato@quickeats.com</p>
                    <p><strong>Telefone:</strong> (11) 1234-5678</p>
                    <p><strong>Endereço:</strong> Rua dos Sabores, 100 - São Paulo/SP</p>

                    <h5 class="mt-4 fw-bold">Redes Sociais</h5>
                    <ul class="list-unstyled d-flex gap-3">
                        <li><a href="#" class="fab fa-instagram text-decoration-none"> Instagram</a></li>
                        <li><a href="#" class="fab fa-facebook text-decoration-none"> Facebook</a></li>
                        <li><a href="#" class="fab fa-linkedin text-decoration-none"> LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection