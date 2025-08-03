<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura-Recibo #{{ $fatura->numero }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 700px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: 500;
            color: #7f8c8d;
            display: inline-block;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border-radius: 6px;
            overflow: hidden;
        }

        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .totals-table {
            width: 60%;
            margin-left: auto;
            margin-top: 20px;
        }

        .totals-table td {
            padding: 12px;
            font-weight: 500;
        }

        .grand-total {
            font-weight: 700;
            font-size: 16px;
            background-color: #ecf0f1 !important;
        }

        .signature-area {
            margin-top: 50px;
            text-align: center;
        }

        .signature-line {
            display: inline-block;
            width: 250px;
            border-top: 1px dashed #7f8c8d;
            margin-top: 40px;
            padding-top: 10px;
            color: #7f8c8d;
            font-style: italic;
        }

        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 20px auto;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-paid {
            background-color: #2ecc71;
            color: white;
        }

        .badge-pending {
            background-color: #f39c12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $empresa->nome_empresa ?? 'HOTELARIA' }}</h1>
            <p>{{ $empresa->endereco_empresa ?? 'Endereço não disponível' }}</p>
        </div>

        <div class="content">
            <div class="section">
                <div class="invoice-title">FATURA-RECIBO</div>
                <div class="invoice-number">Nº {{ $fatura->numero }}</div>
                <div class="info-grid">
                    <div>
                        <div class="info-item">
                            <span class="info-label">Data de Emissão:</span>
                            {{ \Carbon\Carbon::parse($fatura->created_at)->format('d/m/Y H:i') }}
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="badge badge-{{ $fatura->pago ? 'paid' : 'pending' }}">
                                {{ $fatura->pago ? 'PAGO' : 'PENDENTE' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="info-item">
                            <span class="info-label">NIF:</span>
                            {{ $empresa->numero_registo_fiscal ?? '999999999' }}
                        </div>
                        <div class="info-item">
                            <span class="info-label">Contato:</span>
                            {{ $empresa->telefone ?? 'N/D' }} | {{ $empresa->email ?? 'N/D' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Dados do Cliente</div>
                <div class="info-grid">
                    <div>
                        <div class="info-item">
                            <span class="info-label">Nome:</span>
                            {{ $reserva->cliente_nome ?? 'N/D' }}
                        </div>
                        <div class="info-item">
                            <span class="info-label">Documento:</span>
                            {{ $reserva->cliente_documento ?? 'N/D' }}
                        </div>
                    </div>
                    <div>
                        @if($reserva->cliente_email)
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            {{ $reserva->cliente_email }}
                        </div>
                        @endif
                        @if($reserva->cliente_telefone)
                        <div class="info-item">
                            <span class="info-label">Telefone:</span>
                            {{ $reserva->cliente_telefone }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Detalhes da Reserva</div>
                <table>
                    <tbody>
                        <tr>
                            <th>Quarto</th>
                            <td>Quarto {{ $reserva->quarto->numero ?? 'N/D' }} - {{ $reserva->quarto->tipo->nome ?? 'Tipo N/D' }}</td>
                        </tr>
                        <tr>
                            <th>Data de Entrada</th>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_entrada)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Data de Saída</th>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_saida)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Nº de Pessoas</th>
                            <td>{{ $reserva->numero_pessoas }}</td>
                        </tr>
                        @if($reserva->observacoes)
                        <tr>
                            <th>Observações</th>
                            <td>{{ $reserva->observacoes }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Pagamento</div>
                <table class="totals-table">
                    <tbody>
                        <tr>
                            <td>Total:</td>
                            <td class="text-right">{{ number_format($fatura->total, 2, ',', '.') }} KZ</td>
                        </tr>
                        <tr>
                            <td>Valor Entregue:</td>
                            <td class="text-right">{{ number_format($fatura->valor_entregue, 2, ',', '.') }} KZ</td>
                        </tr>
                        <tr class="grand-total">
                            <td>Troco:</td>
                            <td class="text-right">{{ number_format($fatura->troco, 2, ',', '.') }} KZ</td>
                        </tr>
                        <tr>
                            <td>Método de Pagamento:</td>
                            <td class="text-right">{{ $fatura->metodo_pagamento }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="qr-code">
                [QR CODE PARA PAGAMENTO]
            </div>

            <div class="signature-area">
                <div class="signature-line">Assinatura do Responsável</div>
            </div>
        </div>

        <div class="footer">
            <p>Processado por {{ $empresa->nome_empresa ?? 'Sistema de Hotelaria' }} - Versão {{ $empresa->versao_produto ?? 'N/D' }}</p>
            <p>Documento gerado eletronicamente em {{ now()->format('d/m/Y H:i') }}</p>
            <p>{{ $empresa->comentario_cabecalho ?? 'Obrigado pela sua preferência!' }}</p>
        </div>
    </div>
</body>
</html>