<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura-Recibo de Pagamento #{{ $fatura->numero }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
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
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #7f8c8d;
        }
        
        .company-info,
        .client-info {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        
        .company-info {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }
        
        .client-info {
            background-color: #f8f9fa;
            border-left: 4px solid #e67e22;
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
            padding: 10px;
            text-align: left;
        }
        
        .details-table td {
            padding: 10px;
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
        
        .totals-table th,
        .totals-table td {
            padding: 10px;
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
            margin: 20px auto;
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
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-paid {
            background-color: #2ecc71;
            color: white;
        }
        
        .status-pending {
            background-color: #f39c12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h3>{{ $empresa->nome_empresa ?? 'HOTELARIA' }}</h3>
                <p>Sistema de Gestão Hoteleira</p>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FATURA-RECIBO</div>
                <div class="invoice-number">Pagamento #{{ $fatura->numero }}</div>
                <div><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y H:i') }}</div>
            
            </div>
        </div>

        <div class="company-info">
            <div class="info-title">Dados do Estabelecimento</div>
            <div><strong>Nome:</strong> {{ $empresa->nome_empresa ?? 'Não definido' }}</div>
            <div><strong>Endereço:</strong> {{ $empresa->endereco_empresa ?? 'Não definido' }}</div>
            <div><strong>Telefone:</strong> {{ $empresa->telefone ?? 'N/D' }} | <strong>E-mail:</strong> {{ $empresa->email ?? 'N/D' }}</div>
            <div><strong>Contribuinte (NIF):</strong> {{ $empresa->numero_registo_fiscal ?? '9999999' }}</div>
        </div>

        <div class="client-info">
            <div class="info-title">Dados do Cliente</div>
            <div><strong>Nome:</strong> {{ $fatura->nome_cliente }}</div>
            
            <div><strong>NIF:</strong> {{ $fatura->nif ?? '---' }}</div>
            <div><strong>Telefone:</strong> {{ $fatura->telefone ?? '---' }}</div>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Detalhes</th>
                    <th style="text-align: right">Valor (KZ)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pagamento</td>
                    <td>Método: {{ $fatura->metodo_pagamento ?? 'Método não informado' }}</td>
                    <td style="text-align: right">{{ number_format($fatura->total, 2, ',', '.') }}</td>
                </tr>
                @if($fatura->reserva)
                <tr>
                    <td>Reserva</td>
                    <td colspan="2">
                        Quarto {{ optional($fatura->reserva->quarto)->numero ?? 'N/D' }} - 
                        {{ optional($fatura->reserva->quarto->tipo)->nome ?? 'N/D' }} | 
                        {{ \Carbon\Carbon::parse($fatura->reserva->data_entrada)->format('d/m/Y') }} a 
                        {{ \Carbon\Carbon::parse($fatura->reserva->data_saida)->format('d/m/Y') }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="totals">
            <table class="totals-table">
                <tr>
                    <th>Total:</th>
                    <td>{{ number_format($fatura->total, 2, ',', '.') }} KZ</td>
                </tr>
                <tr>
                    <th>Valor Entregue:</th>
                    <td>{{ number_format($fatura->valor_entregue, 2, ',', '.') }} KZ</td>
                </tr>
                <tr class="grand-total">
                    <th>Troco:</th>
                    <td>{{ number_format($fatura->troco, 2, ',', '.') }} KZ</td>
                </tr>
            </table>
        </div>

        <div class="payment-info">
            <p><strong>Método de Pagamento:</strong> {{ $fatura->metodo_pagamento ?? 'Método não informado' }}</p>
            <p><strong>Operador:</strong> {{ optional($fatura->operador)->name ?? 'N/D' }}</p>
            <p><em>Documento registrado em: {{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y H:i') }}</em></p>
        </div>

        <div class="qr-code">
            [QR CODE PARA VALIDAÇÃO]
        </div>

        <div class="signature-area">
            <p>Assinatura do Responsável</p>
            <div style="height: 50px;"></div>
            <p>_________________________________________</p>
        </div>

        <div class="footer">
            <p>Processado por {{ $empresa->nome_empresa ?? 'Sistema de Hotelaria' }}</p>
            <p>Este documento é gerado eletronicamente e não requer assinatura manuscrita</p>
            <p>{{ $empresa->comentario_cabecalho ?? 'Obrigado pela sua preferência!' }}</p>
        </div>
    </div>
</body>
</html>