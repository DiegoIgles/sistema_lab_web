<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use App\Models\CitaLaboratorio;
use App\Models\Grupo;
use App\Models\Reserva;
use Illuminate\Http\Request;

class CitaLaboratorioViewController extends Controller
{
    public function showDisponibilidadForm(Request $request)
    {
        // Obtener todos los centros médicos y grupos
        $centrosMedicos = CentroMedico::all();
        $grupos = Grupo::all();

        // Variables para las citas (si se hace búsqueda)
        $citas = null;

        // Si el formulario fue enviado (se hace la búsqueda)
        if ($request->isMethod('get') && $request->has('centro_medico_id') && $request->has('grupo_id') && $request->has('fecha')) {
            // Filtrar las citas según los datos enviados
            $citas = CitaLaboratorio::where('centro_medico_id', $request->centro_medico_id)
                ->where('grupo_id', $request->grupo_id)
                ->where('fecha', $request->fecha)
                ->get();
        }

        return view('admin.disponibilidad', compact('centrosMedicos', 'grupos', 'citas'));
    }


    //crear cita admin
    public function showCrearCitaForm()
    {
        // Obtener todos los centros médicos y grupos
        $centrosMedicos = CentroMedico::all();
        $grupos = Grupo::all();

        // Retornar la vista con los datos necesarios
        return view('admin.crearCita', compact('centrosMedicos', 'grupos'));
    }

    public function crearCita(Request $request)
    {
        $request->validate([
            'centro_medico_id' => 'required|exists:centros_medicos,id',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'cupos_disponibles' => 'required|integer|min:1'
        ]);

        // Verificamos si ya existe una cita con los mismos 'centro_medico_id', 'grupo_id', 'fecha' y 'hora'
        $existingCita = CitaLaboratorio::where('centro_medico_id', $request->centro_medico_id)
            ->where('grupo_id', $request->grupo_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->first();

            if ($existingCita) {
                // Si la cita ya existe, redirigimos al formulario con un mensaje de error
                return redirect()->back()->with('error', 'Ya existe una cita para este centro médico, grupo, fecha y hora.');
            }

        $cita = CitaLaboratorio::create([
            'centro_medico_id' => $request->centro_medico_id,
            'grupo_id' => $request->grupo_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'cupos_disponibles' => $request->cupos_disponibles
        ]);

        return redirect()->route('admin.disponibilidad')->with('success', 'Cita creada con éxito.');
    }
    public function eliminarCita($id)
{
    $cita = CitaLaboratorio::find($id);

    if (!$cita) {
        return redirect()->route('admin.disponibilidad')->with('error', 'Cita no encontrada.');
    }

    $cita->delete();

    return redirect()->route('admin.disponibilidad')->with('success', 'Cita eliminada con éxito.');
}



// vista reservas
public function obtenerReservasAdmin(Request $request)
{
    // Obtener los filtros desde el formulario
    $centroMedicoId = $request->input('centro_medico_id');
    $fechaCita = $request->input('fecha');

    // Consulta base con relaciones
    $query = Reserva::with(['afiliado', 'cita.centroMedico', 'cita.grupo']);

    // Aplicar filtro por Centro Médico si está seleccionado
    if ($centroMedicoId) {
        $query->whereHas('cita', function ($q) use ($centroMedicoId) {
            $q->where('centro_medico_id', $centroMedicoId);
        });
    }

    // Aplicar filtro por Fecha de Cita si está seleccionada
    if ($fechaCita) {
        $query->whereHas('cita', function ($q) use ($fechaCita) {
            $q->whereDate('fecha', $fechaCita); // Asegura que la fecha coincida exactamente
        });
    }

    // Obtener los resultados
    $reservas = $query->get();

    // Verificar si no hay reservas con los filtros seleccionados
    if ($reservas->isEmpty()) {
        return view('admin.reservas', [
            'mensaje' => 'No se encontraron reservas con los filtros seleccionados.',
            'centrosMedicos' => CentroMedico::all(), // Pasamos todos los centros médicos a la vista
        ]);
    }

    // Pasar los resultados filtrados a la vista
    return view('admin.reservas', [
        'reservas' => $reservas->map(function ($reserva) {
            return [
                'afiliado' => $reserva->afiliado->nombre . ' ' . $reserva->afiliado->paterno . ' ' . $reserva->afiliado->materno,
                'centro_medico' => $reserva->cita->centroMedico->nombre,
                'fecha_cita' => \Carbon\Carbon::parse($reserva->cita->fecha)->format('Y-m-d'),
                'hora_cita' => $reserva->cita->hora,
                'grupo_cita' => $reserva->cita->grupo->nombre,
                'telefono' => $reserva->telefono,
            ];
        }),
        'centrosMedicos' => CentroMedico::all(), // Centros médicos para el filtro
    ]);
}


}
