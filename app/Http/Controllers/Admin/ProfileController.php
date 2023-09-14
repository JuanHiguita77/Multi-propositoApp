<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//Importar el validador
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    //Segundo paso para el profile: creamos la funcion del index
    //Vamos al componente UpdateProfile.vue
    //Quinto paso del profile al volver
    public function index(Request $request)
    {
        //Indicamos que vamos a pedir solamente estos datos al usuario
        return $request->user()->only(['name', 'email', 'role']);
        //Volvermos al UpdateProfile para montar la funcion
    }

    //Octavo paso: crear el controlador para el profile y hacer la validacion de campos
    public function update(Request $request)
    {
        //Le indicamos que los campos sean obligatorios y el email que no este repetido para el usuario con esa id o logeado en el momento, se usa la tabla users
        $validated = $request->validate
        ([
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->user()->id)],
        ]);
       
       //Se toman los datos del usuario logeado y se actualizan con los del formulario al hacer submit
        $request->user()->update($validated);

        //Devolvemos la respuesta al completar el proceso y ahora volvemos al updateProfile.vue
        return response()->json(['success', true]);
    }
}
