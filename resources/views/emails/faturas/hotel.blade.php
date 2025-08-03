<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura - Reserva de Hotel</title>
</head>
<body>
    <h2>Olá {{ $fatura->nome_cliente }},</h2>
    <p>Segue anexa a sua fatura-recibo referente à reserva realizada no estabelecimento <strong>{{ $empresa->nome_empresa }}</strong>.</p>

    <p><strong>Número da Fatura:</strong> {{ $fatura->numero }}</p>
    <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y H:i') }}</p>
    <p><strong>Nome do Cliente:</strong> {{ $fatura->nome_cliente }}</p>
    <p><strong>NIF:</strong> {{ $fatura->nif }}</p>
    <p><strong>Telefone:</strong> {{ $fatura->telefone ?? 'Não informado' }}</p>

    <h3>Detalhes da Reserva:</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Quarto</th>
                <th>Tipo</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Noites/Horas</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Quarto {{ $fatura->reserva->quarto->numero ?? 'Excluido' }}</td>
                <td>{{ $fatura->reserva->quarto->tipo->nome ?? 'Excluido' }}</td>
                <td>{{ \Carbon\Carbon::parse($fatura->reserva->data_entrada)->format('d/m/Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($fatura->reserva->data_saida)->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($fatura->reserva->numero_noites, 1) }}</td>
                <td>{{ number_format($fatura->reserva->valor_total, 2, ',', '.') }} KZ</td>
            </tr>
        </tbody>
    </table>

    <h3>Totais da Fatura:</h3>
    <p><strong>Total:</strong> {{ number_format($fatura->total, 2, ',', '.') }} KZ</p>
    <p><strong>Valor Entregue:</strong> {{ number_format($fatura->valor_entregue, 2, ',', '.') }} KZ</p>
    <p><strong>Troco:</strong> {{ number_format($fatura->troco, 2, ',', '.') }} KZ</p>
    <p><strong>Método de Pagamento:</strong> {{ $fatura->metodo_pagamento }}</p>

    <p>Agradecemos pela sua preferência!</p>

    <p>Atenciosamente, <br><strong>{{ $empresa->nome_empresa }}</strong></p>
</body>
</html>
