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
use App\Models\Pagamento;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;

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
            DB::beginTransaction();

            $checkin = Checkin::with('quarto', 'reserva.hospede')->findOrFail($request->checkin_id);

            // 1. Calcular valor da hospedagem
            $dias = Carbon::parse($checkin->data_entrada)->diffInDays(Carbon::parse($checkin->data_saida));
            $valorEstadia = $dias * ($checkin->quarto->preco_noite ?? 0);

            // 2. Calcular valor dos serviços adicionais
            $valorServicos = 0;
            $servicosSelecionados = collect();


            // 3. Somar os valores
            $valorTotal = $valorEstadia + $valorServicos;

            // 4. Criar checkout
            $checkout = Checkout::create([
                'checkin_id' => $checkin->id,
                'data_checkout' => now(),
                'valor_total' => $valorTotal,
            ]);

            if ($request->has('servicos')) {
                $servicosSelecionados = ServicoAdicional::whereIn('id', $request->servicos)->get();
                $valorServicos = $servicosSelecionados->sum('preco');

                if ($servicosSelecionados->isNotEmpty()) {
                    $checkout->servicosAdicionais()->attach($servicosSelecionados->pluck('id'));
                }
            }

            // 5. Criar pagamento (se não existir)
            $pagamento = Pagamento::firstOrCreate(
                ['checkin_id' => $checkin->id],
                [
                    'valor' => $valorTotal,
                    'metodo_pagamento' => 'pendente', // Pode ser ajustado para um método padrão
                    'status_pagamento' => 'pendente',
                    'data_pagamento' => now(),
                    'hospede_id' => $checkin->reserva->hospede_id ?? null,
                ]
            );

         
            // Atualiza o quarto, se existir
            if ($checkin->quarto) {
                $checkin->quarto->update(['status' => 'disponível']);
            }

            // Atualiza a reserva, se existir
            if ($checkin->reserva) {
                $checkin->reserva->update(['status' => 'finalizado']);
            }

            // Atualiza o próprio checkin
            $checkin->update(['status' => 'concluído']);

            DB::commit();

            // 7. Gerar e retornar o recibo de estadia
            session()->flash('recibo_estadia_id', $checkout->id);
            return redirect()->back()->with('success', 'Check-out realizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao realizar check-out: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao realizar check-out: ' . $e->getMessage());
        }
    }

    public function reciboEstadia($id)
    {
        try {
            $checkout = Checkout::with(['checkin.reserva.hospede', 'checkin.quarto', 'checkin.reserva', 'servicosAdicionais'])->findOrFail($id);
            $empresa = Empresa::firstOrFail();
            $pagamento = Pagamento::where('checkin_id', $checkout->checkin_id)->first();
            $servicos = $checkout->servicosAdicionais;

            $pdf = Pdf::loadView('pdf.recibo_estadia', compact('checkout', 'empresa', 'pagamento', 'servicos'));
            return $pdf->stream('recibo_estadia_' . $checkout->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar recibo de estadia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar recibo de estadia: ' . $e->getMessage());
        }
    }
}
