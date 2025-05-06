<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelConfigController extends Controller
{
    public function index()
    {
        $titulo = 'Dados da Empresa'; // ou "Configurações do Hotel"
        return view('header.config', compact('titulo'));
    }
}
