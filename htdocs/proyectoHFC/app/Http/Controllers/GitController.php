<?php

namespace App\Http\Controllers;

use App\Functions\ConexionSshFunctions;

class GitController extends GeneralController
{
    public function index()
    {  
    }
    
    public function update()
    {  
        $sshConexiones = new ConexionSshFunctions;
        
        $conexiones = $sshConexiones->webserver_conexion(); 

        $con_user = $conexiones["user"];
        $con_pass = $conexiones["pass"];
        $con_ip = $conexiones["ip"];
        $con_puerto = $conexiones["puerto"];
        
        $ssh_exec = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip." '. ~/bin/updmulticonsulta_git.sh"."'";
        //dd($ssh_exec);
        $result = exec($ssh_exec);
        dd("Result: ".$result);
    }
}