<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifica se a tabela existe para adicionar o que falta
        if (Schema::hasTable('hospede_servico')) {
            Schema::table('hospede_servico', function (Blueprint $table) {
                // Verifica e adiciona colunas ausentes
                if (!Schema::hasColumn('hospede_servico', 'hospede_id')) {
                    $table->foreignId('hospede_id')
                          ->constrained('hospedes')
                          ->onDelete('cascade');
                }

                if (!Schema::hasColumn('hospede_servico', 'servico_adicional_id')) {
                    $table->foreignId('servico_adicional_id')
                          ->constrained('servicos_adicionais')
                          ->onDelete('cascade');
                }

                // Adiciona índice único se não existir
                $table->unique(['hospede_id', 'servico_adicional_id'], 'hospede_servico_unique');
            });
        } else {
            Schema::create('hospede_servico', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospede_id')->constrained()->onDelete('cascade');
                $table->foreignId('servico_adicional_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['hospede_id', 'servico_adicional_id']);
            });
        }
    }

    public function down(): void
    {
        // Não removemos a tabela, apenas os elementos adicionados
        Schema::table('hospede_servico', function (Blueprint $table) {
            $table->dropUnique('hospede_servico_unique');
            $table->dropForeign(['hospede_id']);
            $table->dropForeign(['servico_adicional_id']);
        });
    }
};