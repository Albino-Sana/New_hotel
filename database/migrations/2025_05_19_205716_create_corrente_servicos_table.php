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
        Schema::create('corrente_servicos', function (Blueprint $t) {
            $t->id();

            // FK opcional para um hóspede direto…
            $t->foreignId('hospede_id')->nullable()
              ->constrained('hospedes')->cascadeOnDelete();

            // …ou para um check‑in de reserva
            $t->foreignId('checkin_id')->nullable()
              ->constrained('checkins')->cascadeOnDelete();

            // sempre deve vir **um** dos dois acima
            $t->foreignId('servico_adicional_id')
              ->constrained('servicos_adicionais');

            $t->integer('quantidade')->default(1);
            $t->decimal('valor_unitario', 15, 2);       // cache do preço
            $t->decimal('valor_total',   15, 2);       // quantidade × unitário
            $t->text('observacao')->nullable();

            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrente_servicos');
    }
};
