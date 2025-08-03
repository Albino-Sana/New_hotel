{{-- resources/views/pdf/faturas/hospede.blade.php --}}
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Fatura - Hospedagem</h1>
    <p><strong>Cliente:</strong> {{ $hospede->nome }}</p>
    <p><strong>Fatura Nº:</strong> {{ $fatura->numero }}</p>
    <p><strong>Data:</strong> {{ $fatura->data_emissao->format('d/m/Y') }}</p>
    <p><strong>Valor:</strong> {{ number_format($fatura->total, 2, ',', '.') }} Kz</p>
</body>
</html>