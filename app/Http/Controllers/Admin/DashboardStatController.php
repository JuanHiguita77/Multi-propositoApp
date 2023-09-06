<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

//Quinto paso del contador de usuarios: importamos el modelo del usuario
use App\Models\User;

class DashboardStatController extends Controller
{
    //Contador de appointments
    public function appointments()
    {
        $totalAppointmentCount = Appointment::query()

        ->when(request('status') === 'scheduled', function($query){
        
            $query->where('status', AppointmentStatus::SCHEDULED);
        })

        ->when(request('status') === 'confirmed', function($query){
        
            $query->where('status', AppointmentStatus::CONFIRMED);
        })

        ->when(request('status') === 'cancelled', function($query){
        
            $query->where('status', AppointmentStatus::CANCELLED);
        })

        ->count();

        return response()->json([
            'totalAppointmentsCount' => $totalAppointmentCount,
        ]);
    }

    //Cuarto paso: Crear el metodo que le pasamos a la ruta para contar usuarios y importar el modelo de usuarios para sacar los datos
    public function users()
    {
        //Recibimos la respuesta con el valor seleccionado de las opciones en el componente dashboard.vue y lo comparamos
        $totalUsersCount = User::query()

            //request para el rango de el dia en que se consulta
            ->when(request('date_range') === 'today', function($query)
            {
                //Sacamos la fecha de creacion y la del dia en que se consulta
                $query->whereBetween('created_at', [now()->today(), now()]);
            })

            //request para el rango de 30 dias
            ->when(request('date_range') === '30_days', function($query)
            {
                //Sacamos la fecha de creacion y el rango de dias hasta el dia en que se consulta
                $query->whereBetween('created_at', [now()->subDays(30), now()]);
            })

            //request para el rango de 60 dias
            ->when(request('date_range') === '60_days', function($query)
            {
                //Sacamos la fecha de creacion y el rango de dias hasta el dia en que se consulta
                $query->whereBetween('created_at', [now()->subDays(60), now()]);
            })

            //request para el rango de 360 dias
            ->when(request('date_range') === '360_days', function($query)
            {
                //Sacamos la fecha de creacion y el rango de dias hasta el dia en que se consulta
                $query->whereBetween('created_at', [now()->subDays(360), now()]);
            })


            ->when(request('date_range') === 'month_to_day', function($query)
            {
                //Sacamos la fecha de creacion y le pasamos un metodo para saber el tiempo desde el primer dia del mes
                $query->whereBetween('created_at', [now()->firstOfMonth(), now()]);
            })


            ->when(request('date_range') === 'year_to_date', function($query)
            {
                //Sacamos la fecha de creacion y le pasamos un metodo para saber el tiempo desde el primer dia del año
                $query->whereBetween('created_at', [now()->firstOfYear(), now()]);
            })

            //Le pasamos el contador al final para saber cuantos registros cumplen con la solicitud
            ->count();

        //Paso seis y final: Devolvemos la respuesta que se solicita
        return response()->json([
            //Le pasamos la respuesta final de la query para saber el numero de usuarios y mostrarlo
            'totalUsersCount' => $totalUsersCount,
        ]);
    }
}
