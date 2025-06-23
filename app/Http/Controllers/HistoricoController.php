<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Checkin;
use App\Models\Hospede;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function index(Request $request)
    {
        $dataInicio = $request->query('data_inicio');
        $dataFim = $request->query('data_fim');
        $quartoId = $request->query('quarto_id');
        $status = $request->query('status');

        // Buscar reservas
        $reservasQuery = Reserva::query();
        if ($dataInicio) {
            $reservasQuery->where('data_entrada', '>=', $dataInicio);
        }
        if ($dataFim) {
            $reservasQuery->where('data_saida', '<=', $dataFim);
        }
        if ($quartoId) {
            $reservasQuery->where('quarto_id', $quartoId);
        }
        if ($status) {
            if ($status === 'Reservado') {
                $reservasQuery->where('status', 'Reservado');
            }
        }
        $reservas = $reservasQuery->with('quarto')->get();

        // Buscar check-ins
        $checkinsQuery = Checkin::query();
        if ($dataInicio) {
            $checkinsQuery->where('data_entrada', '>=', $dataInicio);
        }
        if ($dataFim) {
            $checkinsQuery->where('data_saida', '<=', $dataFim);
        }
        if ($quartoId) {
            $checkinsQuery->where('quarto_id', $quartoId);
        }
        if ($status === 'Check-in') {
            $checkinsQuery->whereNotNull('data_entrada');
        }
        $checkins = $checkinsQuery->with('quarto', 'reserva')->get();

        // Buscar hóspedes diretos (sem reserva)
        $hospedesQuery = Hospede::query();
        if ($dataInicio) {
            $hospedesQuery->where('data_entrada', '>=', $dataInicio);
        }
        if ($dataFim) {
            $hospedesQuery->where('data_saida', '<=', $dataFim);
        }
        if ($quartoId) {
            $hospedesQuery->where('quarto_id', $quartoId);
        }
        if ($status === 'Check-out') {
            $hospedesQuery->whereNotNull('data_saida');
        }
        $hospedes = $hospedesQuery->with('quarto')->get();

        // Combinar dados
        $historico = [];
        foreach ($reservas as $reserva) {
            $historico[] = [
                'tipo' => 'Reserva',
                'id' => $reserva->id,
                'cliente' => $reserva->cliente_nome,
                'quarto' => $reserva->quarto ? $reserva->quarto->numero : 'Excluido',
                'data_entrada' => \Carbon\Carbon::parse($reserva->data_entrada)->format('d/m/Y'),
                'data_saida' => \Carbon\Carbon::parse($reserva->data_saida)->format('d/m/Y'),
                'valor_total' => number_format($reserva->valor_total, 2, ',', '.'),
                'status' => $reserva->status,
            ];
        }
        foreach ($checkins as $checkin) {
            $historico[] = [
                'tipo' => 'Check-in',
                'id' => $checkin->id,
                'cliente' => $checkin->reserva ? $checkin->reserva->cliente_nome : 'Excluido',
                'quarto' => $checkin->quarto ? $checkin->quarto->numero : 'Excluido',
                'data_entrada' => \Carbon\Carbon::parse($checkin->data_entrada)->format('d/m/Y H:i'),
                'data_saida' => $checkin->data_saida ? \Carbon\Carbon::parse($checkin->data_saida)->format('d/m/Y H:i') : 'Excluido',
                'valor_total' => number_format($checkin->reserva->valor_total ?? 0, 2, ',', '.'),
                'status' => $checkin->data_saida ? 'Check-out' : 'Check-in',
            ];
        }
        foreach ($hospedes as $hospede) {
            $historico[] = [
                'tipo' => 'Hóspede Direto',
                'id' => $hospede->id,
                'cliente' => $hospede->nome,
                'quarto' => $hospede->quarto ? $hospede->quarto->numero : 'Excluido',
                'data_entrada' => \Carbon\Carbon::parse($hospede->data_entrada)->format('d/m/Y'),
                'data_saida' => $hospede->data_saida ? \Carbon\Carbon::parse($hospede->data_saida)->format('d/m/Y') : 'N/A',
                'valor_total' => number_format($hospede->valor_a_pagar, 2, ',', '.'),
                'status' => $hospede->data_saida ? 'Check-out' : 'Check-in',
            ];
        }

        return response()->json($historico);
    }
}