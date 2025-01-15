<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class AfiliadoController extends Controller
{
    public function index()
    {
        $afiliados = Afiliado::all(); // Recupera todos los registros
        return response()->json($afiliados);
    }

    public function show(Request $request){
        $afiliado = Afiliado::find($request->afiliado_id);
        return json_encode( $afiliado);
    }
}
