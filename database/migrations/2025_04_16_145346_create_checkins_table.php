<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id'); // associação com reserva
            $table->unsignedBigInteger('quarto_id');  // quarto associado
     
    
            $table->dateTime('data_entrada');
            $table->integer('num_pessoas')->nullable();
            $table->string('observacoes')->nullable();
    
            $table->timestamps();
    
            // Relacionamentos
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('quarto_id')->references('id')->on('quartos')->onDelete('cascade');
            $table->foreign('hospede_id')->references('id')->on('hospedes')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
