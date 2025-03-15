@extends('template_cliente')

@section('title', 'Home | Cliente')

@section('nav-buttons')

@endsection

@section('content')
<div class="px-5" style="margin-top: 13rem;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h2 class="mb-4">Meus Endereços</h2>
    
    <!-- Botão para abrir o modal -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#enderecoModal">
        Adicionar Endereço
    </button>

    <div class="row">
        @foreach($enderecos as $endereco)
            <div class="col-md-3 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $endereco->logradouro }}, {{ $endereco->numero }}</h5>
                            <p class="card-text">
                                {{ $endereco->bairro }}, {{ $endereco->cidade }} - {{ $endereco->estado }}<br>
                                CEP: {{ $endereco->cep }}
                            </p>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <button class="btn btn-custom4 btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#editarEnderecoModal{{ $endereco->id_endereco }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <form action="{{ route('excluir_endereco', $endereco->id_endereco) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este endereço?');">
                                @csrf
                                <button type="submit" class="btn btn-custom3 btn-sm">
                                    <i class='fas fa-trash-alt'></i> Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach($enderecos as $endereco)
<div class="modal fade" id="editarEnderecoModal{{ $endereco->id_endereco }}" tabindex="-1" aria-labelledby="editarEnderecoModalLabel{{ $endereco->id_endereco }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarEnderecoModalLabel{{ $endereco->id_endereco }}">Editar Endereço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('editar_endereco', $endereco->id_endereco) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="logradouro{{ $endereco->id_endereco }}" class="form-label">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro{{ $endereco->id_endereco }}" name="logradouro" value="{{ $endereco->logradouro }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero{{ $endereco->id_endereco }}" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero{{ $endereco->id_endereco }}" name="numero" value="{{ $endereco->numero }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="bairro{{ $endereco->id_endereco }}" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro{{ $endereco->id_endereco }}" name="bairro" value="{{ $endereco->bairro }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cidade{{ $endereco->id_endereco }}" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade{{ $endereco->id_endereco }}" name="cidade" value="{{ $endereco->cidade }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado{{ $endereco->id_endereco }}" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="estado{{ $endereco->id_endereco }}" name="estado" value="{{ $endereco->estado }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cep{{ $endereco->id_endereco }}" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep{{ $endereco->id_endereco }}" name="cep" value="{{ $endereco->cep }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


<!-- Modal de Cadastro de Endereço -->
<div class="modal fade" id="enderecoModal" tabindex="-1" aria-labelledby="enderecoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 350px;">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="enderecoModalLabel">Cadastrar Endereço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cadastrar_endereco') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="cep" type="text" class="form-control rounded-4" placeholder="CEP" name="cep" required>
                        <label for="cep">CEP</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select id="estado" class="form-select rounded-4" name="estado" required>
                            <option value="" selected>Selecione</option>
                            <option value="SP">São Paulo</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="MG">Minas Gerais</option>
                            <!-- Adicione mais estados -->
                        </select>
                        <label for="estado">Estado</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="cidade" type="text" class="form-control rounded-4" placeholder="Cidade" name="cidade" required>
                        <label for="cidade">Cidade</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="bairro" type="text" class="form-control rounded-4" placeholder="Bairro" name="bairro" required>
                        <label for="bairro">Bairro</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="logradouro" type="text" class="form-control rounded-4" placeholder="Rua" name="logradouro" required>
                        <label for="logradouro">Rua</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="numero" type="number" class="form-control rounded-4" placeholder="Número" name="numero" required>
                        <label for="numero">Número</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-custom4 w-50">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection