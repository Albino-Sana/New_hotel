<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        DAT Hotelaria - Configurações do Hotel
    </title>
    @include('components.css')
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dash position-absolute w-100"></div>
    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @php
            $titulo = 'Configurações do Hotel';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid py-4">
            <div class="card shadow-lg mx-4 card-profile-bottom">
                <div class="card-body p-3">
                    <div class="row gx-4">
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <img src="{{ asset('assets/img/dat-sys-3D.png') }}" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h5 class="mb-1">
                                    {{ $hotel->nome_empresa ?? 'Não definido' }}
                                </h5>
                                <p class="mb-0 font-weight-bold text-sm">
                                    <b>NIF: {{ $hotel->numero_registo_fiscal ?? '999999999' }}</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Configurar Dados do Hotel</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('empresa.store') }}" method="POST" class="container mt-2 shadow">
                                    @csrf
                                    @method('PUT')

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Versão do Arquivo de Auditoria</label>
                                            <input type="text" name="versao_arquivo_auditoria" class="form-control @error('versao_arquivo_auditoria') is-invalid @enderror" value="{{ old('versao_arquivo_auditoria', $hotel->versao_arquivo_auditoria ?? '') }}">
                                            @error('versao_arquivo_auditoria')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">ID da Empresa</label>
                                            <input type="text" name="id_empresa" class="form-control @error('id_empresa') is-invalid @enderror" value="{{ old('id_empresa', $hotel->id_empresa ?? '') }}">
                                            @error('id_empresa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Número de Registo Fiscal (NIF)</label>
                                            <input type="text" name="numero_registo_fiscal" class="form-control @error('numero_registo_fiscal') is-invalid @enderror" value="{{ old('numero_registo_fiscal', $hotel->numero_registo_fiscal ?? '') }}">
                                            @error('numero_registo_fiscal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Base Contábil Tributária</label>
                                            <input type="text" name="base_contabil_tributaria" class="form-control @error('base_contabil_tributaria') is-invalid @enderror" value="{{ old('base_contabil_tributaria', $hotel->base_contabil_tributaria ?? '') }}">
                                            @error('base_contabil_tributaria')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Nome da Empresa</label>
                                            <input type="text" name="nome_empresa" class="form-control @error('nome_empresa') is-invalid @enderror" value="{{ old('nome_empresa', $hotel->nome_empresa ?? '') }}">
                                            @error('nome_empresa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Nome do Negócio</label>
                                            <input type="text" name="nome_negocio" class="form-control @error('nome_negocio') is-invalid @enderror" value="{{ old('nome_negocio', $hotel->nome_negocio ?? '') }}">
                                            @error('nome_negocio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Endereço da Empresa</label>
                                            <input type="text" name="endereco_empresa" class="form-control @error('endereco_empresa') is-invalid @enderror" value="{{ old('endereco_empresa', $hotel->endereco_empresa ?? '') }}">
                                            @error('endereco_empresa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Número do Edifício</label>
                                            <input type="text" name="numero_edificio" class="form-control @error('numero_edificio') is-invalid @enderror" value="{{ old('numero_edificio', $hotel->numero_edificio ?? '') }}">
                                            @error('numero_edificio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Nome da Rua</label>
                                            <input type="text" name="nome_rua" class="form-control @error('nome_rua') is-invalid @enderror" value="{{ old('nome_rua', $hotel->nome_rua ?? '') }}">
                                            @error('nome_rua')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Cidade</label>
                                            <input type="text" name="cidade" class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade', $hotel->cidade ?? '') }}">
                                            @error('cidade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Código Postal</label>
                                            <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" value="{{ old('codigo_postal', $hotel->codigo_postal ?? '') }}">
                                            @error('codigo_postal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">País</label>
                                            <input type="text" name="pais" class="form-control @error('pais') is-invalid @enderror" value="{{ old('pais', $hotel->pais ?? 'AO') }}">
                                            @error('pais')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Província</label>
                                            <input type="text" name="provincia" class="form-control @error('provincia') is-invalid @enderror" value="{{ old('provincia', $hotel->provincia ?? '') }}">
                                            @error('provincia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Ano Fiscal</label>
                                            <input type="text" name="ano_fiscal" class="form-control @error('ano_fiscal') is-invalid @enderror" value="{{ old('ano_fiscal', $hotel->ano_fiscal ?? '') }}">
                                            @error('ano_fiscal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Data de Início</label>
                                            <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror" value="{{ old('data_inicio', $hotel->data_inicio ?? '') }}">
                                            @error('data_inicio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Data de Fim</label>
                                            <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror" value="{{ old('data_fim', $hotel->data_fim ?? '') }}">
                                            @error('data_fim')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Código da Moeda</label>
                                            <input type="text" name="codigo_moeda" class="form-control @error('codigo_moeda') is-invalid @enderror" value="{{ old('codigo_moeda', $hotel->codigo_moeda ?? 'AOA') }}">
                                            @error('codigo_moeda')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Data de Criação</label>
                                            <input type="datetime-local" name="data_criacao" class="form-control @error('data_criacao') is-invalid @enderror" value="{{ old('data_criacao', $hotel->data_criacao ?? '') }}">
                                            @error('data_criacao')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Entidade Tributária</label>
                                            <input type="text" name="entidade_tributaria" class="form-control @error('entidade_tributaria') is-invalid @enderror" value="{{ old('entidade_tributaria', $hotel->entidade_tributaria ?? '') }}">
                                            @error('entidade_tributaria')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">ID do Imposto da Empresa do Produto</label>
                                            <input type="text" name="id_imposto_empresa_produto" class="form-control @error('id_imposto_empresa_produto') is-invalid @enderror" value="{{ old('id_imposto_empresa_produto', $hotel->id_imposto_empresa_produto ?? '') }}">
                                            @error('id_imposto_empresa_produto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Número de Validação do Software</label>
                                            <input type="text" name="numero_validacao_software" class="form-control @error('numero_validacao_software') is-invalid @enderror" value="{{ old('numero_validacao_software', $hotel->numero_validacao_software ?? '') }}">
                                            @error('numero_validacao_software')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">ID do Produto</label>
                                            <input type="text" name="id_produto" class="form-control @error('id_produto') is-invalid @enderror" value="{{ old('id_produto', $hotel->id_produto ?? '') }}">
                                            @error('id_produto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Versão do Produto</label>
                                        <input type="text" name="versao_produto" class="form-control @error('versao_produto') is-invalid @enderror" value="{{ old('versao_produto', $hotel->versao_produto ?? '') }}">
                                        @error('versao_produto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Comentário do Cabeçalho</label>
                                        <textarea name="comentario_cabecalho" class="form-control @error('comentario_cabecalho') is-invalid @enderror">{{ old('comentario_cabecalho', $hotel->comentario_cabecalho ?? '') }}</textarea>
                                        @error('comentario_cabecalho')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone', $hotel->telefone ?? '') }}">
                                            @error('telefone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Fax</label>
                                            <input type="text" name="fax" class="form-control @error('fax') is-invalid @enderror" value="{{ old('fax', $hotel->fax ?? '') }}">
                                            @error('fax')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 col-lg-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $hotel->email ?? '') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6 mx-1">
                                            <label class="form-label">Website</label>
                                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $hotel->website ?? '') }}">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-profile">
                            <div class="card-header text-start border-0 pt-0 pt-lg-2 pb-4 pb-lg-3 d-flex justify-content-between">
                                Métodos de Pagamento
                            </div>
                            <div class="card-body pt-0">
                                @if(!isset($pagamentos_metodos) || $pagamentos_metodos->isEmpty())
                                    <center>
                                        <img src="{{ asset('assets/img/gallery-svgrepo-com.svg') }}" style="opacity: 10%;" width="150">
                                        <p>Nenhum método de pagamento foi encontrado...</p>
                                    </center>
                                @else
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Designação</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pagamentos_metodos as $metodo)
                                                <tr style="cursor: pointer;">
                                                    <td>{{ $metodo->designacao }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMetodoModal{{ $metodo->id }}"><i class="bi bi-trash"></i></button>
                                                    </td>
                                                </tr>
                                                <!-- Modal de Exclusão de Métodos -->
                                                <div class="modal fade" id="deleteMetodoModal{{ $metodo->id }}" tabindex="-1" aria-labelledby="deleteMetodoModalLabel{{ $metodo->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteMetodoModalLabel{{ $metodo->id }}">Excluir Método</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Tem certeza de que deseja excluir o método de pagamento {{ $metodo->designacao }}?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form action="{{ route('pagamentos-metodos.destroy', $metodo->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#configPagamentoModal">
                                    Adicionar Método
                                </button>
                            </div>

                            <!-- Modal para Adicionar Métodos de Pagamento -->
                            <div class="modal fade" id="configPagamentoModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel">Novo Método de Pagamento</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('pagamentos-metodos.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="designacao" class="form-label">Designação</label>
                                                    <input type="text" class="form-control @error('designacao') is-invalid @enderror" id="designacao" name="designacao" required>
                                                    @error('designacao')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-success">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.customise')
    @include('components.js')
</body>
</html>