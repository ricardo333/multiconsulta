<?php

namespace App\Functions;
 

class ConexionSshFunctions
{

    function primera_conexion(){
        $conexiones = config('ssh.one_conexion'); 
            
        return $conexiones;
    }    
    
    function webserver_conexion(){
        $conexiones = config('ssh.webserver_conexion'); 
            
        return $conexiones;
    }

    function server207_conexion(){
        $conexiones = config('ssh.procesos_conexion'); 
            
        return $conexiones;
    }
}