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
        $titulo = 'Dashboard';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class=" shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-capitalize ps-3">Lista de Usuários</h6>
                            <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-light ms-3">Novo Usuário</a>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="Table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuário</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">E-mail</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Criado em</th>
                                            <th class="text-secondary opacity-7">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($usuarios as $usuario)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm" style="margin-left: 20px;">{{ $usuario->id }}</h6>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <span class="avatar avatar-sm rounded-circle bg-gradient-primary me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $usuario->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">{{ $usuario->email }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $usuario->active ? 'success' : 'secondary' }}">
                                                    {{ $usuario->active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $usuario->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" title="Editar usuário">
                                                    Editar
                                                </a>
                                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Tem certeza?')" data-toggle="tooltip" title="Excluir usuário">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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