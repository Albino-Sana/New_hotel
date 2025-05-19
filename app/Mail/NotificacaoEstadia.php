<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacaoEstadia extends Mailable
{
    use Queueable, SerializesModels;

    public $diasPassados;
    public $diasRestantes;

    public function __construct($diasPassados, $diasRestantes)
    {
        $this->diasPassados = $diasPassados;
        $this->diasRestantes = $diasRestantes;
    }

    public function build()
    {
        return $this->subject('Atualização da sua estadia')
                    ->view('emails.notificacao_estadia');
    }    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificacao Estadia',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
