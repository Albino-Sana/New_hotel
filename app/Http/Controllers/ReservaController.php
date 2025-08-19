<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Quarto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Fatura;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\FaturaReciboMail;


class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with(['quarto.tipo', 'user'])->orderBy('data_entrada', 'desc')->get();
        $quartos = Quarto::where('status', 'disponivel')->get();
        return view('reservas.index', compact('reservas', 'quartos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'cliente_nome' => 'required',
            'cliente_documento' => 'required',
            'cliente_email' => 'nullable|email',
            'cliente_telefone' => 'nullable',
            'quarto_id' => 'required|exists:quartos,id',
            'data_entrada' => 'required|date_format:Y-m-d\TH:i',
            'data_saida' => 'required|date_format:Y-m-d\TH:i|after:data_entrada',
            'numero_pessoas' => 'required|integer|min:1',
            'observacoes' => 'nullable|string',
        ]);

        try {
            $quarto = Quarto::findOrFail($request->quarto_id);

            $data_entrada = Carbon::parse($request->data_entrada);
            $data_saida = Carbon::parse($request->data_saida);
            $numero_noites = $data_entrada->diffInMinutes($data_saida) / 60;
            $valor_total = ceil($numero_noites) * $quarto->preco_noite;

            $reserva = new Reserva();
            $reserva->cliente_nome = $request->cliente_nome;
            $reserva->cliente_documento = $request->cliente_documento;
            $reserva->cliente_email = $request->cliente_email;
            $reserva->cliente_telefone = $request->cliente_telefone;
            $reserva->quarto_id = $request->quarto_id;
            $reserva->data_entrada = $data_entrada;
            $reserva->data_saida = $data_saida;
            $reserva->numero_noites = $numero_noites;
            $reserva->valor_total = $valor_total;
            $reserva->observacoes = $request->observacoes;
            $reserva->numero_pessoas = $request->numero_pessoas;
            $reserva->status = 'Reservado';
            $reserva->user_id = Auth::id();
            $reserva->save();

            $quarto->update(['status' => 'Reservado']);

            // Após salvar a reserva...
            $empresa = Empresa::firstOrFail();
            $numeroFatura = Fatura::max('numero') + 1;
            // Cria Fatura após reserva
            $fatura = Fatura::create([
                'reserva_id' => $reserva->id,
                'tipo_documento' => 'Fatura',
                'serie' => 'A',
                'numero' => $numeroFatura,
                'data_emissao' => now(),
                'total' => $reserva->valor_total,
                'valor_entregue' => 0,
                'troco' => 0,
                'nome_cliente' => $reserva->cliente_nome,
                'nif' => '999999999',
                'telefone' => $reserva->cliente_telefone,
                'estado_documento' => 'Emitido',
                'hash' => Str::random(40),
                'hash_control' => null,
                'regime_autofaturacao' => false,
                'regime_iva_caixa' => false,
                'emitido_terceiros' => false,
                'metodo_pagamento' => '---',
                'codigo_cae' => 'HOTEL-001',
                'servico_id' => null,
            ]);

            // Envia e-mail se cliente tiver e-mail
            if ($reserva->cliente_email) {
                Mail::to($reserva->cliente_email)->send(new FaturaReciboMail($fatura, $empresa));
            }

            return redirect()->back()->with('success', 'Reserva criada com sucesso!')
                ->with('fatura_id', $fatura->id)
                ->with('origem_fatura', 'reserva');
        } catch (\Exception $e) {
            Log::error('Erro ao criar reserva: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar reserva: ' . $e->getMessage());
        }
    }

    public function visualizarFatura($id)
    {
        $fatura = Fatura::with('reserva')->findOrFail($id);
        $empresa = Empresa::firstOrFail();

        $pdf = PDF::loadView('reservas.fatura', compact('fatura', 'empresa'));
        return $pdf->stream('fatura_reserva_' . $fatura->numero . '.pdf');
    }


    public function gerarFaturaPdf($id)
    {
        $fatura = Fatura::findOrFail($id);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('reservas.fatura', compact('fatura', 'empresa'));

        return $pdf->stream('fatura_' . $fatura->numero . '.pdf');
    }

    /*public function cancelar($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            $reserva->status = 'cancelado';
            $reserva->save();

            return back()->with('success', 'Reserva cancelada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cancelar a reserva.');
        }
    }
*/

    public function edit(Reserva $reserva)
    {
        $quartos = Quarto::all();
        return view('reservas.edit', compact('reserva', 'quartos'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        // Validação dos dados (tornando 'quarto_id' opcional na edição)
        $request->validate([
            'cliente_nome' => 'required|string|max:255',
            'cliente_documento' => 'required|max:100|in:bi,passaporte,carta_conducao', // Alteração aqui
            'cliente_email' => 'nullable|email|max:255',
            'cliente_telefone' => 'nullable|string|max:50',
            'quarto_id' => 'nullable|exists:quartos,id', // Tornar 'quarto_id' opcional
            'data_entrada' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'data_saida' => 'required|date_format:Y-m-d\TH:i|after:data_entrada',
            'numero_pessoas' => 'required|integer|min:1',
            'observacoes' => 'nullable|string'
        ]);

        try {
            // Se o quarto não for alterado, mantenha o valor atual
            if ($request->quarto_id) {
                $quarto = Quarto::find($request->quarto_id);
            } else {
                $quarto = $reserva->quarto; // Manter o quarto atual se não for alterado
            }

            // Cálculo da quantidade de noites
            $entrada = Carbon::parse($request->data_entrada);
            $saida = Carbon::parse($request->data_saida);
            $numero_noites = $entrada->diffInMinutes($saida) / 60;

            // Calcular o valor total
            $valor_total = $quarto->preco_noite * $numero_noites;

            // Atualizar os dados da reserva
            $reserva->update([
                'cliente_nome' => $request->cliente_nome,
                'cliente_documento' => $request->cliente_documento,
                'cliente_email' => $request->cliente_email,
                'cliente_telefone' => $request->cliente_telefone,
                'quarto_id' => $request->quarto_id ?: $reserva->quarto_id, // Se não mudar, mantém o quarto anterior
                'data_entrada' => $request->data_entrada,
                'data_saida' => $request->data_saida,
                'numero_noites' => $numero_noites,
                'valor_total' => $valor_total,
                'numero_pessoas' => $request->numero_pessoas, // Atualiza o número de pessoas
                'observacoes' => $request->observacoes
            ]);

            $empresa = Empresa::firstOrFail();
            $numeroFatura = Fatura::max('numero') + 1;

            // Cria Fatura após reserva
            $fatura = Fatura::create([
                'reserva_id' => $reserva->id,
                'tipo_documento' => 'Fatura',
                'serie' => 'A',
                'numero' => $numeroFatura,
                'data_emissao' => now(),
                'total' => $reserva->valor_total,
                'valor_entregue' => 0,
                'troco' => 0,
                'nome_cliente' => $reserva->cliente_nome,
                'nif' => $reserva->cliente_documento,
                'telefone' => $reserva->cliente_telefone,
                'estado_documento' => 'Emitido',
                'hash' => Str::random(40),
                'hash_control' => null,
                'regime_autofaturacao' => false,
                'regime_iva_caixa' => false,
                'emitido_terceiros' => false,
                'metodo_pagamento' => '---',
                'codigo_cae' => 'HOTEL-001',
                'servico_id' => null,
            ]);
            // Enviar e-mail automático
            if ($reserva->cliente_email) {
                Mail::to($reserva->cliente_email)->send(new FaturaReciboMail($fatura, $empresa));
            }


            return redirect()->back()->with(['success' => 'Reserva cadastrada com sucesso!', 'fatura_id' => $fatura->id]);
        } catch (\Exception $e) {
            // Retornar erro caso ocorra algum problema
            return back()->withErrors('Erro ao atualizar reserva: ' . $e->getMessage());
        }
    }

    public function checkin($id)
    {
        $reserva = Reserva::findOrFail($id);

        // Verifica se a reserva está realmente com status 'reservado'
        if (strtolower($reserva->status) !== 'reservado') {
            return redirect()->back()->with('error', 'A reserva não pode ser feita. Status inválido!');
        }

        // Atualiza o status da reserva
        $reserva->status = 'hospedado';
        $reserva->save();

        // Atualiza o status do quarto também
        $reserva->quarto->status = 'Ocupado';
        $reserva->quarto->save();

        return redirect()->back()->with('success', 'Check-in realizado com sucesso!');
    }



    public function finalizar($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            // Altera o status do quarto para "Disponível"
            $quarto = $reserva->quarto;
            $quarto->status = 'Disponível';
            $quarto->save();

            // Atualiza o status da reserva para "finalizado"
            $reserva->status = 'finalizado';
            $reserva->save();

            return redirect()->route('reservas.index')->with('success', 'Reserva finalizada e quarto disponível novamente.');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao finalizar reserva: ' . $e->getMessage());
        }
    }

    public function cancelar($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            // Verifica se a reserva já está finalizada ou cancelada
            if ($reserva->status === 'finalizado' || $reserva->status === 'cancelado') {
                return redirect()->back()->with('error', 'A reserva já foi finalizada ou cancelada.');
            }

            // Altera o status do quarto para "Disponível"
            $quarto = $reserva->quarto;
            $quarto->status = 'Disponível';
            $quarto->save();

            // Atualiza o status da reserva para "cancelado"
            $reserva->status = 'cancelado';
            $reserva->save();

            return redirect()->route('reservas.index')->with('success', 'Reserva cancelada e quarto disponível novamente.');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao cancelar reserva: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            // Altera o status do quarto para "Disponível"
            $quarto = $reserva->quarto;
            $quarto->status = 'Disponível';
            $quarto->save();

            // Deleta a reserva
            $reserva->delete();

            return redirect()->route('reservas.index')->with('success', 'Reserva cancelada e quarto disponível novamente.');
        } catch (\Exception $e) {
            return back()->withErrors('Erro ao cancelar reserva: ' . $e->getMessage());
        }
    }
}
