<?php

namespace App\Models;

use App\Models\Quarto;

use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;


class TipoQuarto extends Model
{
        use HasFactory;
    protected $table = 'tipos_quartos'; 
    protected $fillable = ['nome', 'descricao', 'tipo_cobranca', 'preco'];
    public function quartos()
    {
        return $this->hasMany(Quarto::class, 'tipo_quarto_id');
    }
}
