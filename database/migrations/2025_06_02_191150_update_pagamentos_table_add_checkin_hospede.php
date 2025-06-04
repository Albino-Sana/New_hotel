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
    Schema::table('pagamentos', function (Blueprint $table) {
        // Removendo colunas antigas, se existirem
        if (Schema::hasColumn('pagamentos', 'reserva_id')) {
            $table->dropForeign(['reserva_id']);
            $table->dropColumn('reserva_id');
        }

        if (Schema::hasColumn('pagamentos', 'checkout_id')) {
            $table->dropForeign(['checkout_id']);
            $table->dropColumn('checkout_id');
        }

        if (Schema::hasColumn('pagamentos', 'checkout_hospede_id')) {
            $table->dropForeign(['checkout_hospede_id']);
            $table->dropColumn('checkout_hospede_id');
        }

        // Novos campos
        $table->unsignedBigInteger('checkin_id')->nullable()->after('id');
        $table->unsignedBigInteger('hospede_id')->nullable()->after('checkin_id');

        // Novas chaves estrangeiras
        $table->foreign('checkin_id')->references('id')->on('checkins')->onDelete('set null');
        $table->foreign('hospede_id')->references('id')->on('hospedes')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('pagamentos', function (Blueprint $table) {
        $table->dropForeign(['checkin_id']);
        $table->dropForeign(['hospede_id']);
        $table->dropColumn(['checkin_id', 'hospede_id']);

        // Opcional: reverter os campos antigos se necessário
    });
}


};
