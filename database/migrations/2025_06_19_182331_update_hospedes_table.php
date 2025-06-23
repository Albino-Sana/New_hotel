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
                Schema::table('hospedes', function (Blueprint $table) {
                    $table->datetime('data_entrada')->change();
                    $table->datetime('data_saida')->change();
                    $table->decimal('valor_a_pagar', 10, 2)->nullable()->change();
                    $table->string('status')->default('Hospedado')->change();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospedes', function (Blueprint $table) {
                    $table->datetime('data_entrada')->change();
                    $table->datetime('data_saida')->change();
                    $table->decimal('valor_a_pagar', 10, 2)->nullable()->change();
                    $table->string('status')->default('Hospedado')->change();
                });
    }
};
