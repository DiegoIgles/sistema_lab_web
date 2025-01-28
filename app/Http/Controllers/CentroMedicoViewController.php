<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
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
}
