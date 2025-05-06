<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicoAdicional extends Model
{
    //
    protected $table = 'servicos_adicionais';
    protected $fillable = ['nome',  'preco', 'descricao'];
    public function hospedes()
{
    return $this->belongsToMany(Hospede::class, 'hospede_servico')
                ->withTimestamps();
}

}
