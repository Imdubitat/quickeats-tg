@extends('template_restaurante')

@section('title', 'Adm Restaurante')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5" style="margin-top: 15rem;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-5">
        <div class="row">
            <!-- Coluna de Dados Cadastrais -->
            <div class="col-md-8">
                <div class="card shadow-sm mx-auto" style="min-width: 800px; border-radius: 20px 0px 20px 0px;">
                    <div class="card-header text-white text-center" style="background: #1E3A8A; border-radius: 20px 0px 20px 0px;">
                        <h2>Dados Cadastrais</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('altera_cadastro_res') }}" method="POST">
                            @csrf
                            @foreach($cadastro as $c)
                                <div class="mb-3">
                                    <p class="fs-5">Olá, <span class="fw-bold">{{ $c->nome_fantasia }}!</span></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ $c->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" id="telefone" name="telefone" class="form-control" value="{{ $c->telefone }}">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-custom3">Atualizar Dados</button>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>

            <!-- Coluna da imagem de perfil -->
            <div class="col-md-4 text-center">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <!-- Exibição da imagem -->
                        <img src="{{ asset('imagem_perfil/' . (auth()->user()->imagem_perfil ?? 'sem_foto.png')) }}" 
                             alt="Foto de perfil" class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        
                        <!-- Formulário de upload -->
                        <form action="{{ route('imagem_upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-start">
                                <label for="imagem_perfil" class="form-label">Atualizar Foto de Perfil:</label>
                                <input type="file" id="imagem_perfil" name="imagem_perfil" accept="image/*" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Enviar Foto</button>
                        </form>

                        @error('imagem_perfil')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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