<?php

namespace App\Http\Controllers;

use App\Models\SolServicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = SolServicio::all(); // Recupera todos los registros
        return response()->json($servicios);
    }
    public function show(Request $request){
        $servicio = SolServicio::find($request->solservicio_id);
        return json_encode( $servicio);
    }

    public function show2(Request $request){
         // Buscar los servicios relacionados con el 'afiliado_id'
    $servicios = SolServicio::where('afiliado_id', $request->afiliado_id)->get();

    // Si no hay servicios para el afiliado dado, devolver un mensaje adecuado
    if ($servicios->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron servicios para este afiliado.'
        ], 404);
    }

    // Retornar los servicios encontrados en formato JSON
    return response()->json($servicios, 200);
    }

}

