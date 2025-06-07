<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibo - {{ $fatura->numero }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info, .totais { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info td, .totais td { padding: 4px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Recibo / Fatura</h2>
        <p><strong>Documento:</strong> {{ $fatura->tipo_documento }} {{ $fatura->serie }}/{{ $fatura->numero }}</p>
        <p><strong>Data de Emissão:</strong> {{ $fatura->data_emissao }}</p>
    </div>

    <table class="info">
        <tr>
            <td><strong>Cliente:</strong> {{ $fatura->nome_cliente }}</td>
            <td><strong>NIF:</strong> {{ $fatura->nif }}</td>
        </tr>
        <tr>
            <td><strong>Telefone:</strong> {{ $fatura->telefone }}</td>
            <td><strong>Método de Pagamento:</strong> {{ $fatura->metodo_pagamento ?? '---' }}</td>
        </tr>
    </table>

    <table class="totais">
        <tr>
            <td><strong>Total:</strong></td>
            <td>{{ number_format($fatura->total, 2, ',', '.') }} Kz</td>
        </tr>
        <tr>
            <td><strong>Valor Entregue:</strong></td>
            <td>{{ number_format($fatura->valor_entregue, 2, ',', '.') }} Kz</td>
        </tr>
        <tr>
            <td><strong>Troco:</strong></td>
            <td>{{ number_format($fatura->troco, 2, ',', '.') }} Kz</td>
        </tr>
    </table>

    <p><strong>Estado do Documento:</strong> {{ $fatura->estado_documento == 'N' ? 'Normal' : 'Anulado' }}</p>
</body>
</html>
