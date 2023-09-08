<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Segundo paso para los settings: Creamos los campos de la tabla y luego lo migramos desde consola: 'php artisan migrate'
        //Tercer paso: creamos el seeder con la consola: 'php artisan make:seeder SettingsTableSeerder', el cual se guarda en la carpeta seeders
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();

            $table->string('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
