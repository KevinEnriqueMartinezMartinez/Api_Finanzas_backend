<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

// Asegúrate de usar ::class al llamar al controlador
Route::resource('clientes', ClienteController::class);
Route::resource('user', UserController::class);
Route::resource('roles', RolesController::class);
Route::resource('listagastos', ClienteController::class);

Route::post('validarCredenciales', [UserController::class, 'validarCredenciales']);
Route::delete('user/{id}', [UserController::class, 'destroy']);

//obtener roles
Route::get('/roles', [UserController::class, 'obtenerRoles']);

//obtenerclientes
Route::get('/getClientes', [ClienteController::class, 'getClientes']);

//registrar un nuevo gasto
Route::post('/clientes/registrarGasto', [ClienteController::class, 'createGasto']);

//registrar un nuevo Ingreso
Route::post('/clientes/registrarIngreso', [ClienteController::class, 'createIngreso']);

Route::get('/getGastos', [ClienteController::class, 'getGastos']);

Route::get('/getMeses', [ClienteController::class, 'getMeses']);

Route::get('/getMes/{mesId}', [ClienteController::class, 'getMes']);

Route::get('/getIngresos', [ClienteController::class, 'getIngresos']);

Route::get('/getMesIngresos/{mesId}', [ClienteController::class, 'getMesIngresos']);

Route::get('/gastosPorMes', [ClienteController::class, 'getGastosPorMes']);

Route::get('/getGastosAltos', [ClienteController::class, 'getGastosAltos']);

Route::get('/getCategorias', [ClienteController::class, 'getCategorias']);

Route::get('/gastosPorCategoria', [ClienteController::class, 'getGastosPorCategoria']);
