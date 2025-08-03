<?php
namespace App\Mail;

use App\Models\FaturaRecibo;

use App\Models\Empresa;
use App\Models\Reserva;
use App\Models\Hospede;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class FaturaReciboMail extends Mailable
{
    use Queueable, SerializesModels;

   public $fatura;
    public $empresa;

    public function __construct($fatura, $empresa)
    {
        $this->fatura = $fatura;
        $this->empresa = $empresa;
    }

    public function build()
    {
        return $this->view('pdf.faturas.pagamento')
            ->subject('Fatura Recibo')
            ->with([
                'fatura' => $this->fatura,
                'empresa' => $this->empresa,
            ]);
    }

}


