<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CentroMedicoController;
use App\Http\Controllers\CentroMedicoGrupoController;
use App\Http\Controllers\CentroMedicoViewController;
use App\Http\Controllers\CitaLaboratorioViewController;
use App\Http\Controllers\GrupoViewController;
use App\Http\Controllers\RecomendacionesViewController;
use App\Http\Controllers\RelacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return view('auth.login');
});


Route::get('/centros-medicos', [CentroMedicoViewController::class, 'index'])->name('centros.index');
Route::get('/centros-medicos/create', [CentroMedicoViewController::class, 'create'])->name('centros.create');
Route::delete('/centros-medicos/{id}', [CentroMedicoViewController::class, 'destroy'])->name('centros.destroy');
Route::post('/centros-medicos', [CentroMedicoViewController::class, 'store'])->name('centros.store');


//

// Ruta para mostrar el formulario de eliminar relación
Route::get('/centros/eliminar-relacion', [CentroMedicoViewController::class, 'showEliminarRelacion'])->name('centros.show-eliminar-relacion');

// Ruta para procesar la eliminación de la relación
Route::post('/centros/eliminar-relacion', [CentroMedicoViewController::class, 'eliminarRelacion'])->name('centros.eliminar-relacion');
//
//crear relacion entre centro medico y lab
Route::get('/relaciones/create', [RelacionController::class, 'create'])->name('relaciones.create');
Route::get('/relaciones', [RelacionController::class, 'index'])->name('relaciones.index');
Route::post('/relaciones', [RelacionController::class, 'store'])->name('relaciones.store');

//bitacora
Route::get('/bitacora', [BitacoraController::class, 'verBitacora'])->name('bitacora.index');

//grupos vistas
Route::get('/grupos', [GrupoViewController::class, 'index'])->name('grupos.index');
//crear grupo
Route::post('/grupos', [GrupoViewController::class, 'store'])->name('grupos.store');
// Ruta para mostrar el formulario de creación de un grupo (GET)
Route::get('/grupos/create', [GrupoViewController::class, 'create'])->name('grupos.create');
//asignar lab a grupo
Route::post('/grupos/asignar-laboratorio', [GrupoViewController::class, 'asignarLaboratorio'])->name('grupos.asignarLaboratorio');
Route::get('/grupos/asignar-laboratorio', [GrupoViewController::class, 'asignarLaboratorioView'])->name('grupos.asignarLaboratorioView');
Route::get('/grupos/eliminar', [GrupoViewController::class, 'eliminarGrupoView'])->name('grupos.eliminar.view');
Route::delete('/grupos/eliminar', [GrupoViewController::class, 'eliminarGrupo'])->name('grupos.destroy');


// eliminar aso
Route::get('/grupos/eliminar-asociacion', [GrupoViewController::class, 'eliminarAsociacionView'])->name('grupos.eliminarAsociacionView');
Route::delete('/grupos/eliminar-asociacion', [GrupoViewController::class, 'eliminarAsociacionGrupoLaboratorio'])->name('grupos.eliminarAsociacion');

//relacion entre grupos y centros medicos

Route::get('centro-medico-grupo/create', [CentroMedicoGrupoController::class, 'create'])->name('centro_medico_grupo.create');
Route::post('centro-medico-grupo', [CentroMedicoGrupoController::class, 'store'])->name('centro_medico_grupo.store');
Route::get('centro-medico-grupo', [CentroMedicoGrupoController::class, 'index'])->name('centro_medico_grupo.index');
// En routes/web.php
Route::get('centros-medicos/eliminar-asociacion', [CentroMedicoController::class, 'showRemoveAssociation'])->name('centrosMedicos.showRemoveAssociation');
Route::post('centros-medicos/eliminar-asociacion', [CentroMedicoController::class, 'destroyAssociation'])->name('centrosMedicos.destroyAssociation');

//admin listas de citas
Route::get('/admin/disponibilidad', [CitaLaboratorioViewController::class, 'showDisponibilidadForm'])->name('admin.disponibilidad');

//crearCitas admin
Route::get('/admin/crear-cita', [CitaLaboratorioViewController::class, 'showCrearCitaForm'])->name('citas.create');
Route::post('/admin/crear-cita', [CitaLaboratorioViewController::class, 'crearCita'])->name('citas.store');
//eliminar cita
Route::delete('/citas/{id}', [CitaLaboratorioViewController::class, 'eliminarCita'])->name('citas.eliminar');

//admin reservas
Route::get('/admin/reservas', [CitaLaboratorioViewController::class, 'obtenerReservasAdmin'])->name('admin.reservas');

//recomendaciones vistas admin

Route::get('/recomendaciones', [RecomendacionesViewController::class, 'index'])->name('recomendaciones.index');
Route::get('/recomendaciones/create', [RecomendacionesViewController::class, 'create'])->name('recomendaciones.create');
Route::post('/recomendaciones', [RecomendacionesViewController::class, 'store'])->name('recomendaciones.store');
Route::delete('/recomendaciones/{id}', [RecomendacionesViewController::class, 'destroy'])->name('recomendaciones.destroy');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Rutas de Usuarios sin resource
Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Listar usuarios
Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Formulario de crear usuario
Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Guardar usuario
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Ver un usuario
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Formulario de edición
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Actualizar usuario
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Eliminar usuario
