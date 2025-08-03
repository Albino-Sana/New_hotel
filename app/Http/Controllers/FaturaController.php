<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fatura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

use App\Models\Reserva;
class FaturaController extends Controller
{
    //
    public function index()
{
    $faturas = Fatura::with('operador')->orderBy('data_emissao', 'desc')->get();

    return view('documentos.fatura.index', compact('faturas'));
}




public function verFatura($id)
{
    $fatura = Fatura::findOrFail($id);
    $empresa = Empresa::firstOrFail();
    $reserva = Reserva::find($fatura->reserva_id); // <- Corrigir aqui

    $pdf = Pdf::loadView('pdf.faturas.reserva', compact('fatura', 'empresa', 'reserva'));
    return $pdf->stream('fatura_reserva_' . $fatura->numero . '.pdf');
}

public function download($id)
{
    $fatura = Fatura::findOrFail($id);
    $empresa = Empresa::firstOrFail();
$reserva = $fatura->reserva ?? null;

$pdf = Pdf::loadView('pdf.faturas.reserva', compact('fatura', 'empresa', 'reserva'));
    return PDF::loadView('pdf.faturas.reserva', compact('fatura', 'empresa'))
        ->download("fatura_{$fatura->numero}.pdf");
}
public function enviarEmail(Request $request, $id)
{
    $request->validate(['email' => 'required|email']);

    $fatura = Fatura::findOrFail($id);
    $empresa = Empresa::firstOrFail();
    $email = $request->email;

    Mail::to($email)->send(new \App\Mail\FaturaMail($fatura, $empresa));

    return back()->with('success', 'Fatura enviada com sucesso!');
}



public function destroy($id)
{
    $fatura = Fatura::findOrFail($id);
    $fatura->estado_documento = 'A'; // A = Anulado
    $fatura->save();

    return back()->with('success', 'Fatura anulada com sucesso!');
}

}
