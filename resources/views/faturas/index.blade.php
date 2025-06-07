



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: none;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 1rem 1.5rem;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom-width: 1px;
    }
    
    .table td, .table th {
        white-space: nowrap;
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.65em;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.25rem;
    }
    
    .pagination {
        margin-bottom: 0;
    }
</style>
<body>
    <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Faturas Emitidas</h6>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table class="table align-items-center mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check-in ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Valor</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descrição</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faturas as $fatura)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $fatura->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $fatura->checkin_id }}</p>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-xs font-weight-bold">€{{ number_format($fatura->valor, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $fatura->tipo == 'reserva' ? 'info' : 'success' }}">
                                            {{ ucfirst($fatura->tipo) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $fatura->descricao }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold">{{ $fatura->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-sm bg-gradient-info mb-0" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm bg-gradient-primary mb-0" title="Imprimir">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-sm">
                            Mostrando {{ $faturas->firstItem() }} a {{ $faturas->lastItem() }} de {{ $faturas->total() }} registros
                        </div>
                        <div>
                            {{ $faturas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>