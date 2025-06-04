<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        DAT Hotelaria -- Pagamentos
    </title>

    @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Pagamentos';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize">Lista de Pagamentos</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoPagamento">Novo Pagamento</button>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome do Cliente</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Valor (Kz)</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Método</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data</th>
                                            <th class="text-secondary opacity-7">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pagamentos as $pagamento)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm ms-3">{{ $pagamento->id }}</h6>
                                            </td>

                                            <td class="text-center">
                                                @if ($pagamento->checkin && $pagamento->checkin->reserva)
                                                <span class="text-xs text-secondary font-weight-bold">
                                                    {{ $pagamento->checkin->reserva->cliente_nome }}
                                                </span>
                                                @elseif ($pagamento->hospede)
                                                <span class="text-xs text-secondary font-weight-bold">
                                                    {{ $pagamento->hospede->nome }}
                                                </span>
                                                @else
                                                <span class="text-xs text-danger font-weight-bold">
                                                    Origem indefinida
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-xs text-secondary font-weight-bold">{{ ucfirst($pagamento->metodo_pagamento) }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @php
                                                $badgeColor = match($pagamento->status_pagamento) {
                                                'pago' => 'success',
                                                'pendente' => 'warning',
                                                'falhou' => 'danger',
                                                default => 'secondary'
                                                };
                                                @endphp
                                                <span class="badge bg-{{ $badgeColor }}">
                                                    {{ ucfirst($pagamento->status_pagamento) }}
                                                </span>
                                            </td>



                                            <td class="text-center">
                                                <span class="text-xs text-secondary font-weight-bold">{{ $pagamento->created_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-link text-secondary text-xs mb-0" data-bs-toggle="modal" data-bs-target="#modalEditarPagamento{{ $pagamento->id }}">Editar</button>
                                                <form action="{{ route('pagamentos.destroy', $pagamento) }}" method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-link text-danger text-xs mb-0 border-0 bg-transparent btn-delete">
                                                        Excluir
                                                    </button>
                                                </form>

                                                @if ($pagamento->status_pagamento === 'pago')
                                                <a href="{{ route('pagamentos.fatura', $pagamento->id) }}" class="btn btn-link text-primary text-xs mb-0" target="_blank">
                                                    Fatura
                                                </a>
                                                @endif

                                            </td>
                                        </tr>

                                        <!-- Modal Editar Pagamento -->
                                        <div class="modal fade" id="modalEditarPagamento{{ $pagamento->id }}" tabindex="-1" aria-labelledby="modalEditarPagamentoLabel{{ $pagamento->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-md">
                                                <div class="modal-content">
                                                    <form action="{{ route('pagamentos.update', $pagamento->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">Editar Pagamento</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                        </div>

                                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                            <div class="row g-3">
                                                 

                                                                <!-- Origem -->
                                                                <div class="col-12">
                                                                    <label class="form-label">Origem do Pagamento</label>
                                                                    <select name="origem" id="tipo_origem_edit{{ $pagamento->id }}" class="form-select" onchange="toggleEditSelects({{ $pagamento->id }})" required>
                                                                        <option value="">-- Selecione --</option>
                                                                        <option value="checkin" {{ $pagamento->checkin_id ? 'selected' : '' }}>Check-in</option>
                                                                        <option value="hospede" {{ $pagamento->hospede_id ? 'selected' : '' }}>Hóspede</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Select Check-in -->
                                                                <div class="col-12" id="checkin_select_edit{{ $pagamento->id }}" style="display: {{ $pagamento->checkin_id ? 'block' : 'none' }};">
                                                                    <label class="form-label">Check-in</label>
                                                               <select name="checkin_id" class="form-select" {{ $pagamento->checkin_id ? '' : 'disabled' }} onchange="handleEditSelectChange({{ $pagamento->id }}, 'checkin')">
                                                                        <option value="">-- Nenhum --</option>
                                                                        @foreach ($checkins as $checkin)
                                                                        <option value="{{ $checkin->id }}" {{ $pagamento->checkin_id == $checkin->id ? 'selected' : '' }}>
                                                                            #{{ $checkin->id }} - {{ $checkin->hospede->nome ?? 'Sem Nome' }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Select Hóspede -->
                                                                <div class="col-12" id="hospede_select_edit{{ $pagamento->id }}" style="display: {{ $pagamento->hospede_id ? 'block' : 'none' }};">
                                                                    <label class="form-label">Hóspede</label>
                                                             <select name="hospede_id" class="form-select" {{ $pagamento->hospede_id ? '' : 'disabled' }} onchange="handleEditSelectChange({{ $pagamento->id }}, 'hospede')">
                                                                        <option value="">-- Nenhum --</option>
                                                                        @foreach ($hospedes as $hospede)
                                                                        <option value="{{ $hospede->id }}" {{ $pagamento->hospede_id == $hospede->id ? 'selected' : '' }}>
                                                                            #{{ $hospede->id }} - {{ $hospede->nome }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Valor -->
                                                        <div class="mb-3">
                                                                <div class="col-12">
                                                                    <label class="form-label">Valor</label>
                                                                <input type="number" step="0.01" name="valor" id="valor_edit{{ $pagamento->id }}" value="{{ $pagamento->valor }}" class="form-control" required>

                                                                </div>
                                                        </div>

                                                                <!-- Status -->
                                                             <div class="mb-3">
                                                                   <div class="col-12">
                                                                    <label class="form-label">Estado</label>
                                                                    <select name="status_pagamento" class="form-select" required>
                                                                        <option value="pendente" {{ $pagamento->status_pagamento == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                                                        <option value="pago" {{ $pagamento->status_pagamento == 'pago' ? 'selected' : '' }}>Pago</option>
                                                                        <option value="falhou" {{ $pagamento->status_pagamento == 'falhou' ? 'selected' : '' }}>Falhou</option>
                                                                    </select>
                                                                </div>
                                                             </div>

                                                                <!-- Método -->
                                                                <div class="mb-3">
                                                                    <label class="form-label">Forma de Pagamento</label>
                                                                    <select name="metodo_pagamento" class="form-select" required>
                                                                        <option value="numerario" {{ $pagamento->metodo_pagamento == 'numerario' ? 'selected' : '' }}>Numerário</option>
                                                                        <option value="cartao" {{ $pagamento->metodo_pagamento == 'cartao' ? 'selected' : '' }}>Cartão</option>
                                                                        <option value="transferencia" {{ $pagamento->metodo_pagamento == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                                                                        <option value="mbway" {{ $pagamento->metodo_pagamento == 'mbway' ? 'selected' : '' }}>MB Way</option>
                                                                    </select>
                                                                </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Atualizar Pagamento</button>
                                                        </div>
                                                    </form>
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
        </div>

        <!-- Modal Criar Pagamento -->
        <div class="modal fade" id="modalNovoPagamento" tabindex="-1" aria-labelledby="modalNovoPagamentoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <form action="{{ route('pagamentos.store') }}" method="POST">
                        @csrf

                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title text-white">Novo Pagamento</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <!-- Origem -->
                            <div class="mb-3">
                                <label class="form-label">Origem do Pagamento</label>
                                <select id="tipo_origem" class="form-select" onchange="toggleSelects()" required>
                                    <option value="">-- Selecione --</option>
                                    <option value="checkin">Check-in</option>
                                    <option value="hospede">Hóspede</option>
                                </select>
                            </div>

                            <!-- Check-ins -->
                            <div class="mb-3" id="checkin_select" style="display: none;">
                                <label class="form-label">Check-in</label>
                                <select name="checkin_id" id="checkin_id" class="form-select" onchange="handleSelectChange('checkin')">

                                    <option value="">-- Nenhum --</option>
                                    @foreach ($checkins as $checkin)
                                    <option value="{{ $checkin->id }}">#{{ $checkin->id }} - {{ $checkin->reserva->cliente_nome ?? 'Sem nome' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Hóspedes -->
                            <div class="mb-3" id="hospede_select" style="display: none;">
                                <label class="form-label">Hóspede</label>
                                <select name="hospede_id" id="hospede_id" class="form-select" onchange="handleSelectChange('hospede')">

                                    <option value="">-- Nenhum --</option>
                                    @foreach ($hospedes as $hospede)
                                    <option value="{{ $hospede->id }}">#{{ $hospede->id }} - {{ $hospede->nome }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Valor -->
                            <div class="mb-3">
                                <label class="form-label">Valor Total</label>
                                <input type="number" step="0.01" id="valor" name="valor" class="form-control" readonly>
                            </div>

                            <!-- Método -->
                            <div class="mb-3">
                                <label class="form-label">Forma de Pagamento</label>
                                <select name="metodo_pagamento" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <option value="numerario">Numerário</option>
                                    <option value="cartao">Cartão</option>
                                    <option value="transferencia">Transferência</option>
                                    <option value="mbway">MB Way</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status_pagamento" class="form-select" required>
                                    <option value="pago">Pago</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="falhou">Falhou</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3" style="margin-left: 20px;">
                            <div class="form-check">
                                <input type="checkbox" name="gerar_fatura" class="form-check-input" id="faturaCheck">
                                <label class="form-check-label" for="faturaCheck">Gerar fatura após salvar</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Registar Pagamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </main>
    <!--   Core JS Files   -->

    @if (session('fatura_id'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.open("{{ route('pagamentos.fatura', session('fatura_id')) }}", '_blank');
            }, 1000); // aguarda 1 segundo para o SweetAlert aparecer primeiro
        });
    </script>
    @endif

    @include('components.js')
    @include('layouts.customise')

    <script>
        function toggleSelects() {
            const tipo = document.getElementById('tipo_origem').value;

            document.getElementById('checkin_select').style.display = (tipo === 'checkin') ? 'block' : 'none';
            document.getElementById('hospede_select').style.display = (tipo === 'hospede') ? 'block' : 'none';

            if (tipo === 'checkin') {
                document.getElementById('hospede_id').value = '';
            } else if (tipo === 'hospede') {
                document.getElementById('checkin_id').value = '';
            }

            document.getElementById('valor').value = '';
        }

        function handleSelectChange(type) {
            let url = '';

            if (type === 'checkin') {
                let id = document.getElementById('checkin_id').value;
                if (id) url = `/valor/checkin/${id}`;
            } else if (type === 'hospede') {
                let id = document.getElementById('hospede_id').value;
                if (id) url = `/valor/hospede/${id}`;
            }

            if (url) {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('valor').value = data.valor ?? '';
                    });
            }
        }
    </script>

  <script>
    function toggleEditSelects(id) {
        const tipo = document.getElementById('tipo_origem_edit' + id).value;

        const checkinDiv = document.getElementById('checkin_select_edit' + id);
        const hospedeDiv = document.getElementById('hospede_select_edit' + id);

        const checkinSelect = checkinDiv.querySelector('select');
        const hospedeSelect = hospedeDiv.querySelector('select');

        // Alterna visibilidade
        checkinDiv.style.display = (tipo === 'checkin') ? 'block' : 'none';
        hospedeDiv.style.display = (tipo === 'hospede') ? 'block' : 'none';

        // Limpa valores e desativa os que não estão visíveis
        if (tipo === 'checkin') {
            hospedeSelect.value = '';
            hospedeSelect.disabled = true;
            checkinSelect.disabled = false;
        } else if (tipo === 'hospede') {
            checkinSelect.value = '';
            checkinSelect.disabled = true;
            hospedeSelect.disabled = false;
        } else {
            checkinSelect.value = '';
            checkinSelect.disabled = true;
            hospedeSelect.value = '';
            hospedeSelect.disabled = true;
        }

        // Limpa o campo de valor ao trocar a origem
        document.getElementById('valor_edit' + id).value = '';
    }

    function handleEditSelectChange(id, type) {
        let url = '';

        if (type === 'checkin') {
            const checkinId = document.querySelector(`#checkin_select_edit${id} select`).value;
            if (checkinId) url = `/valor/checkin/${checkinId}`;
        } else if (type === 'hospede') {
            const hospedeId = document.querySelector(`#hospede_select_edit${id} select`).value;
            if (hospedeId) url = `/valor/hospede/${hospedeId}`;
        }

        if (url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('valor_edit' + id).value = data.valor ?? '';
                });
        }
    }
</script>


</body>

</html>