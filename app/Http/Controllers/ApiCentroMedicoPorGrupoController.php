<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
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
}
