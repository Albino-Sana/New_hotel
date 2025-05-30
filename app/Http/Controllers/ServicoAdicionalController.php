<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServicoAdicional; // Importando o modelo ServicoAdicional

class ServicoAdicionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $servicos = ServicoAdicional::orderBy('nome', 'asc')->get();
    return view('servicos_extras.index', compact('servicos'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
        ]);
    
        try {
            ServicoAdicional::create($request->all());
    
            return redirect()->route('servicos_extras.index')->with('success', 'Serviço criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar serviço: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, $id)
     {
         $request->validate([
             'nome' => 'required|string|max:255',
             'descricao' => 'nullable|string',
             'preco' => 'required|numeric|min:0',
         ]);
     
         try {
             $servico = ServicoAdicional::findOrFail($id);
             $servico->update($request->all());
     
             return redirect()->route('servicos_extras.index')->with('success', 'Serviço atualizado com sucesso!');
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Erro ao atualizar serviço: ' . $e->getMessage());
         }
     }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $servico = ServicoAdicional::findOrFail($id);
            $servico->delete();
            
            return redirect()->route('servicos_extras.index')
                ->with('success', 'Serviço excluído com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir serviço: ' . $e->getMessage());
        }
    }
}
