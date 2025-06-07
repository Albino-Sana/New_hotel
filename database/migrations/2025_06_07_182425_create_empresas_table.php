<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('versao_arquivo_auditoria')->nullable(); // Versão do arquivo de auditoria
            $table->string('id_empresa')->nullable(); // ID da empresa
            $table->string('numero_registo_fiscal')->nullable(); // Número de registo fiscal (NIF)
            $table->string('base_contabil_tributaria')->nullable(); // Base contábil tributária
            $table->string('nome_empresa')->nullable(); // Nome da empresa
            $table->string('nome_negocio')->nullable(); // Nome do negócio
            $table->string('endereco_empresa')->nullable(); // Endereço da empresa
            $table->string('numero_edificio')->nullable(); // Número do edifício
            $table->string('nome_rua')->nullable(); // Nome da rua
            $table->string('cidade')->nullable(); // Cidade
            $table->string('codigo_postal')->nullable(); // Código postal
            $table->string('pais')->default('AO'); // País (padrão: Angola)
            $table->string('provincia')->nullable(); // Província
            $table->string('ano_fiscal')->nullable(); // Ano fiscal
            $table->date('data_inicio')->nullable(); // Data de início
            $table->date('data_fim')->nullable(); // Data de fim
            $table->string('codigo_moeda')->default('AOA'); // Código da moeda
            $table->dateTime('data_criacao')->nullable(); // Data de criação
            $table->string('entidade_tributaria')->nullable(); // Entidade tributária
            $table->string('id_imposto_empresa_produto')->nullable(); // ID do imposto da empresa do produto
            $table->string('numero_validacao_software')->nullable(); // Número de validação do software
            $table->string('id_produto')->nullable(); // ID do produto
            $table->string('versao_produto')->nullable(); // Versão do produto
            $table->text('comentario_cabecalho')->nullable(); // Comentário do cabeçalho
            $table->string('telefone')->nullable(); // Telefone
            $table->string('fax')->nullable(); // Fax
            $table->string('email')->nullable(); // Email
            $table->string('website')->nullable(); // Website
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}