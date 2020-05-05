<?php

namespace App\Functions;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MigracionFunctions
{
    public function obtenerIdRolByArea($area){

        $consultaRol = DB::select(
                            "select a.id AS codigo FROM zz_new_system.roles a 
                            WHERE a.nombre=?", [$area]);

        return $consultaRol[0]->codigo;

    }

    public function obtenerIdEmpresa($empresa){

        $consultaEmpresa = DB::select(
                            "select a.id AS codigo FROM zz_new_system.empresas a 
                            WHERE a.nombre=?", [$empresa]);

        return $consultaEmpresa[0]->codigo;

    }

    public function obtenerUsuariosArea($area){

        $usuariosProduccion = DB::select(
                            "select a.idusuario,a.usuario,a.empresa,a.area,a.dni,a.nombre,a.estado,
                            a.refresh,a.scopes,a.veloc_activa,a.fechainic,a.cambio,a.correo,a.celular 
                            FROM ccm1.usuarios a WHERE a.area=? AND a.estado='A'", [$area]);

        return $usuariosProduccion;

    }

    //-----------------------------------------------------------------------------------------//

    public function eliminarUsuarios(){

        $codIni = DB::select("select MIN(id) AS id FROM zz_new_system.users WHERE migrado='NUEVO'");

        $codigoInicio = $codIni[0]->id;

        //dd($codigoInicio);

        if ($codigoInicio != null) {

            $der = "delete FROM zz_new_system.permiso_user WHERE user_id>=$codigoInicio";

            DB::delete("delete FROM zz_new_system.permiso_user WHERE user_id>=$codigoInicio");

            DB::delete("delete FROM zz_new_system.users WHERE id>=$codigoInicio");

        }

        DB::update("update zz_new_system.control_migracion SET estado='1'");

        //dd($codigoInicio);        

    }



    public function obtenerUsuarios(){

        /*
        $usuarios = DB::select(
            "select a.idusuario,a.usuario,a.empresa,a.area,a.dni,a.nombre,a.estado,
            a.refresh,a.scopes,a.veloc_activa,a.aperturatrab,a.fechainic,a.correo,a.celular 
            FROM ccm1.usuarios a WHERE a.estado='A'");
        */
        $usuarios = DB::select(
            "select a.idusuario,a.usuario,a.empresa,a.area,a.dni,a.nombre,a.estado,
            a.refresh,a.scopes,a.veloc_activa,a.aperturatrab,a.fechainic,a.correo,a.celular 
            FROM ccm1.usuarios a 
            LEFT JOIN zz_new_system.users b ON a.usuario=b.username
            WHERE a.estado='A' AND b.username IS NULL");

        return $usuarios;

    }

    public function obtenerIdEmpresaDesarrollo($empresa){

        $consultaEmpresa = DB::select(
                    "select a.id AS codigo FROM zz_new_system.empresas a 
                    WHERE a.nombre=?", [$empresa]);

        return $consultaEmpresa[0]->codigo;

    }

    public function obtenerIdRolDesarrollo($area){

        $consultaRol = DB::select(
            "select a.id AS codigo FROM zz_new_system.roles a 
            WHERE a.nombre=?", [$area]);

        return $consultaRol[0]->codigo;

    }

    public function estadoControl(){

        $control = DB::select("select estado FROM zz_new_system.control_migracion");

        return $control;

    }


    public function registrarUsuario(){

        //$control = DB::select("select estado FROM zz_new_system.control_migracion");

        $control = $this->estadoControl();

        $estado = $control[0]->estado;

        if ($estado==0) {

            //$mensaje = "<script type='text/javascript'>confirm('¿Desea iniciar la migracion?')</script>";
            //echo $mensaje;
            $mensaje = "Iniciando Migracion...";

            echo $mensaje;
            
            $this->eliminarUsuarios();

        } else {
            DB::delete("delete FROM zz_new_system.users WHERE migrado='NO' OR migrado IS NULL");
        }

        //dd($estado);

        $listaUsuarios = $this->obtenerUsuarios();
        //dd($listaUsuarios);
        $cantUsuarios = count($listaUsuarios);

        foreach ($listaUsuarios as $usuarios) {

            $empresa = $usuarios->empresa;

            if ($empresa=="" || $empresa==null || $empresa==" ") {
                $empresa = "TEST";
                $idEmpresa = $this->obtenerIdEmpresaDesarrollo($empresa);
            } else {
                $empresa = $usuarios->empresa;
                $idEmpresa = $this->obtenerIdEmpresaDesarrollo($empresa);
            }

            //$idEmpresa = $this->obtenerIdEmpresaDesarrollo($usuarios->empresa);

            $idRol = $this->obtenerIdRolDesarrollo($usuarios->area);

            $usuarios->idEmpresa = $idEmpresa;
            $usuarios->idRol = $idRol;

            $username = $usuarios->usuario;
            $codUsuario = $usuarios->idusuario;
            $area = $usuarios->area;
            //dd($username);

            $refreshDecos = $usuarios->refresh;
            $scopegroup = $usuarios->scopes;
            $velocidad = $usuarios->veloc_activa;
            $moduloUsuarios = $usuarios->aperturatrab;

            if ($usuarios->correo == null) {
                $usuarios->correo = " ";
            }

            if ($usuarios->celular == null) {
                $usuarios->celular = " ";
            }
            
            $insert = DB::insert(
                "insert ignore into zz_new_system.users 
                VALUES (null,?,?,?,?,?,?,?,null,?,?,?,null,null,NOW(),NOW(),null,'NO')", 
                [$idEmpresa,$idRol,$usuarios->nombre," ",$usuarios->dni,$usuarios->celular,
                $usuarios->correo,$usuarios->usuario,bcrypt($usuarios->dni),$usuarios->estado]);

            $regist = DB::select("select a.id AS username FROM zz_new_system.users a 
                                    WHERE a.username=?", [$username]);
            

            if (count($regist)>0) {
                
                $listaPermisosEspecial = $this->obtenerPermisosEspeciales($codUsuario,$area);

                $idUsuarioCreado = DB::select(
                                    "select a.id FROM zz_new_system.users a 
                                    WHERE a.username=?", [$username]);

                $idUsuarioNuevo = $idUsuarioCreado[0]->id;
                
                $this->registrarPermisosEspeciales($listaPermisosEspecial,$idUsuarioNuevo,$refreshDecos,$scopegroup,$velocidad,$moduloUsuarios);

                DB::update(
                    "update zz_new_system.users set migrado='NUEVO' 
                    WHERE username=?",[$username]);

            }

        }

        $mensajeFinal = "Migracion Terminada";

        echo $mensajeFinal;

        DB::update("update zz_new_system.control_migracion SET estado='0'");

        //return $registros;
        
    }


    public function obtenerPermisosEspeciales($codUsuario,$area){

        //Obtener permisos que no son del Rol - Permisos agregados

        //Para usuarios del area CALL//
        if ($area=="CALL") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CALLx=0 OR b.CALLx IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CCM1//
        if ($area=="CCM1") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CCM1=0 OR b.CCM1 IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CGM1//
        if ($area=="CGM1") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CGM1=0 OR b.CGM1 IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area INGENIERIA//
        if ($area=="INGENIERIA") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.INGENIERIA=0 OR b.INGENIERIA IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area PEXT//
        if ($area=="PEXT") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.PEXT=0 OR b.PEXT IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CORE//
        if ($area=="CORE") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CORE=0 OR b.CORE IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CRITICOS//
        if ($area=="CRITICOS") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CRITICOS=0 OR b.CRITICOS IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area EECC//
        if ($area=="EECC") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.EECC=0 OR b.EECC IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CDC//
        if ($area=="CDC") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CDC=0 OR b.CDC IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area EXTRA//
        if ($area=="EXTRA") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.EXTRA=0 OR b.EXTRA IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area PINT//
        if ($area=="PINT") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.PINT=0 OR b.PINT IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area COM//
        if ($area=="COM") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.COM=0 OR b.COM IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CALL101//
        if ($area=="CALL101") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CALL101=0 OR b.CALL101 IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area NOC//
        if ($area=="NOC") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.NOC=0 OR b.NOC IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area NOCEXT//
        if ($area=="NOCEXT") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.NOCEXT=0 OR b.NOCEXT IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area SEGU//
        if ($area=="SEGU") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.SEGU=0 OR b.SEGU IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area ATTDIF//
        if ($area=="ATTDIF") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.ATTDIF=0 OR b.ATTDIF IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area SUPERVISOREC//
        if ($area=="SUPERVISOREC") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.SUPERVISOREC=0 OR b.SUPERVISOREC IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area TRASU//
        if ($area=="TRASU") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.TRASU=0 OR b.TRASU IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area SEGURIDAD//
        if ($area=="SEGURIDAD") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.SEGURIDAD=0 OR b.SEGURIDAD IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area CALLBACK//
        if ($area=="CALLBACK") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.CALLBACK=0 OR b.CALLBACK IS NULL)", [$codUsuario]);    
        }

        //Para usuarios del area ENERGIA//
        if ($area=="ENERGIA") {
            $permisosEspeciales = DB::select(
                "select a.idusuario,a.idmodulo
                FROM ccm1.usuarios_accesos a
                LEFT JOIN ccm1.user_modulos b ON a.idmodulo=b.idmodulo
                WHERE a.idusuario=? AND b.status=1 AND b.idmodulo>6 AND (b.ENERGIA=0 OR b.ENERGIA IS NULL)", [$codUsuario]);    
        }

        return $permisosEspeciales;

    }

    public function registrarPermisosEspeciales($listaPermisosEspecial,$idUsuarioNuevo,$refreshDecos,
    $scopegroup,$velocidad,$moduloUsuarios){

        $idUsuario = $idUsuarioNuevo;

        if ($refreshDecos=="1") {
            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["37",$idUsuario,"Asignado"]);
        }

        if ($scopegroup=="1") {
            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["39",$idUsuario,"Asignado"]);
        }

        if ($velocidad=="1") {
            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["38",$idUsuario,"Asignado"]);
        }

        if ($moduloUsuarios=="1") {
            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["6",$idUsuario,"Asignado"]);

            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["7",$idUsuario,"Asignado"]);

            DB::insert(
                "insert ignore into zz_new_system.permiso_user 
                VALUES (null,?,?,?)",["8",$idUsuario,"Asignado"]);
        }

        foreach ($listaPermisosEspecial as $permisos) {

            //$idUsuario = $permisos->idusuario;
            $idModulo = $permisos->idmodulo;

            $idPermiso = "";

            if ($idModulo=='8') {
                $idPermiso = '46';
            }


            if ($idModulo=='59' || $idModulo=='61') {
                $idPermiso = '156';
            }

            if ($idModulo=='120') {
                $idPermiso = '157';
            }

            if ($idModulo=='27' || $idModulo=='28') {
                $idPermiso = '56';
            }

            if ($idModulo=='16') {
                $idPermiso = '67';
            }

            if ($idModulo=='19') {
                $idPermiso = '80';
            }

            if ($idModulo=='9') {
                $idPermiso = '80';
            }

            if ($idModulo=='31') {
                $idPermiso = '94';
            }

            if ($idModulo=='24') {
                $idPermiso = '92';
            }

            //CAIDAS
            if ($idModulo=='10') {
                $idPermiso = '155';
            }

            if ($idModulo=='17') {
                $idPermiso = '152';
            }

            if ($idModulo=='21') {
                $idPermiso = '154';
            }

            if ($idModulo=='51') {
                $idPermiso = '153';
            }

            //Problemas Señal

            //Permisos de Cuarentena
            if ($idModulo=='56' || $idModulo=='108' || $idModulo=='112' || $idModulo=='113' || 
                $idModulo=='121' || $idModulo=='123' || $idModulo=='124') {

                    $idPermiso = '116';
                
            }

            if ($idModulo=='33') {
                $idPermiso = '117';
            }

            if ($idModulo=='115') {
                $idPermiso = '120';
            }

            if ($idModulo=='126') {
                $idPermiso = '121';
            }

            if ($idModulo=='127') {
                $idPermiso = '141';
            }

            if ($idModulo=='128') {
                $idPermiso = '134';
            }

            if ($idModulo=='109') {
                $idPermiso = '123';
            }

            if ($idModulo=='125') {
                $idPermiso = '133';
            }

            if ($idModulo=='132') {
                $idPermiso = '137';
            }

            if ($idModulo=='129') {
                $idPermiso = '143';
            }

            if ($idModulo=='130') {
                $idPermiso = '145';
            }

            if ($idModulo=='131') {
                $idPermiso = '149';
            }



            if ($idPermiso!="") {
                DB::insert(
                    "insert ignore into zz_new_system.permiso_user 
                    VALUES (null,?,?,?)",[$idPermiso,$idUsuario,"Asignado"]);
            }
            
        }

    }


    //-----------------------------------------------------------------------------------------//


    
    public function registrarUsuarios($area){

        $idRol = $this->obtenerIdRolByArea($area);

        $listaUsuarios = $this->obtenerUsuariosArea($area);

        //dd($listaUsuarios);
        $registros = count($listaUsuarios);

        foreach ($listaUsuarios as $usuario) {

            $idEmpresa = $this->obtenerIdEmpresa($usuario->empresa);

            $usuario->idempresa = $idEmpresa;
            $usuario->idRol = $idRol;

            if ($usuario->correo == null) {
                $usuario->correo = " ";
            }

            if ($usuario->celular == null) {
                $usuario->celular = " ";
            }

            //$registro = $usuario;

            DB::insert(
                    "insert ignore into zz_new_system.users 
                    VALUES (null,?,?,?,?,?,?,?,null,?,?,?,null,null,NOW(),NOW(),null)", 
                    [$idEmpresa,$idRol,$usuario->nombre," ",$usuario->dni,$usuario->celular,
                    $usuario->correo,$usuario->usuario,bcrypt($usuario->dni),$usuario->estado]);

        }

        return $registros;

    }


    public function getPermisosModulo($area){

        $columnaArea = "a.".$area."='1'";

        $permisoModulo = DB::select(
                                "select a.idmodulo,a.detalle FROM ccm1.user_modulos a 
                                WHERE a.status='1' AND $columnaArea");

        /*
        "select a.idmodulo,a.detalle FROM ccm1.user_modulos a 
        WHERE a.status='1' AND a.CGM1='1'"
        */

        $listaPermisos = array();

        for ($i=0; $i < count($permisoModulo); $i++) { 

            //Verificar acceso a MULTICONSULTA
            if($permisoModulo[$i]->idmodulo == '7'){
                $listaPermisos[] = '17';
            }

            //Verificar acceso a LLAMADAS POR TROBA
            if($permisoModulo[$i]->idmodulo == '8'){
                $listaPermisos[] = '46';
            }

            //Verificar acceso a MASIVAS CMS
            if($permisoModulo[$i]->idmodulo == '9'){
                $listaPermisos[] = '83';
            }

            //Verificar acceso a MONITOR CAIDAS
            if($permisoModulo[$i]->idmodulo == '10'){
                $listaPermisos[] = '58';
            }

            //Verificar acceso a PROBLEMAS SEÑAL
            if($permisoModulo[$i]->idmodulo == '12'){
                $listaPermisos[] = '66';
            }

            //Verificar acceso a DESCARGA CLIENTES TROBA
            if($permisoModulo[$i]->idmodulo == '16'){
                $listaPermisos[] = '67';
            }

            /*
            if($permisoModulo[$i]->idmodulo == '17'){
                $listaPermisos[] = '58';
            }
            */

            //Verificar acceso a ESTADO MODEMS
            if($permisoModulo[$i]->idmodulo == '19'){
                $listaPermisos[] = '80';
            }

            //Verificar acceso a ETIQUETADO DE PUERTOS
            if($permisoModulo[$i]->idmodulo == '20'){
                $listaPermisos[] = '115';
            }

            /*
            if($permisoModulo[$i]->idmodulo == '21'){
                $listaPermisos[] = '58';
            }
            */

            //Verificar acceso a CONTEO DE MODEMS
            if($permisoModulo[$i]->idmodulo == '24'){
                $listaPermisos[] = '92';
            }

            //Verificar acceso a VALIDACION SERVICIOS
            if($permisoModulo[$i]->idmodulo == '27'){
                $listaPermisos[] = '56';
            }

            /*
            if($permisoModulo[$i]->idmodulo == '28'){
                $listaPermisos[] = '56';
            }
            */

            //Verificar acceso a TRABAJOS PROGRAMADOS
            if($permisoModulo[$i]->idmodulo == '30'){
                $listaPermisos[] = '91';
            }

            //Verificar acceso a MONITOR IPS
            if($permisoModulo[$i]->idmodulo == '44'){
                $listaPermisos[] = '102';
            }

            /*
            if($permisoModulo[$i]->idmodulo == '51'){
                $listaPermisos[] = '58';
            }
            */

            //Verificar acceso a SATURACION DOWN
            if($permisoModulo[$i]->idmodulo == '54'){
                $listaPermisos[] = '104';
            }

            //Verificar acceso a DESCARGA CMTS
            if($permisoModulo[$i]->idmodulo == '55'){
                $listaPermisos[] = '105';
            }

            //Verificar acceso a MONITOR AVERIAS
            if($permisoModulo[$i]->idmodulo == '59'){
                $listaPermisos[] = '52';
            }

            /*
            if($permisoModulo[$i]->idmodulo == '60'){
                $listaPermisos[] = '104';
            }
            */

            /*
            if($permisoModulo[$i]->idmodulo == '120'){
                $listaPermisos[] = '52';
            }
            */

            //Verificar acceso a MENSAJES OPERADOR
            if($permisoModulo[$i]->idmodulo == '122'){
                $listaPermisos[] = '112';
            }
            
        }

        //dd($listaPermisos);

        return $listaPermisos;

    }


    public function permiso($area){

        $listaUsuarios = $this->obtenerUsuariosArea($area);

        $usuariosNuevos = array();

        for ($i=0; $i < count($listaUsuarios); $i++) {

            $username = $listaUsuarios[$i]->usuario;
            $resetDeco = $listaUsuarios[$i]->refresh;
            $scopegroud = $listaUsuarios[$i]->scopes;
            $velocidad = $listaUsuarios[$i]->veloc_activa;

            $permisoModulo = DB::select(
                                "select a.id,a.username FROM zz_new_system.users a 
                                WHERE a.username='$username'");

            $usuariosNuevos[$i][] = $permisoModulo[0]->id;
            $usuariosNuevos[$i][] = $resetDeco;
            $usuariosNuevos[$i][] = $velocidad;
            $usuariosNuevos[$i][] = $scopegroud;
            
        }

        //Permisos especiales Multiconsulta
        for ($i=0; $i < count($usuariosNuevos); $i++) { 

            $idusuario = $usuariosNuevos[$i][0];
            
            //Verificacion acceso Reset Decos
            if ($usuariosNuevos[$i][1]=="1") {
                DB::insert(
                    "insert ignore into zz_new_system.permiso_user 
                    VALUES (null,?,?)", ["37",$idusuario]);
            }

            //Verificacion acceso Cambio de Velocidad
            if ($usuariosNuevos[$i][2]=="1") {
                DB::insert(
                    "insert ignore into zz_new_system.permiso_user 
                    VALUES (null,?,?)", ["38",$idusuario]);
            }

            //Verificacion acceso Cambio ScopeGroup
            if ($usuariosNuevos[$i][3]=="1") {
                DB::insert(
                    "insert ignore into zz_new_system.permiso_user 
                    VALUES (null,?,?)", ["39",$idusuario]);
            }

        }
        
        //dd($usuariosNuevos);

    }









}
