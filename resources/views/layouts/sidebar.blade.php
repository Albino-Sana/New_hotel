@php
$tipoUser = Auth::user()->tipo ?? null;
@endphp
<div class="min-height-300 bg-dark position-absolute w-100"></div>

<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
      <img src="{{ asset('assets/img/dat-sys-3D.png') }}" width="80px" height="100px" class="navbar-brand-img h-100" alt="main_logo">
    </a>
  </div>

  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto h-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link active" href="{{ route('dashboard') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      @if ($tipoUser === 'Administrador')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('funcionarios.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Funcionários</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('cargos.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-badge text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Cargos</span>
        </a>
      </li>

      <x-dropdown align="right" width="48">
        <x-slot name="trigger">
          <a class="nav-link d-flex align-items-center" href="#" role="button">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-building text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Quartos</span>
            <i class="fas fa-caret-down ms-2"></i>
          </a>
        </x-slot>
        <x-slot name="content">
          <a href="{{ route('tipos-quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
            Gerir Tipos de Quartos
          </a>
          <a href="{{ route('quartos.index') }}" class="dropdown-item d-block px-4 py-2 text-sm text-dark hover:bg-gray-100">
            Gerir Quartos
          </a>
        </x-slot>
      </x-dropdown>
      
      @endif

      <li class="nav-item">
        <a class="nav-link" href="{{ route('reservas.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Reservas</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Check-in e check-out</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('checkins.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Check-in e check-out</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Hospedagem</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('hospedes.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-building text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Hóspedes</span>
        </a>
      </li>
      @if ($tipoUser === 'Administrador')
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Serviços Extras</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('servicos_extras.index') }}">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Serviços</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../pages/sign-up.html">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Sign Up</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pagamentos e Fatura</h6>
      </li>
      @endif
    </ul>
  </div>
</aside>