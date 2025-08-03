<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quarto;
use App\Models\Reserva;
use App\Models\Checkin;
use App\Models\Consumo;
use App\Models\ServicoAdicional;
use App\Models\Hospede;
use App\Models\Pagamento;
use App\Models\Empresa;
use App\Models\PagamentoMetodo;

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $quartosTodos = Quarto::with(['tipo', 'checkin', 'hospede'])->get(); // Para exibir todos os quartos com tudo já carregado
        $quartosDisponiveis = $quartosTodos->where('status', 'Disponivel'); // Usa o mesmo resultado para filtrar os disponíveis

        // Busca todas as reservas ativas
        $reservas = Reserva::with('hospede')
            ->whereDoesntHave('checkin') // só reservas que ainda não têm check-in
            ->where('status', '!=', 'finalizada') // e não estão finalizadas
            ->get();

        $checkin = Checkin::where('status', 'hospedado')->get();
        $hospedesHospedados = Hospede::where('status', 'Hospedado')->with('quarto')->get();

        $pagamentosPendentes = Pagamento::with(['hospede', 'checkin.reserva'])
            ->where('status_pagamento', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();
        $pagamentosPagos = Pagamento::with(['hospede', 'checkin.reserva'])
            ->where('status_pagamento', 'pago')
            ->orderBy('created_at', 'desc')
            ->get();

        $servicosAdicionais = ServicoAdicional::all();

        $pagamentos = Pagamento::latest()
            ->with(['checkin.reserva', 'hospede'])
            ->get();
        $checkins = Checkin::doesntHave('pagamento')->get();
        $hospedes = Hospede::doesntHave('pagamento')->get();
        $metodos_pagamento = PagamentoMetodo::all();

        return view('POS.pos1', [
            'nomeUsuario' => $user->name,
            'cargo' => $user->cargo,
            'tipo' => $user->tipo,
            'reservas' => $reservas,
            'checkin' => $checkin,
            'servicosAdicionais' => $servicosAdicionais, // Corrigido: $servicosAdicional para $servicosAdicionais
            'quartos' => $quartosTodos, // Agora contém os relacionamentos
            'quartosDisponiveis' => $quartosDisponiveis, // Só os disponíveis para o select
            'hospedesHospedados' => $hospedesHospedados,
            'pagamentosPendentes' => $pagamentosPendentes,
            'pagamentos' => $pagamentos,
            'checkins' => $checkins,
            'hospedes' => $hospedes,
            'metodos_pagamento' => $metodos_pagamento,
            'pagamentosPagos' => $pagamentosPagos,

        ]);
    }


}
