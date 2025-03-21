@extends('template_admin')

@section('title', 'Home | Admin')

@section('nav-buttons')
@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 15rem; max-width: 60%;">
    <div class="mb-5 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Restaurantes cadastrados</h3>
    </div>
    <table class="mx-auto table table-responsive table-striped table-hover table-bordered">
        <tr>
            <th>Nome fantasia</th>
            <th>Telefone</th>
            <th>CPF titular</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
        @foreach($restaurantes as $r)
            <tr>
                <td>{{ $r->nome_fantasia }}</td>
                <td>{{ $r->telefone }}</td>
                <td>{{ $r->cpf_titular }}</td>
                <td>{{ $r->email }}</td>
                <td>
                    <a id="details" class="btn btn-custom4 ms-4" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $r->id_estab }}">Detalhes</a>
                    <form action="{{ route('desativar_restaurantes', $r->id_estab) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger ms-2" {{ $r->perfil_ativo == 0 ? 'disabled' : '' }}>Desativar</button>
                    </form>

                    <form action="{{ route('ativar_restaurantes', $r->id_estab) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success ms-2" {{ $r->perfil_ativo == 1 ? 'disabled' : '' }}>Ativar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

@endsection

<!-- Modal de Detalhes -->
@foreach($restaurantes as $r)
<div class="modal fade" id="detailsModal-{{ $r->id_estab }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Detalhes do Estabelecimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Razão Social</label>
                            <input type="text" class="form-control" value="{{ $r->razao_social }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nome Fantasia</label>
                            <input type="text" class="form-control" value="{{ $r->nome_fantasia }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">CNPJ</label>
                            <input type="text" class="form-control" value="{{ $r->cnpj }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" value="{{ $r->telefone }}" readonly>                        
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">CPF Titular</label>
                            <input type="text" class="form-control" value="{{ $r->cpf_titular }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">RG Titular</label>
                            <input type="text" class="form-control" value="{{ $r->rg_titular }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">CNAE</label>
                            <input type="text" class="form-control" value="{{ $r->cnae }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Endereço</label>
                            <input type="text" class="form-control" value="{{ $r->logradouro }}, {{ $r->numero }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" value="{{ $r->bairro }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cidade/Estado</label>
                            <input type="text" class="form-control" value="{{ $r->cidade }} - {{ $r->estado }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" value="{{ $r->cep }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $r->email }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horário de Expediente</label>
                            <input type="text" class="form-control" value="{{ $r->inicio_expediente }} - {{ $r->termino_expediente }}" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
