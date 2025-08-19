<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quarto;
use App\Models\User;
use App\Models\Hospede;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ServicoAdicional;

class Reserva extends Model
{
    protected $table = 'reservas'; // Nome da tabela no banco de dados
    use SoftDeletes;
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

    protected $dates = ['data_entrada', 'data_saida', 'created_at', 'deleted_at'];
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
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
        return $this->belongsTo(Quarto::class, 'quarto_id');
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }

    public function servicosAdicionais()
    {
        return $this->belongsToMany(ServicoAdicional::class, 'reserva_servico_adicional', 'reserva_id', 'servico_adicional_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // ou outro nome da coluna se não for 'user_id'
    }
}
