<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//Importar el validador
use Illuminate\Validation\Rule;
use App\Actions\Fortify\UpdateUserPassword;

//IMportacion del storage para la foto de perfil
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    //Segundo paso para el profile: creamos la funcion del index
    //Vamos al componente UpdateProfile.vue
    //Quinto paso del profile al volver
    public function index(Request $request)
    {
        //Indicamos que vamos a pedir solamente estos datos al usuario
        return $request->user()->only(['name', 'email', 'role', 'avatar']);
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

    //octavo paso para la imagen de perfil: Importamos el Storage para usarlo y le indicamos la ruta local para guardar las fotos (storage/app/photos)

    //Luego creamos una migracion 'php artisan make:migration add_avatar_field_to_users_table' y vamos a ella
    public function UploadImage(Request $request)
    {
        if ($request->hasFile('profile_picture')) 
        {
            $previousPath = $request->user()->getRawOriginal('avatar');

            $link = Storage::put('/photos', $request->file('profile_picture'));

            //Noveno paso para la foto de perfil: Le pasamos el avatar a la variable link cuando se haga la actualizacion
            //Ahora vamos al user.php para proteger la variable avatar y crear el metodo de cambio
            $request->user()->update(['avatar' => $link]);

            //Borramos la anterior imagen del almacenamiento cuando se añada una nueva
            //Vamos a filesystems.php en config para crear el path
            Storage::delete($previousPath);


            return response()->json(['message' => 'Profile picture uploaded succesfully']);
        }
    }

    //cuarto paso para el cambio de contraseña
    //Estamos usando fortify por lo tanto le pasamos el archivo que usamos para esto y sacamos las confirmacion que usaremos: app/actions/fortify
    public function changePassword(Request $request, UpdateUserPassword $updater)
    {
        $updater->update
        (
            auth()->user(),

            [
                'current_password' => $request->currentPassword,
                'password' => $request->password,
                'password_confirmation' => $request->passwordConfirmation,
            ]
        );

        //vamos al updateProfile a poner los vmodel en los campos de las contraseñas
        return response()->json(['message' => 'Password Updated succesfully!']);
    }

    
}
