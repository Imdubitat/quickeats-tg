@extends('template_cliente')

@section('title', 'Adm Cliente')

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
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="min-width: 800px; border-radius: 20px 0px 20px 0px;">
            <div class="card-header text-white text-center" style="background: #1E3A8A; border-radius: 20px 0px 20px 0px;">
                <h2>Dados Cadastrais</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('altera_cadastro') }}" method="POST">
                    @csrf
                    @foreach($cadastro as $c)
                        <div class="mb-3">
                        <p class="fs-5">Olá, <span  class="fw-bold">{{ $c->nome }}!</span></p>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $c->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" id="telefone" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ $c->telefone }}">
                            @error('telefone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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

<script src="https://unpkg.com/imask"></script>

<script>
    IMask(
        document.getElementById('telefone'),
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
</script>
@endsection