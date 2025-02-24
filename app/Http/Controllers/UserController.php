<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::with('role')->paginate(10);
        return view('users.index', compact('users'));
    }

    // 📌 FORMULARIO DE CREAR USUARIO
    public function create() {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // 📌 GUARDAR USUARIO NUEVO
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    // 📌 MOSTRAR DETALLES DE UN USUARIO
    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    // 📌 FORMULARIO PARA EDITAR USUARIO
    public function edit(User $user) {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // 📌 ACTUALIZAR USUARIO
    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);
    
        // Buscar el rol en la base de datos
        $role = Role::find($request->role_id);
    
        // Verificar si el rol existe
        if (!$role) {
            return redirect()->back()->with('error', 'El rol seleccionado no existe.');
        }
    
        // Actualizar el usuario con el nuevo rol
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
        ]);
    
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // 📌 ELIMINAR USUARIO
    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }
}
