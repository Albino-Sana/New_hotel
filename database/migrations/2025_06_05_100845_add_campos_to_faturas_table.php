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
    Schema::table('faturas', function (Blueprint $table) {
        $table->unsignedBigInteger('checkin_id')->nullable()->after('id');
        $table->decimal('valor', 10, 2)->default(0)->after('checkin_id');
        $table->string('tipo')->default('recibo')->after('valor'); // recibo, fatura, etc.
        $table->text('descricao')->nullable()->after('tipo');

        $table->foreign('checkin_id')->references('id')->on('checkins')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('faturas', function (Blueprint $table) {
        $table->dropForeign(['checkin_id']);
        $table->dropColumn(['checkin_id', 'valor', 'tipo', 'descricao']);
    });
}

};
