<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Checkin;
use App\Models\Hospede;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;

class PagamentoController extends Controller
{
public function index() 
{
    // Lista todos os pagamentos, agora com 'checkin' e 'hospede' carregados
    $pagamentos = Pagamento::latest()->with('checkin', 'hospede')->get();

    // Filtra checkins que ainda não têm pagamento
    $checkins = Checkin::doesntHave('pagamento')->get();

    // Filtra hóspedes que ainda não têm pagamento (se necessário, dependendo da lógica do seu sistema)
    $hospedes = Hospede::doesntHave('pagamento')->get();
$pagamentos = Pagamento::latest()
    ->with(['checkin.reserva', 'hospede']) // precisa do checkin->reserva para pegar o cliente_nome
    ->get();

    return view('pagamentos.index', compact('pagamentos', 'checkins', 'hospedes'));
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
        if (!$hospede) return response()->json(['valor' => null]);

        return response()->json(['valor' => $hospede->valor_a_pagar ?? 0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|string|max:30',
            'status_pagamento' => 'required|in:pendente,pago,falhou',
            'checkin_id' => 'nullable|exists:checkins,id',
            'hospede_id' => 'nullable|exists:hospedes,id',
        ]);

        try {
            $valor = 0;

            if ($request->filled('checkin_id')) {
                $checkin = Checkin::findOrFail($request->checkin_id);
                $valor = $checkin->reserva->valor_total ?? 0;
            } elseif ($request->filled('hospede_id')) {
                $hospede = Hospede::findOrFail($request->hospede_id);
                $valor = $hospede->valor_a_pagar ?? 0;
            }

            if ($valor <= 0) {
                return back()->with('error', 'Valor inválido ou não encontrado para este pagamento.');
            }

           $pagamento = Pagamento::create([
                'valor' => $valor,
                'metodo_pagamento' => $request->metodo_pagamento,
                'status_pagamento' => $request->status_pagamento,
                'checkin_id' => $request->checkin_id,
                'hospede_id' => $request->hospede_id,
            ]);

    // Se checkbox estiver marcado, redireciona direto pra fatura
        if ($request->has('gerar_fatura')) {
          return redirect()->route('pagamentos.index')->with('fatura_id', $pagamento->id);
        }
            return redirect()->route('pagamentos.index')->with('success', 'Pagamento registado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao registar pagamento: ' . $e->getMessage());
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
]);


    try {
        $pagamento = Pagamento::findOrFail($id);

        // Limpa os dois campos para evitar conflito
        $pagamento->checkin_id = null;
        $pagamento->hospede_id = null;

        // Atribuição condicional conforme a origem
        if ($request->origem === 'checkin') {
            $pagamento->checkin_id = $request->checkin_id;
        } else {
            $pagamento->hospede_id = $request->hospede_id;
        }

        $pagamento->valor = $request->valor;
        $pagamento->status_pagamento = $request->status_pagamento;

        $pagamento->save();

        return redirect()->back()->with('success', 'Pagamento atualizado com sucesso!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao atualizar pagamento: ' . $e->getMessage());
    }
}

public function fatura($id)
{
    $pagamento = Pagamento::with(['checkin.reserva', 'hospede'])->findOrFail($id);

    $pdf = Pdf::loadView('pagamentos.fatura', compact('pagamento'));

    return $pdf->stream('fatura_pagamento_' . $pagamento->id . '.pdf');
}


    public function destroy($id)
    {
        try {
            $pagamento = Pagamento::findOrFail($id);
            $pagamento->delete();

            return redirect()->route('pagamentos.index')->with('success', 'Pagamento removido com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover pagamento: ' . $e->getMessage());
        }
    }
}
