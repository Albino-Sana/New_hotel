<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura-Recibo de Hospedagem #{{ $fatura->numero }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .header {
            background: linear-gradient(135deg, #4a6bff, #2a52d1);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        
        .message {
            margin-bottom: 25px;
            color: #555;
        }
        
        .invoice-card {
            background: #f9fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #4a6bff;
        }
        
        .invoice-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .detail-item {
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: 500;
            color: #7f8c8d;
            display: block;
            margin-bottom: 3px;
            font-size: 14px;
        }
        
        .detail-value {
            font-weight: 500;
            color: #2c3e50;
            font-size: 15px;
        }
        
        .total-amount {
            font-size: 20px;
            font-weight: 700;
            color: #2a52d1;
            margin-top: 10px;
        }
        
        .footer {
            background-color: #f0f2f5;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #7f8c8d;
        }
        
        .contact-info {
            margin-top: 10px;
            font-size: 14px;
        }
        
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #7f8c8d;
        }
        
        .btn {
            display: inline-block;
            background: #4a6bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin-top: 15px;
        }
        
        .attachments {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }
        
        .attachment-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .attachment-icon {
            margin-right: 10px;
            color: #4a6bff;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $empresa->nome_empresa ?? 'HOTELARIA' }}</h1>
        </div>
        
        <div class="content">
            <div class="greeting">Prezado(a) {{ $hospede->nome }},</div>
            
            <div class="message">
                Segue em anexo sua fatura-recibo referente à hospedagem. Caso necessite de qualquer informação adicional, não hesite em nos contactar.
            </div>
            
            <div class="invoice-card">
                <div class="invoice-title">Detalhes da Fatura</div>
                
                <div class="invoice-details">
                    <div>
                        <div class="detail-item">
                            <span class="detail-label">Número da Fatura</span>
                            <span class="detail-value">#{{ $fatura->numero }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Data de Emissão</span>
                          <span class="detail-value">{{ \Carbon\Carbon::parse($fatura->data_emissao)->format('d/m/Y') }}</span>

                        </div>
                    </div>
                    <div>
                        <div class="detail-item">
                            <span class="detail-label">Método de Pagamento</span>
                            <span class="detail-value">{{ $fatura->metodo_pagamento ?? 'Não especificado' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value" style="color: {{ $fatura->pago ? '#2ecc71' : '#e74c3c' }}">
                                {{ $fatura->pago ? 'Pago' : 'Pendente' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="total-amount">{{ number_format($fatura->total, 2, ',', '.') }} Kz</div>
            </div>
            
            <div class="attachments">
                <div class="attachment-item">
                    <span class="attachment-icon">📎</span>
                    <span>Fatura-Recibo_{{ $fatura->numero }}.pdf</span>
                </div>
            </div>
            
            <div class="signature">
                <p>Atenciosamente,</p>
                <p>Equipe {{ $empresa->nome_empresa ?? 'HOTELARIA' }}</p>
            </div>
        </div>
        
        <div class="footer">
            <div>{{ $empresa->endereco_empresa ?? 'Endereço não disponível' }}</div>
            <div class="contact-info">
                {{ $empresa->telefone ?? 'N/D' }} | {{ $empresa->email ?? 'N/D' }}
            </div>
            <div style="margin-top: 15px;">
                © {{ date('Y') }} {{ $empresa->nome_empresa ?? 'HOTELARIA' }}. Todos os direitos reservados.
            </div>
        </div>
    </div>
</body>
</html>