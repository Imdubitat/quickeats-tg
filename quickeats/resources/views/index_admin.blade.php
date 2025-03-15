@extends('template')

@section('title', 'Página inicial | Admin')

@section('nav-buttons')
@endsection

@section('content')
<section class="d-flex px-5 justify-content-center" style="margin-top: 15rem;">
    <div class="border rounded p-5 bg-body-tertiary" style="min-width: 400px;">
        <div class="" style="min-width: 350px;">
            <div class="rounded-4">
                <div>
                    <h5 class="fw-bold">Log in</h5>
                </div>
                <div>
                    <form action="{{ route('login_admin') }}" method="POST">
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
                            <button type="submit" class="btn btn-custom4 w-50">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection