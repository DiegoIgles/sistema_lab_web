<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use App\Models\Laboratorio;
use Illuminate\Http\Request;

class RelacionController extends Controller
{
    // Mostrar formulario para asociar laboratorio con centro médico
    public function create()
    {
        // Obtener todos los centros médicos y laboratorios
        $centrosMedicos = CentroMedico::all();
        $laboratorios = Laboratorio::all();

        return view('relaciones.create', compact('centrosMedicos', 'laboratorios'));
    }

    // Asociar laboratorio con centro médico
    public function store(Request $request)
    {
        // Validar la información recibida
        $request->validate([
            'centros_medicos_id' => 'required|integer|exists:centros_medicos,id',
            'laboratorio_id' => 'required|integer|exists:laboratorio,id',
        ]);

        // Obtener el centro médico
        $centroMedico = CentroMedico::find($request->centros_medicos_id);

        // Asociar el laboratorio
        $centroMedico->laboratorios()->syncWithoutDetaching($request->laboratorio_id);

        return redirect()->route('relaciones.create')->with('success', 'Laboratorio asociado exitosamente.');
    }


    public function index()
    {
        // Obtener todos los centros médicos con sus laboratorios asociados
        $centrosMedicos = CentroMedico::with('laboratorios')->get();

        return view('relaciones.index', compact('centrosMedicos'));
    }

}
