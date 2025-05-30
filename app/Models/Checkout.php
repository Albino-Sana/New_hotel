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
    public function checkoutHospedes()
    {
        return $this->hasMany(CheckoutHospede::class);
    }
}
