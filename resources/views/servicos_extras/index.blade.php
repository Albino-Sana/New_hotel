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
        $titulo = 'Serviços Extras';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize">Lista de Serviços Adicionais</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoServico">Novo Serviço</button>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Descrição</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Preço</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Criado Em</th>
                                            <th class="text-center text-secondary opacity-7">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($servicos as $servico)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $servico->id }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $servico->nome }}</h6>
                                            </td>
                                            <td class="text-center"><span class="text-secondary text-xs">{{ $servico->descricao }}</span></td>
                                            <td class="text-center"><span class="text-secondary text-xs">{{ number_format($servico->preco, 2, ',', '.') }} kz</span></td>
                                            <td class="text-center"><span class="text-secondary text-xs">{{ $servico->created_at->format('d/m/Y') }}</span></td>
                                            <td class="text-center">
                                                <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="modal" data-bs-target="#editarServicoModal{{ $servico->id }}">Editar</a>
                                                <form action="{{ route('servicos_extras.destroy', $servico) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="text-danger font-weight-bold text-xs border-0 bg-transparent btn-delete" title="Excluir serviço">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Editar -->
                                        <div class="modal fade" id="editarServicoModal{{ $servico->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('servicos_extras.update', $servico->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i>Editar Serviço</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Nome</label>
                                                                <input type="text" name="nome" class="form-control" value="{{ $servico->nome }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Descrição</label>
                                                                <textarea name="descricao" class="form-control" rows="2">{{ $servico->descricao }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Preço (kz)</label>
                                                                <input type="number" step="0.01" name="preco" class="form-control" value="{{ $servico->preco }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Salvar</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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

            <!-- Modal Criar Serviço -->
            <div class="modal fade" id="modalNovoServico" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('servicos_extras.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-gradient-primary text-white">
                                <h5 class="modal-title text-white"><i class="fas fa-plus-circle me-2"></i>Novo Serviço</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Descrição</label>
                                    <textarea name="descricao" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Preço (kz)</label>
                                    <input type="number" step="0.01" name="preco" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Salvar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
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