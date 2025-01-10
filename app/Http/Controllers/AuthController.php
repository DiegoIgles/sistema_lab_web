<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function generateToken(Request $request){
        $request->validate([
          'email' => 'required|email',
          'password' => 'required',
           'device_name' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if( ! $user || ! Hash::check($request->password, $user->password)){
           return response()->json([
            'message' => 'Datos incorrectos.',
             'errors' => [
                'email' => ['Datos incorrectos'],
             ]
           ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

}
