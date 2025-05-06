<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->string('status')->default('Hospedado')->after('quarto_id'); // ou após o campo que quiser
        });
    }
    
    public function down(): void
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
};
