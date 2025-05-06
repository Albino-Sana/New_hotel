<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE quartos MODIFY status ENUM('Disponível', 'Ocupado', 'Reservado', 'Manutenção') DEFAULT 'Disponível'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE quartos MODIFY status ENUM('Disponível', 'Ocupado', 'Manutenção') DEFAULT 'Disponível'");
    }
};
