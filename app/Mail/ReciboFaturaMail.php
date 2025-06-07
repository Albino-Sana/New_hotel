<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboFaturaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    
public $fatura;
 public function __construct($fatura)
{
    $this->fatura = $fatura;
}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo Fatura Mail',
        );
    }
public function build()
{
    $pdf = Pdf::loadView('faturas.pdf', ['fatura' => $this->fatura])->output();

    return $this->subject('Seu Recibo de Check-in')
        ->markdown('emails.recibo')
        ->attachData($pdf, 'recibo_' . $this->fatura->numero . '.pdf', [
            'mime' => 'application/pdf',
        ]);
}
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.recibo',
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
