<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CentroMedicoController;
use App\Http\Controllers\CentroMedicoViewController;
use App\Http\Controllers\RelacionController;
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
   return view('welcome');
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

