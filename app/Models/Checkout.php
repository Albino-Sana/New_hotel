<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkin_id',
        'data_checkout',
        'valor_total',
    ];

    protected $dates = ['data_checkout'];


    public function checkin()
    {
        return $this->belongsTo(Checkin::class);
    }
    public function pagamento()
{
    return $this->hasOne(Pagamento::class);
}

    public function checkoutHospedes()
    {
        return $this->hasMany(CheckoutHospede::class);
    }
    // App\Models\Checkout.php
public function servicosAdicionais()
{
    return $this->belongsToMany(ServicoAdicional::class, 'checkout_servico_adicional', 'checkout_id', 'servico_adicional_id');
}

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
