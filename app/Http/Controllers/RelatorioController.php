<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\Quarto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Se usar DomPDF
use App\Models\Checkout;
use App\Models\Reserva;

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

       private function obterDadosFaturamento($periodo)
    {
        $dataFim = now();
        if ($periodo === '7dias') {
            $dataInicio = now()->subDays(7);
        } elseif ($periodo === '30dias') {
            $dataInicio = now()->subDays(30);
        } elseif ($periodo === '12meses') {
            $dataInicio = now()->subMonths(12);
        } else {
            $dataInicio = now()->subDays(7);
        }

        // Buscar checkouts no intervalo com base em created_at
        $checkouts = Checkout::whereBetween('created_at', [$dataInicio, $dataFim])
            ->orderBy('created_at', 'desc')
            ->get();

        // Preparar dados para o relatório
        $labels = [];
        $data = [];
        $totalFaturamento = 0;

        if ($periodo === '7dias' || $periodo === '30dias') {
            $intervalo = Carbon::parse($dataInicio)->daysUntil($dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->format('d M');
                $checkoutsDia = $checkouts->filter(function ($checkout) use ($dia) {
                    return Carbon::parse($checkout->created_at)->isSameDay($dia);
                });
                $soma = $checkoutsDia->sum('valor_total');
                $data[] = $soma;
                $totalFaturamento += $soma;
            }
        } elseif ($periodo === '12meses') {
            $intervalo = Carbon::parse($dataInicio)->monthsUntil($dataFim);
            foreach ($intervalo as $mes) {
                $labels[] = $mes->format('M Y');
                $checkoutsMes = $checkouts->filter(function ($checkout) use ($mes) {
                    return Carbon::parse($checkout->created_at)->isSameMonth($mes);
                });
                $soma = $checkoutsMes->sum('valor_total');
                $data[] = $soma;
                $totalFaturamento += $soma;
            }
        }

        return [
            'titulo' => "Relatório de Faturamento - " . ucfirst(str_replace(['7dias', '30dias', '12meses'], ['7 Dias', '30 Dias', '12 Meses'], $periodo)),
            'labels' => $labels,
            'data' => $data,
            'totalFaturamento' => $totalFaturamento,
            'checkouts' => $checkouts,
        ];
    }

    public function relatorioServicosExtrasPDF(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $dados = $this->obterDadosServicosExtras($periodo);

        $pdf = Pdf::loadView('relatorios.servicos-extraspdf', compact('dados', 'periodo'));
        return $pdf->download('relatorio_servicos_extras_' . $periodo . '.pdf');
    }

    private function obterDadosServicosExtras($periodo)
    {
        $dataFim = now();
        if ($periodo === '7dias') {
            $dataInicio = now()->subDays(6); // 7 dias incluindo hoje
        } elseif ($periodo === '30dias') {
            $dataInicio = now()->subDays(29); // 30 dias incluindo hoje
        } elseif ($periodo === '12meses') {
            $dataInicio = now()->subMonths(11)->startOfMonth(); // 12 meses começando no início do mês
        } else {
            $dataInicio = now()->subDays(6);
        }

        $faturamento = DB::table('checkout_hospedes')
            ->join('hospedes', 'checkout_hospedes.hospede_id', '=', 'hospedes.id')
            ->join('hospede_servico', 'hospedes.id', '=', 'hospede_servico.hospede_id')
            ->join('servicos_adicionais', 'hospede_servico.servico_adicional_id', '=', 'servicos_adicionais.id')
            ->selectRaw("DATE_FORMAT(checkout_hospedes.data_checkout, '%Y-%m-%d') as data, SUM(servicos_adicionais.preco) as total")
            ->whereDate('checkout_hospedes.data_checkout', '>=', $dataInicio)
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        $labels = [];
        $dataFaturamento = [];
        $totalFaturamento = 0;

        if ($periodo === '7dias' || $periodo === '30dias') {
            $intervalo = Carbon::parse($dataInicio)->daysUntil($dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->translatedFormat('d M');
                $dataFormatada = $dia->format('Y-m-d');
                $faturamentoDia = isset($faturamento[$dataFormatada]) ? round($faturamento[$dataFormatada]->total, 2) : 0;
                $dataFaturamento[] = $faturamentoDia;
                $totalFaturamento += $faturamentoDia;
            }
        } elseif ($periodo === '12meses') {
            $intervalo = Carbon::parse($dataInicio)->monthsUntil($dataFim);
            foreach ($intervalo as $mes) {
                $labels[] = $mes->translatedFormat('M Y');
                $inicioMes = $mes->copy()->startOfMonth();
                $fimMes = $mes->copy()->endOfMonth();
                $totalMes = 0;

                for ($dia = $inicioMes; $dia <= $fimMes; $dia->addDay()) {
                    $diaFormatado = $dia->format('Y-m-d');
                    $totalMes += isset($faturamento[$diaFormatado]) ? $faturamento[$diaFormatado]->total : 0;
                }

                $dataFaturamento[] = round($totalMes, 2);
                $totalFaturamento += round($totalMes, 2);
            }
        }

        return [
            'titulo' => "Relatório de Serviços Extras - " . ucfirst(str_replace(['7dias', '30dias', '12meses'], ['7 Dias', '30 Dias', '12 Meses'], $periodo)),
            'labels' => $labels,
            'data' => $dataFaturamento,
            'total_faturamento' => $totalFaturamento,
        ];
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
    public function relatorioFaturamentoPDF(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $dados = $this->obterDadosFaturamento($periodo);

        $pdf = Pdf::loadView('relatorios.faturamentopdf', compact('dados', 'periodo'));
        return $pdf->download('relatorio_faturamento_' . $periodo . '.pdf');
    }

 

    public function relatorioReservasCancelamentosPDF(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $dados = $this->obterDadosReservasCancelamentos($periodo);

        // Depuração: Verificar os dados retornados
        // dd($dados);

        $pdf = Pdf::loadView('relatorios.reservas-cancelamentospdf', compact('dados', 'periodo'));
        return $pdf->download('relatorio_reservas_cancelamentos_' . $periodo . '.pdf');
    }

    private function obterDadosReservasCancelamentos($periodo)
    {
        $dataFim = now();
        if ($periodo === '7dias') {
            $dataInicio = now()->subDays(7);
        } elseif ($periodo === '30dias') {
            $dataInicio = now()->subDays(30);
        } elseif ($periodo === '12meses') {
            $dataInicio = now()->subMonths(12);
        } else {
            $dataInicio = now()->subDays(7);
        }

        // Buscar todas as reservas, incluindo as soft deleted
        $reservas = Reserva::withTrashed()
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->orderBy('created_at', 'desc')
            ->get();

        // Depuração: Verificar os dados brutos das reservas
        // dd($reservas->toArray());

        $labels = [];
        $dataCriadas = [];
        $dataCanceladas = [];
        $dataApagadas = [];
        $totalCriadas = 0;
        $totalCanceladas = 0;
        $totalApagadas = 0;

        if ($periodo === '7dias' || $periodo === '30dias') {
            $intervalo = Carbon::parse($dataInicio)->daysUntil($dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->format('d M');

                // Filtrar reservas do dia
                $reservasDia = $reservas->filter(function ($reserva) use ($dia) {
                    return Carbon::parse($reserva->created_at)->isSameDay($dia);
                });

                // Reservas criadas: status 'reservado' ou 'hospedado' e não deletadas
                $criadas = $reservasDia->whereIn('status', ['reservado', 'hospedado'])
                    ->whereNull('deleted_at')
                    ->count();

                // Reservas canceladas: status 'cancelado' e não deletadas
                $canceladas = $reservasDia->where('status', 'cancelado')
                    ->whereNull('deleted_at')
                    ->count();

                // Reservas apagadas: deleted_at não nulo
                $apagadas = $reservasDia->whereNotNull('deleted_at')->count();

                $dataCriadas[] = $criadas;
                $dataCanceladas[] = $canceladas;
                $dataApagadas[] = $apagadas;

                $totalCriadas += $criadas;
                $totalCanceladas += $canceladas;
                $totalApagadas += $apagadas;
            }
        } elseif ($periodo === '12meses') {
            $intervalo = Carbon::parse($dataInicio)->monthsUntil($dataFim);
            foreach ($intervalo as $mes) {
                $labels[] = $mes->format('M Y');

                // Filtrar reservas do mês
                $reservasMes = $reservas->filter(function ($reserva) use ($mes) {
                    return Carbon::parse($reserva->created_at)->isSameMonth($mes);
                });

                // Reservas criadas: status 'reservado' ou 'hospedado' e não deletadas
                $criadas = $reservasMes->whereIn('status', ['reservado', 'hospedado'])
                    ->whereNull('deleted_at')
                    ->count();

                // Reservas canceladas: status 'cancelado' e não deletadas
                $canceladas = $reservasMes->where('status', 'cancelado')
                    ->whereNull('deleted_at')
                    ->count();

                // Reservas apagadas: deleted_at não nulo
                $apagadas = $reservasMes->whereNotNull('deleted_at')->count();

                $dataCriadas[] = $criadas;
                $dataCanceladas[] = $canceladas;
                $dataApagadas[] = $apagadas;

                $totalCriadas += $criadas;
                $totalCanceladas += $canceladas;
                $totalApagadas += $apagadas;
            }
        }

        return [
            'titulo' => "Relatório de Reservas e Cancelamentos - " . ucfirst(str_replace(['7dias', '30dias', '12meses'], ['7 Dias', '30 Dias', '12 Meses'], $periodo)),
            'labels' => $labels,
            'data_criadas' => $dataCriadas,
            'data_canceladas' => $dataCanceladas,
            'data_apagadas' => $dataApagadas,
            'total_criadas' => $totalCriadas,
            'total_canceladas' => $totalCanceladas,
            'total_apagadas' => $totalApagadas,
            'reservas' => $reservas,
        ];
    }



    public function relatorioOcupacaoPDF(Request $request)
    {
        $periodo = $request->query('periodo', '7dias');
        $dados = $this->obterDadosOcupacao($periodo);

        $pdf = Pdf::loadView('relatorios.ocupacaopdf', compact('dados', 'periodo'));
        return $pdf->download('relatorio_ocupacao_' . $periodo . '.pdf');
    }

    private function obterDadosOcupacao($periodo)
    {
        $dataFim = now();
        if ($periodo === '7dias') {
            $dataInicio = now()->subDays(7);
        } elseif ($periodo === '30dias') {
            $dataInicio = now()->subDays(30);
        } elseif ($periodo === '12meses') {
            $dataInicio = now()->subMonths(12);
        } else {
            $dataInicio = now()->subDays(7);
        }

        // Buscar check-ins no intervalo, considerando data_entrada e data_saida
        $checkins = Checkin::where('data_entrada', '<=', $dataFim)
            ->where(function ($query) use ($dataInicio) {
                $query->where('data_saida', '>=', $dataInicio)
                    ->orWhereNull('data_saida');
            })
            ->where('status', 'hospedado') // Ajuste o status conforme necessário
            ->get();

        $labels = [];
        $data = [];
        $totalOcupacao = 0;

        if ($periodo === '7dias' || $periodo === '30dias') {
            $intervalo = Carbon::parse($dataInicio)->daysUntil($dataFim);
            foreach ($intervalo as $dia) {
                $labels[] = $dia->format('d M');
                $checkinsDia = $checkins->filter(function ($checkin) use ($dia) {
                    $entrada = Carbon::parse($checkin->data_entrada);
                    $saida = $checkin->data_saida ? Carbon::parse($checkin->data_saida) : now();
                    return $dia->between($entrada, $saida);
                });
                $ocupacao = $checkinsDia->count(); // Número de quartos ocupados no dia
                $data[] = $ocupacao;
                $totalOcupacao += $ocupacao;
            }
        } elseif ($periodo === '12meses') {
            $intervalo = Carbon::parse($dataInicio)->monthsUntil($dataFim);
            foreach ($intervalo as $mes) {
                $labels[] = $mes->format('M Y');
                $checkinsMes = $checkins->filter(function ($checkin) use ($mes) {
                    $entrada = Carbon::parse($checkin->data_entrada);
                    $saida = $checkin->data_saida ? Carbon::parse($checkin->data_saida) : now();
                    $mesInicio = $mes->copy()->startOfMonth();
                    $mesFim = $mes->copy()->endOfMonth();
                    return $entrada->lte($mesFim) && $saida->gte($mesInicio);
                });
                $ocupacao = $checkinsMes->count();
                $data[] = $ocupacao;
                $totalOcupacao += $ocupacao;
            }
        }

        return [
            'titulo' => "Relatório de Ocupação - " . ucfirst(str_replace(['7dias', '30dias', '12meses'], ['7 Dias', '30 Dias', '12 Meses'], $periodo)),
            'labels' => $labels,
            'data' => $data,
            'total_ocupacao' => $totalOcupacao,
            'checkins' => $checkins, // Para detalhes no PDF, se necessário
        ];
    }
}
