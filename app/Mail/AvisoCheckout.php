<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Checkin;

class AvisoCheckout extends Mailable
{
    use Queueable, SerializesModels;

    public $checkin;
    public $diasPassados;
    public $diasRestantes;

    public function __construct(Checkin $checkin, $diasPassados, $diasRestantes)
    {
        $this->checkin = $checkin;
        $this->diasPassados = $diasPassados;
        $this->diasRestantes = $diasRestantes;
    }

    public function build()
    {
        $mensagem = "Estás no {$this->diasPassados}º dia da sua estadia. Faltam {$this->diasRestantes} dias para o checkout.";

        return $this->subject('Atualização da Sua Estadia')
                    ->view('emails.aviso_checkout')
                    ->with([
                        'mensagem' => $mensagem,
                        'cliente' => optional($this->checkin->reserva)->cliente_nome ?? 'Hóspede',
                        'entrada' => $this->checkin->data_entrada,
                        'saida' => $this->checkin->data_saida,
                    ]);
    }
}