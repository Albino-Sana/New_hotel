<?php

namespace App\Models;

use App\Models\TipoQuarto;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Quarto extends Model
{
    //
    use HasFactory;
    protected $table = 'quartos'; // Nome da tabela no banco de dados
    protected $fillable = [
        'numero',
        'andar',
        'tipo_quarto_id',
        'status',
        'preco_noite',
        'descricao',
    ];

    public function checkin()
{
    return $this->hasOne(Checkin::class)->where('status', 'Hospedado');
}
public function reserva()
{
    return $this->hasOne(Reserva::class, 'quarto_id', 'id');
}
public function hospede()
{
    return $this->hasOne(Hospede::class, 'quarto_id')->latest(); 
}




    public function tipo()
    {
        return $this->belongsTo(TipoQuarto::class, 'tipo_quarto_id');
    }
}
