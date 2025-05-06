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
        Schema::create('estadias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospede_id')->constrained('hospedes')->onDelete('cascade');
            $table->foreignId('quarto_id')->constrained('quartos');
            $table->date('data_entrada');
            $table->date('data_saida')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadias');
    }
};
