<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TipoQuarto extends Model
{
    protected $table = 'tipos_quartos'; 
    protected $fillable = ['nome', 'descricao'];
    public function quartos()
    {
        return $this->hasMany(Quarto::class, 'tipo_quarto_id');
    }
}
