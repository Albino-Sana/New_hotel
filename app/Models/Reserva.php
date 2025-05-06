<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quarto;

class Reserva extends Model
{
    protected $table = 'reservas'; // Nome da tabela no banco de dados
    protected $fillable = [
        'cliente_nome',
        'cliente_documento',
        'cliente_email',
        'cliente_telefone',
        'quarto_id',
        'data_entrada',
        'data_saida',
        'numero_noites',
        'valor_total',
        'status',
        'observacoes',
        'numero_pessoas', // Adicionando o campo numero_pessoas
    ];
    public function hospede()
    {
        return $this->belongsTo(Hospede::class, 'hospede_id');
    }

    public function checkin()
    {
        return $this->hasOne(Checkin::class);
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }
}
