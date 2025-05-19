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
            $table->date('ultimo_email_enviado')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropColumn('ultimo_email_enviado');
        });
    }
    
};
