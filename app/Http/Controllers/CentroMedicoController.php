<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use Illuminate\Http\Request;

class CentroMedicoController extends Controller
{
    // Obtener todos los centros médicos
    public function index(Request $request)
    {
        // Validar si se requieren parámetros específicos en la consulta
        $request->validate([
            'name' => 'nullable|string|max:255',  // Si buscas por nombre
        ]);

        // Si se pasa un nombre, filtrar por el nombre
        $query = CentroMedico::query();
        if ($request->has('name')) {
            $query->where('nombre', 'like', '%' . $request->name . '%');
        }

        // Obtener todos los centros médicos que coinciden con los filtros
        $centrosMedicos = $query->get();

        // Retornar la lista de centros médicos
        return response()->json($centrosMedicos);
    }

    // Crear un nuevo centro médico
    public function store(Request $request)
    {
        // Validar la información recibida
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        // Crear un nuevo centro médico
        $centroMedico = CentroMedico::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
        ]);

        return response()->json($centroMedico, 201);
    }

    public function asociarLaboratorio(Request $request)
{
    // Validar que se envíen los datos correctos
    $request->validate([
        'centros_medicos_id' => 'required|integer|exists:centros_medicos,id',
        'laboratorio_id' => 'required|integer|exists:laboratorio,id',
    ]);

    // Obtener el centro médico
    $centroMedico = CentroMedico::find($request->centros_medicos_id);

    // Asociar el laboratorio solo si no está ya asociado
    $centroMedico->laboratorios()->syncWithoutDetaching($request->laboratorio_id);

    return response()->json([
        'message' => 'Laboratorio asociado exitosamente.',
    ]);
}

    public function destroy(Request $request)
{
    // Validar que se envíe el ID del centro médico
    $request->validate([
        'id' => 'required|integer|exists:centros_medicos,id',
    ]);

    // Buscar el centro médico por su ID
    $centroMedico = CentroMedico::find($request->id);

    // Manejar si el centro médico no existe (aunque la validación ya lo asegura)
    if (!$centroMedico) {
        return response()->json(['error' => 'Centro Médico no encontrado.'], 404);
    }

    // Eliminar el centro médico
    $centroMedico->delete();

    return response()->json(['message' => 'Centro Médico eliminado exitosamente.']);
}

public function eliminarRelacion(Request $request)
{
    // Validar los datos recibidos
    $request->validate([
        'centros_medicos_id' => 'required|integer|exists:centros_medicos,id',
        'laboratorio_id' => 'required|integer|exists:laboratorio,id',
    ]);

    // Buscar el centro médico
    $centroMedico = CentroMedico::find($request->centros_medicos_id);

    // Verificar si la relación existe
    if (!$centroMedico->laboratorios()->where('laboratorio.id', $request->laboratorio_id)->exists()) {
        return response()->json(['error' => 'La relación no existe.'], 404);
    }

    // Eliminar la relación
    $centroMedico->laboratorios()->detach($request->laboratorio_id);

    return response()->json(['message' => 'Relación eliminada exitosamente.']);
}


}
