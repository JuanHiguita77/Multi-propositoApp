<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
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

/*Route::get('/', function () {
    return view('welcome');
});*/

/*
Route::get('/admin/dashboard', function () {
    return view('dashboard');
});*/

//Ruta para borrar usuarios, se le pasa el usuario completo y en la funcion controladora ya se saca el id
Route::delete('/api/users/{user}', [UserController::class, 'delete']);

//Obtenemos los usuarios con el metodo index en userController
Route::get('/api/users', [UserController::class, 'index']);

Route::get('/api/users/search', [UserController::class, 'search']);

//Le indicamos la ruta al metodo post con el metodo store en userController
Route::post('/api/users', [UserController::class, 'store']);

Route::put('/api/users/{user}', [UserController::class, 'update']);

Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
//obtenemos la vista segun la ruta
Route::get('{view}',ApplicationController::class)->where('view', '(.*)');

