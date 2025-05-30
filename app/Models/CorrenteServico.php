<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrenteServico extends Model
{
    //
       protected $fillable = [
        'hospede_id',
        'checkin_id',
        'servico_adicional_id',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'observacao',
    ];

     /* ────────────────  RELACIONAMENTOS  ──────────────── */

    public function hospede()  { return $this->belongsTo(Hospede::class); }
    public function checkin()  { return $this->belongsTo(Checkin::class); }
    public function servico()  { return $this->belongsTo(ServicoAdicional::class, 'servico_adicional_id'); }
}
