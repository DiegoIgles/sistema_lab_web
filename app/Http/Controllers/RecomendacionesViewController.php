<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Recomendacion;
use Illuminate\Http\Request;

class RecomendacionesViewController extends Controller
{

    // Mostrar la vista con todas las recomendaciones y grupos
    public function index()
    {
        $grupos = Grupo::with('recomendacion')->get();
        return view('recomendaciones.index', compact('grupos'));
    }

    // Mostrar la vista para crear una nueva recomendación
    public function create()
    {
        $grupos = Grupo::doesntHave('recomendacion')->get(); // Solo grupos sin recomendación
        return view('recomendaciones.create', compact('grupos'));
    }

    // Guardar la nueva recomendación en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id|unique:recomendaciones,grupo_id',
            'descripcion' => 'required|string|max:255'
        ]);

        Recomendacion::create([
            'grupo_id' => $request->grupo_id,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('recomendaciones.index')->with('success', 'Recomendación creada con éxito.');
    }

    // Eliminar una recomendación
    public function destroy($id)
    {
        $recomendacion = Recomendacion::findOrFail($id);
        $recomendacion->delete();

        return redirect()->route('recomendaciones.index')->with('success', 'Recomendación eliminada correctamente.');
    }
}
