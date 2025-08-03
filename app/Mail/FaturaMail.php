<?php
namespace App\Mail;

use App\Models\Fatura;

use App\Models\Empresa;
use App\Models\Reserva;
use App\Models\Hospede;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class FaturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fatura;
    public $empresa;
    public $reserva;
    public $hospede;

    /**
     * Cria a instância do mailable.
     */
    public function __construct(Fatura $fatura, Empresa $empresa)
    {
        $this->fatura = $fatura;
        $this->empresa = $empresa;

        // Verifica se é reserva ou hóspede associado à fatura
        $this->reserva = $fatura->reserva_id ? Reserva::find($fatura->reserva_id) : null;
        $this->hospede = $fatura->hospede_id ? Hospede::find($fatura->hospede_id) : null;
    }

    /**
     * Constrói o e-mail.
     */
    public function build()
    {
        if ($this->reserva) {
            return $this->subject('Fatura da sua Reserva')
                        ->view('emails.faturas.recibo')
                        ->with([
                            'fatura' => $this->fatura,
                            'empresa' => $this->empresa,
                            'reserva' => $this->reserva,
                        ]);
        }

        if ($this->hospede) {
            return $this->subject('Fatura da sua Estadia')
                        ->view('emails.faturas.recibohospede')
                        ->with([
                            'fatura' => $this->fatura,
                            'empresa' => $this->empresa,
                            'hospede' => $this->hospede,
                        ]);
        }

        // Caso nenhum associado
        return $this->subject('Fatura')
                    ->view('emails.faturas.base')
                    ->with([
                        'fatura' => $this->fatura,
                        'empresa' => $this->empresa,
                    ]);
    }
}


