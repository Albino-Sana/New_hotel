<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class FaturaRecibo extends Model
{
    use HasFactory;

    protected $table = 'fatura_recibos';
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
        'servico_id',
        'reserva_id',
        'hospede_id',
        'pagamento_id'
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
    public function hospede()
    {
        return $this->belongsTo(Hospede::class);
    }
    public function pagamento()
{
    return $this->belongsTo(Pagamento::class);
}

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

}
