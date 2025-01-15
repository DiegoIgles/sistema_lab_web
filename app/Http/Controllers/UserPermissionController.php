<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function updatePermission(Request $request)
    {
        // Validación de los datos enviados
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permiso' => 'required|boolean',  // true o false
        ]);

        // Encontrar al usuario
        $user = User::find($request->user_id);

        // Actualizar el permiso
        $user->permiso = $request->permiso;
        $user->save();

        return response()->json([
            'message' => 'Permiso actualizado correctamente',
            'user' => $user,
        ], 200);
    }
}
