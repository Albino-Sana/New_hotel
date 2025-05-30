<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel POS - Dashboard</title>
    @include('components.pos')


</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-hotel me-2"></i>Hotel POS
            </a>
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


        <div class="row">
            @foreach($quartos as $quarto)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
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
                                <small class="text-muted">por noite</small>
                            </div>
                            <i class="fas fa-bed fa-2x text-secondary opacity-25"></i>
                        </div>
                    </div>

                    <!-- Rodapé com ações -->
                    <div class="card-footer p-3 bg-light">
                        @if(strtolower($quarto->status) === 'ocupado')
                        <!-- Adicionar consumo -->
                        <button class="btn btn-sm btn-outline-primary w-100 mb-2 rounded-pill"
                            data-bs-toggle="modal"
                            data-bs-target="#consumoModal{{ $quarto->id }}">
                            <i class="fas fa-plus-circle me-1"></i> Adicionar serviço
                        </button>

                        <!-- Check-out: reserva (check-in) ou hóspede direto -->
                        @if(isset($quarto->checkin))
                        <!-- Check-out de uma reserva com check-in -->
                        <button class="btn btn-sm btn-danger w-100 rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#modalCheckoutReserva-{{ $quarto->checkin->id }}">
                            <i class="fas fa-door-open me-1"></i> Fazer Check-out
                        </button>
                        @elseif(isset($quarto->hospede))
                        <!-- Check-out de um hóspede direto -->
                        <button class="btn btn-sm btn-danger w-100 rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#modalCheckoutHospede-{{ $quarto->hospede->id }}">
                            <i class="fas fa-door-open me-1"></i> Fazer Check-out
                        </button>
                        @else
                        <span class="text-muted">Sem dados de ocupação</span>
                        @endif

                        @elseif(strtolower($quarto->status) === 'disponível')
                        <div class="text-center">
                            <span class="badge bg-primary bg-gradient text-white px-3 py-2 rounded-pill w-100"
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
                        <div class="text-center">
                            <span class="badge bg-warning bg-gradient text-dark px-3 py-2 rounded-pill w-100">
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
                    <button class="btn btn-warning rounded-pill px-3 shadow-sm">
                        <i class="fas fa-receipt me-2"></i> Faturas
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

    <!-- Modals -->

    <!-- Modal Novo Hóspede -->
    <div class="modal fade" id="modalNovoHospede" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-dark rounded">
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
                                    <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                    <input type="date" class="form-control" name="data_entrada" id="nova_data_entrada" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                    <input type="date" class="form-control" name="data_saida" id="nova_data_saida" required>
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
                                    <select id="novo_quarto" name="quarto_id" class="form-control">
                                        <option value="">Selecione um quarto</option>
                                        @foreach($quartos as $quarto)
                                        <option value="{{ $quarto->id }}" data-valor="{{ $quarto->preco_noite }}">Quarto {{ $quarto->numero }} - {{ $quarto->tipo->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><i class="fas fa-money-bill-wave me-1 text-secondary"></i>Valor a Pagar</label>
                                    <input type="text" class="form-control" id="novo_valor" name="valor_a_pagar" readonly>
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
            <div class="modal-content text-dark rounded">

                <form action="{{ route('checkins.store') }}" method="POST">
                    @csrf

                    <!-- Cabeçalho -->
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-door-open me-2"></i> Novo Check-in
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <!-- Corpo -->
                    <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                        <!-- Seleção de Reserva -->
                        <div class="mb-4">
                            <label for="reserva_id" class="form-label">
                                <i class="fas fa-file-alt me-1"></i> Selecionar Reserva
                            </label>
                            <select name="reserva_id" id="reserva_id" class="form-control text-dark" required>
                                <option value="">Selecione a Reserva...</option>
                                @foreach($reservas as $reserva)
                                <option value="{{ $reserva->id }}"
                                    data-cliente="{{ $reserva->cliente_nome }}"
                                    data-quarto="{{ $reserva->quarto_id }}"
                                    data-quarto_num="{{ $reserva->quarto->numero }}"
                                    data-preco="{{ $reserva->valor_total }}"
                                    data-pessoas="{{ $reserva->numero_pessoas }}"
                                    data-entrada="{{ $reserva->data_entrada }}"
                                    data-saida="{{ $reserva->data_saida }}">
                                    Reserva #{{ $reserva->reserva_id }} - {{ $reserva->cliente_nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dados da Reserva -->
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i> Dados da Reserva</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Cliente</label>
                                    <input type="text" id="cliente" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Quarto ID</label>
                                    <input type="text" id="quarto_vis" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Número do Quarto</label>
                                    <input type="text" id="numero_quarto_vis" class="form-control" required readonly>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Preço</label>
                                    <input type="text" id="preco" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Nº de Pessoas</label>
                                    <input type="text" id="pessoas" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Entrada</label>
                                    <input type="text" id="entrada_vis" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Saída</label>
                                    <input type="text" id="saida_vis" class="form-control" readonly>
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
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Confirmar Check-in
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

    @foreach($quartos as $quarto)
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
                            <i class="fas fa-door-open me-2"></i>Check-out de {{ $quarto->checkin->reserva->cliente_nome }}
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


    @include('components.posjs')


</body>

</html>