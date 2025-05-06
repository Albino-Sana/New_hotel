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
        Schema::table('reservas', function (Blueprint $table) {
            $table->integer('numero_pessoas')->default(1)->after('data_saida');
        });
    }
    
    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('numero_pessoas');
        });
    }
    
};
