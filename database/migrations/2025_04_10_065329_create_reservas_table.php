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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_nome');
            $table->string('cliente_documento');
            $table->string('cliente_email')->nullable();
            $table->string('cliente_telefone')->nullable();
            $table->foreignId('quarto_id')->constrained('quartos');
            $table->date('data_entrada');
            $table->date('data_saida');
            $table->integer('numero_noites')->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->enum('status', ['reservado', 'cancelado', 'finalizado'])->default('reservado');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
