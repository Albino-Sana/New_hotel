<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Hospedagem</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-top: 5px solid #4a6ee0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
        }
        ul.details {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }
        ul.details li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        ul.details li:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #2c3e50;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            color: #4a6ee0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #4a6ee0;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Substitua pelo caminho do seu logo -->
            <img src="{{ asset('assets/img/dat-sys-3D.png') }}" alt="Logo do Hotel" class="logo">
            <h2>Olá {{ $hospede->nome }},</h2>
            <p>Sua estadia foi confirmada com sucesso!</p>
        </div>

        <p>Estamos felizes em tê-lo como nosso hóspede. Abaixo estão os detalhes da sua reserva:</p>

        <ul class="details">
            <li>
                <span class="label">Quarto:</span>
                <span class="value">{{ $hospede->quarto->numero ?? 'N/A' }}</span>
            </li>
            <li>
                <span class="label">Data de Entrada:</span>
                <span class="value">{{ $hospede->data_entrada->format('d/m/Y') }}</span>
            </li>
            <li>
                <span class="label">Data de Saída:</span>
                <span class="value">{{ $hospede->data_saida->format('d/m/Y') }}</span>
            </li>
            <li>
                <span class="label">Duração da Estadia:</span>
                <span class="value">{{ $dias }} dia(s)</span>
            </li>
            <li>
                <span class="label">Total a Pagar:</span>
                <span class="value total">{{ number_format($hospede->valor_a_pagar, 2, ',', '.') }} Kz</span>
            </li>
        </ul>

        <p>Para qualquer dúvida ou informação adicional, nossa equipe está à sua disposição.</p>

        <center>
            <a href="https://exemplo.com/contato" class="button">Fale Conosco</a>
        </center>

        <div class="footer">
            <p>Atenciosamente,<br>Equipe do Hotel</p>
            <p>Telefone: +244 123 456 789<br>
            Email: contato@hotel.com</p>
        </div>
    </div>
</body>
</html>