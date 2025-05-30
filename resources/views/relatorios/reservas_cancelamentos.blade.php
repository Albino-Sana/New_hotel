<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>DAT Hotelaria -- Relatórios</title>

    @include('components.css')
</head>
<body class="g-sidenav-show bg-gray-100">
    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @php
            $titulo = 'Relatório de Reservas e Cancelamentos';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

      <div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-capitalize" id="grafico-titulo">Reservas e Cancelamentos no Período</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold" id="variacao-texto">Carregando...</span>
                        </p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary periodo-btn active" data-periodo="7dias">7 Dias</button>
                        <button type="button" class="btn btn-sm btn-outline-primary periodo-btn" data-periodo="30dias">30 Dias</button>
                        <button type="button" class="btn btn-sm btn-outline-primary periodo-btn" data-periodo="12meses">12 Meses</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-pdf">Exportar PDF</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="reservasCancelamentosChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>

    @include('relatorios.grafico_reservas_cancelamentos')
    @include('layouts.customise')
    @include('components.js')
</body>
</html>