<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\CentroMedico;
use App\Models\Grupo;
use Illuminate\Http\Request;

class CentroMedicoGrupoController extends Controller
{
    // Muestra el formulario para crear la relación
    public function create()
    {
        // Obtener todos los Centros Médicos y Grupos
        $centrosMedicos = CentroMedico::all();
        $grupos = Grupo::all();

        return view('centro_medico_grupo.create', compact('centrosMedicos', 'grupos'));
    }

    // Almacena la relación en la tabla intermedia
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'centro_medico_id' => 'required|exists:centros_medicos,id',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        // Obtener el centro médico
        $centroMedico = CentroMedico::find($request->centro_medico_id);
        $grupo = Grupo::find($request->grupo_id);

        // Verificar si ya existe la relación entre el centro médico y el grupo
        if ($centroMedico->grupos->contains($request->grupo_id)) {
            return redirect()->route('centro_medico_grupo.create')->with('error', 'El Centro Médico ya está asociado a este Grupo');
        }

        // Crear la relación en la tabla intermedia
        $centroMedico->grupos()->attach($request->grupo_id);

     //bitacora
     Bitacora::create([
        'accion' => 'HABILITADO',
        'nombre_laboratorio' => $grupo->nombre,
        'nombre_centro_medico' => $centroMedico->nombre,
        'fecha_accion' => now(),
    ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('centro_medico_grupo.create')->with('success', 'Relación creada correctamente');
    }
    public function index()
    {
        // Obtener todos los Centros Médicos con sus Grupos asociados
        $centrosMedicos = CentroMedico::with('grupos')->get();

        // Retornar la vista con los Centros Médicos y sus Grupos
        return view('centro_medico_grupo.index', compact('centrosMedicos'));
    }
}
