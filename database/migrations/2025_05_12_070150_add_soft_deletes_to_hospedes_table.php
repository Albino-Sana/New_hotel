<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToHospedesTable extends Migration
{
    public function up()
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}