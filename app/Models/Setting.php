<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    //Permite pasar datos de manera masiva: Recomendable usar adecuadamente, ya que permite ataques desde afuera, osea modificar los settings sin permiso
    protected $guarded = [];
}
