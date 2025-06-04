<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutHospede extends Model
{
    protected $fillable = [
        'hospede_id',
        'data_checkout',
        'valor_hospedagem',
        'valor_servicos',
        'valor_total',
        'servicos_adicionais',
    ];

    protected $casts = [
        'data_checkout' => 'datetime',
        'servicos_adicionais' => 'array',
    ];

    public function hospede()
    {
        return $this->belongsTo(Hospede::class);
    }
public function pagamento()
{
    return $this->hasOne(Pagamento::class);
}

    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id', 'id');
    }
}