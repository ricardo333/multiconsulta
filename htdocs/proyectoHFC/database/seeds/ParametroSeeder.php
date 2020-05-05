<?php

use Illuminate\Database\Seeder;
use App\Administrador\Parametro;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "días";
        $parametro->description = "Cambio de contraseña";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "días";
        $parametro->description = "Reporte de usuarios sin acceso";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 5;
        $parametro->time = "cantidad";
        $parametro->description = "Intentos de Login";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "minutos";
        $parametro->description = "Reactivación de Login";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 35;
        $parametro->time = "días";
        $parametro->description = "Bloqueo por abandono de la cuenta";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 5;
        $parametro->time = "minutos";
        $parametro->description = "Inhabilitar cambio de Contraseña";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "minutos";
        $parametro->description = "Cierre de sesión por Inactividad";
        $parametro->save();
    }
}
