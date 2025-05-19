
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
        $titulo = 'Configurações da Empresa';
        @endphp
        @include('layouts.navbar', ['titulo' => $titulo])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="container mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h4 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>{{ $titulo }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nome da Empresa</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.nome') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">NIF</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.nif') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Licença Comercial</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.licenca_comercial') }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ config('empresa.email') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Telefone</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.telefone') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Telefone Secundário</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.telefone_secundario') }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Endereço</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.endereco') }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.cidade') }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Província</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.provincia') }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">País</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.pais') }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Banco</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.banco') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Conta Bancária</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.conta_bancaria') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">IBAN</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.iban') }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" value="{{ config('empresa.website') }}" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Notas da Fatura</label>
                                        <textarea class="form-control" rows="3" readonly>{{ config('empresa.notas_fatura') }}</textarea>
                                    </div>

                                    <div class="col-md-4 mb-3 text-center">
                                        <label class="form-label">Logotipo</label>
                                        <div>
                                            <img src="{{ asset('assets/img/dat-sys-3D.png') }}" alt="Logo da empresa" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                        </div>
                                    </div>
                                </div>
                            </form>
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