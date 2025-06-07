<?php

namespace App\Http\Controllers;

use App\Models\PagamentoMetodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PagamentoMetodoController extends Controller
{
    public function index()
    {
        $metodos = PagamentoMetodo::all();
        return view('empresa.config', compact('metodos'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'designacao' => 'required|string|max:255|unique:pagamentos_metodos,designacao',
        ]);

        try {
            PagamentoMetodo::create([
                'designacao' => $request->designacao,
            ]);

            return redirect()->route('empresa.index')->with('success', 'Método de pagamento adicionado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar método de pagamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao adicionar método de pagamento: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $metodo = PagamentoMetodo::findOrFail($id);
        $metodo->delete();

        return redirect()->route('hotel.index')->with('success', 'Método de pagamento excluído com sucesso!');
    }
}