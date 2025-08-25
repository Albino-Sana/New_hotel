<?php

namespace App\Http\Controllers;

use App\Models\Quarto;
use App\Models\TipoQuarto;
use Illuminate\Http\Request;


class QuartoController extends Controller
{
    public function index(Request $request)
    {
        $query = Quarto::query();

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por tipo de quarto (usa relacionamento)
        if ($request->filled('tipo')) {
            $query->whereHas('tipoQuarto', function ($q) use ($request) {
                $q->where('nome', $request->tipo);
            });
        }

        // Filtro por andar
        if ($request->filled('andar')) {
            $query->where('andar', $request->andar);
        }

        // Paginação ou ordenação, se desejar
        $quartos = $query->with('tipoQuarto')->paginate(10);

        // Tipos de quarto para o select
        $tipos = TipoQuarto::all();


        return view('quartos.index', compact('quartos', 'tipos'));
    }


    public function create()
    {
        $tipos = TipoQuarto::all();
        return view('quartos.create', compact('tipos'));
    }




    public function store(Request $request)
    {

        $request->validate([
            'numero' => 'required',
            'andar' => 'required',
            'tipo_quarto_id' => 'required|exists:tipos_quartos,id', // <- Aqui
            'preco_noite' => 'required|numeric',
            'tipo_cobranca' => 'nullable|string',
            'status' => 'required',
            'descricao' => 'nullable|string|max:255',
        ]);

        $existe = Quarto::where('numero', $request->numero)
            ->where('andar', $request->andar)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Já existe um quarto com este número e andar.');
        }

        try {
            $tipo = TipoQuarto::findOrFail($request->tipo_quarto_id);

            $quarto = new Quarto();
            $quarto->numero = $request->numero;
            $quarto->andar = $request->andar;
            $quarto->tipo_quarto_id = $request->tipo_quarto_id;
            $quarto->status = $request->status;
            $quarto->preco_noite = $request->preco_noite;

            $quarto->tipo_cobranca = $tipo->tipo_cobranca;
            $quarto->descricao = $request->descricao;
            $quarto->save();

            return redirect()->route('quartos.index')->with('success', 'Quarto criado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao adicionar quarto: ' . $e->getMessage());
        }
    }
    public function edit(Quarto $quarto)
    {
        $tipos = TipoQuarto::all();
        return view('quartos.edit', compact('quarto', 'tipos'));
    }

    public function update(Request $request, Quarto $quarto)
    {
        // Validação dos dados
        $request->validate([
            'numero' => 'required|max:10',
            'andar' => 'required|integer',
            'tipo_quarto_id' => 'required|exists:tipos_quartos,id',
            'status' => 'required|in:Disponível,Reservado,Ocupado,Manutenção',
            'preco_noite' => 'nullable|numeric',
            'descricao' => 'nullable|string|max:255',

        ]);

        // Verifica se existe outro quarto com o mesmo número e andar
        $existe = Quarto::where('numero', $request->numero)
            ->where('andar', $request->andar)
            ->where('id', '!=', $quarto->id)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Já existe outro quarto com este número e andar.');
        }

        try {
            $quarto->update([
                'numero' => $request->numero,
                'andar' => $request->andar,
                'tipo_quarto_id' => $request->tipo_quarto_id,
                'status' => $request->status,
                'preco_noite' => $request->preco_noite,
                'descricao' => $request->descricao,
            ]);

            return redirect()->route('quartos.index')->with('success', 'Quarto atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar Quarto: ' . $e->getMessage());
        }
    }



    public function destroy(Quarto $quarto)
    {
        try {
            // 1. Zera o vínculo nas reservas
            \App\Models\Reserva::where('quarto_id', $quarto->id)
                ->update(['quarto_id' => null]);

            // 2. (Opcional) Apaga hóspedes vinculados, ou quebra o vínculo
            \App\Models\Hospede::where('quarto_id', $quarto->id)
                ->update(['quarto_id' => null]); // ou ->delete()

            // 3. Apaga o quarto
            $quarto->delete();

            return redirect()->route('quartos.index')
                ->with('success', 'Quarto excluído com sucesso, e vínculos foram desfeitos.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir Quarto: ' . $e->getMessage());
        }
    }
}
