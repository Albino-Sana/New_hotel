@php
$tipoUser = Auth::user()->tipo ?? null;
@endphp
<div class="min-height-300 position-absolute w-100"
    style="background-image: url('{{ asset('assets/img/pos.JPG') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;">
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
    <div class="collapse navbar-collapse w-auto h-auto">
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('funcionarios.*') ? 'active' : '' }}" href="{{ route('funcionarios.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Funcionários</span>
                </a>
            </li>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('tipos-quartos.*') || request()->routeIs('quartos.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Quartos</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('tipos-quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('tipos-quartos.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-layer-group mr-2 ml-1"></i> Tipos de Quartos
                    </a>
                    <a href="{{ route('quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('quartos.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-door-closed mr-2 ml-1"></i> Gerir Quartos
                    </a>
                </x-slot>
            </x-dropdown>
            @endif

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('reservas.*') || request()->routeIs('mapas.reservas') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reservas</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('reservas.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('reservas.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-layer-group mr-2 ml-1"></i> Lista de Reservas
                    </a>
                    <a href="{{ route('mapas.reservas') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('mapas.reservas') ? 'bg-gray-100' : '' }}">
                        <i class="ni ni-calendar-grid-58 mr-2 ml-1"></i> Mapa
                    </a>
                </x-slot>
            </x-dropdown>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('checkins.*') ? 'active' : '' }}" href="{{ route('checkins.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-door-open text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Check-In/Check-Out</span>
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

            @if ($tipoUser === 'Administrador' || $tipoUser === 'Recepcionista' || $tipoUser === 'Gerente de Caixa')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('servicos_extras.*') ? 'active' : '' }}" href="{{ route('servicos_extras.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-concierge-bell text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Serviços</span>
                </a>
            </li>
            @endif

            @if ($tipoUser === 'Administrador')
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Relatórios</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('relatorios.ocupacao') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('relatorios.ocupacao') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-bed mr-2 ml-1"></i> Ocupação de Quartos
                    </a>
                    <a href="{{ route('relatorios.reservas-cancelamentos') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('relatorios.reservas-cancelamentos') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-calendar-times mr-2 ml-1"></i> Cancelamento de Reservas
                    </a>
                    <a href="{{ route('relatorios.faturamento') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('relatorios.faturamento') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-money-bill-wave mr-2 ml-1"></i> Faturamento
                    </a>
                    <a href="{{ route('relatorios.servicos-extras') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('relatorios.servicos-extras') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-glass-cheers mr-2 ml-1"></i> Serviços extras vendidos
                    </a>
                </x-slot>
            </x-dropdown>
            @endif

            @if ($tipoUser === 'Administrador' || $tipoUser === 'Recepcionista' || $tipoUser === 'Gerente de Caixa')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pagamentos.*') ? 'active' : '' }}" href="{{ route('pagamentos.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pagamentos</span>
                </a>
            </li>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('documentos.*') || request()->routeIs('faturas.*') || request()->routeIs('faturasRecibo.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Documentos</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('faturas.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('faturas.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-file-invoice mr-2 ml-1"></i> Fatura
                    </a>
                    <a href="{{ route('faturasRecibo.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('faturasRecibo.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-receipt mr-2 ml-1"></i> Fatura Recibo
                    </a>
                </x-slot>
            </x-dropdown>
            @endif

            @if ($tipoUser === 'Administrador')
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('saft.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-contract text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">AGTributária</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('saft.form') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('saft.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-file-export mr-2 ml-1"></i> Gerar SAFT
                    </a>
                </x-slot>
            </x-dropdown>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('cargos.*') ? 'active' : '' }}" href="#" role="button">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-cog text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Configurações</span>
                        <i class="fas fa-caret-down ms-2 transition-transform duration-200"></i>
                    </a>
                </x-slot>
                <x-slot name="content">
                    <a href="{{ route('cargos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100 {{ request()->routeIs('cargos.*') ? 'bg-gray-100' : '' }}">
                        <i class="fas fa-user-tie mr-2 ml-1"></i> Cargos
                    </a>
                </x-slot>
            </x-dropdown>
            @endif
        </ul>
    </div>
</aside>