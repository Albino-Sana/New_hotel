<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Reserva;
use App\Models\Checkout;
use App\Models\CheckoutHospede;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkin_id',
        'hospede_id',
        'valor',
        'metodo_pagamento',
        'nif_cliente',
        'descricao',
        'data_pagamento',
        'status_pagamento',
    ];
protected $dates = ['data_pagamento'];
// ou em versões mais novas do Laravel:
protected $casts = [
    'data_pagamento' => 'datetime',
];

public function checkin()
{
    return $this->belongsTo(Checkin::class);
}

public function hospede()
{
    return $this->belongsTo(Hospede::class);
}

}

