<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'versao_arquivo_auditoria', // Versão do arquivo de auditoria
        'id_empresa', // ID da empresa
        'numero_registo_fiscal', // Número de registo fiscal (NIF)
        'base_contabil_tributaria', // Base contábil tributária
        'nome_empresa', // Nome da empresa
        'nome_negocio', // Nome do negócio
        'endereco_empresa', // Endereço da empresa
        'numero_edificio', // Número do edifício
        'nome_rua', // Nome da rua
        'cidade', // Cidade
        'codigo_postal', // Código postal
        'pais', // País
        'provincia', // Província
        'ano_fiscal', // Ano fiscal
        'data_inicio', // Data de início
        'data_fim', // Data de fim
        'codigo_moeda', // Código da moeda
        'data_criacao', // Data de criação
        'entidade_tributaria', // Entidade tributária
        'id_imposto_empresa_produto', // ID do imposto da empresa do produto
        'numero_validacao_software', // Número de validação do software
        'id_produto', // ID do produto
        'versao_produto', // Versão do produto
        'comentario_cabecalho', // Comentário do cabeçalho
        'telefone', // Telefone
        'fax', // Fax
        'email', // Email
        'website', // Website
    ];
    
}