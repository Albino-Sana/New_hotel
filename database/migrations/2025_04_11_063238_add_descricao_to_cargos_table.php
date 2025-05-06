<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescricaoToCargosTable extends Migration
{
    public function up()
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->text('descricao')->nullable()->after('nome');
        });
    }

    public function down()
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
}
