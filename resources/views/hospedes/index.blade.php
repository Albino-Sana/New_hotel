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

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telefone</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entrada</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saída</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quarto</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor a Pagar</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nº Pessoas</th>
                                            <th class="text-center text-secondary opacity-7">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hospedes as $hospede)
                                        <tr>
                                            <td>{{ $hospede->id }}</td>
                                            <td>{{ $hospede->nome }}</td>
                                            <td>{{ $hospede->email }}</td>
                                            <td>{{ $hospede->telefone }}</td>
                                            <td>{{ $hospede->data_entrada }}</td>
                                            <td>{{ $hospede->data_saida }}</td>
                                            <td>
                                                <a href="{{ route('quartos.index') }}"> Nº {{ $hospede->quarto->numero ?? '-' }}</a>
                                            </td>
                                            <td>{{ number_format($hospede->valor_a_pagar, 2, ',', '.') }} kz</td>
                                            <td>{{ $hospede->numero_pessoas }}</td>
                                            <td class="text-center">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editarHospedeModal{{ $hospede->id }}" class="text-secondary font-weight-bold text-xs">
                                                 Editar
                                                </a>

                                                <form action="{{ route('hospedes.destroy', $hospede) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Tem certeza?')" class="btn btn-link text-danger p-0 m-0 align-baseline">
                                                        Excluir
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                        <!-- Modal Editar Hóspede -->
                                        <div class="modal fade" id="editarHospedeModal{{ $hospede->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('hospedes.update', $hospede->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Cabeçalho com gradiente azul -->
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white">
                                                                <i class="fas fa-user-edit me-2"></i>Editar Hóspede
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

                                                            <!-- Detalhes da Hospedagem -->
                                                            <div class="card mb-3 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Período da Hospedagem</strong>
                                                                </div>
                                                                <div class="card-body row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-in-alt me-1 text-secondary"></i>Data de Entrada</label>
                                                                        <input type="date" class="form-control" name="data_entrada" id="editar_data_entrada_{{ $hospede->id }}" value="{{ $hospede->data_entrada }}" required>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-sign-out-alt me-1 text-secondary"></i>Data de Saída</label>
                                                                        <input type="date" class="form-control" name="data_saida" id="editar_data_saida_{{ $hospede->id }}" value="{{ $hospede->data_saida }}" required>
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
                                                                        <select class="form-control" name="quarto_id" id="editar_quarto_{{ $hospede->id }}">
                                                                            <option value="" selected disabled>Mudar de Quarto</option>
                                                                            @foreach($quartos as $quarto)
                                                                            <option value="{{ $quarto->id }}" data-valor="{{ $quarto->preco_noite }}" {{ $quarto->id == $hospede->quarto_id ? 'selected' : '' }}> Quarto {{ $quarto->numero }} - {{ $quarto->tipo->nome }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <label><i class="fas fa-money-bill-wave me-1 text-secondary"></i>Valor a Pagar</label>
                                                                        <input type="text" class="form-control" id="editar_valor_{{ $hospede->id }}" name="valor_a_pagar" value="{{ $hospede->valor_a_pagar }}" readonly>
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


                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="p-3">
                                    {{ $hospedes->links() }}
                                </div>
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