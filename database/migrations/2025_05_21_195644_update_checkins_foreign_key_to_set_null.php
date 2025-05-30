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
        Schema::table('checkins', function (Blueprint $table) {
            // Remove a FK antiga
            $table->dropForeign(['quarto_id']);

            // Permite null
            $table->unsignedBigInteger('quarto_id')->nullable()->change();

            // Cria nova FK com ON DELETE SET NULL
            $table->foreign('quarto_id')
                ->references('id')->on('quartos')
                ->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('set_null', function (Blueprint $table) {
            //
        });
    }
};
