<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    DAT Hotelaria - Dashboard SYS
  </title>

  @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">
<div class="min-height-300 bg-dash position-absolute w-100"></div>
  @include('layouts.sidebar')

  <main class="main-content position-relative border-radius-lg ">
    @php
    $titulo = 'Dashboard';
    @endphp
    @include('layouts.navbar', ['titulo' => $titulo])

    <div class="container-fluid py-4">

      <div class="row">
        <!-- Quartos Disponíveis -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Quartos Disponíveis</p>
                    <h5 class="font-weight-bolder">{{ $quartosDisponiveisTotal}}</h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Funcionários -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Checkin Ativos</p>
                    <h5 class="font-weight-bolder">{{ $totalFuncionarios  }}</h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-badge text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Reservas Ativas -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Reservas Ativas</p>
                    <h5 class="font-weight-bolder">{{ $reservasAtivas }}</h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-calendar-grid-58 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Hóspedes Hospedados -->
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">H. Hospedados</p>
                    <h5 class="font-weight-bolder">{{  $hospedesHospedadosTotal  }}</h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<div class="row mt-4">
    <div class="col-lg-12 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-capitalize" id="grafico-titulo">{{ $dadosGrafico['titulo'] }}</h6>
                    <p class="text-sm mb-0">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold" id="variacao-texto">Carregando...</span>
                    </p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary periodo-btn active" data-periodo="7dias">7 Dias</button>
                    <button type="button" class="btn btn-sm btn-outline-primary periodo-btn" data-periodo="1mes">1 Mês</button>
                    <button type="button" class="btn btn-sm btn-outline-primary periodo-btn" data-periodo="1ano">1 Ano</button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="btn-pdf">Exportar PDF</button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">

  {{-- -------------------------------------------------------------  QUARTOS --}}
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card shadow-sm">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">
          <i class="fas fa-bed me-2 text-primary"></i>Quartos disponíveis
          <span class="badge bg-gradient-success ms-2">{{ $quartosDisponiveis->count() }}</span>
        </h6>
      </div>

      <div class="table-responsive">
        <table class="table align-items-center mb-0" id="Table">
          <thead class="text-xs text-dark opacity-7">
            <tr>
              <th>#</th>
              <th>Número</th>
              <th>Tipo</th>
              <th>Andar</th>
              <th>Estado</th>
              <th class="text-end">Preço / noite</th>
            </tr>
          </thead>
          <tbody>
            @forelse($quartosDisponiveis as $q)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <span class="badge bg-gradient-success">{{ $q->numero }}</span>
                </td>
                <td>{{ $q->tipo->nome ?? '-' }}</td>
                <td>{{ $q->andar }}º</td>
                <td>
                  @if ($q->status == 'Disponível')
                    <span class="badge bg-gradient-success">{{ $q->status }}</span>
                  @elseif ($q->status == 'Ocupado')
                    <span class="badge bg-gradient-danger">{{ $q->status }}</span>
                  @else 
                    <span class="badge bg-gradient-warning">{{ $q->status }}</span>
                  @endif
                </td>
                <td class="text-end">{{ number_format($q->preco_noite,2,',','.') }} Kz</td>
              </tr>
            @empty
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ------------------------------------------------------------  HÓSPEDES --}}
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">
          <i class="fas fa-user-friends me-2 text-primary"></i>Hóspedes hospedados
          <span class="badge bg-gradient-primary ms-2">{{ $hospedesHospedados->count() }}</span>
        </h6>
      </div>

      <div class="card-body p-3">
        <ul class="list-group">
          @forelse($hospedesHospedados as $h)
            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
              <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                  <i class="fas fa-user text-white"></i>
                </div>

                <div class="d-flex flex-column">
                  <h6 class="mb-0 text-sm fw-bold">{{ $h->nome }}</h6>
                  <span class="text-xs">
                    Quarto #{{ $h->quarto->numero ?? '–' }} •
                    Entrada: {{ \Carbon\Carbon::parse($h->data_entrada)->format('d/m') }}
                  </span>
                </div>
              </div>

              <div class="d-flex">
                {{-- Botão de check‑out direto do card --}}
                <button class="btn btn-link btn-sm text-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCheckoutHospede-{{ $h->id }}">
                  <i class="fas fa-door-open"></i>
                </button>
              </div>
            </li>
          @empty
            <li class="list-group-item border-0 text-center text-muted py-4">
              Nenhum hóspede hospedado.
            </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

</div>


    </div>
  </main>

  @include('dashboard.charts')
  @include('layouts.customise')

  <!--   Core JS Files   -->
  @include('components.js')

</body>

</html>