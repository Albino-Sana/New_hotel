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

use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\PagamentoMetodoController;
use App\Models\PagamentoMetodo;
use App\Http\Controllers\FaturaReciboController;
use App\Http\Controllers\SAFTController;



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
        Route::get('/saft', [SAFTController::class, 'form'])->name('saft.form');
        Route::post('/saft', [SAFTController::class, 'gerar'])->name('saft.gerar');
        Route::get('/saft/download/{filename}', [SAFTController::class, 'download'])->name('download.saft');
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
        Route::put('/sys/hotelaria/usuarios/atualizar-usuario/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/sys/hotelaria/usuarios/remover-usuario/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Funcionários
        Route::get('/sys/hotelaria/funcionarios/321/listar-funcionarios', [FuncionarioController::class, 'index'])->name('funcionarios.index');
        Route::get('/sys/hotelaria/funcionarios/654/criar-funcionario', [FuncionarioController::class, 'create'])->name('funcionarios.create');
        Route::post('/sys/hotelaria/funcionarios/987/salvar-funcionario', [FuncionarioController::class, 'store'])->name('funcionarios.store');
        Route::get('/sys/hotelaria/funcionarios/147/editar-funcionario', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
        Route::put('/sys/hotelaria/funcionarios/atualizar-funcionario/{id}', [FuncionarioController::class, 'update'])->name('funcionarios.update');
        Route::delete('/sys/hotelaria/funcionarios/remover-funcionario/{id}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');

        // Cargos
        Route::get('/sys/hotelaria/cargos/159/listar-cargos', [CargoController::class, 'index'])->name('cargos.index');
        Route::get('/sys/hotelaria/cargos/753/criar-cargo', [CargoController::class, 'create'])->name('cargos.create');
        Route::post('/sys/hotelaria/cargos/852/salvar-cargo', [CargoController::class, 'store'])->name('cargos.store');
        Route::get('/sys/hotelaria/cargos/951/editar-cargo', [CargoController::class, 'edit'])->name('cargos.edit');
        Route::put('/sys/hotelaria/cargos/atualizar-cargo/{cargo}', [CargoController::class, 'update'])->name('cargos.update');
        Route::delete('/sys/hotelaria/cargos/remover-cargo/{cargo}', [CargoController::class, 'destroy'])->name('cargos.destroy');

        // Tipos de Quartos
        Route::get('/sys/hotelaria/tipos-quartos/258/listar-tipos-quartos', [TipoQuartoController::class, 'index'])->name('tipos-quartos.index');
        Route::get('/sys/hotelaria/tipos-quartos/369/criar-tipo-quarto', [TipoQuartoController::class, 'create'])->name('tipos-quartos.create');
        Route::post('/sys/hotelaria/tipos-quartos/147/salvar-tipo-quarto', [TipoQuartoController::class, 'store'])->name('tipos-quartos.store');
        Route::get('/sys/hotelaria/tipos-quartos/852/editar-tipo-quarto', [TipoQuartoController::class, 'edit'])->name('tipos-quartos.edit');
        Route::put('/sys/hotelaria/tipos-quartos/atualizar-tipo-quarto/{tipos_quarto}', [TipoQuartoController::class, 'update'])->name('tipos-quartos.update');
        Route::delete('/sys/hotelaria/tipos-quartos/remover-tipo-quarto/{tipos_quarto}', [TipoQuartoController::class, 'destroy'])->name('tipos-quartos.destroy');

        // Quartos
        Route::get('/sys/hotelaria/quartos/456/listar-quartos', [QuartoController::class, 'index'])->name('quartos.index');
        Route::get('/sys/hotelaria/quartos/789/criar-quarto', [QuartoController::class, 'create'])->name('quartos.create');
        Route::post('/sys/hotelaria/quartos/123/salvar-quarto', [QuartoController::class, 'store'])->name('quartos.store');
        Route::get('/sys/hotelaria/quartos/258/editar-quarto', [QuartoController::class, 'edit'])->name('quartos.edit');
        Route::put('/sys/hotelaria/quartos/atualizar-quarto/{quarto}', [QuartoController::class, 'update'])->name('quartos.update');
        Route::delete('/sys/hotelaria/quartos/remover-quarto/{quarto}', [QuartoController::class, 'destroy'])->name('quartos.destroy');

        // Serviços Extras
        Route::get('/sys/hotelaria/servicos-extras/741/listar-servicos-extras', [ServicoAdicionalController::class, 'index'])->name('servicos_extras.index');
        Route::get('/sys/hotelaria/servicos-extras/852/criar-servico-extra', [ServicoAdicionalController::class, 'create'])->name('servicos_extras.create');
        Route::post('/sys/hotelaria/servicos-extras/963/salvar-servico-extra', [ServicoAdicionalController::class, 'store'])->name('servicos_extras.store');
        Route::get('/sys/hotelaria/servicos-extras/147/mostrar-servico-extra', [ServicoAdicionalController::class, 'show'])->name('servicos_extras.show');
        Route::get('/sys/hotelaria/servicos-extras/258/editar-servico-extra', [ServicoAdicionalController::class, 'edit'])->name('servicos_extras.edit');
        Route::put('/sys/hotelaria/servicos-extras/atualizar-servico-extra/{id}', [ServicoAdicionalController::class, 'update'])->name('servicos_extras.update');
        Route::delete('/sys/hotelaria/servicos-extras/remover-servico-extra/{id}', [ServicoAdicionalController::class, 'destroy'])->name('servicos_extras.destroy');

        // Relatórios
        Route::get('/sys/hotelaria/relatorios/ocupacao', [RelatorioController::class, 'ocupacao'])->name('relatorios.ocupacao');
        Route::get('/sys/hotelaria/relatorios/dados-ocupacao', [RelatorioController::class, 'dadosOcupacao'])->name('relatorios.dados-ocupacao');
        Route::get('/sys/hotelaria/relatorios/reservas-cancelamentos', [RelatorioController::class, 'reservasCancelamentos'])->name('relatorios.reservas-cancelamentos');
        Route::get('/sys/hotelaria/relatorios/dados-reservas-cancelamentos', [RelatorioController::class, 'dadosReservasCancelamentos'])->name('relatorios.dados-reservas-cancelamentos');
        Route::get('/sys/hotelaria/relatorios/faturamento', [RelatorioController::class, 'faturamento'])->name('relatorios.faturamento');
        Route::get('/sys/hotelaria/relatorios/dados-faturamento', [RelatorioController::class, 'dadosFaturamento'])->name('relatorios.dados-faturamento');

        Route::get('/sys/hotelaria/pos/123/pos', function () {
            return view('POS.pos2');
        })->name('pos2');
        Route::get('/sys/hotelaria/config/456/configuracoes', [HotelConfigController::class, 'index'])->name('hotel.config');
    });

    //Fatura
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/fatura/{id}/pdf', [FaturaController::class, 'gerarPdf'])->name('faturas.pdf');
    Route::get('/sys/hotelaria/documentos/fatura', [FaturaReciboController::class, 'index'])->name('fatura');


    // Visualizar/Imprimir PDF da Fatura
    Route::get('/faturas/ver/{id}', [FaturaController::class, 'verFatura'])->name('fatura');

    // Baixar PDF
    Route::get('/faturas/download/{id}', [FaturaController::class, 'download'])->name('faturas.download');

    // Enviar por e-mail
    Route::post('/faturas/enviar-email/{id}', [FaturaController::class, 'enviarEmail'])->name('faturas.email');

    // Anular
    Route::delete('/faturas/{id}', [FaturaController::class, 'destroy'])->name('faturas.destroy');

    Route::prefix('faturas')->group(function () {
        Route::get('/faturas-recibo', [FaturaReciboController::class, 'index'])->name('faturasRecibo.index');
        Route::get('/ver/{id}', [FaturaReciboController::class, 'verFatura'])->name('fatura'); // Imprimir PDF
        // Certo para fatura recibo
        Route::get('/fatura-recibo/download/{id}', [FaturaReciboController::class, 'download'])->name('faturas.download');

        Route::post('/enviar-email/{id}', [FaturaReciboController::class, 'enviarEmail'])->name('faturas.email');
        Route::delete('/anular/{id}', [FaturaReciboController::class, 'destroy'])->name('faturas.destroy');
    });
});




// 🔄 Rotas para gestão de reservas (Admin + Recepcionista)
Route::middleware('can:gerenciar-reservas')->group(function () {

    Route::get('/contador-reservas', [PosController::class, 'contadorReservas'])->name('contador.reservas');
    Route::prefix('pos')->middleware(['auth'])->group(function () {
        Route::get('/sys/hotelaria/pos/789/index', [PosController::class, 'index'])->name('pos.index');
        Route::post('/sys/hotelaria/pos/234/checkin', [PosController::class, 'storeCheckin'])->name('pos.checkin.store');
        Route::post('/sys/hotelaria/pos/567/checkout', [PosController::class, 'storeCheckout'])->name('pos.checkout.store');
        Route::post('/sys/hotelaria/pos/890/consumo', [PosController::class, 'storeConsumo'])->name('pos.consumo.store');
        Route::get('/pos', [PosController::class, 'pos'])->name('pos.index');
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
    Route::put('/sys/hotelaria/reservas/atualizar-reserva/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/sys/hotelaria/reservas/remover-reserva/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::post('/sys/hotelaria/reservas/checkin/{id}', [ReservaController::class, 'checkin'])->name('reservas.checkin');
    Route::get('/reservas/{id}/fatura', [ReservaController::class, 'fatura'])->name('reservas.fatura');

Route::post('/sys/hotelaria/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');

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
    Route::post('/sys/hotelaria/checkouts/salvar', [CheckoutController::class, 'store'])->name('checkouts.store');
    Route::get('/recibo-estadia/{id}', [CheckoutController::class, 'reciboEstadia'])->name('recibo.estadia');

    Route::get('/sys/hotelaria/checkouts/258/editar-checkout', [CheckoutController::class, 'edit'])->name('checkouts.edit');
    Route::put('/sys/hotelaria/checkouts/369/atualizar-checkout', [CheckoutController::class, 'update'])->name('checkouts.update');
    Route::delete('/sys/hotelaria/checkouts/741/remover-checkout', [CheckoutController::class, 'destroy'])->name('checkouts.destroy');

    // Hóspedes
    Route::get('/sys/hotelaria/hospedes/listar-hospedes', [HospedeController::class, 'index'])->name('hospedes.index');
    Route::get('/sys/hotelaria/hospedes/147/criar-hospede', [HospedeController::class, 'create'])->name('hospedes.create');
    Route::post('/sys/hotelaria/hospedes/258/salvar-hospede', [HospedeController::class, 'store'])->name('hospedes.store');
    Route::get('/sys/hotelaria/hospedes/369/editar-hospede', [HospedeController::class, 'edit'])->name('hospedes.edit');
    Route::put('/sys/hotelaria/hospedes/atualizar-hospede/{id}', [HospedeController::class, 'update'])->name('hospedes.update');
    Route::delete('/sys/hotelaria/hospedes/remover-hospede/{id}', [HospedeController::class, 'destroy'])->name('hospedes.destroy');
    Route::post('/sys/hotelaria/hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');
    // web.php
    Route::get('/hospedes/fatura/{id}', [HospedeController::class, 'verFatura'])->name('hospedes.fatura');

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
    Route::get('/pagamentos/fatura/pdf/{id}', [PagamentoController::class, 'verFatura'])->name('pagamentos.fatura.pdf');


    // web.php
    Route::get('/pagamentos/{id}/fatura', [PagamentoController::class, 'fatura'])->name('pagamentos.fatura');


    //Fatura
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/reservas/{id}/fatura', [ReservaController::class, 'visualizarFatura'])->name('reservas.fatura');
    Route::get('/reservas/{id}/fatura/pdf', [ReservaController::class, 'gerarFaturaPdf'])->name('reservas.fatura.pdf');
    Route::get('/hospedes/fatura/{id}', [HospedeController::class, 'verFatura'])->name('hospedes.fatura');

    //Fatura
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/fatura/{id}/pdf', [FaturaController::class, 'gerarPdf'])->name('faturas.pdf');
    Route::get('/sys/hotelaria/documentos/fatura', [FaturaReciboController::class, 'index'])->name('fatura');


    // Visualizar/Imprimir PDF da Fatura
    Route::get('/faturas/ver/{id}', [FaturaController::class, 'verFatura'])->name('fatura');
    // Baixar PDF
    Route::get('/faturas/download/{id}', [FaturaController::class, 'download'])->name('faturas.download');
    // Enviar por e-mail
    Route::post('/faturas/enviar-email/{id}', [FaturaController::class, 'enviarEmail'])->name('faturas.email');
    // Anular
    Route::delete('/faturas/{id}', [FaturaController::class, 'destroy'])->name('faturas.destroy');
    Route::prefix('faturas')->group(function () {
        Route::get('/faturas-recibo', [FaturaReciboController::class, 'index'])->name('faturasRecibo.index');
        // Certo para fatura recibo
        Route::get('/fatura-recibo/download/{id}', [FaturaReciboController::class, 'download'])->name('faturas.download');

        Route::post('/enviar-email/{id}', [FaturaReciboController::class, 'enviarEmail'])->name('faturas.email');
        Route::delete('/anular/{id}', [FaturaReciboController::class, 'destroy'])->name('faturas.destroy');
    });

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
    Route::get('/historico', [HistoricoController::class, 'index'])->name('historico.index');
    Route::get('/dashboard/relatorio-pdf', [DashboardController::class, 'relatorioPDF'])->name('dashboard.relatorio-pdf');
    Route::get('/relatorios/relatorio-faturamento-pdf', [RelatorioController::class, 'relatorioFaturamentoPDF'])->name('relatorios.relatorio-faturamento-pdf');
    Route::get('/relatorios/relatorio-reservas-cancelamentos-pdf', [RelatorioController::class, 'relatorioReservasCancelamentosPDF'])->name('relatorios.relatorio-reservas-cancelamentos-pdf');
    Route::get('/relatorios/relatorio-ocupacao-pdf', [RelatorioController::class, 'relatorioOcupacaoPDF'])->name('relatorios.relatorio-ocupacao-pdf');
    Route::get('/relatorios/relatorio-servicos-extras-pdf', [RelatorioController::class, 'relatorioServicosExtrasPDF'])->name('relatorio.servicos.extras');


    Route::prefix('faturas')->group(function () {
        Route::get('/faturas-recibo', [FaturaReciboController::class, 'index'])->name('faturasRecibo.index');
        Route::get('/ver/{id}', [FaturaReciboController::class, 'verFatura'])->name('fatura'); // Imprimir PDF
        Route::get('/download/{id}', [FaturaReciboController::class, 'download'])->name('faturas.download');
        Route::post('/enviar-email/{id}', [FaturaReciboController::class, 'enviarEmail'])->name('faturas.email');
        Route::delete('/anular/{id}', [FaturaReciboController::class, 'destroy'])->name('faturas.destroy');
    });
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
    Route::put('/sys/hotelaria/reservas/atualizar-reserva/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/sys/hotelaria/reservas/remover-reserva/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
Route::post('/sys/hotelaria/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');

    Route::post('/sys/hotelaria/reservas/checkin/{id}', [ReservaController::class, 'checkin'])->name('reservas.checkin');

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
    Route::post('/sys/hotelaria/checkouts/salvar', [CheckoutController::class, 'store'])->name('checkouts.store');
    Route::get('/recibo-estadia/{id}', [CheckoutController::class, 'reciboEstadia'])->name('recibo.estadia');

    Route::get('/sys/hotelaria/checkouts/258/editar-checkout', [CheckoutController::class, 'edit'])->name('checkouts.edit');
    Route::put('/sys/hotelaria/checkouts/369/atualizar-checkout', [CheckoutController::class, 'update'])->name('checkouts.update');
    Route::delete('/sys/hotelaria/checkouts/741/remover-checkout', [CheckoutController::class, 'destroy'])->name('checkouts.destroy');

    // Hóspedes
    Route::get('/sys/hotelaria/hospedes/listar-hospedes', [HospedeController::class, 'index'])->name('hospedes.index');
    Route::get('/sys/hotelaria/hospedes/147/criar-hospede', [HospedeController::class, 'create'])->name('hospedes.create');
    Route::post('/sys/hotelaria/hospedes/258/salvar-hospede', [HospedeController::class, 'store'])->name('hospedes.store');
    Route::get('/sys/hotelaria/hospedes/369/editar-hospede', [HospedeController::class, 'edit'])->name('hospedes.edit');
    Route::put('/sys/hotelaria/hospedes/741/atualizar-hospede', [HospedeController::class, 'update'])->name('hospedes.update');
    Route::delete('/sys/hotelaria/hospedes/remover-hospede/{id}', [HospedeController::class, 'destroy'])->name('hospedes.destroy');
    Route::post('/sys/hotelaria/hospedes/{id}/checkout', [HospedeController::class, 'checkout'])->name('hospedes.checkout');
    // web.php
    Route::get('/hospedes/fatura/{id}', [HospedeController::class, 'verFatura'])->name('hospedes.fatura');

    Route::get('/fatura/{id}/pdf', function ($id) {
        $fatura = Fatura::findOrFail($id);

        return Pdf::loadView('faturas.recibo', compact('fatura'))->stream('Recibo_' . $fatura->numero . '.pdf');
    })->name('fatura.pdf');
});

require __DIR__ . '/auth.php';
