<?php

namespace App\Http\Controllers;

use App\Models\TipoQuarto;
use Illuminate\Http\Request;

class TipoQuartoController extends Controller
{
    public function index()
    {
        $tipos = TipoQuarto::all();
        return view('tipos_quartos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_quartos.create');
    }


    public function store(Request $request)
    {
        // Validação dos dados antes de entrar no try-catch
        $request->validate([
            'nome' => 'required|unique:tipos_quartos',
            'descricao' => 'nullable|string',
        ]);
    
        try {
            // Criação do tipo de quarto
            $tipo = new TipoQuarto();
            $tipo->nome = $request->nome;
            $tipo->descricao = $request->descricao; // Armazenando a descrição
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
