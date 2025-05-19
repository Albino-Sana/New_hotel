<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadia extends Model
{
    use HasFactory;

    protected $table = 'estadia';

    protected $fillable = [
        'hospede_id',
        'quarto_id',
        'data_entrada',
        'data_saida',
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
    ];

    public function hospede()
    {
        return $this->belongsTo(Hospede::class);
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }
}