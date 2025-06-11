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
                        <input type="text" class="form-control" id="logradouro{{ $endereco->id_endereco }}" name="logradouro" value="{{ old('logradouro', $endereco->logradouro) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero{{ $endereco->id_endereco }}" class="form-label">Número</label>
                        <input type="text" class="form-control rounded-4 @error('numero') is-invalid @enderror" id="numero{{ $endereco->id_endereco }}" name="numero" value="{{ old('numero', $endereco->numero) }}" required>
                        @error('numero')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="bairro{{ $endereco->id_endereco }}" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro{{ $endereco->id_endereco }}" name="bairro" value="{{ old('bairro', $endereco->bairro) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cidade{{ $endereco->id_endereco }}" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade{{ $endereco->id_endereco }}" name="cidade" value="{{ old('cidade', $endereco->cidade) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado{{ $endereco->id_endereco }}" class="form-label">Estado</label>
                        <input type="text" class="form-control" maxlength="2" id="estado{{ $endereco->id_endereco }}" name="estado" value="{{ old('estado', $endereco->estado) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cep{{ $endereco->id_endereco }}" class="form-label">CEP</label>
                        <input type="text" class="form-control cep-mask @error('cep') is-invalid @enderror" id="cep{{ $endereco->id_endereco }}" name="cep" value="{{ old('cep', $endereco->cep) }}" required>
                        @error('cep')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
                        <input id="cepCad" type="text" class="form-control rounded-4 @error('cepCad') is-invalid @enderror" placeholder="CEP" name="cepCad" value="{{ old('cepCad') }}" required>
                        <label for="cepCad">CEP</label>
                        @error('cepCad')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <select id="estado" class="form-select rounded-4" name="estado" value="{{ old('estado') }}" required>
                            <option value="" disabled {{ old('estado') ? '' : 'selected' }}>Selecione</option>
                            <option value="AC" {{ old('estado') == 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('estado') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('estado') == 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('estado') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('estado') == 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('estado') == 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('estado') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('estado') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('estado') == 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('estado') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('estado') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('estado') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('estado') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('estado') == 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('estado') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('estado') == 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('estado') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('estado') == 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('estado') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('estado') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('estado') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('estado') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('estado') == 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('estado') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('estado') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('estado') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('estado') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                        <label for="estado">Estado</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="cidade" type="text" class="form-control rounded-4" placeholder="Cidade" name="cidade" value="{{ old('cidade') }}" required>
                        <label for="cidade">Cidade</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="bairro" type="text" class="form-control rounded-4" placeholder="Bairro" name="bairro" value="{{ old('bairro') }}" required>
                        <label for="bairro">Bairro</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="logradouro" type="text" class="form-control rounded-4" placeholder="Rua" name="logradouro" value="{{ old('logradouro') }}" required>
                        <label for="logradouro">Rua</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="numeroCad" type="text" class="form-control rounded-4 @error('numeroCad') is-invalid @enderror" placeholder="Número" name="numeroCad" value="{{ old('numeroCad') }}" required>
                        <label for="numeroCad">Número</label>
                        @error('numeroCad')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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

<script src="https://unpkg.com/imask"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar modal de cadastro se houver erros de cadastro
        @if ($errors->has('cepCad') || $errors->has('numeroCad'))
            var enderecoModal = new bootstrap.Modal(document.getElementById('enderecoModal'));
            enderecoModal.show();
        @endif

        @if ($errors->has('cep') || $errors->has('numero'))
            var editarEnderecoModal = new bootstrap.Modal(document.getElementById('editarEnderecoModal{{ $endereco->id_endereco }}'));
            editarEnderecoModal.show();
        @endif

        const cepElement = document.getElementById('cepCad');
        if (cepElement) {
            IMask(cepElement, { mask: '00000-000' });
        }

        const cepInputs = document.querySelectorAll('.cep-mask');
        cepInputs.forEach(function(input) {
            IMask(input, { mask: '00000-000' });
        });
    });
</script>