<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

class CentrosmedicosPorLaboratorio extends Controller
{
    public function centrosMedicosPorLaboratorio(Request $request)
    {
        // Validar que se envíe el ID del laboratorio
        $request->validate([
            'laboratorio_id' => 'required|integer|exists:laboratorio,id',
        ]);

        // Obtener el ID del laboratorio desde el request
        $laboratorioId = $request->laboratorio_id;

        // Buscar el laboratorio por ID
        $laboratorio = Laboratorio::find($laboratorioId);

        // Obtener los centros médicos asociados
        $centrosMedicos = $laboratorio->centrosMedicos;

        // Retornar los centros médicos como respuesta JSON
        return response()->json($centrosMedicos);
    }
}
