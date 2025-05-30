<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Hospede;

class HospedeCadastrado extends Mailable
{
    use Queueable, SerializesModels;

    public $hospede;
    public $dias;

    public function __construct(Hospede $hospede, $dias)
    {
        $this->hospede = $hospede;
        $this->dias = $dias;
    }

    public function build()
    {
        return $this->subject('Confirmação de Hospedagem')
                    ->view('emails.hospede_cadastrado');
    }
}
