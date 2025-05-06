<?php

namespace App\Http\Controllers;

use App\Models\Quarto;
use App\Models\Funcionario;
use App\Models\Reserva;
use App\Models\Hospede;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $quartosDisponiveis = Quarto::where('status', 'Disponível')->count();
        $totalFuncionarios = Funcionario::count();
        $reservasAtivas = Reserva::where('status', 'Reservado')->count();
        $hospedesHospedados = Hospede::where('status', 'Hospedado')->count();
    
        $hoje = Carbon::today();
        $inicio = $hoje->copy()->subDays(6);
    
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
        $data = [];
    
        for ($date = $inicio; $date->lte($hoje); $date->addDay()) {
            $dataFormatada = $date->format('d M');
            $labels[] = $dataFormatada;
            $data[] = $dadosBrutos[$date->toDateString()]->total ?? 0;
        }
    
        $dadosGrafico = [
            'labels' => $labels,
            'data' => $data,
            'titulo' => 'Reservas nos Últimos 7 Dias'
        ];
    
        return view('dashboard.index', compact(
            'quartosDisponiveis',
            'totalFuncionarios',
            'reservasAtivas',
            'hospedesHospedados',
            'dadosGrafico'
        ));
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
