<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelConfigController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TipoQuartoController;
use App\Http\Controllers\QuartoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HospedeController;
use App\Http\Controllers\ServicoAdicionalController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard (incluindo a rota do gráfico)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/dados-grafico', [DashboardController::class, 'dadosGrafico'])->name('dashboard.grafico');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Usuários
    Route::resource('usuarios', UserController::class)->except(['show']);
    
    // Funcionários
    Route::resource('funcionarios', FuncionarioController::class);
    
    // Cargos
    Route::resource('cargos', CargoController::class);
    
    // Tipos de Quartos
    Route::resource('tipos-quartos', TipoQuartoController::class);
    
    // Quartos
    Route::resource('quartos', QuartoController::class);
    
    // Reservas
    Route::resource('reservas', ReservaController::class);
    Route::post('/reservas/{reserva}/checkin', [ReservaController::class, 'checkin'])
         ->name('reservas.checkin');
    
    // Check-ins
    Route::resource('checkins', CheckinController::class);
    Route::get('/reserva-dados/{reserva}', [CheckinController::class, 'dadosReserva'])
         ->name('checkins.dados-reserva');
    
    // Checkouts
    Route::resource('checkouts', CheckoutController::class);
    
    // Hóspedes
    Route::resource('hospedes', HospedeController::class);
    
    // Serviços adicionais
    Route::resource('servicos-extras', ServicoAdicionalController::class)
         ->names([
             'index' => 'servicos_extras.index',
             'create' => 'servicos_extras.create',
             'store' => 'servicos_extras.store',
             'show' => 'servicos_extras.show',
             'edit' => 'servicos_extras.edit',
             'update' => 'servicos_extras.update',
             'destroy' => 'servicos_extras.destroy'
         ]);
    
    // Configuração do hotel
    Route::get('/header/configuracoes', [HotelConfigController::class, 'index'])
         ->name('hotel.config');
});

require __DIR__.'/auth.php';