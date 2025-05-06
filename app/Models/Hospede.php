<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'numero_pessoas',
        'data_entrada',
        'data_saida',
        'quarto_id',
        'valor_a_pagar',
        'status',

    ];
    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }

    public function servicos()
    {
        return $this->belongsToMany(ServicoAdicional::class, 'hospede_servico');
    }

    public function servicosAdicionais()
    {
        return $this->belongsToMany(ServicoAdicional::class, 'hospede_servico')
            ->withTimestamps();
    }
}
