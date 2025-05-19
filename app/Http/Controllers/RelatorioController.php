<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\Quarto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function ocupacao()
    {
        return view('relatorios.ocupacao');
    }

    public function dadosOcupacao(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $totalQuartos = Quarto::count();
        $hoje = Carbon::today();

        if ($totalQuartos === 0) {
            return response()->json([
                'labels' => [],
                'data' => [],
                'titulo' => 'Nenhum quarto registrado',
                'variacao' => 0
            ]);
        }

        switch ($periodo) {
            case '12meses':
                $dataInicial = $hoje->copy()->subMonths(11)->startOfMonth();
                $formato = 'M Y';
                break;
            case '30dias':
                $dataInicial = $hoje->copy()->subDays(29);
                $formato = 'd M';
                break;
            case '7dias':
            default:
                $dataInicial = $hoje->copy()->subDays(6);
                $formato = 'd M';
                break;
        }

        // Mapear datas do período
        $datasPeriodo = collect();
        if ($periodo === '12meses') {
            for ($i = 0; $i < 12; $i++) {
                $data = $hoje->copy()->subMonths(11 - $i)->startOfMonth()->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            for ($i = 0; $i < $dias; $i++) {
                $data = $hoje->copy()->subDays($dias - $i - 1)->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        }

        // Calcular ocupação por dia
        $labels = [];
        $valores = [];

        foreach ($datasPeriodo as $data) {
            $carbon = Carbon::parse($data);
            $label = $carbon->translatedFormat($formato);
            $labels[] = $label;

            if ($periodo === '12meses') {
                // Ocupação média mensal
                $inicioMes = $carbon->copy()->startOfMonth();
                $fimMes = $carbon->copy()->endOfMonth();
                $diasNoMes = $inicioMes->daysInMonth;
                $ocupacaoTotal = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $ocupados = min(
                        Checkin::whereDate('data_entrada', '<=', $dia)
                            ->whereDate('data_saida', '>=', $dia)
                            ->count(),
                        $totalQuartos
                    );
                    $ocupacaoTotal += $ocupados;
                }

                $valores[] = round($ocupacaoTotal / $diasNoMes, 2);
            } else {
                // Ocupação diária
                $ocupados = min(
                    Checkin::whereDate('data_entrada', '<=', $carbon)
                        ->whereDate('data_saida', '>=', $carbon)
                        ->count(),
                    $totalQuartos
                );
                $valores[] = $ocupados;
            }
        }

        // Cálculo de variação percentual
        $valorAtual = array_sum($valores);
        $valorAnterior = 0;

        if ($periodo === '12meses') {
            $dataAnterior = $dataInicial->copy()->subMonths(12);
            for ($data = $dataAnterior; $data < $dataInicial; $data->addMonth()) {
                $inicioMes = $data->copy()->startOfMonth();
                $fimMes = $data->copy()->endOfMonth();
                $diasNoMes = $inicioMes->daysInMonth;
                $ocupacaoTotal = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $ocupados = min(
                        Checkin::whereDate('data_entrada', '<=', $dia)
                            ->whereDate('data_saida', '>=', $dia)
                            ->count(),
                        $totalQuartos
                    );
                    $ocupacaoTotal += $ocupados;
                }

                $valorAnterior += $ocupacaoTotal / $diasNoMes;
            }
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            $dataAnterior = $dataInicial->copy()->subDays($dias);
            for ($data = $dataAnterior; $data < $dataInicial; $data->addDay()) {
                $ocupados = min(
                    Checkin::whereDate('data_entrada', '<=', $data)
                        ->whereDate('data_saida', '>=', $data)
                        ->count(),
                    $totalQuartos
                );
                $valorAnterior += $ocupados;
            }
        }

        $variacao = $valorAnterior > 0
            ? round((($valorAtual - $valorAnterior) / $valorAnterior) * 100, 2)
            : ($valorAtual > 0 ? 100 : 0);

        return response()->json([
            'titulo' => 'Ocupação no Período',
            'labels' => $labels,
            'data' => $valores,
            'variacao' => $variacao
        ]);
    }


    public function reservasCancelamentos()
    {
        return view('relatorios.reservas_cancelamentos');
    }

    public function dadosReservasCancelamentos(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $hoje = Carbon::today();

        switch ($periodo) {
            case '12meses':
                $dataInicial = $hoje->copy()->subMonths(11)->startOfMonth();
                $formato = 'M Y';
                break;
            case '30dias':
                $dataInicial = $hoje->copy()->subDays(29);
                $formato = 'd M';
                break;
            case '7dias':
            default:
                $dataInicial = $hoje->copy()->subDays(6);
                $formato = 'd M';
                break;
        }

        // Mapear datas do período
        $datasPeriodo = collect();
        if ($periodo === '12meses') {
            for ($i = 0; $i < 12; $i++) {
                $data = $hoje->copy()->subMonths(11 - $i)->startOfMonth()->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            for ($i = 0; $i < $dias; $i++) {
                $data = $hoje->copy()->subDays($dias - $i - 1)->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        }

        // Consultas para reservas criadas, canceladas e apagadas
        $criadas = DB::table('reservas')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as data, COUNT(*) as total")
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereNull('deleted_at')
            ->where('status', '!=', 'finalizado')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        $canceladas = DB::table('reservas')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as data, COUNT(*) as total")
            ->whereDate('created_at', '>=', $dataInicial)
            ->whereNull('deleted_at')
            ->where('status', 'finalizado')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        $apagadas = DB::table('reservas')
            ->selectRaw("DATE_FORMAT(deleted_at, '%Y-%m-%d') as data, COUNT(*) as total")
            ->whereDate('deleted_at', '>=', $dataInicial)
            ->whereNotNull('deleted_at')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        // Mapear dados para os gráficos
        $labels = [];
        $dataCriadas = [];
        $dataCanceladas = [];
        $dataApagadas = [];

        foreach ($datasPeriodo as $data) {
            $carbon = Carbon::parse($data);
            $label = $carbon->translatedFormat($formato);
            $labels[] = $label;

            if ($periodo === '12meses') {
                // Contagem mensal (soma dos dias do mês)
                $inicioMes = $carbon->copy()->startOfMonth();
                $fimMes = $carbon->copy()->endOfMonth();
                $totalCriadas = 0;
                $totalCanceladas = 0;
                $totalApagadas = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $diaFormatado = $dia->format('Y-m-d');
                    $totalCriadas += isset($criadas[$diaFormatado]) ? $criadas[$diaFormatado]->total : 0;
                    $totalCanceladas += isset($canceladas[$diaFormatado]) ? $canceladas[$diaFormatado]->total : 0;
                    $totalApagadas += isset($apagadas[$diaFormatado]) ? $apagadas[$diaFormatado]->total : 0;
                }

                $dataCriadas[] = $totalCriadas;
                $dataCanceladas[] = $totalCanceladas;
                $dataApagadas[] = $totalApagadas;
            } else {
                // Contagem diária
                $dataCriadas[] = isset($criadas[$data]) ? $criadas[$data]->total : 0;
                $dataCanceladas[] = isset($canceladas[$data]) ? $canceladas[$data]->total : 0;
                $dataApagadas[] = isset($apagadas[$data]) ? $apagadas[$data]->total : 0;
            }
        }

        // Cálculo de variação (baseado na soma de reservas criadas)
        $valorAtual = array_sum($dataCriadas);
        $valorAnterior = 0;

        if ($periodo === '12meses') {
            $dataAnterior = $dataInicial->copy()->subMonths(12);
            $anterior = DB::table('reservas')
                ->whereBetween('created_at', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->whereNull('deleted_at')
                ->where('status', '!=', 'finalizado')
                ->count();
            $valorAnterior = $anterior;
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            $dataAnterior = $dataInicial->copy()->subDays($dias);
            $anterior = DB::table('reservas')
                ->whereBetween('created_at', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->whereNull('deleted_at')
                ->where('status', '!=', 'finalizado')
                ->count();
            $valorAnterior = $anterior;
        }

        $variacao = $valorAnterior > 0
            ? round((($valorAtual - $valorAnterior) / $valorAnterior) * 100, 2)
            : ($valorAtual > 0 ? 100 : 0);

        return response()->json([
            'titulo' => 'Reservas e Cancelamentos no Período',
            'labels' => $labels,
            'data_criadas' => $dataCriadas,
            'data_canceladas' => $dataCanceladas,
            'data_apagadas' => $dataApagadas,
            'variacao' => $variacao
        ]);
    }


    public function faturamento()
    {
        return view('relatorios.faturamento');
    }

    public function dadosFaturamento(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $hoje = Carbon::today();

        switch ($periodo) {
            case '12meses':
                $dataInicial = $hoje->copy()->subMonths(11)->startOfMonth();
                $formato = 'M Y';
                break;
            case '30dias':
                $dataInicial = $hoje->copy()->subDays(29);
                $formato = 'd M';
                break;
            case '7dias':
            default:
                $dataInicial = $hoje->copy()->subDays(6);
                $formato = 'd M';
                break;
        }

        // Mapear datas do período
        $datasPeriodo = collect();
        if ($periodo === '12meses') {
            for ($i = 0; $i < 12; $i++) {
                $data = $hoje->copy()->subMonths(11 - $i)->startOfMonth()->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            for ($i = 0; $i < $dias; $i++) {
                $data = $hoje->copy()->subDays($dias - $i - 1)->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        }

        // Consulta para faturamento (soma de valor_total de reservas com check-ins finalizados)
        $faturamento = DB::table('checkins')
            ->join('reservas', 'checkins.reserva_id', '=', 'reservas.id')
            ->selectRaw("DATE_FORMAT(checkins.data_saida, '%Y-%m-%d') as data, SUM(reservas.valor_total) as total")
            ->whereDate('checkins.data_saida', '>=', $dataInicial)
            ->where('checkins.status', 'concluído')
            ->whereNull('reservas.deleted_at')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        // Mapear dados para o gráfico
        $labels = [];
        $dataFaturamento = [];

        foreach ($datasPeriodo as $data) {
            $carbon = Carbon::parse($data);
            $label = $carbon->translatedFormat($formato);
            $labels[] = $label;

            if ($periodo === '12meses') {
                // Soma mensal
                $inicioMes = $carbon->copy()->startOfMonth();
                $fimMes = $carbon->copy()->endOfMonth();
                $totalMes = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $diaFormatado = $dia->format('Y-m-d');
                    $totalMes += isset($faturamento[$diaFormatado]) ? $faturamento[$diaFormatado]->total : 0;
                }

                $dataFaturamento[] = round($totalMes, 2);
            } else {
                // Soma diária
                $dataFaturamento[] = isset($faturamento[$data]) ? round($faturamento[$data]->total, 2) : 0;
            }
        }

        // Cálculo de variação percentual
        $valorAtual = array_sum($dataFaturamento);
        $valorAnterior = 0;

        if ($periodo === '12meses') {
            $dataAnterior = $dataInicial->copy()->subMonths(12);
            $anterior = DB::table('checkins')
                ->join('reservas', 'checkins.reserva_id', '=', 'reservas.id')
                ->whereBetween('checkins.data_saida', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->where('checkins.status', 'concluído')
                ->whereNull('reservas.deleted_at')
                ->sum('reservas.valor_total');
            $valorAnterior = $anterior ?? 0;
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            $dataAnterior = $dataInicial->copy()->subDays($dias);
            $anterior = DB::table('checkins')
                ->join('reservas', 'checkins.reserva_id', '=', 'reservas.id')
                ->whereBetween('checkins.data_saida', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->where('checkins.status', 'concluído')
                ->whereNull('reservas.deleted_at')
                ->sum('reservas.valor_total');
            $valorAnterior = $anterior ?? 0;
        }

        $variacao = $valorAnterior > 0
            ? round((($valorAtual - $valorAnterior) / $valorAnterior) * 100, 2)
            : ($valorAtual > 0 ? 100 : 0);

        return response()->json([
            'titulo' => 'Faturamento no Período',
            'labels' => $labels,
            'data' => $dataFaturamento,
            'variacao' => $variacao
        ]);
    }


    public function servicosExtras()
    {
        return view('relatorios.servicos_extras');
    }

    public function dadosServicosExtras(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $hoje = Carbon::today();

        switch ($periodo) {
            case '12meses':
                $dataInicial = $hoje->copy()->subMonths(11)->startOfMonth();
                $formato = 'M Y';
                break;
            case '30dias':
                $dataInicial = $hoje->copy()->subDays(29);
                $formato = 'd M';
                break;
            case '7dias':
            default:
                $dataInicial = $hoje->copy()->subDays(6);
                $formato = 'd M';
                break;
        }

        $datasPeriodo = collect();
        if ($periodo === '12meses') {
            for ($i = 0; $i < 12; $i++) {
                $data = $hoje->copy()->subMonths(11 - $i)->startOfMonth()->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            for ($i = 0; $i < $dias; $i++) {
                $data = $hoje->copy()->subDays($dias - $i - 1)->format('Y-m-d');
                $datasPeriodo->push($data);
            }
        }

        $faturamento = DB::table('checkout_hospedes')
            ->join('hospedes', 'checkout_hospedes.hospede_id', '=', 'hospedes.id')
            ->join('hospede_servico', 'hospedes.id', '=', 'hospede_servico.hospede_id')
            ->join('servicos_adicionais', 'hospede_servico.servico_adicional_id', '=', 'servicos_adicionais.id')
            ->selectRaw("DATE_FORMAT(checkout_hospedes.data_checkout, '%Y-%m-%d') as data, SUM(servicos_adicionais.preco) as total")
            ->whereDate('checkout_hospedes.data_checkout', '>=', $dataInicial)
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        $labels = [];
        $dataFaturamento = [];

        foreach ($datasPeriodo as $data) {
            $carbon = Carbon::parse($data);
            $label = $carbon->translatedFormat($formato);
            $labels[] = $label;

            if ($periodo === '12meses') {
                $inicioMes = $carbon->copy()->startOfMonth();
                $fimMes = $carbon->copy()->endOfMonth();
                $totalMes = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $diaFormatado = $dia->format('Y-m-d');
                    $totalMes += isset($faturamento[$diaFormatado]) ? $faturamento[$diaFormatado]->total : 0;
                }

                $dataFaturamento[] = round($totalMes, 2);
            } else {
                $dataFaturamento[] = isset($faturamento[$data]) ? round($faturamento[$data]->total, 2) : 0;
            }
        }

        $valorAtual = array_sum($dataFaturamento);
        $valorAnterior = 0;

        if ($periodo === '12meses') {
            $dataAnterior = $dataInicial->copy()->subMonths(12);
            $anterior = DB::table('checkout_hospedes')
                ->join('hospedes', 'checkout_hospedes.hospede_id', '=', 'hospedes.id')
                ->join('hospede_servico', 'hospedes.id', '=', 'hospede_servico.hospede_id')
                ->join('servicos_adicionais', 'hospede_servico.servico_adicional_id', '=', 'servicos_adicionais.id')
                ->whereBetween('checkout_hospedes.data_checkout', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->sum('servicos_adicionais.preco');
            $valorAnterior = $anterior ?? 0;
        } else {
            $dias = $periodo === '30dias' ? 30 : 7;
            $dataAnterior = $dataInicial->copy()->subDays($dias);
            $anterior = DB::table('checkout_hospedes')
                ->join('hospedes', 'checkout_hospedes.hospede_id', '=', 'hospedes.id')
                ->join('hospede_servico', 'hospedes.id', '=', 'hospede_servico.hospede_id')
                ->join('servicos_adicionais', 'hospede_servico.servico_adicional_id', '=', 'servicos_adicionais.id')
                ->whereBetween('checkout_hospedes.data_checkout', [$dataAnterior, $dataInicial->copy()->subDay()])
                ->sum('servicos_adicionais.preco');
            $valorAnterior = $anterior ?? 0;
        }

        $variacao = $valorAnterior > 0
            ? round((($valorAtual - $valorAnterior) / $valorAnterior) * 100, 2)
            : ($valorAtual > 0 ? 100 : 0);

        return response()->json([
            'titulo' => 'Serviços Extras Vendidos no Período',
            'labels' => $labels,
            'data' => $dataFaturamento,
            'variacao' => $variacao
        ]);
    }
    
}