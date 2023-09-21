<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
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

//Ruta general para indicarle cuales rutas mostrar cuando se haga el login, de resto prohibe la entrada
Route::middleware('auth')->group(function()
{
    //ENDPOINT DEL DASHBOARD
    //Ruta del controlador para los dashboard
    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);

    //Tercer paso del contador de usuarios: crear la ruta 
    //Cuarto paso: crear el metodo 'users' en el dashboardStatController
    Route::get('/api/stats/users', [DashboardStatController::class, 'users']);
    

    //ENDPOINTS DE LOS USERS
    //Obtenemos los usuarios con el metodo index en userController
    Route::get('/api/users', [UserController::class, 'index']);

    //Le indicamos la ruta al metodo post para guardar los usuarios y se define en el controlador
    Route::post('/api/users', [UserController::class, 'store']);

    //Cambiar el rol por usuario
    Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);

    //Editar los usuarios
    Route::put('/api/users/{user}', [UserController::class, 'update']);

    //Ruta para borrar usuarios, se le pasa el usuario completo y en la funcion controladora ya se saca el id
    Route::delete('/api/users/{user}', [UserController::class, 'delete']);

    //Borrar varios usuarios
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);
    //_____________________________________________________________

    //Obtenemos el tipo de cliente
    Route::get('/api/clients', [ClientController::class, 'index']);

    //Obtener el estatus con un contador por clasificacion de tareas
    Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCont']);

    //Endpoints del appointment
    Route::get('/api/appointments', [AppointmentController::class, 'index']);

    //Guardar los appointments creados
    Route::post('/api/appointments/create', [AppointmentController::class, 'store']);

    //Editar los appointments
    Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);

    //actualizar los appointments
    Route::put('/api/appointments/{appointment}/edit', [AppointmentController::class, 'update']);

    //Eliminar los appointments
    Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

    //RUTA DE LOS SETTINGS
    //octavo paso: creamos la ruta del controlador para los settings y en la consola: 'php artisan make:controller Admin/SettingController'
    //Noveno paso: crear la funcion en el controlador
    Route::get('/api/settings', [SettingController::class, 'index']);

    //Doceavo paso: creamos la ruta y su funcion en el controlador
    Route::post('/api/settings', [SettingController::class, 'update']);


    //Primer paso para crear la pestaña de profile: Definimos la ruta del index y creamos el controlador en consola donde iran los diferentas funciones: 'php artisan make:controller Admin/ProfileController'
    Route::get('/api/profile', [ProfileController::class, 'index']);

    //Septimo paso: crear el endpoint y luego su controlador en el archivo profilecontroller
    Route::put('/api/profile', [ProfileController::class, 'update']);

    //Septimo paso para la foto de perfil: Creamos la ruta y definimos la funcion en el controlador
    Route::post('/api/upload-profile-image', [ProfileController::class, 'UploadImage']);
    

    //RUTA PARA EL CAMBIO DE CONTRASEÑA
    //tercer paso: creamos la ruta y vamos al controlador
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);
});



//____________________________________________________________
//obtenemos la vista segun la ruta
Route::get('{view}',ApplicationController::class)->where('view', '(.*)')->middleware('auth');
