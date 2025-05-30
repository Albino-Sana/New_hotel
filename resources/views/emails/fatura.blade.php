
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura do Hóspede</title>        
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo {
        max-width: 150px;
    }
    .details {
        list-style-type: none;
        padding: 0;
    }
    .details li {
        margin-bottom: 10px;
    }
    .label {
        font-weight: bold;
    }
    .value {
        margin-left: 10px;
    }
    .total {
        color: #4a6ee0; /* Cor do total */
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 0.9em;
        color: #777;
    }
    .footer a {
        color: #4a6ee0; /* Cor do link */
        text-decoration: none;
    }
    .footer a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('assets/img/dat-sys-3D.png') }}" alt="Logo DAT-SYS" class="logo">
        <h2>Fatura do Hospede</h2>
    </div>
    
    <ul class="details">
        <li><span class="label">Nome:</span><span class="value">{{ $hospede->nome }}</span></li>
        <li><span class="label">Número do Quarto:</span><span class="value">{{ $hospede->quarto->numero }}</span></li>
        <li><span class="label">Data de Entrada:</span><span class="value">{{ \Carbon\Carbon::parse($hospede->data_entrada)->format('d/m/Y') }}</span></li>
        <li><span class="label">Data de Saída:</span><span class="value">{{ \Carbon\Carbon::parse($hospede->data_saida)->format('d/m/Y') }}</span></li>
        <li><span class="label">Valor Total:</span><span class="value total">{{ number_format($hospede->valor_total, 2, ',', '.') }} €</span></li>
    </ul>

    <div class="footer">
        Obrigado por escolher o Hotel Exemplo!<br>
        Se precisar de ajuda, entre em contato conosco.
    </div>
  
        
    </body>