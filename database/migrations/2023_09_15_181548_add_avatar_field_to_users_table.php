<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //
    public function up():
    {
        //Se guarda en la tabla users la url y le decimos que puede estar vacia, luego hacemos la migracion de la tabla a la BD
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down():
    {
        //Elimina los datos de la tabla, solo cuando se ejecute

        //Vamos al profileController para el noveno paso
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
