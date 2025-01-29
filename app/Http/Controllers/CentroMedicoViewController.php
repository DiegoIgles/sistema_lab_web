<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\CentroMedico;
use App\Models\Laboratorio;
use Illuminate\Http\Request;

class CentroMedicoViewController extends Controller
{
    // Mostrar todos los centros médicos
    public function index()
    {
        $centrosMedicos = CentroMedico::all(); // Obtener todos los centros médicos
        return view('centros.index', compact('centrosMedicos'));
    }

    public function create()
    {
        return view('centros.create');
    }

    // Crear un nuevo centro médico
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        // Crear el centro médico
        CentroMedico::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('centros.index')->with('success', 'Centro Médico creado exitosamente.');
    }


    public function destroy($id)
    {
        $centroMedico = CentroMedico::findOrFail($id);
        $centroMedico->delete();

        return redirect()->route('centros.index')->with('success', 'Centro Médico eliminado exitosamente.');
    }


    public function showEliminarRelacion()
{
    $centrosMedicos = CentroMedico::all();
    $laboratorios = Laboratorio::all(); // Asegúrate de que 'Laboratorio' es el modelo correcto
    return view('centros.eliminar-relacion', compact('centrosMedicos', 'laboratorios'));
}

public function eliminarRelacion(Request $request)
{
    // Validar los datos
    $request->validate([
        'centros_medicos_id' => 'required|integer|exists:centros_medicos,id',
        'laboratorio_id' => 'required|integer|exists:laboratorio,id',
    ]);

    // Buscar el centro médico
    $centroMedico = CentroMedico::find($request->centros_medicos_id);
    $laboratorio = Laboratorio::find($request->laboratorio_id);

    // Verificar si la relación existe
    if (!$centroMedico->laboratorios()->where('laboratorio.id', $request->laboratorio_id)->exists()) {
        return back()->with('error', 'La relación no existe.');
    }

    // Eliminar la relación
    $centroMedico->laboratorios()->detach($request->laboratorio_id);

// Registrar la acción en la bitácora
Bitacora::create([
    'accion' => 'DESHABILITADO', // La acción es "DESHABILITADO"
    'nombre_laboratorio' => $laboratorio->nombre,
    'nombre_centro_medico' => $centroMedico->nombre,
]);

    return back()->with('success', 'Relación eliminada exitosamente.');
}

}
