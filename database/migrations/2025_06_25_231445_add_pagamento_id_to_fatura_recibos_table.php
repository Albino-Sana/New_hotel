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
    Schema::table('fatura_recibos', function (Blueprint $table) {
        $table->unsignedBigInteger('pagamento_id')->nullable()->after('id');

        $table->foreign('pagamento_id')
              ->references('id')
              ->on('pagamentos')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('fatura_recibos', function (Blueprint $table) {
        $table->dropForeign(['pagamento_id']);
        $table->dropColumn('pagamento_id');
    });
}

};
