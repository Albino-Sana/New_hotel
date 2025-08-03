<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;
use App\Models\Fatura;
use App\Models\FaturaRecibo;
use App\Models\Hospede;



class GerarSAFT extends Command
{
    protected $signature = 'app:gerar-s-a-f-t {inicio} {fim}';
    protected $description = 'Gera o ficheiro SAFT baseado nas faturas e recibos emitidos.';

    public function handle()
    {
        $inicio = $this->argument('inicio');
        $fim = $this->argument('fim');

        $faturas = Fatura::with('empresa')
            ->whereBetween('data_emissao', [$inicio, $fim])
            ->get();

        $recibos = FaturaRecibo::with('empresa')
            ->whereBetween('data_emissao', [$inicio, $fim])
            ->get();

        $documentos = [];

        foreach ($faturas as $fatura) {
            $documentos[] = [
                'Tipo' => 'Fatura',
                'Numero' => $fatura->id,
                'Data' => $fatura->data_emissao,
                'Cliente' => $fatura->nome_cliente,
                'NIF' => $fatura->nif_cliente,
                'Valor' => $fatura->valor_total,
            ];
        }

        foreach ($recibos as $recibo) {
            $documentos[] = [
                'Tipo' => 'Recibo',
                'Numero' => $recibo->id,
                'Data' => $recibo->data_emissao,
                'Cliente' => $recibo->nome_cliente,
                'NIF' => $recibo->nif_cliente,
                'Valor' => $recibo->valor_total,
            ];
        }

        $saftArray = [
            'AuditFile' => [
                'Header' => [
                    'CompanyID' => $faturas->first()?->empresa->nif ?? '000000000',
                    'CompanyName' => $faturas->first()?->empresa->nome ?? 'Nome da Empresa',
                    'FiscalYear' => date('Y'),
                    'StartDate' => $inicio,
                    'EndDate' => $fim,
                    'CurrencyCode' => 'AOA',
                ],
                'SourceDocuments' => [
                    'SalesInvoices' => [
                        'Invoice' => $documentos
                    ]
                ]
            ]
        ];

        $xml = ArrayToXml::convert($saftArray, 'AuditFile');
        $fileName = 'saft/SAFT-' . now()->format('Y-m-d_H-i-s') . '.xml';
        Storage::put($fileName, $xml);

        $this->info("Ficheiro SAFT gerado com sucesso em: storage/app/{$fileName}");
    }
}