<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Só cria a tabela se ela NÃO existir
        if (!Schema::hasTable('pagamentos')) {
            Schema::create('pagamentos', function (Blueprint $table) {
                $table->id();

                // Origem do pagamento
                $table->unsignedBigInteger('reserva_id')->nullable();
                $table->unsignedBigInteger('checkout_id')->nullable();
                $table->unsignedBigInteger('checkout_hospede_id')->nullable();

                // Dados do pagamento
                $table->decimal('valor', 10, 2);
                $table->string('metodo_pagamento');
                $table->string('nif_cliente')->nullable();
                $table->text('descricao')->nullable();
                $table->timestamp('data_pagamento')->useCurrent();
                $table->string('status_pagamento')->default('pendente'); // pendente, pago, cancelado, reembolsado

                // Chaves estrangeiras (só cria se as tabelas referenciadas existirem)
                if (Schema::hasTable('reservas')) {
                    $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
                }
                if (Schema::hasTable('checkouts')) {
                    $table->foreign('checkout_id')->references('id')->on('checkouts')->onDelete('set null');
                }
                if (Schema::hasTable('checkout_hospedes')) {
                    $table->foreign('checkout_hospede_id')->references('id')->on('checkout_hospedes')->onDelete('set null');
                }

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Só dropa a tabela se ela existir (opcional, cuidado em produção)
        Schema::dropIfExists('pagamentos');
    }
};