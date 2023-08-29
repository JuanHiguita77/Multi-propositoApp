<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $appends = ['formatted_end_time', 'formatted_start_time'];

    //parseamos los datos
    protected $casts = 
    [
        'star_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => AppointmentStatus::class,
    ];

    //sacamos los datos del cliente nombre y apellido
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn()=>Carbon::parse($this->start_time)->format('Y-m-d h:i:s'), 
        );    
    }

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn()=>$this->end_time->format('Y-m-d h:i:s'),     
        );
    }
}
