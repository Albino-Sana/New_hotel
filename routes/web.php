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
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\PosController;

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth'])->group(function () {
    // Acesso comum autenticado
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/dados-grafico', [DashboardController::class, 'dadosGrafico'])->name('dashboard.grafico');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // 🔒 Rotas exclusivas para Admin
    Route::middleware('can:admin-only')->group(function () {
        // Rotas existentes
        Route::resource('usuarios', UserController::class)->except(['show']);
        Route::resource('funcionarios', FuncionarioController::class);
        Route::resource('cargos', CargoController::class);
        Route::resource('tipos-quartos', TipoQuartoController::class);
        Route::resource('quartos', QuartoController::class);
        Route::resource('servicos-extras', ServicoAdicionalController::class)->names([
            'index' => 'servicos_extras.index',
            'create' => 'servicos_extras.create',
            'store' => 'servicos_extras.store',
            'show' => 'servicos_extras.show',
            'edit' => 'servicos_extras.edit',
            'update' => 'servicos_extras.update',
            'destroy' => 'servicos_extras.destroy'
        ]);

        Route::get('/relatorios/ocupacao', [RelatorioController::class, 'ocupacao'])->name('relatorios.ocupacao');
        Route::get('/relatorios/dados-ocupacao', [RelatorioController::class, 'dadosOcupacao'])->name('relatorios.dados-ocupacao');

        Route::get('/relatorios/reservas-cancelamentos', [RelatorioController::class, 'reservasCancelamentos'])->name('relatorios.reservas-cancelamentos');
        Route::get('/relatorios/dados-reservas-cancelamentos', [RelatorioController::class, 'dadosReservasCancelamentos'])->name('relatorios.dados-reservas-cancelamentos');

        Route::get('/relatorios/faturamento', [RelatorioController::class, 'faturamento'])->name('relatorios.faturamento');
        Route::get('/relatorios/dados-faturamento', [RelatorioController::class, 'dadosFaturamento'])->name('relatorios.dados-faturamento');

   
        Route::get('/pos', function () {
            return view('POS.pos2');
        })->name('pos2');
        Route::get('/config/configuracoes', [HotelConfigController::class, 'index'])->name('hotel.config');
    });
});

// 🔄 Rotas para gestão de reservas (Admin + Recepcionista)
Route::middleware('can:gerenciar-reservas')->group(function () {
        // Rotas estáticas para POS
      // Rotas do POS
Route::prefix('pos')->middleware(['auth'])->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('pos.index');
    Route::post('/checkin', [PosController::class, 'storeCheckin'])->name('pos.checkin.store');
    Route::post('/checkout', [PosController::class, 'storeCheckout'])->name('pos.checkout.store');
    Route::post('/consumo', [PosController::class, 'storeConsumo'])->name('pos.consumo.store');
});
    Route::get('/sys/PostoControle/', [PosController::class, 'index'])->name('PostoControle.index');
  
    Route::get('/pos', function () {
        return view('POS.pos2');
    })->name('pos2');
    Route::resource('reservas', ReservaController::class);
    Route::post('/reservas/{reserva}/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');

    Route::resource('checkins', CheckinController::class);
    Route::get('/reserva-dados/{reserva}', [CheckinController::class, 'dadosReserva'])->name('checkins.dados-reserva');

    Route::resource('checkouts', CheckoutController::class);
    Route::resource('hospedes', HospedeController::class);
    Route::post('hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');

    Route::get('/relatorios/servicos-extras', [RelatorioController::class, 'servicosExtras'])->name('relatorios.servicos-extras');
    Route::get('/relatorios/dados-servicos-extras', [RelatorioController::class, 'dadosServicosExtras'])->name('relatorios.dados-servicos-extras');
});

// 🔓 Admin e Recepcionista (e outros)
Route::middleware('can:recepcionista-only')->group(function () {

    Route::get('/reservas/{id}/testar-email', [ReservaController::class, 'testarEmail'])->name('reservas.testarEmail');
    Route::resource('reservas', ReservaController::class);
    Route::post('/reservas/{reserva}/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');

    Route::resource('checkins', CheckinController::class);
    Route::get('/reserva-dados/{reserva}', [CheckinController::class, 'dadosReserva'])->name('checkins.dados-reserva');

    Route::resource('checkouts', CheckoutController::class);
    Route::post('hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');

    Route::resource('hospedes', HospedeController::class);
});

require __DIR__ . '/auth.php';
