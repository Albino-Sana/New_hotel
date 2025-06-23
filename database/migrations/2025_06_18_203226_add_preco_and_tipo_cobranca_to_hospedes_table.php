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
            $table->decimal('preco_noite', 10, 2)->nullable()->after('valor_a_pagar');
            $table->string('tipo_cobranca')->nullable()->after('preco_noite');
        });
    }

    public function down(): void
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->dropColumn(['preco_noite', 'tipo_cobranca']);
        });
    }
};
