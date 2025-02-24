<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use App\Models\CitaLaboratorio;
use App\Models\Grupo;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaLaboratorioViewController extends Controller
{
    public function showDisponibilidadForm(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return redirect()->back()->with('error', 'No tienes un rol asignado para ver la disponibilidad.');
        }

        // Obtener lista de centros y grupos según el rol del usuario
        if (strtoupper($user->role->name) === 'ADMINISTRADOR') {
            $centrosMedicos = CentroMedico::all();
            $grupos = Grupo::all();
        } else {
            $nombreRol = strtoupper($user->role->name);
            $centrosMedicos = CentroMedico::where('nombre', 'LIKE', "%$nombreRol%")->get();
            $grupos = Grupo::where('nombre', 'LIKE', "%$nombreRol%")->get();
        }

        // Inicializar consulta
        $query = CitaLaboratorio::query();

        // Aplicar filtros solo si hay valores seleccionados
        if ($request->has('centro_medico_id') && $request->centro_medico_id != '') {
            $query->where('centro_medico_id', $request->centro_medico_id);
        }

        if ($request->has('grupo_id') && $request->grupo_id != '') {
            $query->where('grupo_id', $request->grupo_id);
        }

        if ($request->has('fecha') && $request->fecha != '') {
            $query->where('fecha', $request->fecha);
        }

        // Restringir la consulta si el usuario no es administrador
        if (strtoupper($user->role->name) !== 'ADMINISTRADOR') {
            $query->whereHas('centroMedico', function ($q) use ($nombreRol) {
                $q->where('nombre', 'LIKE', "%$nombreRol%");
            })->whereHas('grupo', function ($q) use ($nombreRol) {
                $q->where('nombre', 'LIKE', "%$nombreRol%");
            });
        }

        // Obtener los resultados
        $citas = $query->get();

        return view('admin.disponibilidad', compact('centrosMedicos', 'grupos', 'citas'));
    }



    //crear cita admin

    public function showCrearCitaForm()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return redirect()->back()->with('error', 'No tienes un rol asignado para crear una cita.');
        }

        if (strtoupper($user->role->name) === 'ADMINISTRADOR') {
            // Si es Administrador, obtiene todos los registros
            $centrosMedicos = CentroMedico::all();
            $grupos = Grupo::all();
        } else {
            // Si no es Administrador, filtra por el rol del usuario
            $nombreRol = strtoupper($user->role->name);
            $centrosMedicos = CentroMedico::where('nombre', 'LIKE', "%$nombreRol%")->get();
            $grupos = Grupo::where('nombre', 'LIKE', "%$nombreRol%")->get();
        }

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
    $user = Auth::user();

    if (!$user || !$user->role) {
        return redirect()->back()->with('error', 'No tienes un rol asignado para ver la disponibilidad.');
    }
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
