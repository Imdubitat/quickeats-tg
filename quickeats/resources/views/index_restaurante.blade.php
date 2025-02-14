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
                <form action="{{ route('login_estabelecimento') }}" method="POST">
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
    <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="signupModalLabel">Cadastro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cadastro_restaurante') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="nomeFantasiaSignup" name="nomeFantasiaSignup" type="text" class="form-control rounded-4" placeholder="Nome Fantasia">
                                <label for="nomeFantasiaSignup">Nome Fantasia</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cnpjSignup" name="cnpjSignup" type="text" class="form-control rounded-4" placeholder="CNPJ">
                                <label for="cnpjSignup">CNPJ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="telefoneSignup" name="telefoneSignup" type="text" class="form-control rounded-4" placeholder="Telefone">
                                <label for="telefoneSignup">Telefone</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="logradouroSignup" name="logradouroSignup" type="text" class="form-control rounded-4" placeholder="Logradouro">
                                <label for="logradouroSignup">Logradouro</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="numeroSignup" name="numeroSignup" type="number" class="form-control rounded-4" placeholder="Número">
                                <label for="numeroSignup">Número</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="bairroSignup" name="bairroSignup" type="text" class="form-control rounded-4" placeholder="Bairro">
                                <label for="bairroSignup">Bairro</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cidadeSignup" name="cidadeSignup" type="text" class="form-control rounded-4" placeholder="Cidade">
                                <label for="cidadeSignup">Cidade</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="estadoSignup" name="estadoSignup" type="text" class="form-control rounded-4" placeholder="Estado">
                                <label for="estadoSignup">Estado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cepSignup" name="cepSignup" type="text" class="form-control rounded-4" placeholder="CEP">
                                <label for="cepSignup">CEP</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="inicioExpedienteSignup" name="inicioExpedienteSignup" type="time" class="form-control rounded-4" placeholder="Início Expediente">
                                <label for="inicioExpedienteSignup">Início Expediente</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="terminoExpedienteSignup" name="terminoExpedienteSignup" type="time" class="form-control rounded-4" placeholder="Término Expediente">
                                <label for="terminoExpedienteSignup">Término Expediente</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="emailSignup" name="emailSignup" type="email" class="form-control rounded-4" placeholder="Email">
                                <label for="emailSignup">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="senhaSignup" name="senhaSignup" type="password" class="form-control rounded-4" placeholder="Senha">
                                <label for="senhaSignup">Senha</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
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