@component('mail::message')
<style>
    .header {
        background-color: #2c3e50;
        padding: 20px;
        text-align: center;
        border-radius: 5px 5px 0 0;
    }
    .header h1 {
        color: #ffffff;
        margin: 0;
        font-size: 24px;
    }
    .content {
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 0 0 5px 5px;
    }
    .invoice-details {
        background-color: white;
        border-radius: 5px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .detail-row {
        display: flex;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .detail-label {
        font-weight: bold;
        color: #2c3e50;
        width: 150px;
    }
    .detail-value {
        color: #34495e;
    }
    .footer {
        margin-top: 30px;
        text-align: center;
        color: #7f8c8d;
        font-size: 12px;
    }
    .logo {
        max-width: 150px;
        margin-bottom: 20px;
    }
    .thank-you {
        font-size: 16px;
        color: #27ae60;
        margin-top: 20px;
        font-weight: bold;
    }
</style>

<div class="header">
    <h1>Recibo de Hospedagem</h1>
</div>

<div class="content">
    <img src="{{ asset('images/logo-hotel.png') }}" alt="Hotel Logo" class="logo">
    
    <p>Prezado hóspede,</p>
    
    <p>Segue em anexo o recibo detalhado referente à sua estadia em nosso hotel.</p>
    
    <div class="invoice-details">
        <div class="detail-row">
            <span class="detail-label">Número da Fatura:</span>
            <span class="detail-value">{{ $fatura->numero }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Valor Total:</span>
            <span class="detail-value">€{{ number_format($fatura->valor, 2, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Data de Emissão:</span>
            <span class="detail-value">{{ $fatura->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
    
    <p class="thank-you">Obrigado por escolher nosso hotel!</p>
    
    <div class="footer">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>Hotel Exemplo • Rua Principal, 123 • Lisboa • Portugal</p>
        <p>Tel: +351 123 456 789 • Email: info@hotelexemplo.com</p>
    </div>
</div>
@endcomponent