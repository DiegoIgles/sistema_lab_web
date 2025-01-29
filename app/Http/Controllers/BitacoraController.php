<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function verBitacora()
{
    $bitacora = Bitacora::all(); // Obtener todas las entradas de la bitácora
    return view('bitacora.index', compact('bitacora'));
}
}
