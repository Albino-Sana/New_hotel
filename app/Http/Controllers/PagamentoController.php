<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\PagamentoMetodo;
use App\Models\Checkin;
use App\Models\Hospede;
use App\Models\Reserva;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::latest()
            ->with(['checkin.reserva', 'hospede'])
            ->get();
        $checkins = Checkin::doesntHave('pagamento')->get();
        $hospedes = Hospede::doesntHave('pagamento')->get();
        $metodos_pagamento = PagamentoMetodo::all();

        return view('pagamentos.index', compact('pagamentos', 'checkins', 'hospedes', 'metodos_pagamento'));
    }

    public function valorPorCheckin($id)
    {
        $checkin = Checkin::with('reserva')->find($id);

        if (!$checkin || !$checkin->reserva) {
            return response()->json(['valor' => null, 'erro' => 'Valor inválido ou não encontrado para este pagamento.']);
        }

        return response()->json(['valor' => $checkin->reserva->valor_total ?? 0]);
    }

    public function valorPorHospede($id)
    {
        $hospede = Hospede::find($id);
        if (!$hospede) {
            return response()->json(['error' => 'Hóspede não encontrado']);
        }

        return response()->json(['valor' => $hospede->valor_a_pagar ?? 0]);
    }

    public function store(Request $request)
    {
        // Logar os dados recebidos para depuração
        Log::info('Dados recebidos no store:', $request->all());

        $request->validate([
            'valor' => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|string|max:255|exists:pagamentos_metodos,designacao',
            'status_pagamento' => 'required|in:pendente,pago,falhou',
            'checkin_id' => 'nullable|exists:checkins,id',
            'hospede_id' => 'nullable|exists:hospedes,id',
            'gerar_fatura' => 'sometimes|in:1,0,on',
        ]);

        try {
            $valor = $request->valor;

            if ($request->filled('checkin_id')) {
                $checkin = Checkin::find($request->checkin_id);
                if ($checkin && $checkin->reserva) {
                    $valor = $checkin->reserva->valor_total ?? $valor;
                }
            } elseif ($request->filled('hospede_id')) {
                $hospede = Hospede::find($request->hospede_id);
                if ($hospede) {
                    $valor = $hospede->valor_a_pagar ?? $valor;
                }
            }

            if ($valor <= 0) {
                return redirect()->back()->with('error', 'Valor inválido ou não encontrado para este pagamento.');
            }

            $pagamento = Pagamento::create([
                'valor' => $valor,
                'metodo_pagamento' => $request->metodo_pagamento,
                'status_pagamento' => $request->status_pagamento,
                'checkin_id' => $request->checkin_id,
                'hospede_id' => $request->hospede_id,
                'data_pagamento' => now(),
            ]);

            // Converter 'on' ou '1' para booleano
            $gerarFatura = in_array($request->input('gerar_fatura'), ['1', 'on'], true);

            if ($gerarFatura) {
                // Armazenar o ID do pagamento na sessão para abrir a fatura em outra janela
                return redirect()->route('pagamentos.index')->with(['success' => 'Pagamento registado com sucesso.', 'fatura_id' => $pagamento->id]);
            }

            return redirect()->route('pagamentos.index')->with('success', 'Pagamento registado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao registrar pagamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao registrar pagamento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'status_pagamento' => 'required|in:pendente,pago,falhou',
            'origem' => 'required|in:checkin,hospede',
            'checkin_id' => 'nullable|exists:checkins,id',
            'hospede_id' => 'nullable|exists:hospedes,id',
            'metodo_pagamento' => 'required|string|max:255|exists:pagamentos_metodos,designacao',
        ]);

        try {
            $pagamento = Pagamento::findOrFail($id);

            $pagamento->checkin_id = null;
            $pagamento->hospede_id = null;

            if ($request->origem === 'checkin') {
                $pagamento->checkin_id = $request->checkin_id;
            } else {
                $pagamento->hospede_id = $request->hospede_id;
            }

            $pagamento->valor = $request->valor;
            $pagamento->status_pagamento = $request->status_pagamento;
            $pagamento->metodo_pagamento = $request->metodo_pagamento;

            $pagamento->save();

            return redirect()->back()->with('success', 'Pagamento atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar pagamento: ' . $e->getMessage());
        }
    }

    public function fatura($id)
    {
        $pagamento = Pagamento::with(['checkin.reserva', 'hospede'])->findOrFail($id);
        $empresa = Empresa::firstOrFail();

        $pdf = Pdf::loadView('pagamentos.fatura', compact('pagamento', 'empresa'));

        return $pdf->stream('fatura_pagamento_' . $pagamento->id . '.pdf');
    }

    public function destroy($id)
    {
        try {
            $pagamento = Pagamento::findOrFail($id);
            $pagamento->delete();

            return redirect()->route('pagamentos.index')->with('success', 'Pagamento removido com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao remover pagamento: ' . $e->getMessage());
        }
    }
}