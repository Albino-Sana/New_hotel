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

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
$quartos = Quarto::with(['tipo', 'checkin', 'hospede'])
                ->whereIn('status', ['Disponível', 'Ocupado', 'Reservado'])
                ->orderBy('numero')
                ->get();

                         // Busca todas as reservas ativas
                    $reservas = Reserva::where('status', 'reservado')
                    ->where('data_entrada', '>=', now()->format('Y-m-d'))
                    ->get();

                $checkin = Checkin::where('status', 'hospedado')->get();
           $hospedesHospedados = Hospede::where('status', 'Hospedado')->with('quarto')->get();

                  $servicosAdicionais = ServicoAdicional::all();
                    
        return view('POS.pos1', [
            'nomeUsuario' => $user->name,
            'cargo' => $user->cargo,
            'tipo' => $user->tipo,
            'quartos' => $quartos,
            'reservas' => $reservas,
            'checkin' => $checkin,
            'servicosAdicionais' => $servicosAdicionais,
              'hospedesHospedados' => $hospedesHospedados

        ]);
    }


    public function storeCheckout(Request $request)
    {
        // Lógica de checkout
    }

    public function storeConsumo(Request $request)
    {
        // Lógica para adicionar consumo
    }
}