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
use App\Models\FaturaRecibo;
use App\Mail\FaturaReciboMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


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
            // Define o valor a usar
            $valor = $request->valor;
            if ($request->filled('checkin_id')) {
                $checkin = Checkin::with('reserva')->find($request->checkin_id);
                $valor = $checkin->reserva->valor_total ?? $valor;
            } elseif ($request->filled('hospede_id')) {
                $hospede = Hospede::find($request->hospede_id);
                $valor = $hospede->valor_a_pagar ?? $valor;
            }

            if ($valor <= 0) {
                return back()->with('error', 'Valor inválido para este pagamento.');
            }

            // Cria o pagamento
            $pagamento = Pagamento::create([
                'valor'            => $valor,
                'metodo_pagamento' => $request->metodo_pagamento,
                'status_pagamento' => $request->status_pagamento,
                'checkin_id'       => $request->checkin_id,
                'hospede_id'       => $request->hospede_id,
                'data_pagamento'   => now(),
            ]);

            // Gera a fatura-recibo
            $empresa = Empresa::firstOrFail();
            $ultimoNumero = FaturaRecibo::max('numero') ?? 0;
            $numeroFatura  = $ultimoNumero + 1;

            $clienteNome  = $pagamento->checkin
                ? $pagamento->checkin->reserva->cliente_nome
                : ($pagamento->hospede->nome ?? '---');

            $clienteNIF   = $pagamento->checkin
                ? $pagamento->checkin->reserva->cliente_documento
                : ($pagamento->hospede->nif ?? '999999999');

            $clienteTel   = $pagamento->hospede->telefone ?? ($pagamento->checkin->reserva->cliente_telefone ?? '---');

            $faturap = FaturaRecibo::create([
                'tipo_documento'       => 'Fatura-Recibo',
                'serie'                => 'A',
                'numero'               => $numeroFatura,
                'data_emissao'         => now(),
                'total'                => $valor,
                'valor_entregue'       => $valor,
                'troco'                => 0,
                'nome_cliente'         => $clienteNome,
                'nif' => '999999999',
                'telefone'             => $clienteTel,
                'estado_documento'     => 'Emitido',
                'hash'                 => sha1(now() . Str::random(10)),
                'hash_control'         => null,
                'regime_autofaturacao' => false,
                'regime_iva_caixa'     => false,
                'emitido_terceiros'    => false,
                'metodo_pagamento'     => $pagamento->metodo_pagamento,
                'codigo_cae'           => $empresa->id_empresa ?? '0000',
                'servico_id'           => null,
                'reserva_id'           => $pagamento->checkin->reserva_id ?? null,
                'hospede_id'           => $pagamento->hospede_id ?? null,
                'pagamento_id' => $pagamento->id,
            ]);

            // Se quiser anexar o PDF no Storage (opcional)
            $pdf = Pdf::loadView('pdf.faturas.pagamento', [
                'fatura'  => $faturap,
                'empresa' => $empresa,
                'hospede' => $pagamento->hospede ?? null,
                'reserva' => $pagamento->checkin->reserva ?? null,
            ]);
            Storage::put('public/faturas/fatura_recibo_' . $faturap->id . '.pdf', $pdf->output());

            // Envia por e-mail se desejar
            if ($pagamento->hospede && $pagamento->hospede->email) {
                Mail::to($pagamento->hospede->email)
                    ->send(new FaturaReciboMail($faturap, $empresa));
            }

            // Redireciona e aciona abertura do PDF em nova aba
            return redirect()->back()
                ->with('success', 'Pagamento registado com sucesso!')
                ->with('faturap_id', $faturap->id)
                ->with('origem_fatura', 'pagamento');
        } catch (\Exception $e) {
            Log::error('Erro ao registrar pagamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao registrar pagamento: ' . $e->getMessage());
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
        $pagamento->data_pagamento = now();
        $pagamento->save();

        // Verifica se status é 'pago' e ainda não existe fatura
        $faturaExistente = FaturaRecibo::where('pagamento_id', $pagamento->id)->first();
        if ($pagamento->status_pagamento === 'pago' && !$faturaExistente) {
            $empresa = Empresa::firstOrFail();
            $ultimoNumero = FaturaRecibo::max('numero') ?? 0;
            $numeroFatura = $ultimoNumero + 1;

            $clienteNome = $pagamento->checkin
                ? $pagamento->checkin->reserva->cliente_nome
                : ($pagamento->hospede->nome ?? '---');

            $clienteNIF = $pagamento->checkin
                ? $pagamento->checkin->reserva->cliente_documento
                : ($pagamento->hospede->nif ?? '999999999');

            $clienteTel = $pagamento->hospede->telefone ?? ($pagamento->checkin->reserva->cliente_telefone ?? '---');

            $faturap = FaturaRecibo::create([
                'tipo_documento'       => 'Fatura-Recibo',
                'serie'                => 'A',
                'numero'               => $numeroFatura,
                'data_emissao'         => now(),
                'total'                => $pagamento->valor,
                'valor_entregue'       => $pagamento->valor,
                'troco'                => 0,
                'nome_cliente'         => $clienteNome,
                'nif'                  => $clienteNIF,
                'telefone'             => $clienteTel,
                'estado_documento'     => 'Emitido',
                'hash'                 => sha1(now() . Str::random(10)),
                'regime_autofaturacao' => false,
                'regime_iva_caixa'     => false,
                'emitido_terceiros'    => false,
                'metodo_pagamento'     => $pagamento->metodo_pagamento,
                'codigo_cae'           => $empresa->id_empresa ?? '0000',
                'servico_id'           => null,
                'reserva_id'           => $pagamento->checkin->reserva_id ?? null,
                'hospede_id'           => $pagamento->hospede_id ?? null,
                'pagamento_id'         => $pagamento->id,
            ]);

            // Gera o PDF (opcional)
            $pdf = Pdf::loadView('pdf.faturas.pagamento', [
                'fatura' => $faturap,
                'empresa' => $empresa,
                'hospede' => $pagamento->hospede ?? null,
                'reserva' => $pagamento->checkin->reserva ?? null,
            ]);
            Storage::put('public/faturas/fatura_recibo_' . $faturap->id . '.pdf', $pdf->output());

            // Envia e-mail (opcional)
            if ($pagamento->hospede && $pagamento->hospede->email) {
                Mail::to($pagamento->hospede->email)
                    ->send(new FaturaReciboMail($faturap, $empresa));
            }
        }

          // Redireciona e aciona abertura do PDF em nova aba
            return redirect()->back()
                ->with('success', 'Pagamento registado com sucesso!')
                ->with('fatura_id', $faturap->id)
                ->with('origem_fatura', 'pagamento');
    } catch (\Exception $e) {
        Log::error('Erro ao atualizar pagamento: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Erro ao atualizar pagamento: ' . $e->getMessage());
    }
}

public function fatura($id)
{
    $pagamento = Pagamento::with(['hospede', 'checkin.reserva'])->findOrFail($id);

    $fatura = FaturaRecibo::where('pagamento_id', $pagamento->id)->firstOrFail();

    $empresa = Empresa::firstOrFail(); // necessário para o cabeçalho/logotipo
    $reserva = optional($pagamento->checkin)->reserva;
    $hospede = $pagamento->hospede;

    // Gera o PDF com os dados corretos
    $pdf = Pdf::loadView('pdf.faturas.pagamento', [
        'fatura' => $fatura,
        'empresa' => $empresa,
        'reserva' => $reserva,
        'hospede' => $hospede,
    ]);

    // Retorna o PDF no navegador (em nova aba)
    return $pdf->stream('fatura_recibo_' . $fatura->numero . '.pdf');
}
    public function verFatura($id)
    {
        $fatura = FaturaRecibo::findOrFail($id);
        $empresa = Empresa::firstOrFail();

        $pdf = Pdf::loadView('pdf.faturas.pagamento', compact('fatura', 'empresa'));
        return $pdf->stream('fatura_recibo_' . $fatura->numero . '.pdf');
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
