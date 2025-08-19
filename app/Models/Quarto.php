<?php

namespace App\Models;

use App\Models\TipoQuarto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Checkin;
use App\Models\Reserva;
use App\Models\Hospede;

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
        'tipo_cobranca',
        'descricao',
        'updated_at',
        'created_at',
    ];

    public function checkin()
    {
        return $this->hasOne(Checkin::class)->where('status', 'Hospedado');
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
public function reserva()
{
    return $this->hasOne(Reserva::class, 'quarto_id');
}


public function hospede()
    {
        return $this->hasOne(Hospede::class, 'quarto_id')->where('status', 'Hospedado');
    }

    public function tipoQuarto()
    {
        return $this->belongsTo(TipoQuarto::class, 'tipo_quarto_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoQuarto::class, 'tipo_quarto_id');
    }
}
