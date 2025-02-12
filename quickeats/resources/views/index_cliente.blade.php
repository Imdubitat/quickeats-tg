@extends('template')

@section('title', 'Página inicial')

@section('nav-buttons')
    <ul class="nav d-flex flex-wrap justify-content-start">
        <li class="nav-item">
            <a href="" id="login" class="btn btn-custom ms-4" data-bs-toggle="modal" data-bs-target="#signinModal">Tenho conta</a>
        </li>

        <li class="nav-item">
            <a href="" id="signup" class="btn btn-custom2 ms-4" data-bs-toggle="modal" data-bs-target="#signupModal">Cadastrar</a>
        </li>
    </ul>
@endsection

@section('content')

@endsection

<!-- Modal de login-->
<div class="modal fade" id="signinModal" tabindex="-1" aria-labelledby="signinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 350px;">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="signinModalLabel">Log in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('login_cliente') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="emailLogin" type="email" class="form-control rounded-4" placeholder="Email" name="emailLogin">
                        <label for="emailLogin">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="senhaLogin" type="password" class="form-control rounded-4" placeholder="Senha" name="senhaLogin">
                        <label for="senhaLogin">Senha</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>Ainda não tem uma conta? <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Cadastre-se</a></p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-custom4 w-50">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de cadastro -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 350px;">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="signupModalLabel">Cadastro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="nomeSignup" type="text" class="form-control rounded-4" placeholder="Nome">
                        <label for="nomeSignup">Nome Completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="cpfSignup" type="text" class="form-control rounded-4" placeholder="CPF">
                        <label for="cpfSignup">CPF</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="dataNascSignup" type="date" class="form-control rounded-4" placeholder="Data de nascimento">
                        <label for="dataNascSignup">Data de nascimento</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="telefoneSignup" type="text" class="form-control rounded-4" placeholder="Telefone">
                        <label for="telefoneSignup">Telefone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="emailSignup" type="email" class="form-control rounded-4" placeholder="Email">
                        <label for="emailSignup">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="senhaSignup" type="password" class="form-control rounded-4" placeholder="Senha">
                        <label for="senhaSignup">Senha</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>Já tem uma conta? <a href="#" data-bs-toggle="modal" data-bs-target="#signinModal">Faça Login</a></p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-custom4 w-50">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>