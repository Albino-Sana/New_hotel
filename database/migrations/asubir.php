<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // HÓSPEDES
        Schema::table('hospedes', function (Blueprint $table) {
            // Altera para nullable sem perder o valor
            DB::statement('ALTER TABLE hospedes MODIFY quarto_id BIGINT UNSIGNED NULL');
        });

        // Verifica e remove a constraint manualmente (usando raw SQL)
        if ($this->foreignKeyExists('hospedes', 'hospedes_quarto_id_foreign')) {
            DB::statement('ALTER TABLE hospedes DROP FOREIGN KEY hospedes_quarto_id_foreign');
        }

        Schema::table('hospedes', function (Blueprint $table) {
            $table->foreign('quarto_id')->references('id')->on('quartos')->onDelete('set null');
        });

        // RESERVAS
        Schema::table('reservas', function (Blueprint $table) {
            DB::statement('ALTER TABLE reservas MODIFY quarto_id BIGINT UNSIGNED NULL');
        });

        if ($this->foreignKeyExists('reservas', 'reservas_quarto_id_foreign')) {
            DB::statement('ALTER TABLE reservas DROP FOREIGN KEY reservas_quarto_id_foreign');
        }

        Schema::table('reservas', function (Blueprint $table) {
            $table->foreign('quarto_id')->references('id')->on('quartos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Reverte RESERVAS
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['quarto_id']);
            DB::statement('ALTER TABLE reservas MODIFY quarto_id BIGINT UNSIGNED NOT NULL');
            $table->foreign('quarto_id')->references('id')->on('quartos');
        });

        // Reverte HÓSPEDES
        Schema::table('hospedes', function (Blueprint $table) {
            $table->dropForeign(['quarto_id']);
            DB::statement('ALTER TABLE hospedes MODIFY quarto_id BIGINT UNSIGNED NOT NULL');
            $table->foreign('quarto_id')->references('id')->on('quartos');
        });
    }

    // Verifica se a constraint existe
    private function foreignKeyExists(string $table, string $key): bool
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ?
        ", [$table, $key]);

        return count($result) > 0;
    }
};