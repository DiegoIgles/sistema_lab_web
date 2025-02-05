<?php

namespace App\Http\Controllers;

use App\Models\Recomendacion;
use Illuminate\Http\Request;

class RecomendacionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'descripcion' => 'required|string'
        ]);

        // Verificar si el grupo ya tiene una recomendación
        if (Recomendacion::where('grupo_id', $request->grupo_id)->exists()) {
            return response()->json(['error' => 'El grupo ya tiene una recomendación'], 400);
        }

        $recomendacion = Recomendacion::create([
            'grupo_id' => $request->grupo_id,
            'descripcion' => $request->descripcion
        ]);

        return response()->json($recomendacion, 201);
    }
     // Obtener la recomendación de un grupo por ID desde el request
     public function show(Request $request)
     {
         $request->validate([
             'grupo_id' => 'required|exists:grupos,id'
         ]);

         $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

         if (!$recomendacion) {
             return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
         }

         return response()->json($recomendacion);
     }
     // Actualizar la recomendación de un grupo usando el request
    public function update(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'descripcion' => 'required|string'
        ]);

        $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

        if (!$recomendacion) {
            return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
        }

        $recomendacion->update(['descripcion' => $request->descripcion]);

        return response()->json($recomendacion);
    }
 // Eliminar la recomendación de un grupo usando el request
 public function destroy(Request $request)
 {
     $request->validate([
         'grupo_id' => 'required|exists:grupos,id'
     ]);

     $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

     if (!$recomendacion) {
         return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
     }

     $recomendacion->delete();

     return response()->json(['message' => 'Recomendación eliminada correctamente']);
 }
}
