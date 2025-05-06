<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    // Adicionar os novos campos no $fillable
    protected $fillable = [
        'nome',
        'email',
        'cargo_id',
        'telefone',
        'sexo',
        'morada',
        'nacionalidade',
        'data_nascimento',
        'idade',
        'foto',
        'status',
        'tipo_documento',
        'numero_documento',
        'naturalidade',
        'salario',
        'turno',
        'estado_civil',
        'active',
    ];
    

    // Relacionamento com Cargo
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}
