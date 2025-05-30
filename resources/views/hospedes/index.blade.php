<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        DAT Hotelaria --- Hospedes
    </title>

    @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Hospede';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize">Hóspedes</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoHospede">Novo Hóspede</button>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-hover" id="Table" style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">#ID</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Hóspede</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 d-none d-lg-table-cell">Contato</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Período</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Quarto</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 d-none d-md-table-cell">Valor</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hospedes as $hospede)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="text-xs font-weight-bold">{{ $hospede->id }}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initial rounded-circle bg-gradient-{{ 
                            $hospede->status === 'hospedado' ? 'info' : 
                            ($hospede->status === 'finalizado' ? 'success' : 'secondary') 
                        }} text-white">
                                                            {{ substr($hospede->nome, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $hospede->nome }}</h6>
                                                        <small class="text-xs text-secondary d-lg-none">{{ $hospede->email }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="d-none d-lg-table-cell">
                                                <p class="text-xs mb-0">{{ $hospede->email }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $hospede->telefone }}</p>
                                            </td>

                                            <td style="min-width: 120px;">
                                                <div class="d-flex flex-column">
                                                    <span class="text-xs font-weight-bold">{{ $hospede->data_entrada->format('d/m/Y') }}</span>
                                                    <span class="text-xs text-secondary">{{ $hospede->data_saida->format('d/m/Y') }}</span>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-gradient-primary">{{ $hospede->quarto->numero ?? '-' }}</span>
                                            </td>

                                            <td class="d-none d-md-table-cell">
                                                <span class="text-xs font-weight-bold">{{ number_format($hospede->checkoutHospede ? $hospede->checkoutHospede->valor_total : $hospede->valor_a_pagar, 2, ',', '.') }} kz</span>
                                            </td>

                                            <td>
                                                <span class="badge bg-gradient-{{
                    $hospede->status === 'hospedado' ? 'info' : 
                    ($hospede->status === 'finalizado' ? 'success' : 'secondary')
                }}">
                                                    {{ ucfirst($hospede->status) }}
                                                </span>
                                            </td>

                                            <td class="text-center" style="min-width: 120px;">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <!-- Editar -->
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editarHospedeModal{{ $hospede->id }}"
                                                        class="btn btn-icon-only btn-sm btn-outline-primary rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                        <i class="fas fa-pen fa-xs"></i>
                                                    </a>

                                                    @if($hospede->status !== 'finalizado')
                                                    <!-- Check-out -->
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#checkoutHospedeModal{{ $hospede->id }}"
                                                        class="btn btn-icon-only btn-sm btn-outline-success rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Check-out">
                                                        <i class="fas fa-sign-out-alt fa-xs"></i>
                                                    </a>
                                                    @endif

                                                    <!-- Excluir -->
                                                    <form action="{{ route('hospedes.destroy', $hospede) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-icon-only btn-sm btn-outline-danger rounded-circle btn-delete"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir">
                                                            <i class="fas fa-trash fa-xs"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="editarHospedeModal{{ $hospede->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('hospedes.update', $hospede->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">
                                                                <i class="fas fa-user-edit me-2"></i>Editar Hóspede
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-id-card me-2 text-primary"></i>Informações Pessoais</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-user me-1 text-secondary"></i>Nome</label>
                                                                        <input type="text" class="form-control" name="nome" value="{{ $hospede->nome }}" required>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-envelope me-1 text-secondary"></i>Email</label>
                                                                        <input type="email" class="form-control" name="email" value="{{ $hospede->email }}">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-phone me-1 text-secondary"></i>Telefone</label>
                                                                        <input type="text" class="form-control" name="telefone" value="{{ $hospede->telefone }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Período da Hospedagem</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                                                        <input type="date" class="form-control datepicker" name="data_entrada" value="{{ $hospede->data_entrada->format('Y-m-d') }}" required>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                                                        <input type="date" class="form-control datepicker" name="data_saida" value="{{ $hospede->data_saida->format('Y-m-d') }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-bed me-2 text-primary"></i>Quarto e Valores</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                                                        <select class="form-control" name="quarto_id">
                                                                            <option value="" selected disabled>Selecionar Quarto</option>
                                                                            @foreach($quartos as $quarto)
                                                                            <option value="{{ $quarto->id }}" data-valor="{{ $quarto->preco_noite }}" {{ $quarto->id == $hospede->quarto_id ? 'selected' : '' }}> Quarto {{ $quarto->numero }} - {{ $quarto->tipo->nome }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-money-bill-wave me-1 text-secondary"></i>Valor a Pagar</label>
                                                                        <input type="text" class="form-control" name="valor_a_pagar" value="{{ $hospede->valor_a_pagar }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-users me-1 text-secondary"></i>Nº Pessoas</label>
                                                                        <input type="number" class="form-control" name="numero_pessoas" value="{{ $hospede->numero_pessoas }}" required min="1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i> Cancelar
                                                            </button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-save me-1"></i> Salvar Alterações
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Check-out Hóspede -->
                                        <div class="modal fade" id="checkoutHospedeModal{{ $hospede->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('hospedes.checkout', $hospede->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">
                                                                <i class="fas fa-sign-out-alt me-2"></i>Check-out de {{ $hospede->nome }}
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
                                                                        <input type="text" class="form-control" value="{{ $hospede->nome }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-door-open me-1 text-secondary"></i>Quarto</label>
                                                                        <input type="text" class="form-control" value="{{ $hospede->quarto->numero ?? '-' }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                                                        <input type="text" class="form-control" value="{{ $hospede->data_entrada->format('d/m/Y') }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                                                        <input type="text" class="form-control" value="{{ $hospede->data_saida->format('d/m/Y') }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-money-bill-wave me-1 text-secondary"></i>Valor da Hospedagem</label>
                                                                        <input type="text" class="form-control" value="{{ number_format($hospede->valor_a_pagar, 2, ',', '.') }} kz" readonly>
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
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i> Cancelar
                                                            </button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-sign-out-alt me-1"></i> Confirmar Check-out
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-3 pt-2">
                                {{ $hospedes->links() }}
                            </div>
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

        </div>



        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectQuarto = document.getElementById('novo_quarto');
            const inputValor = document.getElementById('novo_valor');

            selectQuarto.addEventListener('change', function() {
                const valorNoite = this.options[this.selectedIndex].getAttribute('data-valor');
                inputValor.value = valorNoite ? `AOA ${parseFloat(valorNoite).toFixed(2)}` : '';
            });
        });
    </script>
    @include('layouts.customise')
    <!--   Core JS Files   -->
    @include('components.js')

</body>

</html>