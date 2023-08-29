<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class clientController extends Controller
{
    //Devuelve los ultimos datos ingresados
    public function index()
    {
        return Client::latest()->get();
    }
}
