<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserTipo
{
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        $allowedTypes = ['Administrador', 'Recepcionista', 'Balconista', 'Gerente de Quarto'];

        if (!Auth::check() || !in_array(Auth::user()->tipo, $tipos)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
