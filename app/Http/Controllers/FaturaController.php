<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $faturas = Fatura::with('checkin')->latest()->get();
    return view('faturas.index', compact('faturas'));
}

public function gerarPdf($id)
{
    $fatura = Fatura::findOrFail($id);
    $pdf = Pdf::loadView('faturas.pdf', compact('fatura')); // View faturas/pdf.blade.php
    return $pdf->download("fatura_{$fatura->numero}.pdf");
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fatura $fatura)
    {
        //
    }
}
