@php
$tipoUser = Auth::user()->tipo ?? null;
@endphp
<div class="min-height-300 position-absolute w-100"
    style="background-image: url('{{ asset('assets/img/pos.JPG') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    ">
</div>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header py-3">
        <a class="navbar-brand m-0 d-flex justify-content-center align-items-center" href="#">
            <img src="{{ asset('assets/img/dat-sys-3D.png') }}"
                class="img-fluid"
                style="max-width: 150px; max-height: 50px; width: auto; height: auto; object-fit: contain;"
                alt="Logo DAT-SYS">
        </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-100" id="sidenav-collapse-main" style="overflow-y: auto; overflow-x: hidden;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            @if ($tipoUser === 'Administrador')
            <li class="nav-item" style="display: none;">
                <a class="nav-link {{ request()->routeIs('funcionarios.*') ? 'active' : '' }}" href="{{ route('funcionarios.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Funcionários</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cargos.*') ? 'active' : '' }}" href="{{ route('cargos.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-badge text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Cargos</span>
                </a>
            </li>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('tipos-quartos.*') || request()->routeIs('quartos.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Quartos</span>
                        <i class="fas fa-caret-down ms-2"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('tipos-quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Tipos de Quartos
                    </a>
                    <a href="{{ route('quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Gerir Quartos
                    </a>
                </x-slot>
            </x-dropdown>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reservas.*') ? 'active' : '' }}" href="{{ route('reservas.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reservas</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('checkins.*') ? 'active' : '' }}" href="{{ route('checkins.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-door-open text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Check-in e check-out</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hospedes.*') ? 'active' : '' }}" href="{{ route('hospedes.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Hóspedes</span>
                </a>
            </li>

            @if ($tipoUser === 'Administrador')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('servicos_extras.*') ? 'active' : '' }}" href="{{ route('servicos_extras.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-concierge-bell text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Serviços</span>
                </a>
            </li>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Relatório</span>
                        <i class="fas fa-caret-down ms-2"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('relatorios.ocupacao') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Ocupação de Quartos
                    </a>
                    <a href="{{ route('relatorios.reservas-cancelamentos') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Cancelamento de Reservas
                    </a>
                    <a href="{{ route('relatorios.faturamento') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Faturamento
                    </a>
                    <a href="{{ route('relatorios.servicos-extras') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
                        Serviços extras vendidos
                    </a>
                </x-slot>
            </x-dropdown>

                        <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pagamentos.*') ? 'active' : '' }}" href="{{ route('pagamentos.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pagamentos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}" href="#">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-file-alt text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Documentos</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</aside>