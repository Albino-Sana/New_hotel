<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\VerificarCheckins; // importa o comando

// Comando padrão do Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ⏰ Agendamento do comando verificar:checkins a cada minuto
Schedule::command(VerificarCheckins::class)->everyMinute();
