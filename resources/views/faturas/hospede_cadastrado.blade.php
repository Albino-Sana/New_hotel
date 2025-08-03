<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Hospedagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 6px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 25px;
            text-align: center;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .info, .details {
            margin-bottom: 25px;
        }

        .info strong {
            display: inline-block;
            width: 150px;
            color: #555;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details th, .details td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .details th {
            background-color: #f8f8f8;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #4a6ee0;
        }

        .footer {
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 13px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('assets/img/dat-sys-3D.png') }}" alt="Logo do Hotel">
        <h2>Recibo de Hospedagem</h2>
    </div>

    <div class="info">
        <p><strong>Cliente:</strong> {{ $fatura->nome_cliente }}</p>
        <p><strong>Telefone:</strong> {{ $fatura->telefone ?? '---' }}</p>
        <p><strong>NIF:</strong> {{ $fatura->nif ?? '---' }}</p>
        <p><strong>Método de Pagamento:</strong> {{ $fatura->metodo_pagamento ?? '---' }}</p>
        <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y') }}</p>
    </div>

    <div class="details">
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Detalhes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Quarto</td>
                    <td>Quarto {{ $hospede->quarto->numero ?? '---' }} - {{ $hospede->quarto->tipo->nome ?? '' }}</td>
                </tr>
                <tr>
                    <td>Data de Entrada</td>
                    <td>{{ \Carbon\Carbon::parse($hospede->data_entrada)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Data de Saída</td>
                    <td>{{ \Carbon\Carbon::parse($hospede->data_saida)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Nº de Pessoas</td>
                    <td>{{ $hospede->numero_pessoas }}</td>
                </tr>
                @if($hospede->observacoes)
                    <tr>
                        <td>Observações</td>
                        <td>{{ $hospede->observacoes }}</td>
                    </tr>
                @endif
                <tr>
                    <td><strong>Total a Pagar</strong></td>
                    <td class="total">{{ number_format($fatura->total, 2, ',', '.') }} KZ</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Obrigado por se hospedar conosco.</p>
        <p>Hotelaria - Sistema de Gestão</p>
    </div>
</div>
</body>
</html>
