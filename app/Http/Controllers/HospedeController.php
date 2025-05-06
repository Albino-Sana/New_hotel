<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospede;
use App\Models\Quarto;
use Carbon\Carbon;

class HospedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        $hospedes = Hospede::when($busca, function ($query, $busca) {
            return $query->where('nome', 'like', "%{$busca}%")
                ->orWhere('email', 'like', "%{$busca}%")
                ->orWhere('documento', 'like', "%{$busca}%");
        })
            ->orderByDesc('created_at')
            ->paginate(10);

        $quartos = Quarto::where('status', 'Disponível')->get(); // ou todos se preferir

        return view('hospedes.index', compact('hospedes', 'busca', 'quartos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:20',
            'numero_pessoas' => 'required|integer|min:1',
            'data_entrada' => 'required|date',
            'data_saida' => 'required|date|after:data_entrada',
            'quarto_id' => 'required|exists:quartos,id',
        ]);
    
        try {
            // Buscar o quarto
            $quarto = Quarto::findOrFail($request->quarto_id);
    
            // Calcular número de noites
            $entrada = \Carbon\Carbon::parse($request->data_entrada);
            $saida = \Carbon\Carbon::parse($request->data_saida);
            $noites = $entrada->diffInDays($saida);
    
            // Calcular valor total
            $valorTotal = $noites * $quarto->preco_noite;
    
            // Criar o hóspede
            Hospede::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'numero_pessoas' => $request->numero_pessoas,
                'data_entrada' => $request->data_entrada,
                'data_saida' => $request->data_saida,
                'quarto_id' => $request->quarto_id,
                'valor_a_pagar' => $valorTotal,
            ]);

                  // Marca o quarto como "ocupado"
                  $quarto = Quarto::find($request->quarto_id);
                  $quarto->status = 'ocupado';
                  $quarto->save();
          
    
            return redirect()->back()->with('success', 'Hóspede cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar hóspede: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $quartos = Quarto::where('status', 'disponível')->get(); // ou todos os quartos
        return view('hospedes.create', compact('quartos'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:20',
            'numero_pessoas' => 'required|integer|min:1',
            'data_entrada' => 'required|date',
            'data_saida' => 'required|date|after:data_entrada',
            'quarto_id' => 'required|exists:quartos,id',
        ]);
    
        try {
            $hospede = Hospede::findOrFail($id);
    
            $entrada = \Carbon\Carbon::parse($request->data_entrada);
            $saida = \Carbon\Carbon::parse($request->data_saida);
            $dias = $entrada->diffInDays($saida);
    
            $quarto = Quarto::findOrFail($request->quarto_id);
            $valorTotal = $dias * $quarto->preco_noite;
    
            // Atualizar o hóspede
            $hospede->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'numero_pessoas' => $request->numero_pessoas,
                'data_entrada' => $request->data_entrada,
                'data_saida' => $request->data_saida,
                'quarto_id' => $request->quarto_id,
                'valor_a_pagar' => $valorTotal,
            ]);
    
            return redirect()->back()->with('success', 'Hóspede atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar hóspede: ' . $e->getMessage());
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


    public function destroy($id)
    {
        try {
            $hospede = Hospede::findOrFail($id);
    
            // Libera o quarto se estiver associado
            if ($hospede->quarto) {
                $hospede->quarto->status = 'Disponivel';
                $hospede->quarto->save();
            }
    
            // Exclui o hóspede
            $hospede->delete();
    
            return redirect()->route('hospedes.index')->with('success', 'Hóspede excluído e quarto liberado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir hóspede!');
        }
    }
    
}
