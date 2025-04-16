@extends('template_cliente')

@section('title', 'Alteração de Senha')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 13rem;">
    <div class="d-flex justify-content-start mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="min-width: 800px; border-radius: 20px 0px 20px 0px;">
            <div class="card-header text-white text-center" style="background: #1E3A8A; border-radius: 20px 0px 20px 0px;">
                <h2>Alterar senha</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('confirmar_senha') }}" method="POST">
                    @csrf
                    @foreach($cadastro as $c)
                        <div class="mb-3">
                        <p class="fs-5">Olá, <span  class="fw-bold">{{ $c->nome }}!</span></p>
                        </div>
                        <div class="mb-3">
                            <label for="senhaAntiga" class="form-label">Senha atual</label>
                            <input type="password" id="senhaAntiga" name="senhaAntiga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="novaSenha" class="form-label">Nova senha</label>
                            <input type="password" id="novaSenha" name="novaSenha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmarSenha" class="form-label">Confirmar senha</label>
                            <input type="password" id="confirmarSenha" name="confirmarSenha" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-custom3">Atualizar Dados</button>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</section>