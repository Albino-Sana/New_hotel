<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
use App\Models\Fatura;
use App\Models\Reserva;

class FaturaHotelMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
public $fatura;
public $empresa;
public $reserva;

public function __construct(Fatura $fatura, Empresa $empresa)
{
    $this->fatura = $fatura;
    $this->empresa = $empresa;
    $this->reserva = Reserva::find($fatura->numero); // Supondo que o campo `numero` referencia `reserva->id`
}

public function build()
{
    return $this->subject('Sua Fatura-Recibo do Hotel')
                ->view('emails.faturas.recibo')
                ->with([
                    'fatura' => $this->fatura,
                    'empresa' => $this->empresa,
                    'reserva' => $this->reserva, // <- isso resolve o problema
                ]);
}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fatura Hotel Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.faturas.hotel',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
