<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Metodo para obtener los usuarios
    public function index()
    {
        $users = User::latest()->get()->map(function ($user)
            {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    //traemos la configuracion de fecha del app.php
                    'created_at' => $user->created_at->format(config('app.date_format')),
                ];  
            });

        return $users;
    }

    //Metodo para almacenar los usuarios en la bd
    public function store()
    {
        //validar que no hayan emails duplicados
        request()->validate([
         
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);

        

        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);
    }

    public function update(User $user)
    {   
        //validacion para el servidor
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8',
        ]);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password,
        ]);

        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();

        return response()->noContent();
    }


    public function changeRole(User $user)
    {
        $user->update([
            'role' => request('role'),
        ]);

        return response()->json(['success' => true]);
    }

    public function search()
    {
        $searchQuery = request('query');

        $users = User::where('name','like', "%{$searchQuery}%")->get();

        return response()->json($users);
    }
}
