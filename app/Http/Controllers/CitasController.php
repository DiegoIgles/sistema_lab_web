<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CuposPorHora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CitasController extends Controller
{
    public function mostrar_citas()
    {
        // Obtener citas con información del usuario
        $citas = Cita::with('user')->get();

        // Formatear el resultado para incluir el nombre del usuario
        $citasConUsuario = $citas->map(function ($cita) {
            return [
                'id' => $cita->id,
                'user_id' => $cita->user_id,
                'user_name' => $cita->user->name, // Agregar el nombre del usuario
                'fecha' => $cita->fecha,
                'hora' => $cita->hora,
                'estado' => $cita->estado,
                'tipo' => $cita->tipo,
            ];
        });

        return response()->json($citasConUsuario, 200);
    }


    public function mostrar_citas_usuario(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $citas = Cita::with('user')->where('user_id', $request->user_id)->get();

        $citasConUsuario = $citas->map(function ($cita) {
            return [
                'id' => $cita->id,
                'user_id' => $cita->user_id,
                'user_name' => $cita->user->name,
                'fecha' => $cita->fecha,
                'hora' => $cita->hora,
                'estado' => $cita->estado,
                'tipo' => $cita->tipo,
            ];
        });

        return response()->json($citasConUsuario, 200);
    }
    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'estado' => 'in:confirmada,pendiente,cancelada',
                'tipo' => 'required|in:hemograma,heces,urianalisis,perfil lipido',
            ]);

            $user = User::find($request->user_id);

            if (!$user || !$user->permiso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este usuario no tiene permiso para crear citas.'
                ], 403);
            }
            $existeCita = Cita::where('user_id', $request->user_id)->exists();

            if ($existeCita) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya tiene una cita registrada'
                ], 400);
            }

            // Buscar el cupo disponible para la fecha y hora seleccionada
            $cupo = CuposPorHora::where('fecha', $validatedData['fecha'])
                ->where('hora_inicio', $validatedData['hora'])
                ->first();

            if (!$cupo || !$cupo->ocuparCupo()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay cupos disponibles para la fecha y hora seleccionadas.'
                ], 400);
            }
            //creacion d ela cita
            $cita = Cita::create([
                'user_id' => $validatedData['user_id'],
                'fecha' => $validatedData['fecha'],
                'hora' => $validatedData['hora'],
                'estado' => $validatedData['estado'] ?? 'pendiente',
                'tipo' => $validatedData['tipo'],
            ]);

            return response()->json(['message' => 'Cita creada con éxito', 'cita' => $cita], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear cita', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function eliminar_cita(Request $request)
    {
        // Validar que el user_id sea proporcionado y sea un id válido
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validamos que el user_id exista
        ]);

        // Buscar la cita del usuario
        $cita = Cita::where('user_id', $request->user_id)->first();

        // Verificar si la cita existe
        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una cita para este usuario.'
            ], 404);
        }

        // Eliminar la cita
        $cita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada exitosamente.'
        ], 200);
    }
}
