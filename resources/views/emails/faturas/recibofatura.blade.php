{{-- resources/views/emails/faturas/recibo.blade.php --}}
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura de Reserva</title>
</head>
<body>
    <h2>Fatura-Recibo</h2>
    <p>Prezado(a) {{ $reserva->cliente_nome }},</p>
    <p>Segue em anexo sua fatura referente à reserva efetuada.</p>
    <p>Detalhes da Fatura:</p>
    <ul>
        <li>Número: {{ $fatura->numero }}</li>
        <li>Valor Total: {{ number_format($fatura->total, 2, ',', '.') }} Kz</li>
        <li>Data de Emissão: {{ $fatura->data_emissao->format('d/m/Y') }}</li>
    </ul>
    <p>Obrigado pela preferência!</p>
</body>
</html>