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
        Schema::table('servicos_adicionais', function (Blueprint $table) {
            $table->text('descricao')->nullable()->after('nome');
        });
    }

 
    public function down()
    {
        Schema::table('servicos_adicionais', function (Blueprint $table) {
            $table->dropColumn('descricao');
            
        });
    }
};
