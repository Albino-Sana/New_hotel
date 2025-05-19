<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;

class AlertaCheckout extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;
    public $dias_passados;

    public function __construct(Reserva $reserva, $dias_passados)
    {
        $this->reserva = $reserva;
        $this->dias_passados = $dias_passados;
    }

    public function build()
    {
        $mensagem = $this->dias_passados == 3
            ? 'Estás no terceiro dia da estadia. Faltam dois dias para o checkout.'
            : 'Estás no quarto dia da estadia. Falta um dia para o checkout.';

        return $this->subject('Aviso de Checkout')
                    ->view('emails.alerta_checkout')
                    ->with([
                        'mensagem' => $mensagem,
                        'cliente' => $this->reserva->cliente_nome,
                        'quarto' => $this->reserva->quarto->nome ?? 'N/A',
                    ]);
    }
}
