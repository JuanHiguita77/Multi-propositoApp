<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    //Noveno paso de los settings: Importamos el modelo de los Settings
    //comprobacion que podemos hacer para saber si recibe los datos bien---'dd(Setting::pluck('key', 'value')->toArray());'
    //Volvemos al componente UpdateSettings
    public function index()
    {
        //Llenar el formulario con los valores default
        $settings = Setting::pluck('value', 'key')->toArray();

        //Si no esta lleno, poner los valores por default que vienen desde settings.php
        if (! $settings )
        {
            return config('settings.default');
        }

        return $settings;
    }

    //Doceavo paso: despues de crear la ruta, creamos la funcion
    //Probamos que se envien los nuevos datos: dd(request()->all());
    public function update()
    {

        //Primer paso para añadir validacion al formulario de los settings
        //Le decimos que datos son requeridos y los guardamos, vamos al updateSettings
        $settings = request()->validate([
            'app_name' => ['required', 'string'],
            'date_format' => ['required', 'string'],
            'pagination_limit' => ['required', 'int', 'min:1', 'max:100'],
        ]);

        /*Guardamos los nuevos datos cuando se da el boton de guardar
        $settings = request()->all();*/

        //cambiamos cada valor recorriendolo con el foreach
        foreach($settings as $key => $value)
        {
            //Quinto paso para guardar en cache: le terminamos de pasar los datos dependiendo de lo que pase y pasamos una propiedad en el modelo de settings
            Setting::updateOrCreate(

                ['key' => $key],

                ['value' => $value],
            );


            //Aqui le asignamos los nuevos datos
            Setting::where('key', $key)->update(['value' => $value]);
        }

        //Segundo paso para guardar la cache: Le decimos que borre todos los datos en cache relacionados con los settings antes de pasarle los nuevos
        //Ahora podemos usar los datos para mostrarlos al usuario como queramos con su nueva configuracion {{  variables }}, en este caso app.blade.php
        Cache::flush('settings');

        //y devolvemos una respuesta satisfactoria
        return response()->json(['success' => true]);
    }
}
