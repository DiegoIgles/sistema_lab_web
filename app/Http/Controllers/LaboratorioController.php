<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    public function index(){
        $laboratorios = Laboratorio::all();
        return $laboratorios;
    }
}
