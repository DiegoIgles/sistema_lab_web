<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{
    public function verBitacora()
{
    $user = Auth::user();

    if (!$user || !$user->role) {
        return redirect()->back()->with('error', 'No tienes un rol asignado para ver la disponibilidad.');
    }
    $bitacora = Bitacora::all(); // Obtener todas las entradas de la bitácora
    return view('bitacora.index', compact('bitacora'));
}
}
