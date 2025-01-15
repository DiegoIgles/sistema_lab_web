<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function generateToken(Request $request){
        $request->validate([
          'matricula' => 'required',
         // 'password' => 'required',
           'device_name' => 'required',
        ]);
        $afiliado = Afiliado::where('matricula', $request->matricula)->first();

        if (!$afiliado) {
            return response()->json([
                'message' => 'Datos incorrectos.',
                'errors' => [
                    'matricula' => ['Datos incorrectos'],
                ]
            ], 401);
        }


       return $afiliado->createToken($request->device_name)->plainTextToken;
    }

}
