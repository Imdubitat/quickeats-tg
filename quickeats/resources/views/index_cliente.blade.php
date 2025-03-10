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
<section class="px-5" style="margin-top: 15rem;">
    @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
</section>
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
                    <div class="text-center mb-3">
                        <a class="" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" style="cursor: pointer;">Esqueci a senha</a>
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
                <form action="{{ route('cadastro_cliente') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="nomeSignup" name="nomeSignup" type="text" class="form-control rounded-4" placeholder="Nome">
                        <label for="nomeSignup">Nome Completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="cpfSignup" name="cpfSignup" type="text" class="form-control rounded-4" placeholder="CPF">
                        <label for="cpfSignup">CPF</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="dataNascSignup" name="dataNascSignup" type="date" class="form-control rounded-4" placeholder="Data de nascimento">
                        <label for="dataNascSignup">Data de nascimento</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="telefoneSignup" name="telefoneSignup" type="text" class="form-control rounded-4" placeholder="Telefone">
                        <label for="telefoneSignup">Telefone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="emailSignup" name="emailSignup" type="email" class="form-control rounded-4" placeholder="Email">
                        <label for="emailSignup">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="senhaSignup" name="senhaSignup" type="password" class="form-control rounded-4" placeholder="Senha">
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

<!-- Modal redefinição de senha -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPAsswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 class="fw-bold mb-0 fs-2">Reset de senha</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 pt-0">
                <form action="{{ route('esqueceuSenhaCliente') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3 @error('emailResetSenha') is-invalid @enderror" id="floatingForgotPassword" name="emailResetSenha" placeholder="name@example.com" value="{{ old('emailResetSenha') }}" required>
                        <label for="floatingForgotPassword">Email address</label>
                        @error('emailResetSenha')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>