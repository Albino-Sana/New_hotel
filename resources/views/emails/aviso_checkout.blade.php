<!DOCTYPE html>
<html>
<head>
    <title>Atualização da Sua Estadia</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f8f8; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Atualização da Sua Estadia</h2>
        </div>
        <div class="content">
            <p>Olá, {{ $cliente }}!</p>
            <p>{{ $mensagem }}</p>
            <p><strong>Data de Entrada:</strong> {{ \Carbon\Carbon::parse($entrada)->format('d/m/Y') }}</p>
            <p><strong>Data de Saída:</strong> {{ \Carbon\Carbon::parse($saida)->format('d/m/Y') }}</p>
            <p>Obrigado por escolher nossos serviços!</p>
        </div>
        <div class="footer">
            <p>Hotelaria - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>