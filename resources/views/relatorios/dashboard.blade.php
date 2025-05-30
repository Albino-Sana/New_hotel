<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Check-ins</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
        }
        
        .container {
            max-width: 100%;
            padding: 0;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        
        .logo {
            width: 150px;
        }
        
        .report-info {
            text-align: right;
        }
        
        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .report-date {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background-color: #2c3e50;
            color: white;
            padding: 8px;
            text-align: left;
        }
        
        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        .details-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }
        
        .totals-table th, .totals-table td {
            padding: 8px;
            text-align: right;
        }
        
        .totals-table th {
            background-color: #2c3e50;
            color: white;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 14px;
            background-color: #ecf0f1 !important;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #7f8c8d;
            text-align: center;
        }
        
        .summary {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h1>HOTEL </h1>
                <p>Sistema de Gestão Hoteleira</p>
            </div>
            <div class="report-info">
                <div class="report-title">{{ $dados['titulo'] }}</div>
                <div class="report-date"><strong>Data de Emissão:</strong> 24-05-2025</div>
            </div>
        </div>

        <div class="summary">
            <p><strong>Período:</strong> {{ ucfirst(str_replace('1', '1 ', $periodo)) }}</p>
        </div>

        <h2>Detalhamento</h2>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Data de Entrada</th>
                    <th>Número de Reservas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['labels'] as $index => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $dados['data'][$index] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Detalhes das Reservas</h2>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Data de Entrada</th>
                    <th>Data de Saída</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['reservas'] as $reservas)
                    <tr>
                        <td>{{ $reservas->cliente_nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservas->data_entrada)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservas->data_saida)->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($reservas->valor_total, 2, ',', '.') }} Kz</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table class="totals-table">
                <tr>
                    <th>Total de  Reservas</th>
                    <td>{{ $dados['totalReservas'] }}</td>
                </tr>
                <tr class="grand-total">
                    <th>Valor Total</th>
                    <td>{{ number_format($dados['valorTotal'], 2, ',', '.') }} Kz</td>
                </tr>
            </table>
        </div>

        <div class="summary">
            <p><strong>Resumo:</strong></p>
            <p>Período de {{ $dados['labels'][0] }} a {{ $dados['labels'][count($dados['labels']) - 1] }}</p>
        </div>

        <div class="footer">
            <p>Processado por HOTEL POS - Sistema de Gestão Hoteleira</p>
            <p>Este documento é gerado eletronicamente e dispensa carimbo e assinatura nos termos do artigo 5º do Decreto-Lei n.º 28/2019 de 15 de fevereiro</p>
        </div>
    </div>
</body>
</html>