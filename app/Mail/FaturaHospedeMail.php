<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Hospede;
use Barryvdh\DomPDF\Facade\Pdf;
class FaturaHospedeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $hospede;
    public $checkout;

    public function __construct($hospede, $checkout)
    {
        $this->hospede = $hospede;
        $this->checkout = $checkout;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fatura Hospede Mail',
        );
    }
       public function build()
    {
        $pdf = Pdf::loadView('pdf.fatura', [
            'hospede' => $this->hospede,
            'checkout' => $this->checkout
        ]);

        return $this->subject('Sua Fatura - Hotel Exemplo')
            ->markdown('emails.fatura')
            ->attachData($pdf->output(), 'Fatura-Hospede-'.$this->hospede->id.'.pdf');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.fatura',
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
