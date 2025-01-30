<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
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
    public function centrosMedicosPorLaboratorios(Request $request)
    {
        // Validar que se envíen los IDs de los laboratorios
        $request->validate([
            'laboratorio_ids' => 'required|array',  // Asegura que se envíe un arreglo
            'laboratorio_ids.*' => 'integer|exists:laboratorio,id',  // Asegura que cada ID sea válido
        ]);

        // Obtener los IDs de los laboratorios desde el request
        $laboratorioIds = $request->laboratorio_ids;

        // Obtener todos los centros médicos asociados a esos laboratorios
        $centrosMedicos = CentroMedico::whereHas('laboratorios', function ($query) use ($laboratorioIds) {
            $query->whereIn('laboratorio.id', $laboratorioIds); // Asegúrate de especificar la tabla
        })->get();

        // Retornar los centros médicos como respuesta JSON
        return response()->json($centrosMedicos);
    }

}
