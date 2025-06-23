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
    Schema::table('quartos', function (Blueprint $table) {
        $table->dropUnique('quartos_numero_unique'); // Remove unique anterior
        $table->unique(['numero', 'andar']); // Aplica o novo
    });
}

public function down()
{
    Schema::table('quartos', function (Blueprint $table) {
        $table->dropUnique(['numero', 'andar']);
        $table->unique('numero');
    });
}

};
