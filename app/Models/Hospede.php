<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospede extends Model
{
    use HasFactory;
  protected $table = 'hospedes';
    protected $fillable = [
    
        'hospede_id',
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

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida' => 'date',
    ];

    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }

    public function pagamento()
{
    return $this->hasOne(Pagamento::class);
}

    public function reserva()
    {
        return $this->hasOne(Reserva::class);
    }

    public function servicosAdicionais()
    {
        return $this->belongsToMany(ServicoAdicional::class, 'hospede_servico')->withTimestamps();
    }
    public function checkin()
    {
        return $this->hasOne(Checkin::class);
    }
    public function estadias()
    {
        return $this->hasMany(Estadia::class);
    }
    public function checkoutHospede()
    {
        return $this->hasOne(CheckoutHospede::class);
    }
}