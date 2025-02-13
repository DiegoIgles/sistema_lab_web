<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Laboratorio;
use App\Models\Recomendacion;
use Illuminate\Http\Request;

class RecomendacionesViewController extends Controller
{

    // // Mostrar la vista con todas las recomendaciones y grupos
    // public function index()
    // {
    //     $grupos = Grupo::with('recomendacion')->get();
    //     return view('recomendaciones.index', compact('grupos'));
    // }

    // // Mostrar la vista para crear una nueva recomendación
    // public function create()
    // {
    //     $grupos = Grupo::doesntHave('recomendacion')->get(); // Solo grupos sin recomendación
    //     return view('recomendaciones.create', compact('grupos'));
    // }

    // // Guardar la nueva recomendación en la base de datos
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'grupo_id' => 'required|exists:grupos,id|unique:recomendaciones,grupo_id',
    //         'descripcion' => 'required|string|max:255'
    //     ]);

    //     Recomendacion::create([
    //         'grupo_id' => $request->grupo_id,
    //         'descripcion' => $request->descripcion
    //     ]);

    //     return redirect()->route('recomendaciones.index')->with('success', 'Recomendación creada con éxito.');
    // }

    // // Eliminar una recomendación
    // public function destroy($id)
    // {
    //     $recomendacion = Recomendacion::findOrFail($id);
    //     $recomendacion->delete();

    //     return redirect()->route('recomendaciones.index')->with('success', 'Recomendación eliminada correctamente.');
    // }

      // Mostrar la vista con todas las recomendaciones y grupos
      public function index()
{
    // Obtener solo los laboratorios que tienen recomendaciones
    $laboratoriosConRecomendacion = Laboratorio::whereHas('recomendacion')->get();

    // Obtener los grupos con sus recomendaciones, si existen
    $grupos = Grupo::with('recomendacion')->get();

    return view('recomendaciones.index', compact('grupos', 'laboratoriosConRecomendacion'));
}

      // Mostrar la vista para crear una nueva recomendación
      public function create()
      {
          // Obtener los grupos y laboratorios que no tienen recomendación
          $grupos = Grupo::doesntHave('recomendacion')->get();
          $laboratorios = Laboratorio::doesntHave('recomendacion')->get();

          return view('recomendaciones.create', compact('grupos', 'laboratorios'));
      }

      // Guardar la nueva recomendación en la base de datos
      public function store(Request $request)
      {
          $request->validate([
              'tipo' => 'required|in:grupo,laboratorio', // Aseguramos que el tipo sea 'grupo' o 'laboratorio'
              'descripcion' => 'required|string|max:255', // Descripción de la recomendación
              'grupo_id' => 'required_if:tipo,grupo|exists:grupos,id|unique:recomendaciones,grupo_id', // Si es 'grupo', validamos el grupo
              'laboratorio_id' => 'required_if:tipo,laboratorio|exists:laboratorio,id|unique:recomendaciones,laboratorio_id', // Si es 'laboratorio', validamos el laboratorio
          ]);

          // Verificar si el tipo es 'grupo' o 'laboratorio' y crear la recomendación correspondiente
          $data = [
              'tipo' => $request->tipo,
              'descripcion' => $request->descripcion
          ];

          // Si el tipo es 'grupo', se agrega el `grupo_id`
          if ($request->tipo === 'grupo') {
              $data['grupo_id'] = $request->grupo_id;
          }

          // Si el tipo es 'laboratorio', se agrega el `laboratorio_id`
          if ($request->tipo === 'laboratorio') {
              $data['laboratorio_id'] = $request->laboratorio_id;
          }

          // Crear la recomendación en la base de datos
          Recomendacion::create($data);

          // Redirigir a la página de recomendaciones con un mensaje de éxito
          return redirect()->route('recomendaciones.index')->with('success', 'Recomendación creada con éxito.');
      }

      // Eliminar una recomendación
      public function destroy($id)
      {
          // Buscar la recomendación por ID y eliminarla
          $recomendacion = Recomendacion::findOrFail($id);
          $recomendacion->delete();

          // Redirigir a la lista de recomendaciones con un mensaje de éxito
          return redirect()->route('recomendaciones.index')->with('success', 'Recomendación eliminada correctamente.');
      }
}
