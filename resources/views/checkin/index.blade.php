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
<style>
    /* Estilo para os botões de ação */
    .btn-delete:hover {
        color: #dc3545 !important;
        transform: scale(1.05);
        transition: all 0.2s ease;
    }

    [data-bs-toggle="tooltip"] {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* Customização do tooltip */
    .tooltip-inner {
        background-color: #2c3e50;
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
    }
</style>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Check-in';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize">Lista de Check-ins</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoCheckin">Novo Check-in</button>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hóspede</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reserva</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quarto</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entrada</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saída</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nº de Pessoas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor Total</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($checkins as $checkin)
                                        <tr>
                                            <td>{{ $checkin->id }}</td>
                                            <td>{{ $checkin->reserva->cliente_nome ?? 'Excluido' }}</td>
                                            <td>#{{ $checkin->reserva->id ?? 'Excluido' }}</td>
                                            <td>
                                                <a href="{{ route('quartos.index') }}">
                                                    {{ $checkin->quarto->numero ?? ''}} - {{ $checkin->quarto->tipo->nome ?? 'Excluido' }}
                                                </a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($checkin->data_entrada)->format('d/m/Y H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($checkin->data_saida)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if($checkin->status == 'hospedado')
                                                <span class="badge badge-sm bg-gradient-success">Hospedado</span>
                                                @else
                                                <span class="badge badge-sm bg-gradient-secondary">Finalizado</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $checkin->num_pessoas }}</td>
                                            <td class="text-center">
                                                @if ($checkin->checkout)
                                                {{-- Checkout já feito: mostra valor total com serviços --}}
                                                <span class="text-success font-weight-bold">
                                                    {{ number_format($checkin->checkout->valor_total ?? 0, 2, ',', '.') }} kz
                                                </span>
                                                @else
                                                {{-- Ainda não fez checkout: mostra apenas valor da reserva --}}
                                                <span class="text-muted">
                                                    {{ number_format($checkin->reserva->valor_total ?? 0, 2, ',', '.') }} kz
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- Botão Excluir -->
                                                    <form action="{{ route('checkins.destroy', $checkin->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon-only btn-outline-danger rounded-circle btn-delete"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Excluir registro permanentemente">
                                                            <i class="fas fa-trash-alt fa-xs"></i>
                                                        </button>
                                                    </form>

                                                    @if($checkin->status == 'hospedado')
                                                    <!-- Botão Check-out -->
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon-only btn-outline-primary rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalCheckout-{{ $checkin->id }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Registrar saída do hóspede">
                                                        <i class="fas fa-door-open fa-xs"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>


                                        <!-- Modal de Checkout -->
                                        <div class="modal fade" id="modalCheckout-{{ $checkin->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('checkouts.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="checkin_id" value="{{ $checkin->id }}">

                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">
                                                                <i class="fas fa-door-open me-2"></i>Check-out de {{ $checkin->reserva->cliente_nome ?? $checkin->hospede_nome ?? 'Hóspede do Quarto '.($checkin->quarto ? $checkin->quarto->numero : 'Indisponível') ?? 'Excluído' }}
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
                                                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->data_entrada)->format('d/m/Y H:i') }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->data_saida)->format('d/m/Y H:i') }}" readonly>
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
                                                                        @if ($checkin->quarto)
                                                                        <input type="text" class="form-control"
                                                                            value="Kz {{ number_format($checkin->quarto->preco, 2, ',', '.') }}"
                                                                            readonly>
                                                                        @else
                                                                        <input type="text" class="form-control text-danger"
                                                                            value="Quarto excluído"
                                                                            readonly>
                                                                        @endif

                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-calculator me-1 text-secondary"></i>Valor Total</label>
                                                                        <input type="text" class="form-control" value="Kz {{ number_format($checkin->reserva->valor_total ?? 0, 2, ',', '.') }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Adicione esta seção se precisar de informações adicionais -->
                                                            <div class="card shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-info-circle me-2 text-primary"></i>Informações Adicionais</strong>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="mb-3">
                                                                        <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                                                        @if ($checkin->quarto)
                                                                        <input type="text" class="form-control"
                                                                            value="Quarto {{ $checkin->quarto->numero }} - {{ optional($checkin->quarto->tipo)->nome }}"
                                                                            readonly>
                                                                        @else
                                                                        <input type="text" class="form-control text-danger"
                                                                            value="Quarto excluído"
                                                                            readonly>
                                                                        @endif

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

                                        @endforeach
                                        @if($checkins->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-secondary">Nenhum check-in encontrado.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Modal Novo Check-in -->
            <div class="modal fade" id="modalNovoCheckin" tabindex="-1" aria-hidden="true">
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
                                <button type="button" class="btn btn-success btn-checkin rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar Check-in
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
                    document.getElementById('numero_quarto_vis').value = option.dataset.quarto_num || '';
                    document.getElementById('preco').value = option.dataset.preco || '';
                    document.getElementById('pessoas').value = option.dataset.pessoas || '';
                    document.getElementById('entrada_vis').value = option.dataset.entrada || '';
                    document.getElementById('saida_vis').value = option.dataset.saida || '';

                    // Preenchendo campos ocultos
                    document.getElementById('quarto').value = option.dataset.quarto || '';
                    document.getElementById('entrada').value = option.dataset.entrada || '';
                    document.getElementById('saida').value = option.dataset.saida || '';
                    document.getElementById('num_pessoas').value = option.dataset.pessoas || '';
                    document.getElementById('numero_quarto').value = option.dataset.quarto_num || ''; // ✅ Esta linha é essencial!
                });
            </script>


        </div>
    </main>
    @include('layouts.customise')
    <!--   Core JS Files   -->
    @include('components.js')

</body>

</html>