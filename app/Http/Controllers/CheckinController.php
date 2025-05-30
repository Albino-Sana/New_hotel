<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Reserva;
use App\Models\Hospede;
use App\Models\Quarto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ServicoAdicional; // <- nova linha

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

        $checkin = Checkin::create([
            'reserva_id' => $request->reserva_id,
            'quarto_id' => $request->quarto_id,
            'numero_quarto' => $request->numero_quarto, // campo incluído
            'data_entrada' => $request->data_entrada,
            'data_saida' => $request->data_saida,
            'num_pessoas' => $request->num_pessoas,
            'status' => 'hospedado',
        ]);

        Quarto::where('id', $request->quarto_id)->update(['status' => 'Ocupado']);
        Reserva::where('id', $request->reserva_id)->update(['status' => 'hospedado']);

        DB::commit();

        return redirect()->back()->with('success', 'Check-in realizado com sucesso!');
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
