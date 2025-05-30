<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CorrenteServico; // Importando o modelo CorrenteServico
use App\Models\Hospede; // Importando o modelo Hospede
use App\Models\Checkin; // Importando o modelo Checkin

class CorrenteServicoController extends Controller
{
    //

    public function store(Request $r)
{
    $r->validate([
        'hospede_id'            => 'nullable|exists:hospedes,id',
        'checkin_id'            => 'nullable|exists:checkins,id',
        'servico_adicional_id'  => 'required|exists:servicos_adicionais,id',
        'quantidade'            => 'required|integer|min:1',
        'valor_unitario'        => 'required|numeric|min:0',
        'valor_total'           => 'required|numeric|min:0',
    ]);

    // regra: precisa ter **um** vínculo (hospede ou checkin)
    if (!$r->hospede_id && !$r->checkin_id) {
        return back()->withErrors('Selecione a estadia do hóspede.');
    }

    CorrenteServico::create($r->only([
        'hospede_id','checkin_id','servico_adicional_id',
        'quantidade','valor_unitario','valor_total','observacao'
    ]));

    return back()->with('success', 'Serviço lançado na conta‑corrente!');
}

}
