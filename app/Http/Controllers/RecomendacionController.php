<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Recomendacion;
use App\Models\SolServicioLab;
use Illuminate\Http\Request;

class RecomendacionController extends Controller
{
//     public function store(Request $request)
//     {
//         $request->validate([
//             'grupo_id' => 'required|exists:grupos,id',
//             'descripcion' => 'required|string'
//         ]);

//         // Verificar si el grupo ya tiene una recomendación
//         if (Recomendacion::where('grupo_id', $request->grupo_id)->exists()) {
//             return response()->json(['error' => 'El grupo ya tiene una recomendación'], 400);
//         }

//         $recomendacion = Recomendacion::create([
//             'grupo_id' => $request->grupo_id,
//             'descripcion' => $request->descripcion
//         ]);

//         return response()->json($recomendacion, 201);
//     }
//      // Obtener la recomendación de un grupo por ID desde el request
//      public function show(Request $request)
//      {
//          $request->validate([
//              'grupo_id' => 'required|exists:grupos,id'
//          ]);

//          $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

//          if (!$recomendacion) {
//              return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
//          }

//          return response()->json($recomendacion);
//      }
//      // Actualizar la recomendación de un grupo usando el request
//     public function update(Request $request)
//     {
//         $request->validate([
//             'grupo_id' => 'required|exists:grupos,id',
//             'descripcion' => 'required|string'
//         ]);

//         $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

//         if (!$recomendacion) {
//             return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
//         }

//         $recomendacion->update(['descripcion' => $request->descripcion]);

//         return response()->json($recomendacion);
//     }
//  // Eliminar la recomendación de un grupo usando el request
//  public function destroy(Request $request)
//  {
//      $request->validate([
//          'grupo_id' => 'required|exists:grupos,id'
//      ]);

//      $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();

//      if (!$recomendacion) {
//          return response()->json(['error' => 'No se encontró una recomendación para este grupo'], 404);
//      }

//      $recomendacion->delete();

//      return response()->json(['message' => 'Recomendación eliminada correctamente']);
//  }

public function store(Request $request)
{
    $request->validate([
        'tipo' => 'required|in:grupo,laboratorio', // Verifica que sea solo 'grupo' o 'laboratorio'
        'descripcion' => 'required|string',
        'grupo_id' => 'required_if:tipo,grupo|exists:grupos,id', // Solo se requiere si el tipo es 'grupo'
        'laboratorio_id' => 'required_if:tipo,laboratorio|exists:laboratorio,id' // Solo se requiere si el tipo es 'laboratorio'
    ]);

    // Verificar si el grupo ya tiene una recomendación
    if ($request->tipo === 'grupo' && Recomendacion::where('grupo_id', $request->grupo_id)->exists()) {
        return response()->json(['error' => 'El grupo ya tiene una recomendación'], 400);
    }

    // Verificar si el laboratorio ya tiene una recomendación
    if ($request->tipo === 'laboratorio' && Recomendacion::where('laboratorio_id', $request->laboratorio_id)->exists()) {
        return response()->json(['error' => 'El laboratorio ya tiene una recomendación'], 400);
    }

    // Crear la recomendación
    $recomendacion = Recomendacion::create([
        'tipo' => $request->tipo,
        'grupo_id' => $request->tipo === 'grupo' ? $request->grupo_id : null,
        'laboratorio_id' => $request->tipo === 'laboratorio' ? $request->laboratorio_id : null,
        'descripcion' => $request->descripcion
    ]);

    return response()->json($recomendacion, 201);
}

public function show(Request $request)
{
    $request->validate([
        'tipo' => 'required|in:grupo,laboratorio',
        'grupo_id' => 'required_if:tipo,grupo|exists:grupos,id',
        'laboratorio_id' => 'required_if:tipo,laboratorio|exists:laboratorio,id'
    ]);

    if ($request->tipo === 'grupo') {
        $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();
    } else {
        $recomendacion = Recomendacion::where('laboratorio_id', $request->laboratorio_id)->first();
    }

    if (!$recomendacion) {
        return response()->json(['error' => 'No se encontró una recomendación'], 404);
    }

    return response()->json($recomendacion);
}
public function destroy(Request $request)
{
    $request->validate([
        'tipo' => 'required|in:grupo,laboratorio', // Validamos que sea grupo o laboratorio
        'grupo_id' => 'required_if:tipo,grupo|exists:grupos,id', // Solo se requiere si el tipo es 'grupo'
        'laboratorio_id' => 'required_if:tipo,laboratorio|exists:laboratorio,id' // Solo se requiere si el tipo es 'laboratorio'
    ]);

    // Eliminar recomendación por grupo
    if ($request->tipo === 'grupo') {
        $recomendacion = Recomendacion::where('grupo_id', $request->grupo_id)->first();
    } else { // Eliminar recomendación por laboratorio
        $recomendacion = Recomendacion::where('laboratorio_id', $request->laboratorio_id)->first();
    }

    // Verificar si se encontró la recomendación
    if (!$recomendacion) {
        return response()->json(['error' => 'No se encontró una recomendación'], 404);
    }

    // Eliminar la recomendación
    $recomendacion->delete();

    return response()->json(['message' => 'Recomendación eliminada correctamente']);
}

 //mostrar recomendaciones dado un solserviciolabID
 public function show2(Request $request)
{


   // Buscar los servicios relacionados con el 'solservicio_id'
   $servicioslab = SolServicioLab::where('solservicio_id', $request->solservicio_id)->get();

   // Si no hay servicios para el solservicio_id dado, devolver un mensaje adecuado
   if ($servicioslab->isEmpty()) {
       return response()->json([
           'message' => 'No se encontraron servicios para este solservicio.'
       ], 404);
   }

   // Filtrar los servicios que tengan una recomendación
   $servicioslabConRecomendacion = $servicioslab->map(function ($servicioLab) {
       $laboratorio = Laboratorio::find($servicioLab->laboratorio_id);
       $grupo = $laboratorio ? $laboratorio->obtenerGrupo() : null;
       $recomendacion = $laboratorio ? $laboratorio->recomendacion : null;

       // Solo devolver los servicios que tengan recomendación
       if ($recomendacion) {
           $servicioLab->laboratorio_nombre = $laboratorio ? $laboratorio->nombre : 'Desconocido';
           $servicioLab->grupo_nombre = $grupo ? $grupo->nombre : 'Sin Grupo';
           $servicioLab->recomendacion = $recomendacion->descripcion;

           return $servicioLab;
       }
   })->filter();  // .filter() elimina los elementos nulos del resultado

   // Si no hay servicios con recomendación, devolver un mensaje adecuado
   if ($servicioslabConRecomendacion->isEmpty()) {
       return response()->json([
           'message' => 'No hay servicios con recomendaciones para este solservicio.'
       ], 404);
   }

   // Retornar los servicios encontrados con las recomendaciones
   return response()->json($servicioslabConRecomendacion, 200);
}

 }
