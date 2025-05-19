<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificação de Estadia</title>
</head>
<body>
    <h2>Olá {{ $reserva->cliente_nome ?? 'Cliente' }},</h2>

    <p>Estás no <strong>terceiro dia</strong> da sua estadia.</p>
    <p>Faltam <strong>dois dias</strong> para o checkout.</p>

    <p>Qualquer dúvida, estamos à disposição.</p>

    <p>Atenciosamente,<br>
    Equipe do Hotel</p>
</body>
</html>
