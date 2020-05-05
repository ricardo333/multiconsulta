<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functions\MigracionFunctions;
use App\Http\Controllers\GeneralController;

set_time_limit(120); //120 segundos

class MigracionController extends GeneralController
{
    public function index()
    {  
        $funcionesMigracion = new MigracionFunctions;

        $estado = $funcionesMigracion->estadoControl();

        $control = $estado[0]->estado;

        $resultado = array();
        $resultado["control"] = $control;
        //if ($control[0]->estado=="0") {
            return view('administrador.modulos.migracion.index',$resultado);
        //}else {
        //    $this->migrar();
        //}

        //return view('administrador.modulos.migracion.index');
    }
    
    public function update($area)
    {  
        $funcionesMigracion = new MigracionFunctions;

        //$area = "CGM1";
        
        $registrarUser = $funcionesMigracion->registrarUsuarios($area);

        //$registrarPermiso = $funcionesMigracion->getPermisosModulo($area);

        $nuevosUsers = $funcionesMigracion->permiso($area);

        dd("Se han migrado: ".$registrarUser." cuentas.");
        //dd($registrar);
    }

    public function migrar()
    {
        $funcionesMigracion = new MigracionFunctions;

        $registrarUser = $funcionesMigracion->registrarUsuario();

        $mensaje = "Proceso Terminado";

        return $mensaje;

    }





}