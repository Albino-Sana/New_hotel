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
 Schema::create('checkout_servico_adicional', function (Blueprint $table) {
    $table->id();
    $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
  $table->foreignId('servico_adicional_id')->constrained('servicos_adicionais')->onDelete('cascade');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_servico_adicional');
    }
};
