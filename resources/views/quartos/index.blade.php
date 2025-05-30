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
        $titulo = 'Quartos';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize ps-3">Quartos</h6>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoQuartoModal">
                                Novo Quarto
                            </button>
                        </div>

                        <div class="container-fluid py-4">
                            <div class="row">
                                <!-- Card de Filtros (Opcional) -->
                                <div class="col-12 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Filtrar Quartos</h5>
                                            <form method="GET" action="{{ route('quartos.index') }}">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select" name="status">
                                                            <option value="">Todos</option>
                                                            <option value="Disponível" {{ request('status') == 'Disponível' ? 'selected' : '' }}>Disponível</option>
                                                            <option value="Reservado" {{ request('status') == 'Reservado' ? 'selected' : '' }}>Reservado</option>
                                                            <option value="Ocupado" {{ request('status') == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                                                            <option value="Manutenção" {{ request('status') == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Tipo</label>
                                                        <select class="form-select" name="tipo">
                                                            <option value="">Todos</option>
                                                            @foreach($tipos as $tipo)
                                                            <option value="{{ $tipo->nome }}" {{ request('tipo') == $tipo->nome ? 'selected' : '' }}>
                                                                {{ $tipo->nome }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Andar</label>
                                                        <select class="form-select" name="andar">
                                                            <option value="">Todos</option>
                                                            @for($i = 1; $i <= 30; $i++)
                                                                <option value="{{ $i }}" {{ request('andar') == $i ? 'selected' : '' }}>{{ $i }}º Andar</option>
                                                                @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end">
                                                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- Cards dos Quartos -->
                                @foreach($quartos as $quarto)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <div class="card card-hover h-100 shadow-sm">
                                        <div class="card-header p-3 pb-2 bg-gradient-{{ 
                                                $quarto->status == 'Disponível' ? 'success' : 
                                                ($quarto->status == 'Reservado' ? 'primary' : 
                                                ($quarto->status == 'Ocupado' ? 'danger' : 'secondary')) 
                                            }} text-white border-radius-lg">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-white">Quarto #{{ $quarto->numero }}</h6>
                                                <span class="badge bg-white text-dark">{{ $quarto->andar }}º Andar</span>
                                            </div>
                                        </div>

                                        <div class="card-body p-3 pt-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm">{{ $quarto->tipo->nome }}</span>
                                                <span class="badge badge-sm bg-gradient-{{ 
                                                        $quarto->status == 'Disponível' ? 'success' : 
                                                        ($quarto->status == 'Reservado' ? 'primary' : 
                                                        ($quarto->status == 'Ocupado' ? 'danger' : 'secondary')) 
                                                    }}">{{ $quarto->status }}</span>
                                            </div>

                                            <hr class="horizontal dark my-2">

                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">{{ number_format($quarto->preco_noite, 2, ',', '.') }} Kz</h5>
                                                <span class="text-sm">
                                                    @if($quarto->tipo_cobranca === 'por_hora')
                                                    /hora
                                                    @elseif($quarto->tipo_cobranca === 'por_noite')
                                                    /noite
                                                    @else
                                                    /período
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="mt-3">
                                                <p class="text-sm mb-1"><i class="fas fa-calendar me-1"></i> Criado em: {{ $quarto->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="card-footer p-3 pt-0 bg-transparent">
                                            <div class="d-flex justify-content-between">
                                                <a href="#" class="btn btn-sm btn-outline-primary mb-0" data-bs-toggle="modal" data-bs-target="#editarTipoModal{{ $quarto->id }}">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>

                                                <form action="{{ route('quartos.destroy', $quarto) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger mb-0 btn-delete"
                                                        data-url="{{ route('quartos.destroy', $quarto) }}">
                                                        <i class="fas fa-trash-alt me-1"></i> Excluir
                                                    </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editarTipoModal{{ $quarto->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('quartos.update', $quarto->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header bg-gradient-primary text-white">
                                                    <h5 class="modal-title text-white">
                                                        <i class="fas fa-edit me-2"></i>Editar Quarto
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- Informações Básicas -->
                                                    <div class="card mb-3 shadow-sm">
                                                        <div class="card-header bg-light">
                                                            <strong><i class="fas fa-info-circle me-2 text-primary"></i>Informações Básicas</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label><i class="fas fa-hashtag me-1 text-secondary"></i>Número do Quarto</label>
                                                                    <input type="text" name="numero" class="form-control" value="{{ $quarto->numero }}" required>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label><i class="fas fa-layer-group me-1 text-secondary"></i>Andar</label>
                                                                    <select name="andar" class="form-control" required>
                                                                        <option value="">Selecione o andar</option>
                                                                        @for($i = 1; $i <= 30; $i++)
                                                                            <option value="{{ $i }}" {{ $quarto->andar == $i ? 'selected' : '' }}>
                                                                            {{ $i }}º Andar
                                                                            </option>
                                                                            @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Tipo e Status -->
                                                    <div class="card mb-3 shadow-sm">
                                                        <div class="card-header bg-light">
                                                            <strong><i class="fas fa-tags me-2 text-primary"></i>Configurações</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label><i class="fas fa-hotel me-1 text-secondary"></i>Tipo de Quarto</label>
                                                                    <select class="form-control" name="tipo_quarto_id" required>
                                                                        @foreach($tipos as $tipo)
                                                                        <option value="{{ $tipo->id }}" {{ $quarto->tipo_quarto_id == $tipo->id ? 'selected' : '' }}>
                                                                            {{ $tipo->nome }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label><i class="fas fa-toggle-on me-1 text-secondary"></i>Status</label>
                                                                    <select class="form-control" name="status" required>
                                                                        <option value="Disponível" {{ $quarto->status == 'Disponível' ? 'selected' : '' }}>Disponível</option>
                                                                        <option value="Indisponível" {{ $quarto->status == 'Indisponível' ? 'selected' : '' }}>Indisponível</option>
                                                                        <option value="Reservado" {{ $quarto->status == 'Reservado' ? 'selected' : '' }}>Reservado</option>
                                                                        <option value="Manutenção" {{ $quarto->status == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Valores -->
                                                    <div class="card mb-3 shadow-sm">
                                                        <div class="card-header bg-light">
                                                            <strong><i class="fas fa-money-bill-wave me-2 text-primary"></i>Valores</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label><i class="fas fa-coins me-1 text-secondary"></i>Preço por Noite</label>
                                                                <input type="number" class="form-control" name="preco_noite" value="{{ $quarto->preco_noite }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save me-1"></i>Salvar Alterações
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>Cancelar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal moderno para Novo Quarto -->
            <div class="modal fade" id="novoQuartoModal" tabindex="-1" aria-labelledby="novoQuartoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('quartos.store') }}" method="POST">
                            @csrf


                            <div class="modal-header bg-gradient-primary text-white">
                                <h5 class="modal-title text-white">
                                    <i class="fas fa-bed me-2"></i>Adicionar Novo Quarto
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>

                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                                <!-- Informações Básicas -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-info-circle me-2 text-primary"></i>Informações Básicas</strong>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-hashtag me-1 text-secondary"></i>Número do Quarto</label>
                                            <input type="text" name="numero" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-layer-group me-1 text-secondary"></i>Andar</label>
                                            <select name="andar" class="form-control" required>
                                                <option value="">Selecione o andar</option>
                                                @for($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}">{{ $i }}º Andar</option>
                                                    @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tipo e Status -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-tags me-2 text-primary"></i>Tipo e Status</strong>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-hotel me-1 text-secondary"></i>Tipo de Quarto</label>
                                            <select class="form-control @error('tipo_quarto_id') is-invalid @enderror"
                                                name="tipo_quarto_id" id="tipo_quarto_id" required>
                                                <option value="" disabled selected>Selecione o tipo</option>
                                                @foreach($tipos as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    @if(old('tipo_quarto_id')==$tipo->id) selected @endif
                                                    data-preco="{{ $tipo->preco }}"
                                                    data-cobranca="{{ $tipo->tipo_cobranca }}">
                                                    {{ $tipo->nome }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('tipo_quarto_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="fas fa-toggle-on me-1 text-secondary"></i>Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="Disponível">Disponível</option>
                                                <option value="Ocupado">Ocupado</option>
                                                <option value="Manutenção">Manutenção</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preço -->
                                <!-- Preço e Tipo de Cobrança -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-money-bill-wave me-2 text-primary"></i>Valores</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3" id="preco-wrapper">
                                            <label><i class="fas fa-coins me-1 text-secondary"></i>Preço por Noite</label>
                                            <input type="number" name="preco_noite" id="preco_noite" class="form-control" step="0.01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label><i class="fas fa-credit-card me-1 text-secondary"></i>Tipo de Cobrança</label>
                                            <input type="text" name="tipo_cobranca" id="tipo_cobranca" class="form-control" readonly required>
                                        </div>
                                    </div>
                                </div>


                                <!-- Descrição -->
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-align-left me-2 text-primary"></i>Descrição</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label><i class="fas fa-comment me-1 text-secondary"></i>Detalhes (opcional)</label>
                                            <textarea name="descricao" class="form-control" rows="3" placeholder="Ex: Quarto com vista para o mar..."></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Salvar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('tipo_quarto_id').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const preco = selected.getAttribute('data-preco');
                const cobranca = selected.getAttribute('data-cobranca');

                const precoInput = document.getElementById('preco_noite');
                const cobrancaInput = document.getElementById('tipo_cobranca');
                const wrapper = document.getElementById('preco-wrapper');

                if (preco && wrapper) {
                    precoInput.value = preco;
                    cobrancaInput.value = cobranca;
                    wrapper.style.display = 'block';
                }
            });
        </script>


    </main>
    @include('layouts.customise')
    <!--   Core JS Files   -->
    @include('components.js')

</body>

</html>