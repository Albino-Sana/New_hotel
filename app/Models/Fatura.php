<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    //
protected $fillable = [
    'tipo_documento',
    'serie',
    'numero',
    'data_emissao',
    'total',
    'valor_entregue',
    'troco',
    'nome_cliente',
    'nif',
    'telefone',
    'estado_documento',
    'hash',
    'hash_control',
    'regime_autofaturacao',
    'regime_iva_caixa',
    'emitido_terceiros',
    'metodo_pagamento',
    'codigo_cae',
    'mesa_id',
    'servico_id',
];


    public function checkin()
    {
        return $this->belongsTo(Checkin::class);
    }

 
}
