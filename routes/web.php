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

//Obtenemos los usuarios con el metodo index en userController
Route::get('/api/users', [UserController::class, 'index']);

//Le indicamos la ruta al metodo post para guardar los usuarios y se define en el controlador
Route::post('/api/users', [UserController::class, 'store']);

Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);

Route::put('/api/users/{user}', [UserController::class, 'update']);

//Ruta para borrar usuarios, se le pasa el usuario completo y en la funcion controladora ya se saca el id
Route::delete('/api/users/{user}', [UserController::class, 'delete']);


Route::get('/api/users/search', [UserController::class, 'search']);


Route::delete('/api/users', [UserController::class, 'bulkDelete']);




//obtenemos la vista segun la ruta
Route::get('{view}',ApplicationController::class)->where('view', '(.*)');
