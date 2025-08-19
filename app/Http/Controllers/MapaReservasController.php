<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Quarto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MapaReservasController extends Controller
{
    public function index()
    {
        // Pega todos os quartos com número, andar e tipo
        $quartos = Quarto::with('tipo')
            ->orderBy('numero', 'asc')
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'nome' => "Quarto {$q->numero} - Andar {$q->andar} (" . ($q->tipo ? $q->tipo->nome : 'Sem Tipo') . ")",
                    'numero' => $q->numero,
                    'andar' => $q->andar,
                    'tipo' => $q->tipo ? $q->tipo->nome : 'Sem Tipo'
                ];
            });

        // Pega reservas ativas ou futuras
        $reservas = Reserva::with('quarto')
            ->where('data_saida', '>=', Carbon::today()->subDays(30)) // Últimos 30 dias e futuras
            ->orderBy('data_entrada', 'asc')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'quarto_id' => $r->quarto_id,
                    'cliente_nome' => $r->cliente_nome,
                    'data_entrada' => Carbon::parse($r->data_entrada)->format('Y-m-d'),
                    'data_saida' => Carbon::parse($r->data_saida)->format('Y-m-d'),
                    'status' => strtolower($r->status), // Padroniza para minúsculas
                ];
            });

        return view('reservas.mapas', compact('quartos', 'reservas'));
    }
}