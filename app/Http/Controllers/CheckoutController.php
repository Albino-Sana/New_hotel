<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Checkout;
use Carbon\Carbon;
use App\Models\Hospede;
use App\Models\Quarto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ServicoAdicional; // <- nova linha

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'checkin_id' => 'required|exists:checkins,id',
            'servicos' => 'nullable|array',
            'servicos.*' => 'exists:servicos_adicionais,id',
        ]);
    
        try {
            $checkin = Checkin::with('quarto', 'reserva.hospede')->findOrFail($request->checkin_id);
    
            // 1. Calcular valor da hospedagem
            $dias = Carbon::parse($checkin->data_entrada)->diffInDays(Carbon::parse($checkin->data_saida));
            $valorEstadia = $dias * $checkin->quarto->preco_noite;
    
            // 2. Calcular valor dos serviços adicionais
            $valorServicos = 0;
            if ($request->has('servicos')) {
                $servicosSelecionados = ServicoAdicional::whereIn('id', $request->servicos)->get();
                $valorServicos = $servicosSelecionados->sum('preco');
    
                if ($checkin->reserva && $checkin->reserva->hospede) {
                    $checkin->reserva->hospede->servicosAdicionais()->sync($request->servicos);
                }
                
            }
    
            // 3. Somar os valores
            $valorTotal = $valorEstadia + $valorServicos;
    
            // 4. Criar checkout
            Checkout::create([
                'checkin_id' => $checkin->id,
                'data_checkout' => now(),
                'valor_total' => $valorTotal,
            ]);
    
            // Atualizações de status
            $checkin->quarto->update(['status' => 'disponível']);
            $checkin->reserva->update(['status' => 'finalizado']);
            $checkin->update(['status' => 'concluído']);
    
            return redirect()->back()->with('success', 'Check-out realizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao realizar check-out: ' . $e->getMessage());
        }
    }
    
    
    public function fatura($id)
{
    $checkin = Checkin::with(['reserva', 'quarto'])->findOrFail($id);

    $pdf = PDF::loadView('pdf.fatura', compact('checkin'));
    return $pdf->download('fatura_' . $checkin->id . '.pdf');
}

    
}
