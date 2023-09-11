<?php

namespace App\Models;
use App\Enums\RoleType;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Cast\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //Segundo paso para el formato de la fecha desde menu
    //Agrega el nuevo formato seleccionado a la nueva respuesta json
    protected $appends = 
    [
        'formatted_created_at',
    ];

    //Tercer paso para el formato de la fecha desde menu: Le asignamos el nuevo valor escogido en el menu, debemos indicarle correctamente los formatos tipo php en UpdateSettings.vue
    public function getFormattedcreatedAtAttribute()
    {
        return $this->created_at->format(setting('date_format'));
    }


    //Funcion accesora para mostrar el nombre de los roles, usando Enum RoleType
    public function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => RoleType::from($value)->name,
        );
       
    }
}
