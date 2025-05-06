<?php

namespace App\Models;

use App\Models\Reserva;
use App\Models\Quarto;
use App\Models\Checkout;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
        'reserva_id',
        'quarto_id',
        'data_entrada',
        'data_saida',
        'num_pessoas',
        'status',
    ];


    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }

    public function valor_total()
    {
        return $this->quarto->preco_diaria * $this->diasHospedagem();
    }

    public function diasHospedagem()
    {
        return \Carbon\Carbon::parse($this->data_entrada)
            ->diffInDays(\Carbon\Carbon::parse($this->data_saida)) ?: 1;
    }

    public function checkout()
    {
        return $this->hasOne(Checkout::class);
    }
}
