<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura de Pagamento</title>
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
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 18px;
            color: #7f8c8d;
        }
        
        .company-info, .client-info {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        
        .company-info {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }
        
        .client-info {
            background-color: #f1f8fe;
            border-left: 4px solid #2980b9;
        }
        
        .info-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
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
        
        .payment-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .payment-method {
            font-weight: bold;
            color: #27ae60;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #7f8c8d;
            text-align: center;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            margin-top: 20px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
        }
        
        .signature-area {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #999;
            text-align: center;
            font-style: italic;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h1>{{ $empresa->nome_empresa ?? 'HOTELARIA' }}</h1>
                <p>Sistema de Gestão Hoteleira</p>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FATURA-RECIBO</div>
                <div class="invoice-number">N.º {{ $pagamento->id }}</div>
                <div><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d-m-Y') }}</div>
            </div>
        </div>
        
        <div class="company-info">
            <div class="info-title">Dados do Estabelecimento</div>
            <div><strong>Nome:</strong> {{ $empresa->nome_empresa ?? 'Não definido' }}</div>
            <div><strong>Endereço:</strong> {{ $empresa->endereco_empresa ?? 'Não definido' }}, {{ $empresa->numero_edificio ?? '' }} {{ $empresa->nome_rua ?? '' }}, {{ $empresa->cidade ?? '' }}, {{ $empresa->provincia ?? '' }}</div>
            <div><strong>Telefone:</strong> {{ $empresa->telefone ?? 'N/D' }} | <strong>E-mail:</strong> {{ $empresa->email ?? 'N/D' }}</div>
            <div><strong>Contribuinte (NIF):</strong> {{ $empresa->numero_registo_fiscal ?? 'N/D' }}</div>
            <div><strong>Número de Validação do Software:</strong> {{ $empresa->numero_validacao_software ?? 'N/D' }}</div>
        </div>
        
        <div class="client-info">
            <div class="info-title">Cliente</div>
            @if ($pagamento->checkin)
                <div><strong>Nome:</strong> {{ $pagamento->checkin->reserva->cliente_nome ?? 'N/D' }}</div>
                <div><strong>NIF:</strong> {{ $pagamento->nif_cliente ?? 'N/D' }}</div>
            @elseif ($pagamento->hospede)
                <div><strong>Nome:</strong> {{ $pagamento->hospede->nome }}</div>
                <div><strong>NIF:</strong> {{ $pagamento->nif_cliente ?? 'N/D' }}</div>
            @else
                <div><strong>Cliente não identificado</strong></div>
            @endif
        </div>
        
        <table class="details-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data</th>
                    <th>Método</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pagamento #{{ $pagamento->id }}</td>
                    <td>{{ number_format($pagamento->valor, 2, ',', '.') }} KZ</td>
                    <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') }}</td>
                    <td>{{ $pagamento->metodo_pagamento }}</td>
                    <td>{{ ucfirst($pagamento->status_pagamento ?? 'confirmado') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            <table class="totals-table">
                <tr class="grand-total">
                    <th>TOTAL PAGO</th>
                    <td>{{ number_format($pagamento->valor, 2, ',', '.') }} KZ</td>
                </tr>
            </table>
        </div>
        
        <div class="payment-info">
            <p><em>Documento pago nesta data: {{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d-m-Y') }}</em></p>
            <p><strong>Meios de pagamento utilizados</strong></p>
            <p><span class="payment-method">{{ $pagamento->metodo_pagamento }}</span> - {{ number_format($pagamento->valor, 2, ',', '.') }} KZ</p>
            @if($pagamento->descricao)
                <p><strong>Observações:</strong> {{ $pagamento->descricao }}</p>
            @endif
            <p><strong>Comentário:</strong> {{ $empresa->comentario_cabecalho ?? 'Obrigado pela sua preferência!' }}</p>
        </div>
        
        <div class="signature-area">
            <p>Assinatura do Responsável</p>
            <div style="height: 50px;"></div>
            <p>_________________________________________</p>
        </div>
        
        <div class="qr-code">
            [QR CODE]
        </div>
        
        <div class="footer">
            <p>Processado por {{ $empresa->id_produto ?? 'Sistema de Hotelaria' }} - Versão {{ $empresa->versao_produto ?? 'N/D' }}</p>
            <p>Este documento é gerado eletronicamente</p>
        </div>
    </div>
</body>
</html>