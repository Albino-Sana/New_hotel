<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaturaRecibo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\FaturaReciboMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;
use App\Models\Reserva;


class FaturaReciboController extends Controller
{
    //

    // FaturaReciboController.php
public function index()
{
    $faturas = FaturaRecibo::latest()->get();
    return view('documentos.faturaRecibo.index', compact('faturas'));
}



public function verFatura($id)
{
    $fatura = FaturaRecibo::findOrFail($id);
    $empresa = Empresa::first();
    return Pdf::loadView('pdf.faturas.pagamento', compact('fatura', 'empresa'))->stream('fatura_' . $fatura->numero . '.pdf');
}

public function download($id)
{
    $fatura = FaturaRecibo::findOrFail($id);
    $empresa = Empresa::first();
    return Pdf::loadView('pdf.faturas.pagamento', compact('fatura', 'empresa'))->download('fatura_' . $fatura->numero . '.pdf');
}

public function enviarEmail(Request $request, $id)
{
    $fatura = FaturaRecibo::findOrFail($id);
    $empresa = Empresa::first(); // ou empresa do fatura se já estiver

    Mail::to($request->email)->send(new FaturaReciboMail($fatura, $empresa));

    return back()->with('success', 'Fatura enviada com sucesso!');
}




public function destroy($id)
{
    $fatura = FaturaRecibo::findOrFail($id);
    $fatura->estado_documento = 'A';
    $fatura->save();
    return redirect()->route('faturasRecibo.index')->with('success', 'Fatura anulada com sucesso.');
}

}
