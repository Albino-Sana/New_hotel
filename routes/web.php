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
use App\Http\Controllers\CorrenteServicoController;
use App\Http\Controllers\PagamentoController;
use App\Models\TipoQuarto;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\ReciboController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Fatura;
use App\Models\Reserva;
use App\Models\Empresa;
use App\Http\Controllers\EmpresaController;
use App\Models\Pagamento;

use App\Http\Controllers\PagamentoMetodoController;
use App\Models\PagamentoMetodo;


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
        Route::get('/hotel', [EmpresaController::class, 'index'])->name('hotel.index');
        Route::put('/hotel', [EmpresaController::class, 'store'])->name('hotel.store');
        Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
        Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
        Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.index');
        Route::put('/empresa', [EmpresaController::class, 'store'])->name('empresa.store');

        Route::post('/pagamentos-metodos', [PagamentoMetodoController::class, 'store'])->name('pagamentos-metodos.store');
        Route::delete('/pagamentos-metodos/{id}', [PagamentoMetodoController::class, 'destroy'])->name('pagamentos-metodos.destroy');

        // Usuários
        Route::get('/sys/hotelaria/usuarios/789/listar-usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/sys/hotelaria/usuarios/234/criar-usuario', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/sys/hotelaria/usuarios/567/salvar-usuario', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/sys/hotelaria/usuarios/890/editar-usuario', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/sys/hotelaria/usuarios/123/atualizar-usuario', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/sys/hotelaria/usuarios/456/remover-usuario', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Funcionários
        Route::get('/sys/hotelaria/funcionarios/321/listar-funcionarios', [FuncionarioController::class, 'index'])->name('funcionarios.index');
        Route::get('/sys/hotelaria/funcionarios/654/criar-funcionario', [FuncionarioController::class, 'create'])->name('funcionarios.create');
        Route::post('/sys/hotelaria/funcionarios/987/salvar-funcionario', [FuncionarioController::class, 'store'])->name('funcionarios.store');
        Route::get('/sys/hotelaria/funcionarios/147/editar-funcionario', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
        Route::put('/sys/hotelaria/funcionarios/258/atualizar-funcionario', [FuncionarioController::class, 'update'])->name('funcionarios.update');
        Route::delete('/sys/hotelaria/funcionarios/369/remover-funcionario', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');

        // Cargos
        Route::get('/sys/hotelaria/cargos/159/listar-cargos', [CargoController::class, 'index'])->name('cargos.index');
        Route::get('/sys/hotelaria/cargos/753/criar-cargo', [CargoController::class, 'create'])->name('cargos.create');
        Route::post('/sys/hotelaria/cargos/852/salvar-cargo', [CargoController::class, 'store'])->name('cargos.store');
        Route::get('/sys/hotelaria/cargos/951/editar-cargo', [CargoController::class, 'edit'])->name('cargos.edit');
        Route::put('/sys/hotelaria/cargos/357/atualizar-cargo', [CargoController::class, 'update'])->name('cargos.update');
        Route::delete('/sys/hotelaria/cargos/741/remover-cargo', [CargoController::class, 'destroy'])->name('cargos.destroy');

        // Tipos de Quartos
        Route::get('/sys/hotelaria/tipos-quartos/258/listar-tipos-quartos', [TipoQuartoController::class, 'index'])->name('tipos-quartos.index');
        Route::get('/sys/hotelaria/tipos-quartos/369/criar-tipo-quarto', [TipoQuartoController::class, 'create'])->name('tipos-quartos.create');
        Route::post('/sys/hotelaria/tipos-quartos/147/salvar-tipo-quarto', [TipoQuartoController::class, 'store'])->name('tipos-quartos.store');
        Route::get('/sys/hotelaria/tipos-quartos/852/editar-tipo-quarto', [TipoQuartoController::class, 'edit'])->name('tipos-quartos.edit');
        Route::put('/sys/hotelaria/tipos-quartos/963/atualizar-tipo-quarto', [TipoQuartoController::class, 'update'])->name('tipos-quartos.update');
        Route::delete('/sys/hotelaria/tipos-quartos/741/remover-tipo-quarto', [TipoQuartoController::class, 'destroy'])->name('tipos-quartos.destroy');

        // Quartos
        Route::get('/sys/hotelaria/quartos/456/listar-quartos', [QuartoController::class, 'index'])->name('quartos.index');
        Route::get('/sys/hotelaria/quartos/789/criar-quarto', [QuartoController::class, 'create'])->name('quartos.create');
        Route::post('/sys/hotelaria/quartos/123/salvar-quarto', [QuartoController::class, 'store'])->name('quartos.store');
        Route::get('/sys/hotelaria/quartos/258/editar-quarto', [QuartoController::class, 'edit'])->name('quartos.edit');
        Route::put('/sys/hotelaria/quartos/369/atualizar-quarto', [QuartoController::class, 'update'])->name('quartos.update');
        Route::delete('/sys/hotelaria/quartos/147/remover-quarto', [QuartoController::class, 'destroy'])->name('quartos.destroy');

        // Serviços Extras
        Route::get('/sys/hotelaria/servicos-extras/741/listar-servicos-extras', [ServicoAdicionalController::class, 'index'])->name('servicos_extras.index');
        Route::get('/sys/hotelaria/servicos-extras/852/criar-servico-extra', [ServicoAdicionalController::class, 'create'])->name('servicos_extras.create');
        Route::post('/sys/hotelaria/servicos-extras/963/salvar-servico-extra', [ServicoAdicionalController::class, 'store'])->name('servicos_extras.store');
        Route::get('/sys/hotelaria/servicos-extras/147/mostrar-servico-extra', [ServicoAdicionalController::class, 'show'])->name('servicos_extras.show');
        Route::get('/sys/hotelaria/servicos-extras/258/editar-servico-extra', [ServicoAdicionalController::class, 'edit'])->name('servicos_extras.edit');
        Route::put('/sys/hotelaria/servicos-extras/369/atualizar-servico-extra', [ServicoAdicionalController::class, 'update'])->name('servicos_extras.update');
        Route::delete('/sys/hotelaria/servicos-extras/741/remover-servico-extra', [ServicoAdicionalController::class, 'destroy'])->name('servicos_extras.destroy');

        // Relatórios
        Route::get('/sys/hotelaria/relatorios/159/ocupacao', [RelatorioController::class, 'ocupacao'])->name('relatorios.ocupacao');
        Route::get('/sys/hotelaria/relatorios/753/dados-ocupacao', [RelatorioController::class, 'dadosOcupacao'])->name('relatorios.dados-ocupacao');
        Route::get('/sys/hotelaria/relatorios/852/reservas-cancelamentos', [RelatorioController::class, 'reservasCancelamentos'])->name('relatorios.reservas-cancelamentos');
        Route::get('/sys/hotelaria/relatorios/951/dados-reservas-cancelamentos', [RelatorioController::class, 'dadosReservasCancelamentos'])->name('relatorios.dados-reservas-cancelamentos');
        Route::get('/sys/hotelaria/relatorios/357/faturamento', [RelatorioController::class, 'faturamento'])->name('relatorios.faturamento');
        Route::get('/sys/hotelaria/relatorios/741/dados-faturamento', [RelatorioController::class, 'dadosFaturamento'])->name('relatorios.dados-faturamento');

        Route::get('/sys/hotelaria/pos/123/pos', function () {
            return view('POS.pos2');
        })->name('pos2');
        Route::get('/sys/hotelaria/config/456/configuracoes', [HotelConfigController::class, 'index'])->name('hotel.config');
    });

    //Fatura
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/fatura/{id}/pdf', [FaturaController::class, 'gerarPdf'])->name('faturas.pdf');
});

// 🔄 Rotas para gestão de reservas (Admin + Recepcionista)
Route::middleware('can:gerenciar-reservas')->group(function () {
    Route::prefix('pos')->middleware(['auth'])->group(function () {
        Route::get('/sys/hotelaria/pos/789/index', [PosController::class, 'index'])->name('pos.index');
        Route::post('/sys/hotelaria/pos/234/checkin', [PosController::class, 'storeCheckin'])->name('pos.checkin.store');
        Route::post('/sys/hotelaria/pos/567/checkout', [PosController::class, 'storeCheckout'])->name('pos.checkout.store');
        Route::post('/sys/hotelaria/pos/890/consumo', [PosController::class, 'storeConsumo'])->name('pos.consumo.store');
    });

    Route::post('/sys/hotelaria/corrente-servicos/123/store', [CorrenteServicoController::class, 'store'])->name('corrente-servicos.store');

    Route::get('/sys/hotelaria/posto-controle/456/index', [PosController::class, 'index'])->name('PostoControle.index');

    Route::get('/sys/hotelaria/pos/789/pos', function () {
        return view('POS.pos2');
    })->name('pos2');

    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::put('/empresa', [EmpresaController::class, 'store'])->name('empresa.store');

    Route::post('/pagamentos-metodos', [PagamentoMetodoController::class, 'store'])->name('pagamentos-metodos.store');
    Route::delete('/pagamentos-metodos/{id}', [PagamentoMetodoController::class, 'destroy'])->name('pagamentos-metodos.destroy');

    // Reservas
    Route::get('/sys/hotelaria/reservas/147/listar-reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/sys/hotelaria/reservas/258/criar-reserva', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/sys/hotelaria/reservas/369/salvar-reserva', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/sys/hotelaria/reservas/741/editar-reserva', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/sys/hotelaria/reservas/852/atualizar-reserva', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/sys/hotelaria/reservas/963/remover-reserva', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::post('/sys/hotelaria/reservas/147/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');

    // Checkins
    Route::get('/sys/hotelaria/checkins/369/listar-checkins', [CheckinController::class, 'index'])->name('checkins.index');
    Route::get('/sys/hotelaria/checkins/741/criar-checkin', [CheckinController::class, 'create'])->name('checkins.create');
    Route::post('/sys/hotelaria/checkins/852/salvar-checkin', [CheckinController::class, 'store'])->name('checkins.store');
    Route::get('/sys/hotelaria/checkins/963/editar-checkin', [CheckinController::class, 'edit'])->name('checkins.edit');
    Route::put('/sys/hotelaria/checkins/147/atualizar-checkin', [CheckinController::class, 'update'])->name('checkins.update');
    Route::delete('/sys/hotelaria/checkins/remover-checkin/{id}', [CheckinController::class, 'destroy'])->name('checkins.destroy');
    Route::get('/sys/hotelaria/checkins/369/dados-reserva', [CheckinController::class, 'dadosReserva'])->name('checkins.dados-reserva');

    // Checkouts
    Route::get('/sys/hotelaria/checkouts/852/listar-checkouts', [CheckoutController::class, 'index'])->name('checkouts.index');
    Route::get('/sys/hotelaria/checkouts/963/criar-checkout', [CheckoutController::class, 'create'])->name('checkouts.create');
    Route::post('/sys/hotelaria/checkouts/147/salvar-checkout', [CheckoutController::class, 'store'])->name('checkouts.store');
    Route::get('/sys/hotelaria/checkouts/258/editar-checkout', [CheckoutController::class, 'edit'])->name('checkouts.edit');
    Route::put('/sys/hotelaria/checkouts/369/atualizar-checkout', [CheckoutController::class, 'update'])->name('checkouts.update');
    Route::delete('/sys/hotelaria/checkouts/741/remover-checkout', [CheckoutController::class, 'destroy'])->name('checkouts.destroy');

    // Hóspedes
    Route::get('/sys/hotelaria/hospedes/listar-hospedes', [HospedeController::class, 'index'])->name('hospedes.index');
    Route::get('/sys/hotelaria/hospedes/147/criar-hospede', [HospedeController::class, 'create'])->name('hospedes.create');
    Route::post('/sys/hotelaria/hospedes/258/salvar-hospede', [HospedeController::class, 'store'])->name('hospedes.store');
    Route::get('/sys/hotelaria/hospedes/369/editar-hospede', [HospedeController::class, 'edit'])->name('hospedes.edit');
    Route::put('/sys/hotelaria/hospedes/741/atualizar-hospede', [HospedeController::class, 'update'])->name('hospedes.update');
    Route::delete('/sys/hotelaria/hospedes/852/remover-hospede', [HospedeController::class, 'destroy'])->name('hospedes.destroy');
    Route::post('/sys/hotelaria/hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');

    // Relatórios
    Route::get('/sys/hotelaria/relatorios/369/servicos-extras', [RelatorioController::class, 'servicosExtras'])->name('relatorios.servicos-extras');
    Route::get('/sys/hotelaria/relatorios/741/dados-servicos-extras', [RelatorioController::class, 'dadosServicosExtras'])->name('relatorios.dados-servicos-extras');

    // Pagamentos
    Route::get('/sys/hotelaria/pagamentos/123/listar-pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
    Route::get('/sys/hotelaria/pagamentos/456/criar-pagamento/{reservaId}', [PagamentoController::class, 'create'])->name('pagamentos.create');
    Route::post('/sys/hotelaria/pagamentos/789/processar-pagamento', [PagamentoController::class, 'store'])->name('pagamentos.store');
    Route::get('/sys/hotelaria/pagamentos/234/editar-pagamento/{id}', [PagamentoController::class, 'edit'])->name('pagamentos.edit');
    Route::put('/sys/hotelaria/pagamentos/567/atualizar-pagamento/{id}', [PagamentoController::class, 'update'])->name('pagamentos.update');
    Route::delete('/sys/hotelaria/pagamentos/890/remover-pagamento/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
    Route::get('/valor/checkin/{id}', [PagamentoController::class, 'valorPorCheckin']);
    Route::get('/valor/hospede/{id}', [PagamentoController::class, 'valorPorHospede']);
    Route::get('/pagamentos/{id}/fatura', [PagamentoController::class, 'fatura'])->name('pagamentos.fatura');


    //Fatura
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/fatura/{id}/pdf', [FaturaController::class, 'gerarPdf'])->name('faturas.pdf');

    Route::get('/api/tipo-quarto/{id}', function ($id) {
        $tipo = TipoQuarto::find($id);

        if (!$tipo) {
            return response()->json(['error' => 'Tipo de quarto não encontrado'], 404);
        }

        return response()->json([
            'preco_noite' => $tipo->preco,
            'tipo_cobranca' => $tipo->tipo_cobranca
        ]);
    });
});

// 🔓 Admin e Recepcionista (e outros)
Route::middleware('can:recepcionista-only')->group(function () {
    Route::get('/dashboard/relatorio-pdf', [DashboardController::class, 'relatorioPDF'])->name('dashboard.relatorio-pdf');
    Route::get('/relatorios/relatorio-faturamento-pdf', [RelatorioController::class, 'relatorioFaturamentoPDF'])->name('relatorios.relatorio-faturamento-pdf');
    Route::get('/relatorios/relatorio-reservas-cancelamentos-pdf', [RelatorioController::class, 'relatorioReservasCancelamentosPDF'])->name('relatorios.relatorio-reservas-cancelamentos-pdf');
    Route::get('/relatorios/relatorio-ocupacao-pdf', [RelatorioController::class, 'relatorioOcupacaoPDF'])->name('relatorios.relatorio-ocupacao-pdf');
    Route::get('/relatorios/relatorio-servicos-extras-pdf', [RelatorioController::class, 'relatorioServicosExtrasPDF'])->name('relatorio.servicos.extras');
    Route::get('/hotel', [EmpresaController::class, 'index'])->name('hotel.index');
    Route::put('/hotel', [EmpresaController::class, 'store'])->name('hotel.store');
    Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
    Route::delete('/pagamentos/{id}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');

    Route::post('/pagamentos-metodos', [PagamentoMetodoController::class, 'store'])->name('pagamentos-metodos.store');
    Route::delete('/pagamentos-metodos/{id}', [PagamentoMetodoController::class, 'destroy'])->name('pagamentos-metodos.destroy');
    // Reservas
    Route::get('/sys/hotelaria/reservas/147/listar-reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/sys/hotelaria/reservas/258/criar-reserva', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/sys/hotelaria/reservas/369/salvar-reserva', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/sys/hotelaria/reservas/741/editar-reserva', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/sys/hotelaria/reservas/852/atualizar-reserva', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/sys/hotelaria/reservas/963/remover-reserva', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::post('/sys/hotelaria/reservas/147/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::post('/sys/hotelaria/reservas/258/checkin', [ReservaController::class, 'checkin'])->name('reservas.checkin');

    // Checkins
    Route::get('/sys/hotelaria/checkins/369/listar-checkins', [CheckinController::class, 'index'])->name('checkins.index');
    Route::get('/sys/hotelaria/checkins/741/criar-checkin', [CheckinController::class, 'create'])->name('checkins.create');
    Route::post('/sys/hotelaria/checkins/852/salvar-checkin', [CheckinController::class, 'store'])->name('checkins.store');
    Route::get('/sys/hotelaria/checkins/963/editar-checkin', [CheckinController::class, 'edit'])->name('checkins.edit');
    Route::put('/sys/hotelaria/checkins/147/atualizar-checkin', [CheckinController::class, 'update'])->name('checkins.update');
    Route::delete('/sys/hotelaria/checkins/remover-checkin/{id}', [CheckinController::class, 'destroy'])->name('checkins.destroy');
    Route::get('/sys/hotelaria/checkins/369/dados-reserva', [CheckinController::class, 'dadosReserva'])->name('checkins.dados-reserva');

    // Checkouts
    Route::get('/sys/hotelaria/checkouts/852/listar-checkouts', [CheckoutController::class, 'index'])->name('checkouts.index');
    Route::get('/sys/hotelaria/checkouts/963/criar-checkout', [CheckoutController::class, 'create'])->name('checkouts.create');
    Route::post('/sys/hotelaria/checkouts/147/salvar-checkout', [CheckoutController::class, 'store'])->name('checkouts.store');
    Route::get('/sys/hotelaria/checkouts/258/editar-checkout', [CheckoutController::class, 'edit'])->name('checkouts.edit');
    Route::put('/sys/hotelaria/checkouts/369/atualizar-checkout', [CheckoutController::class, 'update'])->name('checkouts.update');
    Route::delete('/sys/hotelaria/checkouts/741/remover-checkout', [CheckoutController::class, 'destroy'])->name('checkouts.destroy');

    // Hóspedes
    Route::get('/sys/hotelaria/hospedes/listar-hospedes', [HospedeController::class, 'index'])->name('hospedes.index');
    Route::get('/sys/hotelaria/hospedes/147/criar-hospede', [HospedeController::class, 'create'])->name('hospedes.create');
    Route::post('/sys/hotelaria/hospedes/258/salvar-hospede', [HospedeController::class, 'store'])->name('hospedes.store');
    Route::get('/sys/hotelaria/hospedes/369/editar-hospede', [HospedeController::class, 'edit'])->name('hospedes.edit');
    Route::put('/sys/hotelaria/hospedes/741/atualizar-hospede', [HospedeController::class, 'update'])->name('hospedes.update');
    Route::delete('/sys/hotelaria/hospedes/852/remover-hospede', [HospedeController::class, 'destroy'])->name('hospedes.destroy');
    Route::post('/sys/hotelaria/hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');

    Route::get('/fatura/{id}/pdf', function ($id) {
        $fatura = Fatura::findOrFail($id);

        return Pdf::loadView('faturas.recibo', compact('fatura'))->stream('Recibo_' . $fatura->numero . '.pdf');
    })->name('fatura.pdf');
});

require __DIR__ . '/auth.php';
