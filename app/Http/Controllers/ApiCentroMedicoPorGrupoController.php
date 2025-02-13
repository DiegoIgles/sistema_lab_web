<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use App\Models\Grupo;
use App\Models\SolServicioLab;
use Illuminate\Http\Request;

class ApiCentroMedicoPorGrupoController extends Controller
{
    public function centrosMedicosPorGrupo(Request $request)
    {
        // Validar que se envíe el ID del grupo
        $request->validate([
            'grupo_id' => 'required|integer|exists:grupos,id', // Validamos el grupo_id en el cuerpo de la solicitud
        ]);

        // Obtener el ID del grupo desde el request
        $grupoId = $request->grupo_id;

        // Buscar el grupo por ID
        $grupo = Grupo::find($grupoId);

        // Obtener los centros médicos asociados a este grupo
        $centrosMedicos = $grupo->centrosMedicos;

        // Verificar si el grupo tiene centros médicos asociados
        if ($centrosMedicos->isEmpty()) {
            return response()->json(['message' => 'No hay centros médicos asociados a este grupo'], 404);
        }

        // Retornar los centros médicos como respuesta JSON
        return response()->json($centrosMedicos);
    }

    public function gruposPorCentroMedico(Request $request)
{
    $request->validate([
        'centro_medico_id' => 'required|integer|exists:centros_medicos,id',
        'solservicio_id' => 'required|integer|exists:solservicio,id',
    ]);

    $laboratoriosDelSolServicio = SolServicioLab::where('solservicio_id', $request->solservicio_id)
                                                ->pluck('laboratorio_id');

    if ($laboratoriosDelSolServicio->isEmpty()) {
        return response()->json(['message' => 'No se encontraron laboratorios para este SolServicio.'], 404);
    }

    $grupos = Grupo::with(['laboratorios' => function ($query) use ($laboratoriosDelSolServicio) {
        $query->whereIn('laboratorio.id', $laboratoriosDelSolServicio);
    }])
    ->whereHas('laboratorios', function ($query) use ($laboratoriosDelSolServicio) {
        $query->whereIn('laboratorio.id', $laboratoriosDelSolServicio);
    })
    ->whereHas('centrosMedicos', function ($query) use ($request) {
        $query->where('centros_medicos.id', $request->centro_medico_id);
    })
    ->get();

    if ($grupos->isEmpty()) {
        return response()->json(['message' => 'No se encontraron grupos para este SolServicio y Centro Médico.'], 404);
    }

    return response()->json($grupos, 200);
}

}
