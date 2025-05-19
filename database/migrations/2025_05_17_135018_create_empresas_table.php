<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
   Schema::create('empresa', function (Blueprint $table) {
    $table->id();
    $table->string('nome'); // Hotel Presidente, Lda.
    $table->string('nif'); // NIF válido para AGT
    $table->string('licenca_comercial')->nullable(); // Nº de licença
    $table->string('email')->nullable(); // Email de contacto
    $table->string('telefone')->nullable(); // Telefones principais
    $table->string('telefone_secundario')->nullable();
    $table->string('website')->nullable(); // Site do hotel
    $table->text('endereco')->nullable(); // Endereço completo
    $table->string('cidade')->nullable(); // Ex: Luanda
    $table->string('provincia')->nullable(); // Ex: Luanda
    $table->string('pais')->default('Angola');
    $table->string('conta_bancaria')->nullable(); // Nº da conta
    $table->string('iban')->nullable(); // IBAN bancário
    $table->string('banco')->nullable(); // Nome do banco
    $table->string('logo')->nullable(); // Caminho para o logo
    $table->text('notas_fatura')->nullable(); // Termos da fatura
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
