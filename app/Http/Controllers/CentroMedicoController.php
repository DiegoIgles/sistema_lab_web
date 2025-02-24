<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\CentroMedico;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
public function destroyAssociation(Request $request)
{
    // Obtener los datos del Centro Médico y Grupo seleccionados
    $centroMedicoId = $request->input('centro_medico_id');
    $grupoId = $request->input('grupo_id');

    // Validar que ambos valores fueron seleccionados
    if (!$centroMedicoId || !$grupoId) {
        return redirect()->back()->with('error', 'Debe seleccionar tanto un Centro Médico como un Grupo.');
    }

    // Obtener el Centro Médico y el Grupo
    $centroMedico = CentroMedico::findOrFail($centroMedicoId);
    $grupo = Grupo::findOrFail($grupoId);

    // Verificar si la relación existe
    if (!$centroMedico->grupos->contains($grupo)) {
        return redirect()->back()->with('error', 'Grupos no relacionados.');
    }

    // Eliminar la asociación entre el Centro Médico y el Grupo
    $centroMedico->grupos()->detach($grupoId);

    // Registrar la acción en la bitácora con el nombre del usuario autenticado
    Bitacora::create([
        'accion' => 'DESHABILITADO',
        'nombre_laboratorio' => $grupo->nombre,
        'nombre_centro_medico' => $centroMedico->nombre,
        'nombre_usuario' => auth()->user()->name, // Manejo seguro de usuario
        'fecha_accion' => now(),
    ]);

    // Redirigir con mensaje de éxito
    return redirect()->route('centrosMedicos.showRemoveAssociation')
        ->with('success', 'La asociación entre el Centro Médico y el Grupo ha sido eliminada.');
}

public function showRemoveAssociation()
{
    $user = Auth::user();

    // Verificar si el usuario tiene un rol asignado
    if (!$user || !$user->role) {
        return redirect()->back()->with('error', 'No tienes un rol asignado para eliminar una asociación.');
    }

    // Si el usuario es Administrador, obtiene todos los Centros Médicos y Grupos
    if (strtoupper($user->role->name) === 'ADMINISTRADOR') {
        $centrosMedicos = CentroMedico::all();
        $grupos = Grupo::all();
    } else {
        // Si no es Administrador, filtra los Centros Médicos y Grupos según su rol
        $nombreRol = strtoupper($user->role->name);
        $centrosMedicos = CentroMedico::where('nombre', 'LIKE', "%$nombreRol%")->get();
        $grupos = Grupo::where('nombre', 'LIKE', "%$nombreRol%")->get();
    }

    // Mostrar la vista con los datos filtrados
    return view('centro_medico_grupo.removeAssociation', compact('centrosMedicos', 'grupos'));
}

}
