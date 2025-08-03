<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;
use App\Models\Fatura;
use App\Models\FaturaRecibo;
use App\Models\Hospede;
use App\Models\Empresa;


class SAFTController extends Controller
{
    // Exibe o formulário
    public function form()
    {
        return view('saft.form'); // Certifica-te que o ficheiro está em: resources/views/saft/form.blade.php
    }

    // Gera o ficheiro SAFT
public function gerar(Request $request)
{
    $request->validate([
        'inicio' => 'required|date',
        'fim' => 'required|date|after_or_equal:inicio',
    ]);

    $inicio = $request->input('inicio');
    $fim = $request->input('fim');

    // Incluímos as relações: empresa + cliente (hóspede ou reserva)
    $faturas = Fatura::with(['empresa', 'hospede', 'reserva.hospede'])
        ->whereBetween('data_emissao', [$inicio, $fim])
        ->get();

    $recibos = FaturaRecibo::with(['hospede', 'reserva.hospede'])
        ->whereBetween('data_emissao', [$inicio, $fim])
        ->get();

    $saftArray = [
        'AuditFile' => [
            'Header' => [
                'CompanyID' => $faturas->first()?->empresa->nif ?? '000000000',
                'CompanyName' => $faturas->first()?->empresa->nome ?? 'Nome da Empresa',
                'FiscalYear' => now()->format('Y'),
                'StartDate' => $inicio,
                'EndDate' => $fim,
                'CurrencyCode' => 'AOA',
            ],
            'SourceDocuments' => [
                'SalesInvoices' => [
                    'Invoice' => $faturas->map(function ($fatura) {
                        // Determinar cliente (de hóspede direto ou reserva)
                        $cliente = $fatura->hospede ?? $fatura->reserva->hospede ?? null;

                        return [
                            'InvoiceNo' => $fatura->id,
                            'InvoiceDate' => $fatura->data_emissao,
                            'Customer' => [
                                'CustomerID' => $cliente->id ?? '0',
                                'CompanyName' => $cliente->nome ?? 'Cliente desconhecido',
                                'CustomerTaxID' => $cliente->nif ?? '999999990',
                            ],
                            'GrossTotal' => number_format($fatura->valor_total, 2),
                        ];
                    })->toArray()
                ]
            ]
        ]
    ];

    $xml = ArrayToXml::convert($saftArray, 'AuditFile');

    $filename = 'SAFT-' . now()->format('Y-m-d-His') . '.xml';

    Storage::put("private/saft/{$filename}", $xml);

    return response($xml)
        ->header('Content-Type', 'application/xml')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}



    // Baixar o ficheiro
    public function download($filename)
    {
        $path = storage_path("app/private/saft/{$filename}");

        if (!file_exists($path)) {
            abort(404, "Ficheiro não encontrado");
        }

       return response($xml)
        ->header('Content-Type', 'application/xml')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
