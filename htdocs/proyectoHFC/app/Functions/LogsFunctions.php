<?php

namespace App\Functions;

use DB; 
use App\Administrador\User;
use App\Functions\UserFunctions;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogsFunctions
{ 

    const LOG_ACCESO = "log_acceso";
    const LOG_PASSWORD = "log_password";
    const LOG_USUARIO = "log_usuario";
    const LOG_CM_ACTIVACION = "log_cm_activacion";
    const LOG_CM_RESET_ITW = "log_cm_reset_itw";
    const LOG_CM_SCOPESGROUP = "log_cm_scopesgroup";
    const LOG_CM_VELOCIDADES = "log_cm_velocidades";
    const LOG_MODEM_DHCP = "log_modem_dhcp";
    const LOG_MODEM_MAPING = "log_modem_maping";
    const LOG_MODEM_STATUS = "log_modem_status";
    const LOG_MODEM_WIFI = "log_modem_wifi";
    const LOG_SEGURIDAD = "log_seguridad";
    const LOG_ROLES = "log_roles";
 

    function registroLog($tabla, $parametros)
    {

        #obteniendo el 'user_agent' y SO del CLIENT
            $userFunctions = new UserFunctions;
            if ( isset( $_SERVER ) ) {
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                global $HTTP_SERVER_VARS;
                if ( isset( $HTTP_SERVER_VARS ) ) {
                    $user_agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
                } else {
                    global $HTTP_USER_AGENT;
                    $user_agent = $HTTP_USER_AGENT;
                }
            }
            $SO = $userFunctions->getOS($user_agent);
        #END


        switch ($tabla) {
            case LogsFunctions::LOG_ACCESO:
                 DB::insert("insert into  zz_auditoria.log_acceso 
                            VALUES (null,?,?,?,NOW())", [ $parametros["usuario"], $parametros["acceso_exitoso"], $SO ]);
                break;
            case LogsFunctions::LOG_PASSWORD:
                DB::insert( "insert into  zz_auditoria.log_password 
                            VALUES (null,?,?,?,?,NOW())", [$parametros["usuario"],$parametros["rol"],$SO,sha1($parametros["newPassword"])]);
                break;
            case LogsFunctions::LOG_USUARIO:
                if ($parametros["accion"] == "update") {
                    DB::insert(
                        "insert into 
                        zz_auditoria.log_usuario 
                        VALUES (null,?,?,?,?,?,?,?,?,?,?,NOW())", [
                                $parametros["usuarioAuth"],$parametros["perfil"],$SO,$parametros["ipChanging"],"update",
                                $parametros["oldUsuario"],$parametros["oldEmpresa"],$parametros["oldRol"],
                                $parametros["newEmpresa"],$parametros["newRol"]
                            ]);
                }
                if ($parametros["accion"] == "store") {
                    DB::insert(
                        "insert into 
                        zz_auditoria.log_usuario 
                        VALUES (null,?,?,?,?,?,?,?,?,'','',NOW())", [
                                $parametros["usuarioAuth"],$parametros["perfil"],$SO,$parametros["ipChanging"],"store",
                                $parametros["usuario"],$parametros["empresa"],$parametros["rol"]
                            ]);
                }
               break;
            case LogsFunctions::LOG_CM_ACTIVACION:
                    DB::insert(
                        "insert into zz_auditoria.log_cm_activacion values 
                            (null,?,?,?,?,?,?,?,?,?,now())",[$parametros["usuario"],$parametros["perfil"],$SO,
                                                        $parametros["idCliente"],$parametros["macAddress"],$parametros["estado"],
                                                        $parametros["velocidad"],$parametros["newEstado"],$parametros["justificacion"]]
                    );
                break;
            case LogsFunctions::LOG_CM_RESET_ITW:
                    DB::insert(
                        "insert into zz_auditoria.log_cm_reset_itw values 
                            (null,?,?,?,?,?,?,?,now())",[$parametros["usuario"],$parametros["perfil"],$SO,
                                                    $parametros["idCliente"],$parametros["servicio"],$parametros["producto"],
                                                    $parametros["venta"]]
                    );
                break;
            case LogsFunctions::LOG_CM_SCOPESGROUP:

                    DB::insert("insert zz_auditoria.log_cm_scopesgroup 
                        VALUES  (null,?,?,?,?,?,?,?,?,now())",[$parametros["usuario"],$parametros["perfil"],$SO,$parametros["idcliente"],
                                                            $parametros["macAddress"],$parametros["scopeGroup"],$parametros["nuevoScopeGroup"],
                                                            $parametros["motivo"]]
                    );
                    
                break;
            case LogsFunctions::LOG_CM_VELOCIDADES:
 
                    DB::insert(
                        "insert into zz_auditoria.log_cm_velocidades values 
                            (null,?,?,?,?,?,?,?,?,?,?,now())",[$parametros["usuario"],$parametros["perfil"],$SO,
                                                            $parametros["idcliente"],$parametros["macAddress"],$parametros["velocidad"],
                                                            $parametros["nueva_velocidad"],$parametros["fecha_inicio"],$parametros["fecha_fin"],
                                                            $parametros["motivo"]]
                    );
                    
                break;
            case LogsFunctions::LOG_MODEM_DHCP:
 
                        DB::insert(
                            "insert into zz_auditoria.log_modem_dhcp values 
                                                    (null,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                                                    [$parametros["usuario"],$parametros["perfil"],$SO,
                                                     $parametros["codCliente"],$parametros["macaddress"],$parametros["fabricante"],
                                                     $parametros["modelo"],$parametros["firmware"],$parametros["dhcp_host"],
                                                     $parametros["dhcp_interface"],$parametros["dhcp_mac"],$parametros["dhcp_ip"],
                                                     $parametros["dhcp_nivel"]
                                                    ]
                        );
                    
                break;
            case LogsFunctions::LOG_MODEM_MAPING:

                    DB::insert(
                        "insert into zz_auditoria.log_modem_maping values 
                            (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                            [ $parametros["usuario"],$parametros["perfil"],$SO,
                                $parametros["codCliente"],$parametros["macaddress"],$parametros["fabricante"],
                                $parametros["modelo"],$parametros["firmware"],$parametros["operacion"],
                                $parametros["service"],$parametros["ipLan"],$parametros["protocolo"],
                                $parametros["privatePort"],$parametros["publicPort"]]
                    );
 
            
                break;
            case LogsFunctions::LOG_MODEM_STATUS:

                    DB::insert(
                        "insert into zz_auditoria.log_modem_status values 
                            (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                        [
                            $parametros["usuario"],$parametros["perfil"],$SO,
                            $parametros["codCliente"],$parametros["mac"],$parametros["fabricante"],
                            $parametros["modelo"],$parametros["firmware"],$parametros["frecuenciaUp1"],
                            $parametros["powerUp1"],$parametros["frecuenciaUp2"],$parametros["powerUp2"],
                            $parametros["frecuenciaDown1"],$parametros["snrDown1"],$parametros["powerDown1"],
                            $parametros["frecuenciaDown2"],$parametros["snrDown2"],$parametros["powerDown2"],
                            $parametros["frecuenciaDown3"],$parametros["snrDown3"],$parametros["powerDown3"],
                            $parametros["frecuenciaDown4"],$parametros["snrDown4"],$parametros["powerDown4"],
                            $parametros["frecuenciaDown5"],$parametros["snrDown5"],$parametros["powerDown5"],
                            $parametros["frecuenciaDown6"],$parametros["snrDown6"],$parametros["powerDown6"],
                            $parametros["frecuenciaDown7"],$parametros["snrDown7"],$parametros["powerDown7"],
                            $parametros["frecuenciaDown8"],$parametros["snrDown8"],$parametros["powerDown8"]
                        ]
                    );
 
                break;
 
            case LogsFunctions::LOG_MODEM_WIFI:
                    DB::insert(
                        "insert into zz_auditoria.log_modem_wifi values 
                            (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                            [
                                $parametros["usuario"],$parametros["perfil"],$SO,$parametros["codCliente"],
                                $parametros["mac"],$parametros["fabricante"],$parametros["modelo"],$parametros["firmware"],
                                $parametros["ssid"],$parametros["interface"],$parametros["channel"],$parametros["bandwidth"],
                                $parametros["power"],$parametros["secutiry1"],$parametros["security2"],$parametros["password"],
                                $parametros["ssid5G"],$parametros["interface5G"],$parametros["channel5G"],$parametros["bandwidth5G"],
                                $parametros["power5G"],$parametros["security5G"],$parametros["password5G"],$parametros["ssid_nuevo"],
                                $parametros["interface_nuevo"],$parametros["channel_nuevo"],$parametros["bandwidth_nuevo"],$parametros["power_nuevo"],
                                $parametros["secutiry1_nuevo"],$parametros["security2_nuevo"],$parametros["password_nuevo"],$parametros["ssid5G_nuevo"],
                                $parametros["interface5G_nuevo"],$parametros["channel5G_nuevo"],$parametros["bandwidth5G_nuevo"],$parametros["power5G_nuevo"],
                                $parametros["security5G_nuevo"],$parametros["password5G_nuevo"]
                            ]
                    );
 
                break;
            case LogsFunctions::LOG_SEGURIDAD:
                    DB::insert(
                        "insert into zz_auditoria.log_seguridad values 
                            (null,?,?,?,?,?,?,?,?,now())",
                            [
                                $parametros["usuario"],$parametros["perfil"],$SO,$parametros["id_parametro"],
                                $parametros["periodo_old"],$parametros["periodo_new"],$parametros["time"],$parametros["description"]
                            ]
                    );
 
                break;
            case LogsFunctions::LOG_ROLES:
            /*ESTAMOS ENESTO... */
                    DB::insert(
                        "insert into zz_auditoria.log_roles values 
                            (null,?,?,?,?,?,?,?,?,?,?,?,?,now())",
                            [
                                $parametros["usuario"],$parametros["perfil"],$SO,$parametros["accion"],
                                $parametros["rol"],$parametros["estado"],$parametros["acceso_total"],$parametros["rol_padre"],
                                $parametros["new_rol"],$parametros["new_estado"],$parametros["new_acceso_total"],$parametros["new_rol_padre"]
                            ]
                    );
 
                break;
 
            default:
                //dd("No reconoce ninguna..");
                throw new HttpException(409,"Se generó un problema al crear un registro Log de la acción.");
                break;
        }

    }
    
}