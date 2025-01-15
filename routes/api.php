<?php

use App\Http\Controllers\AfiliadoController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('users', [ApiController::class, 'index']);
Route::get('users/id={user_id}', [ApiController::class, 'show']);
Route::post('users/id', [ApiController::class, 'show2']);
Route::post('users/create', [ApiController::class, 'create']);

//Ruta para registrar una cita
Route::get('citas',[CitasController::class,'mostrar_citas']);
Route::post('citas/id',[CitasController::class,'mostrar_citas_usuario']);
Route::post('citas/create', [CitasController::class,'create']);
Route::delete('citas/eliminar', [CitasController::class, 'eliminar_cita']);
///////////////////////////////////////////////////

//permisos
Route::post('permission', [UserPermissionController::class, 'updatePermission']);

//afiliados
Route::get('afiliados', [AfiliadoController::class, 'index']);
Route::post('afiliados/id', [AfiliadoController::class, 'show']);

//token

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('sanctum/token', [AuthController::class, 'generateToken']);

Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return 'tokens eliminados';
});

//solservicios
Route::get('servicios', [ServicioController::class, 'index']);
Route::post('servicios/id', [ServicioController::class, 'show']);
Route::post('servicios/id_afiliado',[ServicioController::class,'show2']);
