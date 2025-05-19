<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\AlertaCheckout;

class VerificarReservas extends Command
{
    protected $signature = 'verificar:reservas';
    protected $description = 'Verifica reservas longas e envia alertas por e-mail após 3 dias';

    public function handle()
    {
        $hoje = Carbon::today();

        // Pegamos todas as reservas com 5 dias ou mais de duração
        $reservas = Reserva::whereRaw('DATEDIFF(data_saida, data_entrada) >= 5')->get();

        foreach ($reservas as $reserva) {
            if (!$reserva->cliente_email) {
                continue; // pula se não tiver email
            }

            $dias_passados = Carbon::parse($reserva->data_entrada)->diffInDays($hoje);

            if ($dias_passados == 3 || $dias_passados == 4) {
                try {
                    Mail::to($reserva->cliente_email)->send(new AlertaCheckout($reserva, $dias_passados));
                    $this->info("E-mail enviado para {$reserva->cliente_email}");
                } catch (\Exception $e) {
                    $this->error("Erro ao enviar e-mail: " . $e->getMessage());
                }
            }
        }

        $this->info('Reservas verificadas e e-mails enviados quando aplicável.');
    }
}
