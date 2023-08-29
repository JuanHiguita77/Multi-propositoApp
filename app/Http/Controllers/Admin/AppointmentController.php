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

    public function store()
    {
        //Validaciones por campos con vee-validate, y sirve para mandar el mensaje de llenado automatico en caso tal
        $validated = request()->validate
        ([
            'client_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',

        ],

        [
            'client_id.required' => 'The Client Name Field is required',
        ]);

        //validando con vee-validate
        Appointment::create([
            'title' => $validated['title'],
            'client_id' => $validated['client_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit(Appointment $appointment)
    {
        return $appointment;
    }

    public function update(Appointment $appointment)
    {
        $validated = request()->validate
        ([
            'client_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',

        ],

        [
            'client_id.required' => 'The Client Name Field is required',
        ]);

        $appointment->update($validated);

        return response()->json(['success'=>true]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['success', true], 200);
    }
}
