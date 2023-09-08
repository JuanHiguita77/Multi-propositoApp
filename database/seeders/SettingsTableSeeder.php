<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // cuarto paso de los settings: Definimos los campos con valores preestablecidos
    //Lo enviamos a la base de datos desde consola: 'php artisan db:seed --class=SettingsTableSeeder', confirmamos que se hayan creado con phpmyadmin
    //Volvemos al componente para programar las funciones
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'Test app',
            ],

            [
                'key' => 'date_format',
                'value' => 'MM/DD/YYYY',
            ],

            [
                'key' => 'pagination_limit',
                'value' => 10,
            ],
        ]);
    }
}
