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
        Schema::table('checkout_hospedes', function (Blueprint $table) {
            $table->foreignId('checkout_id')
                  ->constrained('checkouts')
                  ->onDelete('cascade')
                  ->after('id');
        });
    }

    public function down()
    {
        Schema::table('checkout_hospedes', function (Blueprint $table) {
            $table->dropForeign(['checkout_id']);
            $table->dropColumn('checkout_id');
        });
    }
};
