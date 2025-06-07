<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .box { border: 1px solid #000; padding: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Recibo Nº {{ $fatura->numero }}</h2>
        <p>Data: {{ $fatura->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="box">
        <p><strong>Valor:</strong> €{{ number_format($fatura->valor, 2, ',', '.') }}</p>
        <p><strong>Descrição:</strong> {{ $fatura->descricao }}</p>
    </div>


</body>
</html>
