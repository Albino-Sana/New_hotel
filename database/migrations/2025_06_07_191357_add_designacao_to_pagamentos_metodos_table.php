<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignacaoToPagamentosMetodosTable extends Migration
{
    public function up()
    {
        Schema::table('pagamentos_metodos', function (Blueprint $table) {
            $table->string('designacao')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('pagamentos_metodos', function (Blueprint $table) {
            $table->dropColumn('designacao');
        });
    }
}