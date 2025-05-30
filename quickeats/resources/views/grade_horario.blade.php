@extends('template_restaurante')

@section('title', 'Grade Horaria')

@section('content')
<section class="d-flex" style="margin-top: 11rem; margin-bottom: 10rem;">
<div class="container mt-4">
    <h2 class="mb-3">Cadastro de Grade Horária</h2>

    <!-- Formulário de cadastro e edição -->
    <form id="form-grade-horario" action="{{ route('salvarGrade') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="dia_semana">Dia da Semana:</label>
                <select id="dia_semana" name="dia_semana" class="form-select" required>
                    <option value="">Escolha o dia da semana</option>
                    <option value="1">Segunda-feira</option>
                    <option value="2">Terça-feira</option>
                    <option value="3">Quarta-feira</option>
                    <option value="4">Quinta-feira</option>
                    <option value="5">Sexta-feira</option>
                    <option value="6">Sábado</option>
                    <option value="7">Domingo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="inicio_expediente">Hora de Início:</label>
                <input type="time" id="inicio_expediente" name="inicio_expediente" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="termino_expediente">Hora de Término:</label>
                <input type="time" id="termino_expediente" name="termino_expediente" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Salvar</button>   
            </div>
        </div>
    </form>

    <div id="error-message" class="mt-2 text-danger mb-3" style="display:none;"></div>

    @if (session('success'))
        <div class="mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-2 alert alert-success alert-dismissible fade show" role="alert">|
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabela de Horários Cadastrados -->
    <div class="mt-5">
        <h3>Horários Cadastrados</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dia da Semana</th>
                    <th>Início Expediente</th>
                    <th>Fim Expediente</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @if(count($horarios) > 0)
                    @foreach($horarios as $horario)
                        <tr>
                            <td>
                                @if($horario->dia_semana == '1')
                                    Segunda-feira
                                @elseif($horario->dia_semana == '2')
                                    Terça-feira
                                @elseif($horario->dia_semana == '3')
                                    Quarta-feira
                                @elseif($horario->dia_semana == '4')
                                    Quinta-feira
                                @elseif($horario->dia_semana == '5')
                                    Sexta-feira
                                @elseif($horario->dia_semana == '6')
                                    Sábado
                                @elseif($horario->dia_semana == '7')
                                    Domingo
                                @endif
                            </td>
                            <td>{{ $horario->inicio_expediente }}</td>
                            <td>{{ $horario->termino_expediente }}</td>
                            <td>
                                <!-- Botão que abre o modal e passa o ID -->
                                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirmModal" 
                                        data-id="{{ $horario->id_grade }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma grade cadastrada para este profissional.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmação de Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este horário?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                <!-- Formulário dinâmico para exclusão -->
                <form id="delete-form" action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Atualiza o action do formulário de exclusão
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const form = document.getElementById('delete-form');
                form.action = "{{ route('deletarHorario', '') }}/" + id;
            });
        });

        // Foca no botão cancelar ao abrir o modal
        var confirmModal = document.getElementById('confirmModal');
        confirmModal.addEventListener('shown.bs.modal', function () {
            confirmModal.querySelector('button[data-bs-dismiss="modal"]').focus();
        });

        // Validação do horário no envio do formulário
        document.getElementById('form-grade-horario').addEventListener('submit', function(event) {
            const inicio = document.getElementById('inicio_expediente').value;
            const termino = document.getElementById('termino_expediente').value;
            const errorMessage = document.getElementById('error-message');

            if (termino <= inicio) {
                event.preventDefault();
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'O horário de término deve ser posterior ao horário de início.';
            } else {
                errorMessage.style.display = 'none';
                errorMessage.textContent = '';
            }
        });
    });
</script>

@endsection