<?php
namespace App\Http\Controllers;

use App\Models\Quarto;
use App\Models\TipoQuarto;
use Illuminate\Http\Request;


class QuartoController extends Controller
{
    public function index()
    {
        // Lista os quartos
        $quartos = Quarto::all();
        
        // Lista os tipos de quartos (tipos que estarão no select)
        $tipos = TipoQuarto::all();

        
        
        // Passa os quartos e tipos de quartos para a view
        return view('quartos.index', compact('quartos', 'tipos'));
    }

    public function create()
    {
        $tipos = TipoQuarto::all();
        return view('quartos.create', compact('tipos'));
    }


    public function store(Request $request)
    {
        // Validação padrão
        $request->validate([
            'numero' => 'required',
            'andar' => 'required',
            'tipo_quarto_id' => 'required',
            'status' => 'required',
            'preco_noite' => 'required|numeric|min:0',
            'descricao' => 'nullable|string|max:255',
            
        ]);
    
        // Verifica se já existe um quarto com o mesmo número e andar
        $existe = Quarto::where('numero', $request->numero)
                        ->where('andar', $request->andar)
                        ->exists();
    
        if ($existe) {
            return back()->with('error', 'Já existe um quarto com este número e andar.');
        }

    
        try {
            $quarto = new Quarto();
            $quarto->numero = $request->numero;
            $quarto->andar = $request->andar;
            $quarto->tipo_quarto_id = $request->tipo_quarto_id;
            $quarto->status = $request->status;
            $quarto->preco_noite = $request->preco_noite;
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
            'tipo_quarto_id' => 'required|exists:tipo_quartos,id',
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
            $quarto->delete();
            return redirect()->route('quartos.index')->with('success', 'Quarto excluído com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir Quarto: ' . $e->getMessage());
        }
    }
}
