<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        DAT Hotelaria --- Saft
    </title>

    @include('components.css')

</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('layouts.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @php
        $titulo = 'Gerar SAFT';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <h3>Gerar Ficheiro SAFT</h3>

                        <form method="POST" action="{{ route('saft.gerar') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="inicio" class="form-label">Data de Início</label>
                                <input type="date" name="inicio" id="inicio" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="fim" class="form-label">Data de Fim</label>
                                <input type="date" name="fim" id="fim" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Gerar SAFT</button>
                        </form>
                    </div>
                </div>

            </div>
            @if(session('saft_file'))
            <a href="{{ route('download.saft', ['filename' => session('saft_file')]) }}" class="btn btn-success mt-3">
                📥 Baixar Ficheiro SAFT
            </a>
            @endif

        </div>

    </main>
    @include('layouts.customise')
    <!--   Core JS Files   -->
    @include('components.js')

</body>

</html>