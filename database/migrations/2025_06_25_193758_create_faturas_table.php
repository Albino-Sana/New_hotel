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
     Schema::create('faturas', function (Blueprint $table) {
    $table->id();
    $table->string('tipo_documento')->default('Fatura');
    $table->string('serie')->nullable();
    $table->unsignedBigInteger('numero')->unique();
    $table->dateTime('data_emissao')->nullable();
    $table->decimal('total', 10, 2);
    $table->decimal('valor_entregue', 10, 2)->default(0);
    $table->decimal('troco', 10, 2)->default(0);
    $table->string('nome_cliente')->nullable();
    $table->string('nif')->nullable();
    $table->string('telefone')->nullable();
    $table->string('estado_documento')->default('Proforma');
    $table->string('hash')->nullable();
    $table->string('hash_control')->nullable();
    $table->boolean('regime_autofaturacao')->default(false);
    $table->boolean('regime_iva_caixa')->default(false);
    $table->boolean('emitido_terceiros')->default(false);
    $table->string('metodo_pagamento')->nullable();
    $table->string('codigo_cae')->nullable();
    $table->unsignedBigInteger('servico_id')->nullable();
    $table->unsignedBigInteger('reserva_id')->nullable();
    $table->unsignedBigInteger('hospede_id')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
