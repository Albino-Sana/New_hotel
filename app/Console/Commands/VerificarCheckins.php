<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Checkin;
use App\Mail\AvisoCheckout;
use App\Mail\NotificacaoEstadia;

class VerificarCheckins extends Command
{
    protected $signature = 'verificar:checkins';

    protected $description = 'Verifica check-ins com 5 dias ou mais e envia e-mail dinâmico de notificação (1 por dia)';

    public function handle()
    {
        $hoje = Carbon::today();

        $checkins = Checkin::with('reserva')
            ->whereDate('data_entrada', '<=', $hoje)
            ->whereDate('data_saida', '>=', $hoje)
            ->get();

        foreach ($checkins as $checkin) {
            $entrada = Carbon::parse($checkin->data_entrada);
            $saida = Carbon::parse($checkin->data_saida);

            $diasTotal = $entrada->diffInDays($saida);
            $diasPassados = $entrada->diffInDays($hoje);
            $diasRestantes = $saida->diffInDays($hoje);

            // Verifica se o check-in atende aos critérios e se não foi enviado e-mail hoje
            if (
                $diasTotal >= 5 &&
                $diasPassados >= 3 &&
                $diasRestantes > 0 &&
                $checkin->ultimo_email_enviado != $hoje->toDateString()
            ) {
                if (optional($checkin->reserva)->cliente_email) {
                    $email = $checkin->reserva->cliente_email;

                    try {
                        // Envia o e-mail usando a classe AvisoCheckout
                        Mail::to($email)->send(new NotificacaoEstadia($diasPassados, $diasRestantes));

                        // Atualiza a data do último envio
                        $checkin->ultimo_email_enviado = $hoje->toDateString();
                        $checkin->save();

                        Log::info("E-mail enfileirado para {$email} (Checkin ID: {$checkin->id})");
                    } catch (\Exception $e) {
                        Log::error("Erro ao enfileirar e-mail para {$email}: " . $e->getMessage());
                    }
                } else {
                    Log::warning("Checkin ID {$checkin->id} não tem e-mail cadastrado na reserva.");
                }
            }
        }

        $this->info('Check-ins verificados e e-mails enfileirados quando aplicável.');
    }
}