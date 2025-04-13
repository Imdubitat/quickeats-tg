@extends('template_admin')

@section('title', 'Home | Admin')

@section('nav-buttons')
@endsection

@section('content')
<section class="container mx-auto" style="margin-top: 15rem; max-width: 60%;">
    <div class="mb-5 ps-3 border-bottom border-start border-danger border-3 rounded-start" style="border-left-width: 5px !important; padding-left: 10px;">
        <h3 class="fw-bold">Clientes cadastrados</h3>
    </div>
    <table class="mx-auto table table-responsive table-striped table-hover table-bordered">
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
        @foreach($clientes as $c)
            <tr>
                <td>{{ $c->nome }}</td>
                <td>{{ $c->telefone }}</td>
                <td>{{ $c->cpf }}</td>
                <td>{{ $c->email }}</td>
                <td>
                    <a id="details" class="btn btn-custom4 ms-4" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $c->id_cliente }}">Detalhes</a>
                    <form action="{{ route('desativar_cliente', $c->id_cliente) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger ms-2" {{ $c->perfil_ativo == 0 ? 'disabled' : '' }}>Desativar</button>
                    </form>

                    <form action="{{ route('ativar_cliente', $c->id_cliente) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success ms-2" {{ $c->perfil_ativo == 1 ? 'disabled' : '' }}>Ativar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm">
                {{-- Página Anterior --}}
                <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $clientes->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Anterior</span>
                    </a>
                </li>

                {{-- Links de Páginas --}}
                @foreach ($clientes->getUrlRange(1, $clientes->lastPage()) as $page => $url)
                    <li class="page-item {{ $clientes->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Página Seguinte --}}
                <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $clientes->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">Próxima &raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection

<!-- Modal de Detalhes -->
@foreach($clientes as $c)
<div class="modal fade" id="detailsModal-{{ $c->id_cliente }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Detalhes do Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" value="{{ $c->nome }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF</label>
                            <input type="text" class="form-control" value="{{ $c->cpf }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Data de nascimento</label>
                            <input type="text" class="form-control" value="{{ $c->data_nasc }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" value="{{ $c->telefone }}" readonly>                        
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">E-mail</label>
                            <input type="text" class="form-control" value="{{ $c->email }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Endereços</label>
                            @foreach($c->enderecos as $endereco)
                                <input type="text" class="form-control mb-1" value="{{ $endereco->logradouro }}, {{ $endereco->numero }} - {{ $endereco->bairro }}, {{ $endereco->cidade }}/{{ $endereco->estado }}, {{ $endereco->cep }}" readonly>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
