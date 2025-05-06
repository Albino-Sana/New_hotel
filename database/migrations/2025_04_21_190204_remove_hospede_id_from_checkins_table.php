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
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropForeign(['hospede_id']); // Se houver FK
            $table->dropColumn('hospede_id');
        });
    }
    
    public function down()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->unsignedBigInteger('hospede_id')->nullable();
            // $table->foreign('hospede_id')->references('id')->on('hospedes'); // se quiser reverter
        });
    }
    
};
