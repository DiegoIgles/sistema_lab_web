<?php

use App\Http\Controllers\AfiliadoController;
use App\Http\Controllers\ApiCentroMedicoPorGrupoController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CentroMedicoController;
use App\Http\Controllers\CentrosmedicosPorLaboratorio;
use App\Http\Controllers\CitaLaboratorioController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\RecomendacionController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ServicioLabController;
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
Route::post('servicios/id_afiliado',[ServicioController::class,'getServiciosLaboratorio']);

//laoratorio
Route::get('laboratorios', [LaboratorioController::class, 'index']);

//solserviciolab

Route::get('servicioslab', [ServicioLabController::class, 'index']);
Route::post('servicioslab/id_servicio', [ServicioLabController::class, 'show']);

//centros medicos
Route::get('centros', [CentroMedicoController::class, 'index']);
Route::post('centros/create', [CentroMedicoController::class, 'store']);


//centros medicos por laboratorio
Route::post('/laboratorios/centros-medicos', [CentrosmedicosPorLaboratorio::class, 'centrosMedicosPorLaboratorio']);
//centros medicos por laboratorios (array)
Route::post('/centros-medicos/por-laboratorios', [CentrosmedicosPorLaboratorio::class, 'centrosMedicosPorLaboratorios']);


//asociar centro medico con lab
Route::post('/centros-medicos/asociar-laboratorio', [CentroMedicoController::class, 'asociarLaboratorio']);


//eliminar centro medico
Route::post('/centros-medicos/eliminar', [CentroMedicoController::class, 'destroy']);

//eliminar relaciones
Route::post('/centros-medicos/eliminar-relacion', [CentroMedicoController::class, 'eliminarRelacion']);


//grupos


// Obtener todos los grupos con sus laboratorios
Route::get('grupos', [GrupoController::class, 'index']);

// Crear un nuevo grupo
Route::post('grupos', [GrupoController::class, 'store']);

// Asignar un laboratorio a un grupo
Route::post('/asignar-laboratorio', [GrupoController::class, 'asignarLaboratorio']);


// Obtener todos los laboratorios de un grupo
Route::post('/obtener-laboratorios', [GrupoController::class, 'obtenerLaboratorios']);

//grupos con lab de un afiliado
Route::post('grupos-con-laboratorios', [GrupoController::class, 'getGruposConLaboratorios']);

//eliminar grupo
Route::delete('grupo/eliminar', [GrupoController::class, 'eliminarGrupo']);

//eliminar asociacion entre grupo y lab
Route::delete('grupo/laboratorio/eliminar', [GrupoController::class, 'eliminarAsociacionGrupoLaboratorio']);


//laboratoiro por servicio y grupo
Route::post('laboratorios-por-grupo-y-servicio', [GrupoController::class, 'getLaboratoriosPorGrupoYServicio']);

//centros medicos por grupo
Route::post('/centros-medicos-por-grupo', [ApiCentroMedicoPorGrupoController::class, 'centrosMedicosPorGrupo']);

//citalaboratorio
Route::post('/disponibilidad', [CitaLaboratorioController::class, 'disponibilidad']);
//crear cita
Route::post('/crear-cita', [CitaLaboratorioController::class, 'crearCita']);
//eliminar cita
Route::delete('/eliminar-cita', [CitaLaboratorioController::class, 'eliminarCita']);
//reservar cita
Route::post('/reservar-cita', [CitaLaboratorioController::class, 'reservarCita']);
//mostrar reservas
Route::post('/reservas', [CitaLaboratorioController::class, 'mostrarReservas']);
//eliminar reserva
Route::post('/eliminar-reserva', [CitaLaboratorioController::class, 'eliminarReserva']);
//reservas admin
Route::get('reservas/admin', [CitaLaboratorioController::class, 'obtenerReservasAdmin']);

//recomendaciones


Route::post('/recomendaciones/store', [RecomendacionController::class, 'store']); // Crear recomendación
Route::post('/recomendaciones/show', [RecomendacionController::class, 'show']); // Obtener recomendación
Route::post('/recomendaciones/update', [RecomendacionController::class, 'update']); // Actualizar recomendación
Route::post('/recomendaciones/destroy', [RecomendacionController::class, 'destroy']); // Eliminar recomendación
