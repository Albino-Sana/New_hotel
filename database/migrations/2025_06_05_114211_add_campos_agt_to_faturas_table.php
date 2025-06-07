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
 

$table->date('data_emissao')->nullable()->after('numero');

        $table->decimal('total', 14, 2)->after('data_emissao');
        $table->decimal('valor_entregue', 14, 2)->nullable()->after('total');
        $table->decimal('troco', 14, 2)->nullable()->after('valor_entregue');

        $table->string('nome_cliente')->after('troco');
        $table->string('nif', 15)->after('nome_cliente');
        $table->string('telefone')->nullable()->after('nif');

        $table->string('estado_documento', 1)->default('N')->after('telefone');
        $table->string('hash', 172)->nullable()->after('estado_documento');
        $table->string('hash_control', 70)->nullable()->after('hash');

        $table->boolean('regime_autofaturacao')->default(0)->after('hash_control');
        $table->boolean('regime_iva_caixa')->default(0)->after('regime_autofaturacao');
        $table->boolean('emitido_terceiros')->default(0)->after('regime_iva_caixa');

        $table->string('metodo_pagamento')->nullable()->after('emitido_terceiros');
        $table->string('codigo_cae', 10)->nullable()->after('metodo_pagamento');
        $table->unsignedBigInteger('mesa_id')->nullable()->after('codigo_cae');
        $table->unsignedBigInteger('servico_id')->nullable()->after('mesa_id');
    });
}

public function down()
{
    Schema::table('faturas', function (Blueprint $table) {
        $table->dropColumn([
            'serie', 'numero', 'data_emissao', 'total', 'valor_entregue', 'troco',
            'nome_cliente', 'nif', 'telefone', 'estado_documento', 'hash', 'hash_control',
            'regime_autofaturacao', 'regime_iva_caixa', 'emitido_terceiros',
            'metodo_pagamento', 'codigo_cae', 'mesa_id', 'servico_id'
        ]);
    });
}

};
