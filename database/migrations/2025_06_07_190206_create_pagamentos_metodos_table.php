<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentosMetodosTable extends Migration
{
    public function up()
    {
        Schema::create('pagamentos_metodos', function (Blueprint $table) {
            $table->id();
            $table->string('designacao')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos_metodos');
    }
}