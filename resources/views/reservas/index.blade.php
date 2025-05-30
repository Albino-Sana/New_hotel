<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        DAT Hotelaria
    </title>

    @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Reservas';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize">Lista de Reservas</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#criarReservaModal">Nova Reserva</button>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cliente</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">Quarto</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-sm-table-cell">Pessoas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entrada</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-lg-table-cell">Saída</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservas as $reserva)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="text-xs font-weight-bold">{{ $reserva->id }}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-0 text-sm">{{ $reserva->cliente_nome }}</h6>
                                                    <small class="text-xs text-secondary">{{ $reserva->cliente_email ?? '---' }}</small>
                                                </div>
                                            </td>

                                            <td class="d-none d-md-table-cell">
                                                <a href="{{ route('quartos.index') }}" class="text-info text-sm text-decoration-none">
                                                    {{ optional($reserva->quarto)->numero ?? 'Excluido' }}
                                                </a>
                                            </td>

                                            <td>
                                                <span class="text-xs font-weight-bold">{{ number_format($reserva->valor_total, 2, ',', '.') }} kz</span>
                                            </td>

                                            <td class="d-none d-sm-table-cell">
                                                <span class="badge bg-gradient-secondary">{{ $reserva->numero_pessoas }}</span>
                                            </td>

                                            <td>
                                                <span class="text-xs">{{ \Carbon\Carbon::parse($reserva->data_entrada)->format('d/m/Y') }}</span>
                                            </td>

                                            <td class="d-none d-lg-table-cell">
                                                <span class="text-xs">{{ \Carbon\Carbon::parse($reserva->data_saida)->format('d/m/Y') }}</span>
                                            </td>

                                            <td>
                                                <span class="badge bg-gradient-{{
                                                    $reserva->status === 'reservado' ? 'info' :
                                                    ($reserva->status === 'finalizado' ? 'success' : 'secondary')
                                                }}">{{ ucfirst($reserva->status) }}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-end gap-1">
                                                    <!-- Botão Ver -->
                                                    <button class="btn btn-sm btn-icon-only btn-outline-info rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verModal{{ $reserva->id }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Visualizar">
                                                        <i class="fas fa-eye fa-xs"></i>
                                                    </button>

                                                    @if($reserva->status !== 'finalizado')
                                                    <!-- Botão Editar -->
                                                    <button class="btn btn-sm btn-icon-only btn-outline-primary rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editarModal{{ $reserva->id }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Editar">
                                                        <i class="fas fa-edit fa-xs"></i>
                                                    </button>

                                                    <!-- Botão Cancelar -->
                                                    <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon-only btn-outline-danger rounded-circle btn-delete"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Cancelar">
                                                            <i class="fas fa-times fa-xs"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Botão Eliminar (apenas para admin) -->

                                                    <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon-only btn-outline-dark rounded-circle btn-delete-permanent"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Eliminar permanentemente">
                                                            <i class="fas fa-trash fa-xs"></i>
                                                        </button>
                                                    </form>

                                                    @endif

                                                    @if($reserva->status == 'reservado')
                                                    <!-- Botão Check-in -->
                                                    <form action="{{ route('reservas.checkin', $reserva->id) }}" method="POST" class="d-inline form-checkin" data-id="{{ $reserva->id }}">
                                                        @csrf
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon-only btn-outline-success rounded-circle"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Fazer Check-in">
                                                            <i class="fas fa-check-circle fa-xs"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal de Editar Reserva -->
                                        <div class="modal fade" id="editarModal{{ $reserva->id }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $reserva->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">
                                                                <i class="fas fa-calendar-edit me-2"></i>Editar Reserva
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <!-- Dados do Cliente -->
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-user-tie me-2 text-primary"></i>Dados do Cliente</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-user me-1 text-secondary"></i>Nome do Cliente</label>
                                                                        <input type="text" class="form-control" name="cliente_nome" value="{{ $reserva->cliente_nome }}" required>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-id-card me-1 text-secondary"></i>Documento do Cliente</label>
                                                                        <select class="form-control" name="cliente_documento">
                                                                            <option value="bi" {{ $reserva->cliente_documento == 'bi' ? 'selected' : '' }}>BI</option>
                                                                            <option value="passaporte" {{ $reserva->cliente_documento == 'passaporte' ? 'selected' : '' }}>Passaporte</option>
                                                                            <option value="carta_conducao" {{ $reserva->cliente_documento == 'carta_conducao' ? 'selected' : '' }}>Carta de Condução</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-envelope me-1 text-secondary"></i>E-mail do Cliente</label>
                                                                        <input type="email" class="form-control" name="cliente_email" value="{{ $reserva->cliente_email }}">
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-phone me-1 text-secondary"></i>Telefone do Cliente</label>
                                                                        <input type="text" class="form-control" name="cliente_telefone" value="{{ $reserva->cliente_telefone }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Detalhes da Reserva -->
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Detalhes da Reserva</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-bed me-1 text-secondary"></i>Quarto</label>
                                                                        <select class="form-control" name="quarto_id">
                                                                            @if ($reserva->quarto)
                                                                            <option value="{{ $reserva->quarto_id }}" selected>
                                                                                {{ $reserva->quarto->numero }} - {{ $reserva->quarto->status }}
                                                                            </option>
                                                                            @else
                                                                            <option value="{{ $reserva->quarto_id }}" selected disabled>
                                                                                Quarto excluído
                                                                            </option>
                                                                            @endif


                                                                            @foreach($quartos as $quarto)
                                                                            <option value="{{ $quarto->id }}">
                                                                                {{ $quarto->numero }} - {{ $quarto->status }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-users me-1 text-secondary"></i>Número de Pessoas</label>
                                                                        <input type="number" class="form-control" name="numero_pessoas" value="{{ $reserva->numero_pessoas }}" min="1" required>
                                                                    </div>

                                                                    <div class="col-md-3 mb-3">
                                                                        <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                                                        <input type="date" class="form-control" name="data_entrada" value="{{ $reserva->data_entrada }}" required>
                                                                    </div>

                                                                    <div class="col-md-3 mb-3">
                                                                        <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                                                        <input type="date" class="form-control" name="data_saida" value="{{ $reserva->data_saida }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Observações -->
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-sticky-note me-2 text-primary"></i>Observações</strong>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="mb-3">
                                                                        <label><i class="fas fa-comment-dots me-1 text-secondary"></i>Informações Adicionais</label>
                                                                        <textarea class="form-control" name="observacoes" rows="3">{{ $reserva->observacoes }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i>Fechar
                                                            </button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-save me-1"></i>Salvar Alterações
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Modal de Ver Reserva -->
                                        <div class="modal fade" id="verModal{{ $reserva->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title text-white">Detalhes da Reserva #{{ $reserva->id }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- Coluna 1 -->
                                                            <div class="col-md-6">
                                                                <div class="card mb-3 shadow-sm">
                                                                    <div class="card-header bg-light">
                                                                        <strong><i class="fas fa-user me-2"></i>Informações do Cliente</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>Nome:</strong> {{ $reserva->cliente_nome }}</p>
                                                                        <p><strong>Documento:</strong> {{ $reserva->cliente_documento }}</p>
                                                                        <p><strong>Telefone:</strong> {{ $reserva->cliente_telefone ?? 'Excluido' }}</p>
                                                                        <p><strong>E-mail:</strong> {{ $reserva->cliente_email ?? 'Excluido' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Coluna 2 -->
                                                            <div class="col-md-6">
                                                                <div class="card mb-3 shadow-sm">
                                                                    <div class="card-header bg-light">
                                                                        <strong><i class="fas fa-hotel me-2"></i>Informações da Hospedagem</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if ($reserva->quarto)
                                                                        <p><strong>Quarto:</strong> {{ $reserva->quarto->numero }} ({{ $reserva->quarto->tipo->nome }})</p>
                                                                        <p><strong>Status do Quarto:</strong>
                                                                            <span class="badge bg-gradient-{{ $reserva->quarto->status == 'Disponível' ? 'success' : ($reserva->quarto->status == 'Ocupado' ? 'danger' : 'warning') }}">
                                                                                {{ $reserva->quarto->status }}
                                                                            </span>
                                                                        </p>
                                                                        @else
                                                                        <p><strong>Quarto:</strong> <span class="text-danger">Quarto excluído</span></p>
                                                                        @endif

                                                                        <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($reserva->data_entrada)->format('d/m/Y H:i') }}</p>
                                                                        <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($reserva->data_saida)->format('d/m/Y H:i') }}</p>
                                                                        <p><strong>Noites:</strong> {{ \Carbon\Carbon::parse($reserva->data_entrada)->diffInDays($reserva->data_saida) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Nova Seção - Responsável pela Reserva -->
                                                            <div class="col-12 mt-3">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-header bg-light">
                                                                        <strong><i class="fas fa-user-tie me-2"></i>Responsável pela Reserva</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center">
                                                                            @if($reserva->user)
                                                                            <div class="avatar avatar-sm me-3">
                                                                                <span class="avatar-initial rounded-circle bg-gradient-secondary">
                                                                                    {{ substr($reserva->user->name, 0, 1) }}
                                                                                </span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0">{{ $reserva->user->name }}</h6>
                                                                                <small class="text-muted">
                                                                                    Email: {{ $reserva->user->email ?? '---' }}<br>
                                                                                    Registrado em: {{ $reserva->user->created_at->format('d/m/Y') }}
                                                                                </small>
                                                                            </div>
                                                                            @else
                                                                            <div class="text-muted">
                                                                                <i class="fas fa-user-slash me-2"></i> Usuário não identificado
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <!-- Seção de Valores -->
                                                            <div class="col-12 mt-3">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-header bg-light">
                                                                        <strong><i class="fas fa-money-bill-wave me-2"></i>Valores</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p><strong>Valor Total:</strong> {{ number_format($reserva->valor_total, 2, ',', '.') }} kz</p>
                                                                                <p><strong>Nº de Pessoas:</strong> {{ $reserva->numero_pessoas }}</p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Observações:</strong> {{ $reserva->observacoes ?? 'Nenhuma' }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        @if($reserva->status == 'reservado')
                                                        <form action="{{ route('reservas.checkin', $reserva->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-check-circle me-1"></i> Fazer Check-in
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Criar Reserva -->
            <div class="modal fade" id="criarReservaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('reservas.store') }}" method="POST">
                            @csrf

                            <div class="modal-header bg-gradient-primary text-white">
                                <h5 class="modal-title text-white">
                                    <i class="fas fa-calendar-plus me-2"></i>Nova Reserva
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>

                            <div class="modal-body">
                                <!-- Dados do Cliente -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-user-tie me-2 text-primary"></i>Dados do Cliente</strong>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-user me-1 text-secondary"></i>Nome do Cliente</label>
                                            <input type="text" name="cliente_nome" class="form-control" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-id-card me-1 text-secondary"></i>Tipo de Documento</label>
                                            <select name="cliente_documento" class="form-control" required>
                                                <option value="">Selecione...</option>
                                                <option value="BI">BI</option>
                                                <option value="Passaporte">Passaporte</option>
                                                <option value="Carta de Condução">Carta de Condução</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-envelope me-1 text-secondary"></i>Email</label>
                                            <input type="email" name="cliente_email" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-phone me-1 text-secondary"></i>Telefone</label>
                                            <input type="text" name="cliente_telefone" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalhes da Reserva -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Detalhes da Reserva</strong>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-bed me-1 text-secondary"></i>Quarto</label>
                                            <select name="quarto_id" class="form-control" required>
                                                <option value="">Selecione</option>
                                                @foreach($quartos as $quarto)
                                                <option value="{{ $quarto->id }}">Quarto {{ $quarto->numero }} - {{ $quarto->tipo->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                            <input type="date" name="data_entrada" class="form-control" required>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                            <input type="date" name="data_saida" class="form-control" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-users me-1 text-secondary"></i>Número de Pessoas</label>
                                            <input type="number" name="numero_pessoas" class="form-control" value="1" min="1" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observações -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-sticky-note me-2 text-primary"></i>Informações Adicionais</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label><i class="fas fa-comment-dots me-1 text-secondary"></i>Observações</label>
                                            <textarea name="observacoes" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Salvar Reserva
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    @include('layouts.customise')

    <!--   Core JS Files   -->
    @include('components.js')


</body>

</html>