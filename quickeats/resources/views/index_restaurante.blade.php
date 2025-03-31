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
                <form action="{{ route('login_estabelecimento') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="emailLogin" type="email" class="form-control rounded-4 @error('emailLogin') is-invalid @enderror" placeholder="Email" name="emailLogin" value="{{ old('emailLogin') }}" required>
                        <label for="emailLogin">Email</label>
                        @error('emailLogin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="senhaLogin" type="password" class="form-control rounded-4 @error('senhaLogin') is-invalid @enderror" placeholder="Senha" name="senhaLogin" value="{{ old('senhaLogin') }}" required>
                        <label for="senhaLogin">Senha</label>
                        @error('senhaLogin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>Ainda não tem uma conta? <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Cadastre-se</a></p>
                    </div>
                    <div class="text-center mb-3">
                        <a class="" data-bs-toggle="modal" data-bs-target="#forgotPasswordModalEstab" style="cursor: pointer;">Esqueci a senha</a>
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
                                <input id="nomeFantasiaSignup" name="nomeFantasiaSignup" type="text" class="form-control rounded-4 @error('nomeFantasiaSignup') is-invalid @enderror" placeholder="Nome Fantasia" value="{{ old('nomeFantasiaSignup') }}" required>
                                <label for="nomeFantasiaSignup">Nome Fantasia</label>
                                @error('nomeFantasiaSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cnpjSignup" name="cnpjSignup" type="text" class="form-control rounded-4 @error('cnpjSignup') is-invalid @enderror" placeholder="XX.XXX.XXX/XXXX-XX" value="{{ old('cnpjSignup') }}" required>
                                <label for="cnpjSignup">CNPJ</label>
                                @error('cnpjSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="telefoneSignup" name="telefoneSignup" type="text" class="form-control rounded-4 @error('telefoneSignup') is-invalid @enderror" placeholder="(XX) XXXXX-XXXX" value="{{ old('telefoneSignup') }}" required>
                                <label for="telefoneSignup">Telefone</label>
                                @error('telefoneSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="logradouroSignup" name="logradouroSignup" type="text" class="form-control rounded-4 @error('logradouroSignup') is-invalid @enderror" placeholder="Logradouro" value="{{ old('logradouroSignup') }}" required>
                                <label for="logradouroSignup">Logradouro</label>
                                @error('logradouroSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="numeroSignup" name="numeroSignup" type="number" class="form-control rounded-4 @error('numeroSignup') is-invalid @enderror" value="{{ old('numeroSignup') }}" placeholder="Número" required>
                                <label for="numeroSignup">Número</label>
                                @error('numeroSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="bairroSignup" name="bairroSignup" type="text" class="form-control rounded-4 @error('bairroSignup') is-invalid @enderror" placeholder="Bairro" value="{{ old('bairroSignup') }}" required>
                                <label for="bairroSignup">Bairro</label>
                                @error('bairroSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cidadeSignup" name="cidadeSignup" type="text" class="form-control rounded-4 @error('cidadeSignup') is-invalid @enderror" placeholder="Cidade" value="{{ old('cidadeSignup') }}" required>
                                <label for="cidadeSignup">Cidade</label>
                                @error('cidadeSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="estadoSignup" name="estadoSignup" type="text" class="form-control rounded-4 @error('estadoSignup') is-invalid @enderror" placeholder="Estado" value="{{ old('estadoSignup') }}" required>
                                <label for="estadoSignup">Estado</label>
                                @error('estadoSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="cepSignup" name="cepSignup" type="text" class="form-control rounded-4 @error('cepSignup') is-invalid @enderror" placeholder="XXXXX-XXX" value="{{ old('cepSignup') }}" required>
                                <label for="cepSignup">CEP</label>
                                @error('cepSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="inicioExpedienteSignup" name="inicioExpedienteSignup" type="time" class="form-control rounded-4 @error('inicioExpedienteSignup') is-invalid @enderror" placeholder="Início Expediente" value="{{ old('inicioExpedienteSignup') }}" required>
                                <label for="inicioExpedienteSignup">Início Expediente</label>
                                @error('inicioExpedienteSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="terminoExpedienteSignup" name="terminoExpedienteSignup" type="time" class="form-control rounded-4 @error('terminoExpedienteSignup') is-invalid @enderror" placeholder="Término Expediente" value="{{ old('terminoExpedienteSignup') }}" required>
                                <label for="terminoExpedienteSignup">Término Expediente</label>
                                @error('terminoExpedienteSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="emailSignup" name="emailSignup" type="email" class="form-control rounded-4 @error('emailSignup') is-invalid @enderror" placeholder="Email" value="{{ old('emailSignup') }}" required>
                                <label for="emailSignup">Email</label>
                                @error('emailSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="senhaSignup" name="senhaSignup" type="password" class="form-control rounded-4 @error('senhaSignup') is-invalid @enderror" placeholder="Senha" value="{{ old('senhaSignup') }}" required>
                                <label for="senhaSignup">Senha</label>
                                @error('senhaSignup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

<!-- Modal de redefinição de senha -->
<div class="modal fade" id="forgotPasswordModalEstab" tabindex="-1" aria-labelledby="forgotPAsswordModalLabelEstab" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 class="fw-bold mb-0 fs-2">Reset de senha</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 pt-0">
                <form action="{{ route('esqueceuSenhaEstabelecimento') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3 @error('emailResetSenhaEstab') is-invalid @enderror" id="floatingForgotPasswordEstab" name="emailResetSenhaEstab" placeholder="name@example.com" value="{{ old('emailResetSenhaEstab') }}" required>
                        <label for="floatingForgotPasswordEstab">Email address</label>
                        @error('emailResetSenhaEstab')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/imask"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar modal de cadastro do Estabelecimento se houver erros de cadastro
        @if ($errors->has('nomeFantasiaSignup') || $errors->has('telefoneSignup') || $errors->has('cnpjSignup') 
        || $errors->has('logradouroSignup') || $errors->has('numeroSignup') || $errors->has('bairroSignup') || $errors->has('cidadeSignup') 
        || $errors->has('estadoSignup') || $errors->has('cepSignup') || $errors->has('inicioExpedienteSignup') || $errors->has('terminoExpedienteSignup')
        || $errors->has('emailSignup')|| $errors->has('senhaSignup'))
            var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
            signupModal.show();
        @endif

        // Mostrar modal de login do Estabelecimento se houver erros de login
        @if ($errors->has('emailLogin') || $errors->has('senhaLogin'))
            var signinModal = new bootstrap.Modal(document.getElementById('signinModal'));
            signinModal.show();
        @endif
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Configuração geral do horário
        const configHorario = {
            enableTime: true,       // Ativa a escolha de horário
            noCalendar: true,       // Oculta o calendário (apenas horário)
            dateFormat: "H:i",      // Formato 24h (HH:mm)
            time_24hr: true,        // Usa formato 24h
            minuteIncrement: 5,      // Incremento de minutos
            allowInput: true
        };

        // Aplica o Flatpickr nos campos
        flatpickr("#inicioExpedienteSignup", configHorario);
        flatpickr("#terminoExpedienteSignup", configHorario);
    });

    IMask(
        document.getElementById('cnpjSignup'),
        {
            mask: '00.000.000/0000-00',
        },
    );

    IMask(
        document.getElementById('telefoneSignup'),
        {
            mask: [
                {
                    mask: '(00) 0000-0000',
                },
                {
                    mask: '(00) 00000-0000',
                }
            ],
        }
    );

    IMask(
        document.getElementById('cepSignup'),
        {
            mask: '00000-000',
        },
    );
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>  