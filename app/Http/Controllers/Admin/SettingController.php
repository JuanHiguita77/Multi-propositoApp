<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //Noveno paso de los settings: Importamos el modelo de los Settings
    //comprobacion que podemos hacer para saber si recibe los datos bien---'dd(Setting::pluck('key', 'value')->toArray());'
    //Volvemos al componente UpdateSettings
    public function index()
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    //Doceavo paso: despues de crear la ruta, creamos la funcion
    //Probamos que se envien los nuevos datos: dd(request()->all());
    public function update()
    {
        //Guardamos los nuevos datos cuando se da el boton de guardar
        $settings = request()->all();

        //cambiamos cada valor recorriendolo con el foreach
        foreach($settings as $key => $value)
        {
            //Aqui le asignamos los nuevos datos
            Setting::where('key', $key)->update(['value' => $value]);
        }

        //y devolvemos una respuesta satisfactoria
        return response()->json(['success' => true]);
    }
}
