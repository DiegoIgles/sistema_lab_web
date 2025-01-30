<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Laboratorio;
use App\Models\SolServicio;
use App\Models\SolServicioLab;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
        // Obtener todos los grupos con sus laboratorios
        public function index()
        {
            return response()->json(Grupo::with('laboratorios')->get());
        }

        // Crear un nuevo grupo
        public function store(Request $request)
        {
            $request->validate(['nombre' => 'required|string|max:255']);

            $grupo = Grupo::create(['nombre' => $request->nombre]);

            return response()->json($grupo, 201);
        }

        // Asignar un laboratorio a un grupo
        public function asignarLaboratorio(Request $request)
        {
            // Validar que se envíen los datos correctos
            $request->validate([
                'grupo_id' => 'required|exists:grupos,id', // El grupo debe existir
                'laboratorio_id' => 'required|exists:laboratorio,id', // El laboratorio debe existir
            ]);

            // Obtener el laboratorio y el grupo
            $laboratorio = Laboratorio::findOrFail($request->laboratorio_id);
            $grupo = Grupo::findOrFail($request->grupo_id);

            // Asociar el laboratorio al grupo (de forma que el laboratorio pertenezca a un solo grupo)
            // Utilizando la tabla intermedia 'grupo_laboratorio'
            $laboratorio->grupos()->attach($grupo->id);

            // Devolver una respuesta indicando que la asignación fue exitosa
            return response()->json(['message' => 'Laboratorio asignado al grupo correctamente.'], 200);
        }

        // Obtener todos los laboratorios de un grupo
    public function obtenerLaboratorios(Request $request)
    {
    // Validar que el grupo_id sea enviado en el cuerpo de la solicitud
    $request->validate(['grupo_id' => 'required|exists:grupos,id']);

    // Obtener el grupo con su relación de laboratorios
    $grupo = Grupo::with('laboratorios')->find($request->grupo_id);

    // Si el grupo no tiene laboratorios o no existe
    if (!$grupo) {
        return response()->json(['message' => 'Grupo no encontrado o no tiene laboratorios.'], 404);
    }

    // Retornar el grupo con sus laboratorios asociados
    return response()->json($grupo);
    }


   public function getGruposConLaboratorios(Request $request)
   {
    // Validamos que el solservicio_id exista
    $request->validate([
        'solservicio_id' => 'required|integer|exists:solservicio,id',
    ]);

    // Paso 1: Obtener los laboratorios asociados a ese solservicio_id
    $solServicioLaboratorios = SolServicioLab::where('solservicio_id', $request->solservicio_id)->pluck('laboratorio_id');

    // Si no encontramos laboratorios para ese solservicio_id, devolvemos un mensaje de error
    if ($solServicioLaboratorios->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron laboratorios para este SolServicio.'
        ], 404);
    }

    // Paso 2: Obtener los grupos que contienen esos laboratorios
    $grupos = Grupo::with(['laboratorios' => function ($query) use ($solServicioLaboratorios) {
        // Filtrar los laboratorios de cada grupo que están asociados con los laboratorios de esa solicitud
        $query->whereIn('laboratorio.id', $solServicioLaboratorios);
    }])
    ->whereHas('laboratorios', function ($query) use ($solServicioLaboratorios) {
        // Filtramos los grupos que tienen los laboratorios relacionados con esa solicitud
        $query->whereIn('laboratorio.id', $solServicioLaboratorios);
    })
    ->get();

    // Si no encontramos grupos asociados a esos laboratorios, devolvemos un mensaje de error
    if ($grupos->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron grupos asociados a los laboratorios del SolServicio.'
        ], 404);
    }

    // Retornar los grupos encontrados con los laboratorios filtrados
    return response()->json($grupos, 200);
}


// eliminar grupo
public function eliminarGrupo(Request $request)
{
    // Validamos que el 'grupo_id' exista y sea un ID válido
    $request->validate([
        'grupo_id' => 'required|integer|exists:grupos,id', // Asegura que el grupo exista
    ]);

    // Buscar el grupo por su ID
    $grupo = Grupo::findOrFail($request->grupo_id);

    // Eliminar el grupo y sus relaciones en la tabla intermedia 'grupo_laboratorio'
    $grupo->laboratorios()->detach();  // Elimina la relación del grupo con los laboratorios

    // Eliminar el grupo de la base de datos
    $grupo->delete();

    // Retornar respuesta indicando que se ha eliminado correctamente
    return response()->json([
        'message' => 'Grupo eliminado correctamente.',
    ], 200);
}

//eliminar asociacion entre grupo y lab
public function eliminarAsociacionGrupoLaboratorio(Request $request)
{
    // Validar que los campos 'grupo_id' y 'laboratorio_id' sean proporcionados y sean válidos
    $request->validate([
        'grupo_id' => 'required|integer|exists:grupos,id',  // Asegura que el grupo exista
        'laboratorio_id' => 'required|integer|exists:laboratorio,id',  // Asegura que el laboratorio exista
    ]);

    // Buscar el grupo y el laboratorio
    $grupo = Grupo::findOrFail($request->grupo_id);
    $laboratorio = Laboratorio::findOrFail($request->laboratorio_id);

    // Verificar si la asociación existe entre el grupo y el laboratorio
    $isAssociated = $grupo->laboratorios()->where('laboratorio_id', $laboratorio->id)->exists();

    // Si no están asociados, retornar un mensaje indicando que no están asociados
    if (!$isAssociated) {
        return response()->json([
            'message' => 'El grupo y el laboratorio no están asociados.',
        ], 404);
    }

    // Eliminar la asociación entre el grupo y el laboratorio en la tabla intermedia
    $grupo->laboratorios()->detach($laboratorio->id);

    // Retornar respuesta indicando que la asociación fue eliminada correctamente
    return response()->json([
        'message' => 'Asociación entre el grupo y el laboratorio eliminada correctamente.',
    ], 200);
}


//laboratoiro por servicio y grupo
public function getLaboratoriosPorGrupoYServicio(Request $request)
{
    // Validamos que el solservicio_id y grupo_id sean proporcionados y sean válidos
    $request->validate([
        'solservicio_id' => 'required|integer|exists:solservicio,id',  // Asegura que el solservicio exista
        'grupo_id' => 'required|integer|exists:grupos,id',  // Asegura que el grupo exista
    ]);

    // Paso 1: Obtener los laboratorios asociados a ese solservicio_id
    $solServicioLaboratorios = SolServicioLab::where('solservicio_id', $request->solservicio_id)->pluck('laboratorio_id');

    // Si no encontramos laboratorios para este solservicio_id, devolvemos un mensaje de error
    if ($solServicioLaboratorios->isEmpty()) {
        return response()->json([
            'message' => 'No se encontraron laboratorios para este SolServicio.'
        ], 404);
    }

    // Paso 2: Obtener el grupo específico
    $grupo = Grupo::with(['laboratorios' => function ($query) use ($solServicioLaboratorios) {
        // Filtramos los laboratorios del grupo que están asociados con los laboratorios del solservicio
        $query->whereIn('laboratorio.id', $solServicioLaboratorios);
    }])
    ->where('id', $request->grupo_id)  // Aseguramos que solo obtenemos el grupo especificado
    ->first();  // Usamos first() ya que esperamos solo un grupo

    // Si no encontramos el grupo o no tiene laboratorios asociados, devolvemos un mensaje de error
    if (!$grupo) {
        return response()->json([
            'message' => 'No se encontró el grupo o no tiene laboratorios asociados al SolServicio.'
        ], 404);
    }

    // Retornar el grupo con los laboratorios filtrados por solservicio_id
    return response()->json($grupo, 200);
}

}
