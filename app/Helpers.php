<?php 

	//Importamos el modelo de Setting
	use App\Models\Setting;

	//Para usar la cache
	use Illuminate\Support\Facades\Cache;

	//Segundo paso para configurar la paginacion desde el menu: vamos a composer.json y dentro de 'autoload' a registrar la ruta del helper
	/*En el archivo composer definimos la ruta dentro de autoload 

	        "files": [
            "app/Helpers.php"
        ]

	y recargamos el composer en consola: 'composer dump-autoload'*/

	//Seguimos con los siguientes datos que tenemos que dejar configurar al usuario, que seria el formato de fecha

	//Vamos al UserListItem

	function setting($key)
	{
		//Guardamos los datos y se los pasamos tambien a la cache
		//Primer paso para guardar los datos en cache y que se actualice al momento la configuracion: guardamos los datos al mismo tiempo que los devolvemos
		//vamos al settingsController
		$settings = Cache::rememberForever('settings', function()
		{
			//Le pasamos los nuevos datos a las settings y lo devolvemos
			return Setting::pluck('value', 'key')->all();
		});

		//Quinto paso para guardar en cache: le decimos que si no hay settings pasarle los datos por default
		if (! $settings)
		{
			$settings = config('settings.default');
		}


		return $settings[$key] ?? false;
	}

 ?>