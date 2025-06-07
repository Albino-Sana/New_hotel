<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoMetodo extends Model
{
    use HasFactory;

    protected $table = 'pagamentos_metodos';

    protected $fillable = [
        'designacao',
    ];
}