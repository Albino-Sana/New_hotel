<?php

namespace App\Http\Controllers\Documento;

use App\Models\Fatura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FaturaReciboMail;
use App\Models\Empresa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\FaturaRecibo;

class FaturaReciboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $faturas = FaturaRecibo::latest()->get();
    return view('documentos.faturaRecibo.index', compact('faturas'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fatura $fatura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fatura $fatura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fatura $fatura)
    {
        //
    }

    public function enviarEmail(Request $request, $id)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    try {
        $fatura = Fatura::findOrFail($id);
        $empresa = Empresa::firstOrFail();
        $email = $request->input('email');

        // Envia o e-mail com a fatura em anexo
        Mail::to($email)->send(new FaturaReciboMail($fatura, $empresa));

        return back()->with('success', 'Fatura enviada por e-mail com sucesso!');
    } catch (\Exception $e) {
        Log::error('Erro ao enviar fatura por e-mail: ' . $e->getMessage());
        return back()->with('error', 'Erro ao enviar e-mail: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */

}
