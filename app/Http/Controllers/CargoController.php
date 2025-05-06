<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::all();
        return view('cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('cargos.index');
    }


public function store(Request $request)
{
    // Validação
    $request->validate([
        'nome' => 'required|string|max:100|unique:cargos,nome',
        'descricao' => 'nullable|string|max:500', // Descrição é opcional, mas pode ter um limite de 500 caracteres
    ]);

    try {
        // Criando o cargo com nome e descrição
        Cargo::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao, // Armazenando a descrição
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo criado com sucesso!');
    } catch (\Exception $e) {
        Log::error('Erro ao criar cargo: ' . $e->getMessage());
        return back()->with('error', 'Erro ao criar o cargo. Tente novamente.');
    }
}

    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        // Validação
        $request->validate([
            'nome' => 'required|string|max:100|unique:cargos,nome,' . $cargo->id,
            'descricao' => 'nullable|string|max:500', // Descrição pode ser editada ou deixada em branco
        ]);
    
        try {
            // Atualizando o cargo com nome e descrição
            $cargo->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao, // Atualizando a descrição
            ]);
    
            return redirect()->route('cargos.index')->with('success', 'Cargo atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cargo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o cargo.');
        }
    }
    

    public function destroy(Cargo $cargo)
    {
        try {
            $cargo->delete();
            return redirect()->route('cargos.index')->with('success', 'Cargo excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir cargo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir o cargo.');
        }
    }
}
