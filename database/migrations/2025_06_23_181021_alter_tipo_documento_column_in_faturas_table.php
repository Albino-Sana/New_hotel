<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('faturas', function (Blueprint $table) {
            $table->string('tipo_documento', 50)->change(); // aumente para 50 ou mais
        });
    }

    public function down(): void {
        Schema::table('faturas', function (Blueprint $table) {
            $table->string('tipo_documento', 10)->change(); // ou o tamanho original
        });
    }
};
