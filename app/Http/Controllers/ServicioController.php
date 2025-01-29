<?php

namespace App\Http\Controllers;

use App\Models\SolServicio;
use App\Models\SolServicioLab;
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

 //solo muestra los solservicio de un afiliado pero que tenga laboratorio
    public function getServiciosLaboratorio(Request $request)
    {
        $request->validate([
            'afiliado_id' => 'required|integer|exists:afiliado,id',
        ]);

        // Obtener los IDs de los servicios del afiliado
        $solServiciosIds = SolServicio::where('afiliado_id', $request->afiliado_id)->pluck('id');

        // Buscar los registros en la tabla intermedia solservicio_laboratorio
        // Solo devolver los servicios que están asociados a un laboratorio en la tabla intermedia
        $solServiciosLaboratorio = SolServicioLab::whereIn('solservicio_id', $solServiciosIds)->get();

        // Si no se encuentran registros de solservicio vinculados a laboratorio
        if ($solServiciosLaboratorio->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron servicios de laboratorio para este afiliado.'
            ], 404);
        }

        // Devolver los SolServicios que están vinculados a laboratorios
        $solServicios = SolServicio::whereIn('id', $solServiciosLaboratorio->pluck('solservicio_id'))->get();

        return response()->json($solServicios, 200);
    }
}

