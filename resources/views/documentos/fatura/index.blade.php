<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
        Faturas - DAT SYS
    </title>

    @include('components.css')

</head>


<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-dash position-absolute w-100"></div>
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- Sidebar -->
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @php
        $titulo = 'Faturas';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-4">Faturas emitidas</h6>
                        </div>
                        <!-- Botão "Novo Banco" -->
                        <div class="card-body px-0 pt-0 pb-2  mx-4">

                            @if ($faturas->isEmpty())
                            <center>
                                <img src="{{ asset('assets/img/gallery-svgrepo-com.svg') }}" style="opacity: 10%;"
                                    width="150">
                                <p>Nenhum registro foi encontrado...</p>
                            </center>
                            @else
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Número da Fatura</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Nome do Cliente</th>

                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                NIF do Cliente</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Data de Emissão</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Total Liq.</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Estado da Fatura</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($faturas as $fatura)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <i class="bi bi-receipt fa-2x text-primary me-2"></i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $fatura->numero }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $fatura->nome_cliente }}</td>
                                            <td>{{ $fatura->nif }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ number_format($fatura->total, 2, ',', '.') }} Kz
                                            </td>
                                            </td>
                                            <td>
                                                @if ($fatura->estado_documento === 'A')
                                                <span class="badge bg-danger">Anulado</span>
                                                @else
                                                <span class="badge bg-success">Emitida</span>
                                                @endif
                                            </td>

                                            <td>
                                                <!-- Download -->
                                                <a href="{{ route('faturas.download', $fatura->id) }}"
                                                    class="text-secondary font-weight-bold text-xs me-3"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Baixar Documento">
                                                    <i class="bi bi-file-earmark-text fa-2x"></i>
                                                </a>

                                                <!-- Imprimir -->
                                                <a href="{{ route('fatura', $fatura->id) }}"
                                                    target="_blank"
                                                    class="text-secondary font-weight-bold text-xs me-3"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Imprimir Documento">
                                                    <i class="bi bi-printer fa-2x"></i>
                                                </a>

                                                <!-- E-mail -->
                                                <a href="javascript:void(0);"
                                                    class="text-secondary font-weight-bold text-xs me-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#emailFaturaModal{{ $fatura->id }}"
                                                    title="Enviar Documento por E-mail">
                                                    <i class="bi bi-envelope fa-2x"></i>
                                                </a>

                                                <!-- Anular -->
                                                @if ($fatura->estado_documento !== 'A')
                                                <a href="javascript:void(0);"
                                                    class="text-danger font-weight-bold text-xs"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteFaturaModal{{ $fatura->id }}"
                                                    title="Anular Documento">
                                                    <i class="bi bi-x-circle fa-2x"></i>
                                                </a>
                                                @endif
                                            </td>



                                        </tr>

                                        <!-- Modal de Exclusão -->
                                        <div class="modal fade" id="deleteFaturaModal{{ $fatura->id }}"
                                            tabindex="-1"
                                            aria-labelledby="deleteFaturaModalLabel{{ $fatura->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="deleteFaturaModalLabel{{ $fatura->id }}">
                                                            Anular Fatura: {{ $fatura->serie }}
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Tem certeza de que deseja anular este documento
                                                            <strong>{{ $fatura->serie }}</strong>?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('faturas.destroy', $fatura->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de Envio por E-mail -->
                                        <div class="modal fade" id="emailFaturaModal{{ $fatura->id }}"
                                            tabindex="-1"
                                            aria-labelledby="emailFaturaLabel{{ $fatura->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="emailFaturaLabel{{ $fatura->id }}">Enviar
                                                            Fatura por E-mail</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('faturas.email', $fatura->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="emailCliente{{ $fatura->id }}"
                                                                    class="form-label">E-mail do
                                                                    Cliente</label>
                                                                <input type="email" class="form-control"
                                                                    id="emailCliente{{ $fatura->id }}"
                                                                    name="email"
                                                                    placeholder="Digite o e-mail do cliente"
                                                                    required>
                                                                <small class="text-muted">Certifique-se de
                                                                    inserir um e-mail válido para o envio do
                                                                    documento.</small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Enviar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!--   Core JS Files   -->

    @include('components.js')
</body>

</html>