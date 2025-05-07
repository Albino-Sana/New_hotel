<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        DAT Hotelaria -- Utilizadores
    </title>

    @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Dashboard';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-capitalize ps-3">Lista de Usuários</h6>
                            <button type="button" class="btn btn-sm btn-light ms-3" data-bs-toggle="modal" data-bs-target="#novoUsuarioModal">
                                Novo Usuário
                            </button>
                        </div>

                        <!-- Cards dos Utilizadores -->
                        <div class="row">
                            @foreach($usuarios as $usuario)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="card card-hover h-100 shadow-sm border-0">
                                    <!-- Cabeçalho com violeta suave e ícone -->
                                    <div class="card-header p-3 pb-4 bg-soft-violet position-relative">
                                        <div class="user-icon-container">
                                            <div class="user-icon bg-white text-violet shadow-sm">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-center text-white mt-3 mb-0">{{ $usuario->name }}</h6>
                                    </div>

                                    <div class="card-body p-3 pt-5">
                                        <div class="text-center mb-3">
                                            <p class="text-sm text-muted mb-1">{{ $usuario->email }}</p>
                                            <span class="badge bg-{{ $usuario->active ? 'success' : 'secondary' }}">
                                                {{ $usuario->active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>

                                        <hr class="horizontal light my-2">

                                        <div class="user-details text-center">
                                            <div class="mb-2">
                                                <span class="badge bg-violet">{{ $usuario->tipo }}</span>
                                            </div>
                                            <p class="text-xs text-muted mb-0">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Registrado em: {{ $usuario->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="card-footer p-3 bg-transparent">
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-violet mb-0" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal{{ $usuario->id }}">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </button>

                                            @if($usuario->id !== Auth::id())
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger mb-0" onclick="return confirm('Tem certeza que deseja excluir este utilizador?')">
                                                    <i class="fas fa-trash-alt me-1"></i> Excluir
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Novo Usuário -->
        <div class="modal fade" id="novoUsuarioModal" tabindex="-1" aria-labelledby="novoUsuarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <div class="modal-header bg-gradient-light-violet text-white">
                            <h5 class="modal-title text-white">
                                <i class="fas fa-user-plus me-2"></i>Adicionar Novo Utilizador
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">

                            <!-- Informações Básicas -->
                            <div class="card mb-3 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-user me-1 text-violet"></i>Nome Completo</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-envelope me-1 text-violet"></i>E-mail</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-lock me-1 text-violet"></i>Senha</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-lock me-1 text-violet"></i>Confirmar Senha</label>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-user-tag me-1 text-violet"></i>Tipo de Utilizador</label>
                                        <select class="form-select" name="tipo" required>
                                            <option value="" disabled selected>Selecione o tipo</option>
                                            <option value="Administrador">Administrador</option>
                                            <option value="Recepcionista">Recepcionista</option>
                                            <option value="Balconista">Balconista</option>
                                            <option value="Gerente de Quarto">Gerente de Quarto</option>
                                        </select>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="flexSwitchCheckChecked" checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"><i class="fas fa-toggle-on me-1 text-violet"></i>Ativo</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">
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

        <!-- Modal de Edição para cada Utilizador -->
        @foreach($usuarios as $usuario)
        <div class="modal fade" id="editarUsuarioModal{{ $usuario->id }}" tabindex="-1" aria-labelledby="editarUsuarioModalLabel{{ $usuario->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header bg-gradient-light-violet text-white">
                            <h5 class="modal-title text-white">
                                <i class="fas fa-user-edit me-2"></i>Editar Utilizador
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">

                            <!-- Informações Básicas -->
                            <div class="card mb-3 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-user me-1 text-violet"></i>Nome Completo</label>
                                        <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-envelope me-1 text-violet"></i>E-mail</label>
                                        <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-lock me-1 text-violet"></i>Nova Senha</label>
                                            <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-lock me-1 text-violet"></i>Confirmar Senha</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-user-tag me-1 text-violet"></i>Tipo de Utilizador</label>
                                        <select class="form-select" name="tipo" required>
                                            <option value="Administrador" {{ $usuario->tipo == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                            <option value="Recepcionista" {{ $usuario->tipo == 'Recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                                            <option value="Balconista" {{ $usuario->tipo == 'Balconista' ? 'selected' : '' }}>Balconista</option>
                                            <option value="Gerente de Quarto" {{ $usuario->tipo == 'Gerente de Quarto' ? 'selected' : '' }}>Gerente de Quarto</option>
          
                                        </select>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="flexSwitchCheckChecked{{ $usuario->id }}" {{ $usuario->active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexSwitchCheckChecked{{ $usuario->id }}"><i class="fas fa-toggle-on me-1 text-violet"></i>Ativo</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">
                                <i class="fas fa-save me-1"></i>Atualizar
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
    </main>

    @include('layouts.customise')

    <!--   Core JS Files   -->
    @include('components.js')

</body>

</html>