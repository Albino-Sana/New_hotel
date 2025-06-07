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
        $table->string('numero')->nullable()->change();
    });
}

public function down()
{
    Schema::table('faturas', function (Blueprint $table) {
        $table->string('numero')->nullable(false)->change();
    });
}

};
