<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupoViewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->role) {
            return redirect()->back()->with('error', 'No tienes un rol asignado para ver la disponibilidad.');
        }
        if ($user && $user->role) {
            $roleName = strtolower(trim($user->role->name)); // Convertimos el nombre del rol a minúsculas y eliminamos espacios extras

            if ($roleName === 'administrador') {
                // Si el usuario es Administrador, ve todos los grupos
                $grupos = Grupo::all();
            } else {
                // Para otros roles, filtrar los grupos cuyo nombre contenga el nombre del rol
                $grupos = Grupo::whereRaw("LOWER(nombre) LIKE ?", ["%$roleName%"])->get();
            }
        } else {
            $grupos = collect(); // Si no hay rol, devolver una colección vacía
        }

        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupos.create'); // Muestra la vista create.blade.php
    }
    public function store(Request $request)
{
    // Validar la entrada
    $request->validate([
        'nombre' => 'required|string|max:255',
    ]);

    // Crear un nuevo grupo
    $grupo = Grupo::create([
        'nombre' => $request->nombre
    ]);

    // Redirigir con un mensaje de éxito
    return redirect()->route('grupos.index')->with('success', 'Grupo creado exitosamente.');
}

//
public function asignarLaboratorioView()
{
    $user = Auth::user();

    if (!$user || !$user->role) {
        return redirect()->back()->with('error', 'No tienes un rol asignado.');
    }

    // Si el usuario es administrador, obtiene todos los grupos
    if (strtoupper($user->role->name) === 'ADMINISTRADOR') {
        $grupos = Grupo::all();
    } else {
        // Si no es administrador, filtra los grupos según su rol
        $nombreRol = strtoupper($user->role->name);
        $grupos = Grupo::where('nombre', 'LIKE', "%$nombreRol%")->get();
    }

    // Obtener todos los laboratorios sin filtros
    $laboratorios = Laboratorio::all();

    return view('grupos.asignar_laboratorio', compact('grupos', 'laboratorios'));
}


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
    $laboratorio->grupos()->attach($grupo->id);

    // Redirigir con mensaje de éxito
    return redirect()->route('grupos.index')->with('success', 'Laboratorio asignado al grupo correctamente.');
}

//eliminar grupo

public function eliminarGrupoView()
{
    // Obtener todos los grupos
    $grupos = Grupo::all();

    // Retornar la vista para eliminar grupos
    return view('grupos.eliminar', compact('grupos'));
}

public function eliminarGrupo(Request $request)
{
    // Validamos que 'grupo_id' exista en la base de datos
    $request->validate([
        'grupo_id' => 'required|integer|exists:grupos,id',
    ]);

    // Buscar el grupo por su ID
    $grupo = Grupo::findOrFail($request->grupo_id);

    // Eliminar relaciones en la tabla intermedia
    $grupo->laboratorios()->detach();

    // Eliminar el grupo de la base de datos
    $grupo->delete();

    // Redirigir con mensaje de éxito
    return redirect()->route('grupos.index')->with('success', 'Grupo eliminado correctamente.');
}
public function eliminarAsociacionView()
{
    $user = Auth::user();

    if (!$user || !$user->role) {
        return redirect()->back()->with('error', 'No tienes un rol asignado.');
    }

    // Si el usuario es administrador, obtiene todos los grupos
    if (strtoupper($user->role->name) === 'ADMINISTRADOR') {
        $grupos = Grupo::all();
    } else {
        // Si no es administrador, filtra los grupos según su rol
        $nombreRol = strtoupper($user->role->name);
        $grupos = Grupo::where('nombre', 'LIKE', "%$nombreRol%")->get();
    }

    // Obtener todos los laboratorios sin filtros
    $laboratorios = Laboratorio::all();

    return view('grupos.eliminar_asociacion', compact('grupos', 'laboratorios'));
}
public function eliminarAsociacionGrupoLaboratorio(Request $request)
{
    // Validar que los campos 'grupo_id' y 'laboratorio_id' sean proporcionados y sean válidos
    $request->validate([
        'grupo_id' => 'required|integer|exists:grupos,id',
        'laboratorio_id' => 'required|integer|exists:laboratorio,id',
    ]);

    // Buscar el grupo y el laboratorio
    $grupo = Grupo::findOrFail($request->grupo_id);
    $laboratorio = Laboratorio::findOrFail($request->laboratorio_id);

    // Verificar si la asociación existe
    if (!$grupo->laboratorios()->where('laboratorio_id', $laboratorio->id)->exists()) {
        return redirect()->back()->with('error', 'El grupo y el laboratorio no están asociados.');
    }

    // Eliminar la asociación en la tabla intermedia
    $grupo->laboratorios()->detach($laboratorio->id);

    // Redirigir con mensaje de éxito
    return redirect()->route('grupos.index')->with('success', 'Asociación eliminada correctamente.');
}

}
