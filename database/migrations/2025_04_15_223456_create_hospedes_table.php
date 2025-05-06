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
        Schema::create('hospedes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->integer('numero_pessoas')->default(1);
            $table->date('data_entrada');
            $table->date('data_saida');
            $table->unsignedBigInteger('quarto_id');
            $table->timestamps();
    
            $table->foreign('quarto_id')->references('id')->on('quartos')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospedes');
    }
};
