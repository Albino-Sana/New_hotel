<!-- jQuery (necessário para os componentes Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@php
    $tipoUser = Auth::user()->tipo ?? null;
@endphp
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Página</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $titulo }}</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">{{ $titulo }}</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
          <span hidden class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
          <input hidden type="text" class="form-control" placeholder="Type here...">
        </div>
      </div>
      <ul class="navbar-nav  justify-content-end">

    
        <li class="nav-item d-flex align-items-center">
          <div class="dropdown">
            <a href="javascript:;" class="nav-link text-white font-weight-bold px-0 d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-user me-sm-1"></i>
              <span class="d-sm-inline d-none">{{ Auth::user()->name }}</span>
              <!-- Ícone mais visível -->
              <i class="bi bi-caret-down-fill ms-2"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="#">
                  <b>{{ Auth::user()->cargo }}</b>
                </a>
              </li>

              @if ($tipoUser === 'Administrador')
              <li>
                <a class="dropdown-item" href="{{ route('usuarios.index') }}">
                 Perfil
                </a>
              </li>


              <li>
                <a class="dropdown-item" href="{{ route('empresa.index') }}">
                Configurações</a>
              </li>
              @endif

              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Terminar sessão
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </li>


        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
        </li>

        <li class="nav-item px-3 d-flex align-items-center">
          <a href="#" class="nav-link text-white p-0">
            <i class="fa-solid fa-cookie fixed-plugin-button-nav cursor-pointer"></i>
          </a>
        </li>

        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell cursor-pointer"></i>
          </a>

        </li>
      </ul>
    </div>
  </div>
</nav>
@include('components.js')