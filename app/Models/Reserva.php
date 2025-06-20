<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quarto;
use App\Models\User;
use App\Models\Hospede;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // ou outro nome da coluna se não for 'user_id'
    }
}
