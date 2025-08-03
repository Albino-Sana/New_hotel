<?php

namespace App\Http\Controllers;

use App\Models\Hospede;
use App\Models\Quarto;
use App\Models\CheckoutHospede;
use App\Models\ServicoAdicional;
use App\Mail\HospedeCadastrado;
use App\Models\Estadia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Fatura;
use App\Models\Empresa;
use App\Mail\FaturaReciboMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pagamento;




class HospedeController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        $hospedes = Hospede::with('quarto', 'checkoutHospede')
            ->orderBy('created_at', 'desc')
            ->when($busca, function ($query, $busca) {
                return $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('documento', 'like', "%{$busca}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $quartos = Quarto::with('tipo')
            ->where('status', 'Disponível')
            ->get(['id', 'numero', 'preco_noite', 'tipo_cobranca', 'tipo_quarto_id']);

        $servicosAdicionais = ServicoAdicional::all();

        return view('hospedes.index', compact('hospedes', 'busca', 'quartos', 'servicosAdicionais'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:20',
            'numero_pessoas' => 'required|integer|min:1',
            'data_entrada' => 'required|date|after_or_equal:now',
            'data_saida' => 'required|date|after:data_entrada',
            'quarto_id' => 'required|exists:quartos,id',
            'preco_noite' => 'required|numeric|min:0',
            'tipo_cobranca' => 'required|string',
        ]);

        try {
            $quarto = Quarto::findOrFail($request->quarto_id);
            if ($quarto->status !== 'Disponível') {
                return back()->with('error', 'Quarto selecionado não está disponível.');
            }

            $entrada = Carbon::parse($request->data_entrada);
            $saida = Carbon::parse($request->data_saida);
            $noites = $entrada->diffInDays($saida);
            $valorTotal = $noites * $request->preco_noite; // Usar preco_noite do formulário

            $hospede = Hospede::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'numero_pessoas' => $request->numero_pessoas,
                'data_entrada' => $entrada,
                'data_saida' => $saida,
                'quarto_id' => $request->quarto_id,
                'preco_noite' => $request->preco_noite,
                'tipo_cobranca' => $request->tipo_cobranca,
                'valor_a_pagar' => $valorTotal,
                'status' => 'Hospedado',
            ]);

            Estadia::create([
                'hospede_id' => $hospede->id,
                'quarto_id' => $request->quarto_id,
                'data_entrada' => $entrada,
                'data_saida' => $saida,
            ]);

            $quarto->update(['status' => 'Ocupado']);
   // Cria fatura
        $empresa = Empresa::first();
        $ultimoNumero = Fatura::max('numero') ?? 0;

        $fatura = Fatura::create([
            'tipo_documento' => 'Fatura',
            'serie' => 'A',
            'numero' => $ultimoNumero + 1,
            'data_emissao' => now(),
            'total' => $valorTotal,
            'valor_entregue' => $valorTotal,
            'troco' => 0,
            'nome_cliente' => $request->nome,
            'nif' => '999999999',
            'telefone' => $request->telefone,
            'estado_documento' => 'Emitido',
            'hash' => sha1(now() . $request->nome . Str::random(5)),
            'hash_control' => '?',
            'regime_autofaturacao' => false,
            'regime_iva_caixa' => false,
            'emitido_terceiros' => false,
            'metodo_pagamento' => 'Dinheiro',
            'codigo_cae' => 'HOTEL-001',
            'servico_id' => null,
            'hospede_id' => $hospede->id,
        ]);

        if ($request->filled('email')) {
            Mail::to($request->email)->send(new FaturaReciboMail($fatura, $empresa));
        }

        return redirect()->back()->with('success', 'Hóspede cadastrado com sucesso!')
            ->with('fatura_id', $fatura->id)
            ->with('origem_fatura', 'hospede');

          
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar hóspede: ' . $e->getMessage());
        }
    }

public function verFatura($id)
{
    $fatura = Fatura::findOrFail($id);
    $empresa = Empresa::first();
    $hospede = null;

    // Se a fatura estiver associada a um hóspede
    if ($fatura->hospede_id) {
        $hospede = Hospede::find($fatura->hospede_id);
    }

    return Pdf::loadView('hospedes.fatura', compact('fatura', 'empresa', 'hospede'))
              ->stream('fatura-' . $fatura->numero . '.pdf');
}

    public function create()
    {
        $quartos = Quarto::where('status', 'Disponível')->get();
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
            'preco_noite' => 'required|numeric|min:0',
            'tipo_cobranca' => 'required|string',
        ]);

        try {
            $hospede = Hospede::findOrFail($id);
            $quartoAnterior = Quarto::find($hospede->quarto_id);
            $quartoNovo = Quarto::findOrFail($request->quarto_id);

            if ($quartoNovo->id !== $hospede->quarto_id && $quartoNovo->status !== 'Disponível') {
                return back()->with('error', 'Quarto selecionado não está disponível.');
            }

            $entrada = Carbon::parse($request->data_entrada);
            $saida = Carbon::parse($request->data_saida);
            $noites = $entrada->diffInDays($saida);
            $valorTotal = $noites * $request->preco_noite; // Usar preco_noite do formulário

            $hospede->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'numero_pessoas' => $request->numero_pessoas,
                'data_entrada' => $entrada,
                'data_saida' => $saida,
                'quarto_id' => $request->quarto_id,
                'preco_noite' => $request->preco_noite,
                'tipo_cobranca' => $request->tipo_cobranca,
                'valor_a_pagar' => $valorTotal,
                'status' => $hospede->status === 'Finalizado' ? 'Finalizado' : 'Hospedado',
            ]);

            $estadia = $hospede->estadias()->latest()->first();
            if ($estadia) {
                $estadia->update([
                    'quarto_id' => $request->quarto_id,
                    'data_entrada' => $entrada,
                    'data_saida' => $saida,
                ]);
            } else {
                Estadia::create([
                    'hospede_id' => $hospede->id,
                    'quarto_id' => $request->quarto_id,
                    'data_entrada' => $entrada,
                    'data_saida' => $saida,
                ]);
            }

            $quartoNovo->update(['status' => 'Ocupado']);
            if ($quartoAnterior && $quartoAnterior->id !== $quartoNovo->id) {
                $quartoAnterior->update(['status' => 'Disponível']);
            }

            return redirect()->route('pos.index')->with('success', 'Hóspede atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar hóspede: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $hospede = Hospede::findOrFail($id);

            if ($hospede->quarto) {
                $hospede->quarto->update(['status' => 'Disponível']);
            }

            $hospede->delete();

            return redirect()->route('hospedes.index')->with('success', 'Hóspede excluído e quarto liberado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir hóspede: ' . $e->getMessage());
        }
    }

  public function checkout(Request $request, $id)
{
    $request->validate([
        'servicos' => 'nullable|array',
        'servicos.*' => 'exists:servicos_adicionais,id',
    ]);

    try {
        $hospede = Hospede::with('quarto')->findOrFail($id);

        if ($hospede->status === 'finalizado') {
            return back()->with('error', 'Hóspede já realizou check-out.');
        }

        $valorHospedagem = $hospede->valor_a_pagar ?? 0;
        $valorServicos = 0;
        $servicosNomes = [];

        if ($request->has('servicos')) {
            $servicos = ServicoAdicional::whereIn('id', $request->servicos)->get();
            $valorServicos = $servicos->sum('preco');
            $servicosNomes = $servicos->pluck('nome')->toArray();

            // Salva os serviços adicionais vinculados ao hóspede
            $hospede->servicosAdicionais()->sync($request->servicos);
        }

        $valorTotal = $valorHospedagem + $valorServicos;

        // Registra o checkout
        CheckoutHospede::create([
            'hospede_id' => $hospede->id,
            'data_checkout' => now(),
            'valor_hospedagem' => $valorHospedagem,
            'valor_servicos' => $valorServicos > 0 ? $valorServicos : null,
            'valor_total' => $valorTotal,
            'servicos_adicionais' => !empty($servicosNomes) ? json_encode($servicosNomes) : null,
        ]);

        // Atualiza o status do hóspede
        $hospede->update(['status' => 'finalizado']);

        // Libera o quarto
        if ($hospede->quarto) {
            $hospede->quarto->update(['status' => 'disponível']);
        }

        // Cria um pagamento pendente associado ao hóspede
        Pagamento::firstOrCreate(
            ['hospede_id' => $hospede->id, 'status_pagamento' => 'pendente'],
            [
                'valor' => $valorTotal,
                'metodo_pagamento' => 'pendente',
                'status_pagamento' => 'pendente',
                'data_pagamento' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Check-out realizado com sucesso!');
    } catch (\Exception $e) {
        Log::error('Erro ao realizar check-out: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Erro ao realizar check-out: ' . $e->getMessage());
    }
}

}
