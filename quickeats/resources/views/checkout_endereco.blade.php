@extends('template_cliente')

@section('title', 'Entrega')

@section('nav-buttons')
@endsection

@section('content')
<section class="px-5 mb-5" style="margin-top: 13rem;">
    <div class="d-flex justify-content-start mb-4">
        <button onclick="window.history.back()" class="btn btn-outline-primary d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </button>
    </div>

    <div class="container">
        <!-- Botão para abrir o modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#enderecoModal">
            Adicionar Endereço
        </button>

        <form action="{{ route('exibir_pagamentos') }}" method="POST">
            @csrf
            @foreach($enderecos as $e)
            <div class="row card-endereco p-4 rounded-4 align-items-center mb-3 bg-white">
                <div class="col-md-1 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="endereco" id="endereco{{ $e->id_endereco }}" value="{{ $e->id_endereco }}" required>
                </div>
                <label class="col-md-11" for="endereco{{ $e->id_endereco }}">
                    <h5>{{ $e->logradouro }}, {{ $e->numero }}</h5>
                    <h6>{{ $e->bairro }}, {{ $e->cidade }} - {{ $e->estado }}</h6>
                    <h6>CEP: {{ $e->cep }}</h6>
                </label>
            </div>
            @endforeach

            <div class="text-center mt-4">
                <button id="submit" type="submit" class="btn btn-custom4 w-50">
                    <span id="submit-text">Realizar pagamento</span>
                    <span id="submit-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</section>

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
                    <input type="hidden" name="redirectTo" value="{{ route('exibir_enderecos') }}">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const submitButton = document.getElementById('submit');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');

        form.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitSpinner.classList.remove('d-none');
            submitText.classList.add('d-none');
        });
    });
</script>

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
@endsection