<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDataEntradaSaidaToDatetimeInReservasTable extends Migration
{
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dateTime('data_entrada')->change();
            $table->dateTime('data_saida')->change();
        });
    }

    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->date('data_entrada')->change();
            $table->date('data_saida')->change();
        });
    }
}
