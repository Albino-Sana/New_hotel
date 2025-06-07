<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\PagamentoMetodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function index()
    {
        // Carrega o primeiro registro da empresa ou cria um novo se não existir
        $hotel = Empresa::first() ?? new Empresa();

        // Carrega todos os métodos de pagamento
        $pagamentos_metodos = PagamentoMetodo::all();

        return view('empresa.config', compact('hotel', 'pagamentos_metodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'versao_arquivo_auditoria' => 'nullable|string|max:255',
            'id_empresa' => 'nullable|string|max:255',
            'numero_registo_fiscal' => 'nullable|string|max:255',
            'base_contabil_tributaria' => 'nullable|string|max:255',
            'nome_empresa' => 'required|string|max:255',
            'nome_negocio' => 'nullable|string|max:255',
            'endereco_empresa' => 'nullable|string|max:255',
            'numero_edificio' => 'nullable|string|max:255',
            'nome_rua' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:2',
            'provincia' => 'nullable|string|max:255',
            'ano_fiscal' => 'nullable|string|max:255',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date',
            'codigo_moeda' => 'nullable|string|max:3',
            'data_criacao' => 'nullable|date',
            'entidade_tributaria' => 'nullable|string|max:255',
            'id_imposto_empresa_produto' => 'nullable|string|max:255',
            'numero_validacao_software' => 'nullable|string|max:255',
            'id_produto' => 'nullable|string|max:255',
            'versao_produto' => 'nullable|string|max:255',
            'comentario_cabecalho' => 'nullable|string',
            'telefone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            // Atualiza ou cria o registro da empresa
            $empresa = Empresa::first() ?? new Empresa();
            $empresa->fill($request->all());
            $empresa->save();

            return redirect()->route('empresa.index')->with('success', 'Dados da empresa salvos com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados da empresa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao salvar dados: ' . $e->getMessage());
        }
    }
}