<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\TipoQuarto;

class TipoQuartoController extends Controller
{
    //
     public function show($id): JsonResponse
    {
        $tipo = TipoQuarto::find($id);

        if (!$tipo) {
            return response()->json(['error' => 'Tipo de quarto não encontrado.'], 404);
        }

        return response()->json([
            'preco_noite' => $tipo->preco,
            'tipo_cobranca' => $tipo->tipo_cobranca
        ]);
    }
}
