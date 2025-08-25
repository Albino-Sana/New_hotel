<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoQuarto;
use App\Models\Quarto;
use App\Models\Empresa;

class SiteClienteController extends Controller
{
 // Página inicial -> mostra os tipos de quarto
 public function index()
    {
        // Carrega os tipos de quarto com todos os quartos relacionados
        $tipos = TipoQuarto::with('quartos')->get();
            $empresa = Empresa::first(); 

        return view('SiteCliente.index', compact('tipos', 'empresa'));
    }
    // Mostra todos os quartos de um tipo específico
    public function quartosPorTipo($tipo)
    {
        $quartos = Quarto::where('tipo_quarto_id', $tipo)->get();
        

        return view('SiteCliente.quartos', compact('quartos', 'tipo'));
    }

    public function about() {
    return view('SiteCliente.about');
}

public function contact() {
    return view('SiteCliente.contact');
}

public function menu() {
    return view('SiteCliente.menu');
}

public function service() {
    return view('SiteCliente.service');
}

public function team() {
    return view('SiteCliente.team');
}

public function testimonial() {
    return view('SiteCliente.testimonial');
}
}

