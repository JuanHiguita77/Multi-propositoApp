<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    //DEFINIMOS LOS DATOS A MOSTRAR
    public function index()
    {
        

        return Appointment::query()

            //le pasamos los datos del cliente para usarlos en el componente
            ->with('client:id,first_name,last_name')
            
            //aqui filtramos por tareas cuando se da clic a cada seccion
            ->when(request('status'), function ($query) {
                return $query->where('status', AppointmentStatus::from(request('status')));
            })
            ->latest()

            ->paginate()

            ->through(fn ($appointment)=>[
            
                'id' => $appointment->id,
                'start_time' => Carbon::parse($appointment->start_time)->format('d-m-Y h:i A'),

                'end_time' => Carbon::parse($appointment->end_time)->format('d-m-Y h:i A'),

                'status' => [
                
                    'name' => $appointment->status->name,
                    'color' => $appointment->status->color(),
                ],

                'client' => $appointment->client,
            ]);

    }
}
