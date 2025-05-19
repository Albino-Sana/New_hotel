<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutHospedesTable extends Migration
{
    public function up()
    {
        Schema::create('checkout_hospedes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospede_id')->constrained()->onDelete('cascade');
            $table->dateTime('data_checkout');
            $table->decimal('valor_hospedagem', 10, 2);
            $table->decimal('valor_servicos', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->json('servicos_adicionais')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkout_hospedes');
    }
}
