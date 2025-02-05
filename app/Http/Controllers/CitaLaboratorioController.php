<?php

namespace App\Http\Controllers;

use App\Models\CitaLaboratorio;
use App\Models\Reserva;
use Illuminate\Http\Request;

class CitaLaboratorioController extends Controller
{
    // Obtener disponibilidad de citas
    public function disponibilidad(Request $request)
    {
        $citas = CitaLaboratorio::where('centro_medico_id', $request->centro_medico_id)
            ->where('grupo_id', $request->grupo_id)
            ->where('fecha', $request->fecha)
            ->get();

        return response()->json($citas);
    }


//Crear cita
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
  ->where('hora', $request->hora)  // Verificamos que la hora también coincida
  ->first();

if ($existingCita) {
  return response()->json(['message' => 'Ya existe una cita para este centro médico, grupo, fecha y hora.'], 400);
}
    $cita = CitaLaboratorio::create([
        'centro_medico_id' => $request->centro_medico_id,
        'grupo_id' => $request->grupo_id,
        'fecha' => $request->fecha,
        'hora' => $request->hora,
        'cupos_disponibles' => $request->cupos_disponibles
    ]);

    return response()->json(['message' => 'Cita creada con éxito', 'cita' => $cita], 201);
}
public function eliminarCita(Request $request)
{
    $request->validate([
        'id' => 'required|exists:citas_laboratorio,id'
    ]);

    $cita = CitaLaboratorio::find($request->id);

    if (!$cita) {
        return response()->json(['message' => 'Cita no encontrada'], 404);
    }

    $cita->delete();

    return response()->json(['message' => 'Cita eliminada con éxito'], 200);
}
public function reservarCita(Request $request)
{
    // Validamos que se envíen los parámetros correctos, incluyendo el teléfono
    $request->validate([
        'cita_id' => 'required|exists:citas_laboratorio,id', // Verificamos que la cita exista
        'afiliado_id' => 'required|exists:afiliado,id', // Validamos que el afiliado exista
        'telefono' => 'required|string|max:15' // Validamos el teléfono, debe ser obligatorio y con un máximo de 15 caracteres
    ]);

    // Buscamos la cita
    $cita = CitaLaboratorio::find($request->cita_id);

    if (!$cita) {
        return response()->json(['message' => 'Cita no encontrada'], 404);
    }

    // Verificamos si el afiliado ya tiene una reserva para esta cita
    $existingReserva = Reserva::where('afiliado_id', $request->afiliado_id)
        ->where('cita_id', $cita->id)
        ->first();

    if ($existingReserva) {
        return response()->json(['message' => 'Este afiliado ya tiene una reserva para esta cita.'], 400);
    }

    // Verificamos si hay cupos disponibles
    if ($cita->cupos_disponibles <= 0) {
        return response()->json(['message' => 'No hay cupos disponibles'], 400);
    }

    // Restar 1 cupo
    $cita->cupos_disponibles -= 1;
    $cita->save();

    // Guardar la reserva en la tabla "reservas" incluyendo el teléfono
    $reserva = Reserva::create([
        'afiliado_id' => $request->afiliado_id,
        'cita_id' => $cita->id,
        'telefono' => $request->telefono, // Guardamos el teléfono también

    ]);

  // Obtener la recomendación del grupo (si existe)
  $recomendacion = $cita->grupo->recomendacion->descripcion ?? 'No hay recomendación para este grupo';


    // Responder con un mensaje de éxito y la información de la reserva
    return response()->json([
        'message' => 'Cita reservada con éxito',
        'reserva' => $reserva,
        'grupo' => $cita->grupo->nombre ?? 'Grupo no encontrado',
        'recomendacion' => $recomendacion
    ], 201);
}


//mostrar reservas
public function mostrarReservas(Request $request)
{
    $request->validate([
        'afiliado_id' => 'required|exists:afiliado,id' // Validamos que el afiliado_id exista en la tabla 'afiliado'
    ]);

    // Buscar todas las reservas para el afiliado dado, incluyendo citas, grupo, centro médico y la fecha
    $reservas = Reserva::with(['cita', 'cita.grupo', 'cita.centroMedico']) // Corregimos de 'cita.grupos' a 'cita.grupo'
        ->where('afiliado_id', $request->afiliado_id)
        ->get();

    // Si no tiene reservas
    if ($reservas->isEmpty()) {
        return response()->json(['message' => 'No tienes reservas aún'], 404);
    }

    // Estructura personalizada de la respuesta
    $resultados = $reservas->map(function ($reserva) {
        return [
            'id_reserva' => $reserva->id,
            'hora_reserva' => $reserva->created_at->format('H:i:s'), // Hora de la reserva
            'hora_cita' => $reserva->cita->hora, // Hora de la cita
            'grupo_cita' => $reserva->cita->grupo->nombre, // Nombre del grupo de la cita
            'centro_medico' => $reserva->cita->centroMedico->nombre, // Nombre del centro médico asociado a la cita
            'fecha_cita' => \Carbon\Carbon::parse($reserva->cita->fecha)->format('Y-m-d') // Fecha de la cita (formato: YYYY-MM-DD)
        ];
    });

    return response()->json($resultados, 200);
}

public function eliminarReserva(Request $request)
{
    // Validamos que el 'afiliado_id' y 'id_reserva' sean proporcionados
    $request->validate([
        'afiliado_id' => 'required|exists:afiliado,id', // Aseguramos que el afiliado existe
        'id_reserva' => 'required|exists:reservas,id'   // Aseguramos que la reserva existe
    ]);

    // Buscar la reserva correspondiente al 'afiliado_id' y 'id_reserva'
    $reserva = Reserva::where('afiliado_id', $request->afiliado_id)
        ->where('id', $request->id_reserva)
        ->first();

    // Si no se encuentra la reserva
    if (!$reserva) {
        return response()->json(['message' => 'Reserva no encontrada o no pertenece al afiliado indicado.'], 404);
    }

    // Obtener la cita asociada a la reserva (suponiendo que la relación se llama citaLaboratorio)
    $citaLaboratorio = $reserva->cita;  // Relación con CitaLaboratorio

    // Verificar si se encuentra la cita asociada
    if (!$citaLaboratorio) {
        return response()->json(['message' => 'Cita asociada a la reserva no encontrada.'], 404);
    }

    // Incrementar los cupos disponibles de la cita
    $citaLaboratorio->cupos_disponibles += 1;  // Aumentamos los cupos disponibles por 1
    $citaLaboratorio->save();  // Guardamos los cambios

    // Eliminar la reserva
    $reserva->delete();

    // Devolver una respuesta exitosa
    return response()->json(['message' => 'Reserva eliminada con éxito y cupo disponible incrementado.'], 200);
}

//mostrar todas las reseervas
public function obtenerReservasAdmin()
{
    // Obtener todas las reservas con las relaciones necesarias
    $reservas = Reserva::with(['afiliado', 'cita.centroMedico', 'cita.grupo'])
        ->get();

    // Si no hay reservas
    if ($reservas->isEmpty()) {
        return response()->json(['message' => 'No se encontraron reservas'], 404);
    }

    // Formatear la respuesta
    $resultado = $reservas->map(function ($reserva) {
        return [
            'id_reserva' => $reserva->id,
            'afiliado' => $reserva->afiliado->nombre, // Nombre del afiliado
            'paterno' => $reserva->afiliado->paterno, // Nombre del afiliado
            'materno' => $reserva->afiliado->materno, // Nombre del afiliado
            'centro_medico' => $reserva->cita->centroMedico->nombre, // Nombre del centro médico
            'hora_reserva' => $reserva->created_at->format('H:i:s'), // Hora de la reserva
            'fecha_cita' => \Carbon\Carbon::parse($reserva->cita->fecha)->format('Y-m-d'),
            'hora_cita' => $reserva->cita->hora, // Hora de la cita
            'grupo_cita' => $reserva->cita->grupo->nombre, // Nombre del grupo
            'telefono' => $reserva->telefono // Número de teléfono del afiliado
        ];
    });

    return response()->json(['reservas' => $resultado], 200);
}


}
