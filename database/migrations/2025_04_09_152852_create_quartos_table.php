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
        Schema::create('quartos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->integer('andar');
            $table->foreignId('tipo_quarto_id')->constrained('tipo_quartos')->onDelete('cascade');
            $table->enum('status', ['Disponível', 'Ocupado', 'Manutenção'])->default('Disponível');
            $table->decimal('preco_noite', 10, 2)->nullable();
            $table->string('descricao')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quartos');
    }
};
