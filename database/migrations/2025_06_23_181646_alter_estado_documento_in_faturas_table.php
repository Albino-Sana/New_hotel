<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('faturas', function (Blueprint $table) {
            $table->string('estado_documento', 20)->change(); // Ajuste para 20 ou mais por segurança
        });
    }

    public function down(): void {
        Schema::table('faturas', function (Blueprint $table) {
            $table->string('estado_documento', 5)->change(); // Ou o valor antigo
        });
    }
};
