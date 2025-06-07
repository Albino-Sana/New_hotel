<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Reserva;
use App\Models\Hospede;
use App\Models\Quarto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ServicoAdicional;
use App\Models\Fatura;
use App\Models\Recibo;
use App\Mail\ReciboFaturaMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = Checkin::with('reserva.hospede', 'quarto.tipo')->latest()->get();

        $reservas = Reserva::with('hospede')
            ->whereDoesntHave('checkin') // só reservas que ainda não têm check-in
            ->where('status', '!=', 'finalizada') // e não estão finalizadas
            ->get();

        $quartos = Quarto::with('tipo')->get();
        $hospedes = Hospede::all();
        $servicosAdicionais = ServicoAdicional::all(); // <- nova linha

        return view('checkin.index', compact(
            'checkins',
            'reservas',
            'quartos',
            'hospedes',
            'servicosAdicionais' // <- adicionada ao compact
        ));
    }

public function store(Request $request)
{
    // 1. Validação dos dados de entrada
    $request->validate([
        'reserva_id' => 'required|exists:reservas,id',
        'quarto_id' => 'required|exists:quartos,id',
        'numero_quarto' => 'required|string|max:255',
        'data_entrada' => 'required|date',
        'data_saida' => 'required|date|after_or_equal:data_entrada',
        'num_pessoas' => 'required|integer|min:1',
    ]);

try {
    DB::beginTransaction();

    $reserva = Reserva::findOrFail($request->reserva_id);

    $checkin = Checkin::create([
        'reserva_id' => $request->reserva_id,
        'quarto_id' => $request->quarto_id,
        'numero_quarto' => $request->numero_quarto,
        'data_entrada' => $request->data_entrada,
        'data_saida' => $request->data_saida,
        'num_pessoas' => $request->num_pessoas,
        'status' => 'hospedado',
    ]);

    Quarto::where('id', $request->quarto_id)->update(['status' => 'Ocupado']);
    Reserva::where('id', $request->reserva_id)->update(['status' => 'hospedado']);

    $numeroFormatado = str_pad(Fatura::max('id') + 1, 5, '0', STR_PAD_LEFT);

    $fatura = Fatura::create([
        'tipo_documento' => 'FT',
        'serie' => 'A01',
        'numero' => $numeroFormatado,
        'data_emissao' => Carbon::now()->toDateString(),
        'total' => floatval($reserva->valor_total ?? 0),
        'valor_entregue' => floatval($reserva->valor_total ?? 0),
        'troco' => 0,

        'nome_cliente' => $reserva->cliente_nome,
        'nif' => $reserva->cliente_nif ?? '999999990',
        'telefone' => $reserva->cliente_telefone ?? null,

        'estado_documento' => 'N',
        'hash' => null,
        'hash_control' => null,

        'regime_autofaturacao' => false,
        'regime_iva_caixa' => false,
        'emitido_terceiros' => false,

        'metodo_pagamento' => 'Dinheiro',
        'codigo_cae' => '55101',
        'mesa_id' => null,
        'servico_id' => $checkin->id,
    ]);

    if (!empty($reserva->cliente_email)) {
        Mail::to($reserva->cliente_email)->send(new ReciboFaturaMail($fatura));
    }

    DB::commit();

    // Redireciona para a tela anterior e passa o link do PDF
    return redirect()->back()->with([
        'success' => 'Check-in realizado com sucesso!',
        'fatura_pdf_id' => $fatura->id
    ]);
} catch (\Exception $e) {
    DB::rollBack();
    return redirect()->back()->with('error', 'Erro ao salvar check-in: ' . $e->getMessage());
}


}



    public function update(Request $request, $id)
    {
        $request->validate([
            'data_entrada' => 'required|date',
            'data_saida' => 'required|date|after_or_equal:data_entrada',
            'num_pessoas' => 'required|integer|min:1',
            'numero_quarto' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $checkin = Checkin::findOrFail($id);
            $checkin->update([
                'data_entrada' => $request->data_entrada,
                'data_saida' => $request->data_saida,
                'num_pessoas' => $request->num_pessoas,
                'numero_quarto' => $request->numero_quarto, // campo incluído
                'status' => $request->status,
            ]);

            $quartoStatus = $request->status === 'hospedado' ? 'ocupado' : 'disponível';
            Quarto::where('id', $checkin->quarto_id)->update(['status' => $quartoStatus]);
            Reserva::where('id', $checkin->reserva_id)->update(['status' => $request->status]);

            DB::commit();

            return redirect()->back()->with('success', 'Check-in atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar check-in: ' . $e->getMessage());
        }
    }

    public function dadosReserva($id)
    {
        $reserva = Reserva::with(['hospede', 'quarto.tipo'])->findOrFail($id);

        return response()->json([
            'hospede' => [
                'nome' => $reserva->hospede->nome ?? null,
            ],
            'quarto' => [
                'numero' => $reserva->quarto->numero ?? null,
                'tipo' => $reserva->quarto->tipo->nome ?? null,
            ],
            'preco' => $reserva->preco,
            'preco_formatado' => number_format($reserva->preco, 2, ',', '.'),
            'num_pessoas' => $reserva->num_pessoas,
            'data_entrada' => $reserva->data_entrada,
            'data_saida' => $reserva->data_saida,
            'data_entrada_formatada' => \Carbon\Carbon::parse($reserva->data_entrada)->format('d/m/Y H:i'),
            'data_saida_formatada' => \Carbon\Carbon::parse($reserva->data_saida)->format('d/m/Y H:i'),
        ]);
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Desativa as restrições de chave estrangeira temporariamente
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $checkin = Checkin::findOrFail($id);

            // Atualiza o status do quarto para "disponível"
            Quarto::where('id', $checkin->quarto_id)->update(['status' => 'disponível']);

            // Atualiza o status da reserva para "reservado"
            Reserva::where('id', $checkin->reserva_id)->update(['status' => 'reservado']);

            // Remove o check-in
            $checkin->delete();

            // Reativa as restrições de chave estrangeira
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            return redirect()->back()->with('success', 'Check-in removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao remover check-in: ' . $e->getMessage());
        }
    }
}
