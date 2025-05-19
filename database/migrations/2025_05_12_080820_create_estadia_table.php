<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadiaTable extends Migration
{
    public function up()
    {
        Schema::create('estadia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospede_id')->constrained()->onDelete('cascade');
            $table->foreignId('quarto_id')->constrained()->onDelete('cascade');
            $table->date('data_entrada');
            $table->date('data_saida');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estadia');
    }
}