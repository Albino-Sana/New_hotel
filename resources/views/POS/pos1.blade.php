<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel POS - Dashboard</title>
    @include('components.pos')
    @include('components.css')

</head>
<style>
    .modal-custom-wide {
        max-width: 95%;
        width: 95%;
    }

    .modal-custom-wide .modal-content {
        border-radius: 8px;
    }

    .table-responsive {
        overflow-x: hidden;
        /* Remove scroll horizontal */
    }

    .table th,
    .table td {
        white-space: nowrap;
        /* Impede quebras de linha em células */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Adiciona reticências se o texto for muito longo */
    }

    .table td .btn {
        margin-right: 0.5rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Estilizar tooltips */
    .tooltip-inner {
        background-color: #343a40;
        /* Cor escura para combinar com o tema */
        color: #fff;
        font-size: 0.875rem;
        padding: 0.5rem;
    }

    .tooltip .tooltip-arrow::before {
        border-color: #343a40 transparent transparent transparent;
    }

    @media (max-width: 768px) {
        .modal-custom-wide {
            max-width: 100%;
            margin: 0.5rem;
        }
    }

    .alert.fixed-top {
        position: fixed;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Marca à esquerda -->
            <a class="navbar-brand" href="#">
                <i class="fas fa-hotel me-2"></i>Hotel POS
            </a>
            <!-- Contador no meio -->
            <div class="navbar-text text-white mx-auto">
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                    <i class="fas fa-calendar-check me-2"></i>Reservas Pendentes: <span id="contadorReservas">0</span>
                </span>
            </div>
            <!-- Botão toggler para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    @if($tipo === 'Recepcionista' || $tipo === 'Gerente de Caixa' || $tipo === 'Administrador')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos2')}}"><i class="fas fa-utensils me-1"></i> Consumos</a>
                    </li>
                    @endif
                </ul>

                <!-- Relógio no meio -->
                <div class="navbar-text text-white mx-auto">
                    <span class="badge bg-dark text-white rounded-pill px-3 py-2">
                        <i class="fas fa-clock me-2"></i><span id="relogio">10/06/2025 14:15:00</span>
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="navbar-text text-white me-3">
                        <i class="fas fa-user-circle me-1"></i> {{ $nomeUsuario }} - {{ $cargo }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <div class="container-fluid mt-4 mb-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2 class="text-white"><i class="fas fa-door-open text-white me-2"></i> Status dos Quartos</h2>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list"></i> Lista
                    </button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-th-large"></i> Grid
                    </button>
                </div>
                <button class="btn btn-primary btn-sm ms-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </div>

        <div class="row g-3">
            @foreach ($quartos as $quarto)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card card-hover h-100 shadow-sm rounded-lg overflow-hidden"
                    @if($quarto->status == 'Disponível')
                    data-bs-toggle="modal" data-bs-target="#checkinModal{{ $quarto->id }}"
                    style="cursor: pointer;"
                    @endif>

                    <!-- Cabeçalho com status -->
                    <div class="card-header p-3 bg-gradient-{{ 
                    $quarto->status == 'Disponível' ? 'success' : 
                    ($quarto->status == 'Reservado' ? 'warning' : 'danger') 
                }} text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-dark fw-bold">Quarto #{{ $quarto->numero }}</h6>
                            <span class="badge bg-white text-dark rounded-pill">{{ $quarto->andar }}º Andar</span>
                        </div>
                    </div>

                    <!-- Corpo do card -->
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm text-muted">{{ $quarto->tipo->nome }}</span>
                            <span class="badge bg-{{
                            $quarto->status == 'Disponível' ? 'success' : 
                            ($quarto->status == 'Reservado' ? 'warning' : 'danger')
                        }} text-white rounded-pill">
                                {{ $quarto->status }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <div>
                                <h5 class="mb-0 fw-bold text-primary">{{ number_format($quarto->preco_noite, 2, ',', '.') }} Kz</h5>
                                <span class="text-sm">
                                    @if($quarto->tipo_cobranca === 'Por Hora')
                                    Por Hora
                                    @elseif($quarto->tipo_cobranca === 'Por Noite')
                                    Por Noite
                                    @else
                                    /período
                                    @endif
                                </span>
                            </div>
                            <i class="fas fa-bed fa-2x text-secondary opacity-25"></i>
                        </div>
                    </div>

                    <!-- Rodapé com ações -->
                    <div class="card-footer p-3 bg-light">
                        @if(strtolower($quarto->status) === 'ocupado')
                        <div class="d-grid gap-2">
                            <!-- Adicionar consumo -->
                            <button class="btn btn-sm btn-outline-primary rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#consumoModal{{ $quarto->id }}">
                                <i class="fas fa-plus-circle me-1"></i> Adicionar serviço
                            </button>

                            <!-- Check-out: reserva (check-in) ou hóspede direto -->
                            @if(isset($quarto->checkin))
                            <!-- Check-out de uma reserva com check-in -->
                            <button class="btn btn-sm btn-danger rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCheckoutReserva-{{ $quarto->checkin->id }}">
                                <i class="fas fa-door-open me-1"></i> Fazer Check-out
                            </button>
                            @elseif(isset($quarto->hospede))
                            <!-- Check-out de um hóspede direto -->
                            <button class="btn btn-sm btn-danger rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCheckoutHospede-{{ $quarto->hospede->id }}">
                                <i class="fas fa-door-open me-1"></i> Fazer Check-out
                            </button>
                            @else
                            <span class="text-muted d-block text-center">Sem dados de ocupação</span>
                            @endif
                        </div>
                        @elseif(strtolower($quarto->status) === 'disponível')
                        <div class="d-grid">
                            <span class="badge bg-primary bg-gradient text-white px-3 py-2 rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#modalNovoHospede"
                                data-quarto-id="{{ $quarto->id }}"
                                data-numero="{{ $quarto->numero }}"
                                data-andar="{{ $quarto->andar }}"
                                style="cursor: pointer;">
                                <i class="fas fa-user-plus me-2"></i> Novo Hóspede
                            </span>
                        </div>
                        @elseif(strtolower($quarto->status) === 'reservado')
                        <div class="d-grid">
                            <span class="badge bg-warning bg-gradient text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-calendar-check me-1"></i> Reservado
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    </div>

    <!-- Footer Menu -->
    <div class="footer-menu fixed-bottom bg-white shadow-lg py-3 border-top">
        <div class="container">
            <div class="row justify-content-center g-2">
                <div class="col-auto">
                    <button class="btn btn-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
                        <i class="fas fa-bolt me-2"></i> Check-in Rápido
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-warning rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#criarReservaModalpos">
                        <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i> Reservas
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#historicoModal">
                        <i class="fas fa-history me-2"></i> Histórico
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-info rounded-pill px-3 shadow-sm">
                        <i class="fas fa-sliders-h me-2"></i> Configurações
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Histórico -->
    <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-custom-wide">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title text-white" id="historicoModalLabel">
                        <i class="fas fa-history me-2"></i>Histórico de Reservas e Check-ins
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Filtros -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-filter me-2 text-primary"></i>Filtros</strong>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-3 mb-3">
                                <label><i class="fas fa-calendar me-1 text-secondary"></i>Período</label>
                                <input type="date" class="form-control" id="filtroDataInicio">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label><i class="fas fa-calendar me-1 text-secondary"></i>Fim</label>
                                <input type="date" class="form-control" id="filtroDataFim">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label><i class="fas fa-bed me-1 text-secondary"></i>Quarto</label>
                                <select class="form-control" id="filtroQuarto">
                                    <option value="">Todos</option>
                                    @foreach ($quartos as $quarto)
                                    <option value="{{ $quarto->id }}">Quarto {{ $quarto->numero }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label><i class="fas fa-info-circle me-1 text-secondary"></i>Status</label>
                                <select class="form-control" id="filtroStatus">
                                    <option value="">Todos</option>
                                    <option value="Reservado">Reservado</option>
                                    <option value="Check-in">Check-in Realizado</option>
                                    <option value="Check-out">Check-out Realizado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de Histórico -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="width: 100%; table-layout: fixed;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10%;">Tipo</th>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Cliente/Hóspede</th>
                                    <th style="width: 10%;">Quarto</th>
                                    <th style="width: 15%;">Data Entrada</th>
                                    <th style="width: 15%;">Data Saída</th>
                                    <th style="width: 10%;">Valor Total (Kz)</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="historicoTabela">
                                <!-- Dados serão preenchidos via JavaScript/AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Adicionar Hóspede -->
    <div class="modal fade" id="modalNovoHospede" tabindex="-1" aria-labelledby="modalAdicionarHospedeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('hospedes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-user-plus me-2"></i>Novo Hóspede
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Informações Pessoais -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-id-card me-2 text-primary"></i>Informações Pessoais</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-user me-1 text-secondary"></i>Nome</label>
                                    <input type="text" class="form-control" name="nome" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-envelope me-1 text-secondary"></i>Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-phone me-1 text-secondary"></i>Telefone</label>
                                    <input type="text" class="form-control" name="telefone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-users me-1 text-secondary"></i>Nº Pessoas</label>
                                    <input type="number" class="form-control" name="numero_pessoas" required>
                                </div>
                            </div>
                        </div>
                        <!-- Detalhes da Hospedagem -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Período da Hospedagem</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data e Hora de Entrada</label>
                                    <input type="datetime-local" class="form-control" name="data_entrada" id="nova_data_entrada" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data e Hora de Saída</label>
                                    <input type="datetime-local" class="form-control" name="data_saida" id="nova_data_saida" required>
                                </div>
                            </div>
                        </div>
                        <!-- Quarto e Valores -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-bed me-2 text-primary"></i>Quarto e Valores</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                    <select id="novo_quarto" name="quarto_id" class="form-control" required>
                                        <option value="">Selecione um quarto</option>
                                        @foreach($quartos as $quarto)
                                        <option value="{{ $quarto->id }}"
                                            data-valor="{{ $quarto->preco_noite }}"
                                            data-cobranca="{{ $quarto->tipo_cobranca ?? 'Diária' }}">
                                            Quarto {{ $quarto->numero }} - {{ $quarto->tipo ? $quarto->tipo->nome : 'Sem Tipo' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-coins me-1 text-secondary"></i>Preço por Período</label>
                                    <input type="number" name="preco_noite" id="hospede_preco_noite" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-credit-card me-1 text-secondary"></i>Tipo de Cobrança</label>
                                    <input type="text" name="tipo_cobranca" id="hospede_tipo_cobranca" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Salvar
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Novo Check-in -->
    <div class="modal fade" id="quickCheckinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg border-0 overflow-hidden">
                <form action="{{ route('checkins.store') }}" method="POST">
                    @csrf

                    <!-- Cabeçalho -->
                    <div class="modal-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-door-open fs-4"></i>
                            </div>
                            <div class="bg-primary">
                                <h5 class="modal-title mb-0 text-white fw-semibold">NOVO CHECK-IN</h5>
                                <small class="text-white-50">Preencha os dados para registrar a entrada</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <!-- Corpo -->
                    <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                        <!-- Seleção de Reserva -->
                        <div class="mb-4">
                            <label for="reserva_id" class="form-label fw-medium text-primary">
                                <i class="fas fa-file-alt me-2"></i>Selecionar Reserva
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary bg-opacity-10 text-primary border-end-0">
                                    <i class="fas fa-search"></i>
                                </span>
                                <select name="reserva_id" id="reserva_id" class="form-select border-start-0 ps-2 shadow-none" required>
                                    <option value="">Selecione a Reserva...</option>
                                    @foreach($reservas as $reserva)
                                    <option value="{{ $reserva->id }}"
                                        data-cliente="{{ $reserva->cliente_nome }}"
                                        data-quarto="{{ $reserva->quarto_id }}"
                                        data-quarto_num="{{ $reserva->quarto ? $reserva->quarto->numero : 'Quarto excluído' }}"
                                        data-preco="{{ $reserva->valor_total }}"
                                        data-pessoas="{{ $reserva->numero_pessoas }}"
                                        data-entrada="{{ $reserva->data_entrada }}"
                                        data-saida="{{ $reserva->data_saida }}">
                                        Reserva #{{ $reserva->reserva_id }} - {{ $reserva->cliente_nome }} ({{ $reserva->data_entrada }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Dados da Reserva -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light bg-opacity-50 border-0 py-3">
                                <h6 class="mb-0 fw-medium text-primary">
                                    <i class="fas fa-info-circle me-2"></i>DETALHES DA RESERVA
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="cliente" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Nome">
                                            <label for="cliente" class="text-muted">Cliente</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="quarto_vis" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Quarto ID">
                                            <label for="quarto_vis" class="text-muted">Quarto ID</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="numero_quarto_vis" class="form-control border-0 border-bottom" required placeholder="Número">
                                            <label for="numero_quarto_vis" class="text-muted">Número do Quarto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="preco" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Preço">
                                            <label for="preco" class="text-muted">Preço</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="pessoas" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Pessoas">
                                            <label for="pessoas" class="text-muted">Nº de Pessoas</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="entrada_vis" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Entrada">
                                            <label for="entrada_vis" class="text-muted">Data de Entrada</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="saida_vis" class="form-control border-0 border-bottom bg-light bg-opacity-10" readonly placeholder="Saída">
                                            <label for="saida_vis" class="text-muted">Data de Saída</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos ocultos -->
                        <input type="hidden" name="quarto_id" id="quarto">
                        <input type="hidden" name="numero_quarto" id="numero_quarto">
                        <input type="hidden" name="data_entrada" id="entrada">
                        <input type="hidden" name="data_saida" id="saida">
                        <input type="hidden" name="num_pessoas" id="num_pessoas">
                    </div>

                    <!-- Rodapé -->
                    <div class="modal-footer bg-light bg-opacity-25 border-0 pt-3 pb-4 px-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-checkin rounded-pill px-4 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Check-in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Adicionar Serviço -->
    <div class="modal fade" id="consumoModal{{ $quarto->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('corrente-servicos.store') }}" method="POST">
                    @csrf

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-concierge-bell me-2"></i> Adicionar Serviço - Quarto {{ $quarto->numero }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Identificador da estadia --}}
                        <input type="hidden" name="hospede_id" value="{{ $quarto->checkin->hospede_id ?? '' }}">
                        <input type="hidden" name="checkin_id" value="{{ $quarto->checkin->id ?? '' }}">
                        <input type="hidden" name="quarto_id" value="{{ $quarto->id }}">

                        <div class="mb-3">
                            <label class="form-label">Serviço</label>
                            <select name="servico_adicional_id" class="form-select" required>
                                <option value="">Selecione um serviço...</option>
                                @foreach($servicosAdicionais as $s)
                                <option value="{{ $s->id }}" data-preco="{{ $s->preco }}">
                                    {{ $s->nome }} – {{ number_format($s->preco,2,',','.') }} Kz
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantidade</label>
                                <input type="number" min="1" name="quantidade" class="form-control" value="1" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Valor unitário (Kz)</label>
                                <input type="text" name="valor_unitario" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total (Kz)</label>
                                <input type="text" name="valor_total" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observação</label>
                            <textarea name="observacao" class="form-control" rows="2" placeholder="Detalhes adicionais..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lançar serviço
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Script para preencher campos -->
    <script>
        document.getElementById('reserva_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];

            document.getElementById('cliente').value = option.dataset.cliente || '';
            document.getElementById('quarto_vis').value = option.dataset.quarto || '';
            document.getElementById('numero_quarto').value = option.dataset.quarto_num || '';
            document.getElementById('preco').value = option.dataset.preco || '';
            document.getElementById('pessoas').value = option.dataset.pessoas || '';
            document.getElementById('entrada_vis').value = option.dataset.entrada || '';
            document.getElementById('saida_vis').value = option.dataset.saida || '';

            // Preenchendo campos ocultos
            document.getElementById('quarto').value = option.dataset.quarto || '';
            document.getElementById('entrada').value = option.dataset.entrada || '';
            document.getElementById('saida').value = option.dataset.saida || '';
            document.getElementById('num_pessoas').value = option.dataset.pessoas || '';
            document.getElementById('numero_quarto_vis').value = option.dataset.quarto_num || '';

        });
    </script>


    </div>
    <!-- Script para preencher campos -->
    @foreach ($quartos as $quarto )
    @if($quarto->status === 'Ocupado' && $quarto->checkin)
    <!-- Modal de Checkout -->
    <div class="modal fade" id="modalCheckoutReserva-{{ $quarto->checkin->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('checkouts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="checkin_id" value="{{ $quarto->checkin->id }}">

                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-door-open me-2"></i>Check-out de {{ $quarto->checkin->reserva->cliente_nome ?? 'Reserva' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Período da Hospedagem</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($quarto->checkin->data_entrada)->format('d/m/Y H:i') }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($quarto->checkin->data_saida)->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-money-bill-wave me-2 text-primary"></i>Valores</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-bed me-1 text-secondary"></i>Valor Diária</label>
                                    <input type="text" class="form-control" value="Kz {{ number_format($quarto->checkin->quarto->preco, 2, ',', '.') }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-calculator me-1 text-secondary"></i>Valor Total</label>
                                    <input type="text" class="form-control" value="Kz {{ number_format($quarto->checkin->reserva->valor_total ?? 0, 2, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-info-circle me-2 text-primary"></i>Informações Adicionais</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                    <input type="text" class="form-control" value="Quarto {{ $quarto->checkin->quarto->numero }} - {{ $quarto->checkin->quarto->tipo->nome }}" readonly>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="servicos">Serviços adicionais:</label>
                                    <select class="form-control select2" name="servicos[]" multiple="multiple" style="width: 100%">
                                        @foreach($servicosAdicionais as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i>Confirmar Check-out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    @foreach($quartos as $quarto)
    @if($quarto->hospede)
    <div class="modal fade" id="modalCheckoutHospede-{{ $quarto->hospede->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('hospedes.checkout', $quarto->hospede->id) }}" method="POST">
                    @csrf
                    <div class="modal-header  bg-gradient-primary text-white">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-sign-out-alt me-2"></i>Check-out de {{ $quarto->hospede->nome }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-info-circle me-2 text-primary"></i>Detalhes da Hospedagem</strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-user me-1 text-secondary"></i>Nome</label>
                                    <input type="text" class="form-control" value="{{ $quarto->hospede->nome }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                    <input type="text" class="form-control" value="{{ $quarto->hospede->quarto->numero ?? '-' }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                    <input type="text" class="form-control" value="{{ $quarto->hospede->data_entrada->format('d/m/Y') }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                    <input type="text" class="form-control" value="{{ $quarto->hospede->data_saida->format('d/m/Y') }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-money-bill-wave me-1 text-secondary"></i>Valor da Hospedagem</label>
                                    <input type="text" class="form-control" value="{{ number_format($quarto->hospede->valor_a_pagar, 2, ',', '.') }} kz" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-concierge-bell me-2 text-primary"></i>Serviços Extras (Opcional)</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label><i class="fas fa-list me-1 text-secondary"></i>Selecione os serviços utilizados</label>
                                    <select class="form-control" name="servicos[]" multiple>
                                        @foreach($servicosAdicionais as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->nome }} - {{ number_format($servico->preco, 2, ',', '.') }} kz</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sign-out-alt me-1"></i> Confirmar Check-out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Modal Criar Reserva -->
    <div class="modal fade" id="criarReservaModalpos" tabindex="-1" aria-hidden="true">
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
                                    <input type="datetime-local" name="data_entrada" class="form-control" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                    <input type="datetime-local" name="data_saida" class="form-control" required>
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

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="gerar_fatura" id="gerar_fatura" class="form-check-input">
                                    <label for="gerar_fatura" class="form-check-label">
                                        <i class="fas fa-file-pdf me-1 text-secondary"></i>Gerar Fatura PDF automaticamente
                                    </label>
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


    @if (session('fatura_id'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.open("{{ route('reservas.fatura', session('fatura_id')) }}", '_blank');
            }, 1000); // Espera 1s antes de abrir
        });
    </script>
    @endif

    @if (session('recibo_estadia_id'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.open("{{ route('recibo.estadia', session('recibo_estadia_id')) }}", '_blank');
            }, 1000);
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectQuarto = document.getElementById('novo_quarto');
            const precoInput = document.getElementById('hospede_preco_noite');
            const cobrancaInput = document.getElementById('hospede_tipo_cobranca');

            // Log para verificar se os elementos foram encontrados
            console.log('Script carregado', {
                selectQuarto: !!selectQuarto,
                precoInput: !!precoInput,
                cobrancaInput: !!cobrancaInput
            });

            if (selectQuarto && precoInput && cobrancaInput) {
                selectQuarto.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const valorNoite = selected.getAttribute('data-valor');
                    const tipoCobranca = selected.getAttribute('data-cobranca');

                    // Log para verificar os valores selecionados
                    console.log('Quarto selecionado', {
                        valorNoite,
                        tipoCobranca
                    });

                    // Preencher preço por período
                    if (valorNoite && !isNaN(parseFloat(valorNoite))) {
                        precoInput.value = parseFloat(valorNoite).toFixed(2);
                    } else {
                        precoInput.value = '';
                    }

                    // Preencher tipo de cobrança
                    if (tipoCobranca) {
                        cobrancaInput.value = tipoCobranca;
                    } else {
                        cobrancaInput.value = '';
                    }
                });
            } else {
                console.error('Um ou mais elementos não foram encontrados', {
                    selectQuarto: !!selectQuarto,
                    precoInput: !!precoInput,
                    cobrancaInput: !!cobrancaInput
                });
            }
        });
    </script>
    @include('components.posjs')

</body>

</html>