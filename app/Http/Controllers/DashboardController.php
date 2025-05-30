<?php

namespace App\Http\Controllers;

use App\Models\Quarto;
use App\Models\Funcionario;
use App\Models\Reserva;
use App\Models\Hospede;
use App\Models\Pagamento;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf; // Se usar DomPDF

class DashboardController extends Controller
{
public function index()
{
    // ‑‑‑ Indicadores de topo ‑‑‑
    $quartosDisponiveisTotal  = Quarto::where('status', 'Disponível')->count();
    $totalFuncionarios        = checkin::where('status', 'hospedado')->count();
    $reservasAtivas           = Reserva::where('status', 'Reservado')->count();
    $hospedesHospedadosTotal  = Hospede::where('status', 'Hospedado')->count();

    // ‑‑‑ Gráfico dos últimos 7 dias ‑‑‑
    $hoje    = Carbon::today();
    $inicio  = $hoje->copy()->subDays(6);

    $dadosBrutos = Reserva::select(
            DB::raw('DATE(created_at) as data'),
            DB::raw('COUNT(*) as total')
        )
        ->whereBetween('created_at', [$inicio, $hoje])
        ->groupBy('data')
        ->orderBy('data')
        ->get()
        ->keyBy('data');

    $labels = [];
    $data   = [];

    for ($date = $inicio; $date->lte($hoje); $date->addDay()) {
        $labels[] = $date->format('d M');
        $data[]   = $dadosBrutos[$date->toDateString()]->total ?? 0;
    }

    $dadosGrafico = [
        'labels' => $labels,
        'data'   => $data,
        'titulo' => 'Reservas nos Últimos 7 Dias'
    ];

    // ‑‑‑ Tabelas/Lists do dashboard ‑‑‑
    $quartosDisponiveis = Quarto::with('tipo')
        ->where('status', 'Disponível')
        ->orderBy('numero')
        ->get();

    $hospedesHospedados = Hospede::with('quarto')
        ->where('status', 'Hospedado')
        ->orderBy('nome')
        ->get();

    // ‑‑‑ Retorno da view ‑‑‑
    return view('dashboard.index', compact(
        'quartosDisponiveis',
        'hospedesHospedados',
        'quartosDisponiveisTotal',
        'hospedesHospedadosTotal',
        'totalFuncionarios',
        'reservasAtivas',
        'dadosGrafico'
    ));
}

public function relatorioPDF(Request $request)
{
    $periodo = $request->query('periodo', '7dias');
    // Lógica para buscar dados com base no período
    $dados = $this->obterDadosRelatorio($periodo); // Implemente esta função

    $pdf = Pdf::loadView('relatorios.dashboard', compact('dados', 'periodo'));
    return $pdf->download('relatorio_' . $periodo . '.pdf');
}

private function obterDadosRelatorio($periodo)
    {
        // Definir o intervalo de datas com base no período
        $dataFim = now();
        if ($periodo === '7dias') {
            $dataInicio = now()->subDays(7);
        } elseif ($periodo === '1mes') {
            $dataInicio = now()->subMonth();
        } elseif ($periodo === '1ano') {
            $dataInicio = now()->subYear();
        } else {
            $dataInicio = now()->subDays(7); // Padrão: 7 dias
        }

        // Buscar reservas no intervalo (ajuste conforme seu modelo)
        $reservas = Reserva::whereBetween('created_at', [$dataInicio, $dataFim])
            ->orderBy('created_at')
            ->get();

        // Preparar dados para o relatório
        $labels = [];
        $data = [];
        $totalReservas = 0;
        $valorTotal = 0;

        // Agrupar por dia/mês/ano, dependendo do período
        if ($periodo === '7dias') {
            $intervalo = \Carbon\CarbonPeriod::create($dataInicio, $dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->format('d M');
                $reservasDia = $reservas->filter(function ($reserva) use ($dia) {
                    return Carbon::parse($reserva->created_at)->isSameDay($dia);
                });
                $count = $reservasDia->count();
                $data[] = $count;
                $totalReservas += $count;
                $valorTotal += $reservasDia->sum('valor');
            }
        } elseif ($periodo === '1mes') {
            $intervalo = \Carbon\CarbonPeriod::create($dataInicio, $dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->format('d M');
                $reservasDia = $reservas->filter(function ($reserva) use ($dia) {
                    return Carbon::parse($reserva->created_at)->isSameDay($dia);
                });
                $count = $reservasDia->count();
                $data[] = $count;
                $totalReservas += $count;
                $valorTotal += $reservasDia->sum('valor');
            }
        } elseif ($periodo === '1ano') {
            $intervalo = \Carbon\CarbonPeriod::create($dataInicio, $dataFim, '1 month');
            foreach ($intervalo as $mes) {
                $labels[] = $mes->format('M Y');
                $reservasMes = $reservas->filter(function ($reserva) use ($mes) {
                    return Carbon::parse($reserva->created_at)->isSameMonth($mes);
                });
                $count = $reservasMes->count();
                $data[] = $count;
                $totalReservas += $count;
                $valorTotal += $reservasMes->sum('valor');
            }
        }

        // Dados adicionais para o relatório
        $dadosRelatorio = [
            'titulo' => "Relatório de Reservas - " . ucfirst(str_replace('1', '1 ', $periodo)),
            'labels' => $labels,
            'data' => $data,
            'totalReservas' => $totalReservas,
            'valorTotal' => $valorTotal,
            'periodo' => $periodo,
            'reservas' => $reservas, // Lista completa para detalhamento
        ];

        return $dadosRelatorio;
    }
    
public function dadosGrafico(Request $request)
{
    $periodo = $request->get('periodo', '7dias');

    switch ($periodo) {
        case '1ano':
            $dataInicial = Carbon::now()->subYear();
            $formato = 'M Y'; // Ex: Jan 2025
            break;

        case '1mes':
            $dataInicial = Carbon::now()->subMonth();
            $formato = 'd M'; // Ex: 03 Abr
            break;

        case '7dias':
        default:
            $dataInicial = Carbon::now()->subDays(6);
            $formato = 'd M';
            break;
    }

    // Consulta principal usando 'data_entrada'
    $registros = DB::table('reservas')
        ->selectRaw("DATE_FORMAT(data_entrada, '%Y-%m-%d') as data, COUNT(*) as total")
        ->whereDate('data_entrada', '>=', $dataInicial)
        ->groupBy('data')
        ->orderBy('data')
        ->get();

    // Mapeamento
    $labels = [];
    $valores = [];
    $dadosMap = $registros->keyBy('data');

    $datasPeriodo = collect();

    if ($periodo === '1ano') {
        for ($i = 0; $i < 12; $i++) {
            $data = Carbon::now()->subMonths(11 - $i)->startOfMonth()->format('Y-m-d');
            $datasPeriodo->push($data);
        }
    } else {
        $dias = $periodo === '1mes' ? 30 : 7;
        for ($i = 0; $i < $dias; $i++) {
            $data = Carbon::now()->subDays($dias - $i - 1)->format('Y-m-d');
            $datasPeriodo->push($data);
        }
    }

    foreach ($datasPeriodo as $data) {
        $carbon = Carbon::parse($data);
        $label = $carbon->translatedFormat($formato);
        $labels[] = $label;
        $valores[] = isset($dadosMap[$data]) ? $dadosMap[$data]->total : 0;
    }

    // Cálculo de variação percentual
    $valorAtual = array_sum($valores);

    $valorAnterior = DB::table('reservas')
        ->whereBetween('data_entrada', [
            $dataInicial->copy()->subDays($datasPeriodo->count()),
            $dataInicial->copy()->subDay()
        ])
        ->count();

    $variacao = $valorAnterior > 0
        ? round((($valorAtual - $valorAnterior) / $valorAnterior) * 100, 2)
        : ($valorAtual > 0 ? 100 : 0);

    return response()->json([
        'titulo' => 'Reservas no Período',
        'labels' => $labels,
        'data' => $valores,
        'variacao' => $variacao
    ]);
}


}
