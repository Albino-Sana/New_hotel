<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Hospede;
use App\Models\Fatura;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa;

class HospedeCadastrado extends Mailable
{
    use Queueable, SerializesModels;

    public $hospede;
    public $dias;
    public $fatura;
    public $empresa;
    public function __construct($fatura, $hospede, $empresa)
    {
        $this->fatura = $fatura;
        $this->hospede = $hospede;
        $this->empresa = $empresa;
    }


    public function build()
    {
        return $this->subject('Confirmação de Hospedagem')
                    ->view('emails.hospede_cadastrado')
                       ->with(['fatura' => $this->fatura]);
    }
}
