@extends('template')

@section('title', 'Página inicial')

@section('nav-buttons')
    <!-- <ul class="nav d-flex flex-wrap justify-content-start">
        <li class="nav-item">
            <a href="" id="login" class="btn btn-custom ms-4" data-bs-toggle="modal" data-bs-target="#signinModal">Tenho conta</a>
        </li>

        <li class="nav-item">
            <a href="" id="signup" class="btn btn-custom2 ms-4" data-bs-toggle="modal" data-bs-target="#signupModal">Cadastrar</a>
        </li>
    </ul> -->
@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container text-center mt-5">
        <h1 class="fw-bold" style="color: #1E3A8A">Peça sua comida favorita em poucos cliques!</h1>
        <p class="lead">Descubra restaurantes incríveis perto de você e faça pedidos com facilidade.</p>

        <div class="d-flex justify-content-center mt-4">
            <a href="" class="btn btn-primary btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#signinModal">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </a>
            <a href="" class="btn btn-success btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#signupModal">
                <i class="fas fa-user-plus"></i> Criar Conta
            </a>
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <h3><i class="fas fa-utensils"></i> Restaurantes variados</h3>
                <p>Escolha entre diversas opções de comida.</p>
            </div>
            <div class="col-md-4">
                <h3><i class="fas fa-tags"></i> Promoções e descontos</h3>
                <p>Aproveite ofertas exclusivas.</p>
            </div>
            <div class="col-md-4">
                <h3><i class="fas fa-bicycle"></i> Facilidade no pedido</h3>
                <p>Faça pedidos de forma rápida e segura.</p>
            </div>
        </div>
    </div>
</section>

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
                        <input id="nomeSignup" name="nomeSignup" type="text" class="form-control rounded-4 @error('nomeSignup') is-invalid @enderror" placeholder="Nome"  value="{{ old('nomeSignup') }}" required pattern="^[A-Za-zÀ-ÿ\s]+$">
                        <label for="nomeSignup">Nome Completo</label>
                        @error('nomeSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="cpfSignup" name="cpfSignup" type="text" class="form-control rounded-4 @error('cpfSignup') is-invalid @enderror" placeholder="XXX.XXX.XXX-XX" value="{{ old('cpfSignup') }}" required>
                        <label for="cpfSignup">CPF</label>
                        @error('cpfSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="dataNascSignup" name="dataNascSignup" type="date" class="form-control rounded-4 @error('dataNascSignup') is-invalid @enderror" placeholder="Data de nascimento" value="{{ old('dataNascSignup') }}" required>
                        <label for="dataNascSignup">Data de nascimento</label>
                        @error('dataNascSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="telefoneSignup" name="telefoneSignup" type="text" class="form-control rounded-4 @error('telefoneSignup') is-invalid @enderror" placeholder="(XX) XXXXX-XXXX" value="{{ old('telefoneSignup') }}" required>
                        <label for="telefoneSignup">Telefone</label>
                        @error('telefoneSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="emailSignup" name="emailSignup" type="email" class="form-control rounded-4 @error('emailSignup') is-invalid @enderror" placeholder="Email" value="{{ old('emailSignup') }}" required>
                        <label for="emailSignup">Email</label>
                        @error('emailSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="senhaSignup" name="senhaSignup" type="password" class="form-control rounded-4 @error('senhaSignup') is-invalid @enderror" placeholder="Senha" value="{{ old('senhaSignup') }}" required>
                        <label for="senhaSignup">Senha</label>
                        @error('senhaSignup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
@endsection


<script src="https://unpkg.com/imask"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Mostrar modal de cadastro se houver erros de cadastro
        @if ($errors->has('nomeSignup') || $errors->has('dataNascSignup') || $errors->has('cpfSignup') || $errors->has('telefoneSignup') || $errors->has('emailSignup') || $errors->has('senhaSignup'))
            var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
            signupModal.show();
        @endif

        // Mostrar modal de login se houver erros de login
        @if ($errors->has('emailLogin') || $errors->has('senhaLogin'))
            var signinModal = new bootstrap.Modal(document.getElementById('signinModal'));
            signinModal.show();
        @endif

        // Máscara para telefone
        IMask(document.getElementById('telefoneSignup'), {
            mask: [
                { mask: '(00) 0000-0000' },
                { mask: '(00) 00000-0000' }
            ]
        });

        // Máscara para CPF
        IMask(document.getElementById('cpfSignup'), {
            mask: '000.000.000-00'
        });

        // Flatpickr para data de nascimento
        const flatpickrInstance = flatpickr("#dataNascSignup", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            maxDate: "today",
            locale: "pt",
            allowInput: true,
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.altInput) {
                    // Aplicar máscara no altInput
                    const mask = IMask(instance.altInput, {
                        mask: '00/00/0000'
                    });

                    instance.altInput.addEventListener('input', function () {
                        const dateParts = this.value.split('/');
                        if (dateParts.length === 3) {
                            const [day, month, year] = dateParts.map(Number);
                            if (day > 0 && month > 0 && year > 1000) {
                                const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                                flatpickrInstance.setDate(formattedDate, true);
                            }
                        }
                    });
                }
            }
        });
    });
</script>