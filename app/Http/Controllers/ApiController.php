<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index(){
        $usuarios = User::all();
        return json_encode( $usuarios);
    }
    public function show($user_id){
        $usuario = User::find($user_id);
        return json_encode( $usuario);
    }
    public function show2(Request $request){
        $usuario = User::find($request->user_id);
        return json_encode( $usuario);
    }
    public function create(Request $request){
        $request->validate([
             'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ]);
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        $mensaje = 'usuario creado';
        return json_encode( $mensaje);
    }
}
