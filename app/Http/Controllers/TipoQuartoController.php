<?php

namespace App\Http\Controllers;

use App\Models\TipoQuarto;
use Illuminate\Http\Request;

class TipoQuartoController extends Controller
{
    public function index()
    {
        $tipos = TipoQuarto::orderBy('nome', 'asc')->get();
        return view('tipos_quartos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_quartos.create');
    }

    public function getInfo($id)
    {
        $tipo = TipoQuarto::findOrFail($id);

        return response()->json([
            'preco_noite' => $tipo->preco,
            'tipo_cobranca' => $tipo->tipo_cobranca,
        ]);
    }


    public function store(Request $request)
    {
        // Validação dos dados antes de entrar no try-catch
        $request->validate([
            'nome' => 'required|unique:tipos_quartos',
            'descricao' => 'nullable|string',
            'tipo_cobranca' => 'required|in:Por Noite,Por Hora',
            'preco' => 'required|numeric|min:0',
        ]);

        try {
            // Criação do tipo de quarto
            $tipo = new TipoQuarto();
            $tipo->nome = $request->nome;
            $tipo->descricao = $request->descricao; // Armazenando a descrição
            $tipo->tipo_cobranca = $request->tipo_cobranca;
            $tipo->preco = $request->preco;
            $tipo->save();

            // Redirecionamento com sucesso
            return redirect()->route('tipos-quartos.index')->with('success', 'Tipo de Quarto criado com sucesso!');
        } catch (\Exception $e) {
            // Tratamento de erro
            return back()->withErrors('Erro ao adicionar tipo de quarto: ' . $e->getMessage());
        }
    }


    public function edit(TipoQuarto $tipos_quarto)
    {
        return view('tipos_quartos.edit', ['tipo' => $tipos_quarto]);
    }

    public function update(Request $request, TipoQuarto $tipos_quarto)
    {
        $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'nullable|string',
             'tipo_cobranca' => 'required|in:Por Noite,Por Hora',
            'preco' => 'required|numeric|min:0',
        ]);

        try {
            $tipos_quarto->update($request->all());
            return redirect()->route('tipos-quartos.index')->with('success', 'Tipo de Quarto atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar Tipo de Quarto: ' . $e->getMessage());
        }
    }

    public function destroy(TipoQuarto $tipos_quarto)
    {
        try {
            $tipos_quarto->delete();
            return redirect()->route('tipos-quartos.index')->with('success', 'Tipo de Quarto removido.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover Tipo de Quarto: ' . $e->getMessage());
        }
    }
}
