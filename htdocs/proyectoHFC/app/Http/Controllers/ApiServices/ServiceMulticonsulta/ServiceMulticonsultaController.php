<?php

namespace App\Http\Controllers\ApiServices\ServiceMulticonsulta;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Functions\Services\ServiceAutenticacionFunctions;
use App\Functions\Services\ServiceMulticonsultaFunctions;
use App\Http\Controllers\GeneralController;

class ServiceMulticonsultaController extends GeneralController
{
    /*
    public function Authenticate(Request $request)
    {
        //$usuario = $request->usuario;
        //$password = $request->password;

        $usuario = $request->header('usuario');
        $password = $request->header('password');

        $funcionesMulticonsulta = new ServiceMulticonsultaFunctions;

        ###Validar si existe usuario en la Base de Datos
        $verificaUsuario = $funcionesMulticonsulta->validaUsuario($usuario);

        if(count($verificaUsuario)==0){
            //$mensaje = "Usuario no existe";
            $respuesta["Error"] = "ERROR 201908. USUARIO NO EXISTE.";
            /*
            return $response = [
                'estado' => false,
                'message' => $mensaje
                ];
            //

            return response()->json([
                'estado' => false,
                'message' => $respuesta
                ], 201);

            //return $mensaje;
        }

        ###Validar si el Password es el correcto
        $verificaPassword = $funcionesMulticonsulta->validaPassword($password);

        if(count($verificaPassword)==0){

            //$mensaje = "Password errado";
            $respuesta["Error"] = "ERROR 201909. PASSWORD ERRADO.";
            /*
            return $response = [
                'estado' => false,
                'message' => $mensaje
                ];
            //

            return response()->json([
                'estado' => false,
                'message' => $respuesta
                ], 201);
            //return $mensaje;
        }

        ###Generar Token y guardarlo en Base de Datos
        $obtenerToken = $funcionesMulticonsulta->generarToken($usuario);
        $token = $obtenerToken[0]->token;
        $estado = "OK";

        /*
        return $response = [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Accept' => 'application/json'
                    ],
                'estado' => true,
                'message' => $estado
                ];
        //
        
        return response()->json([
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json'
                ],
            'estado' => true,
            'message' => $estado
        ], 201);

    }
    */


    public function getInfoBasicaServicioHFCxCliente(Request $request)
    {
        $idCliente = $request->idCliente;

        $username = $request->header('Username');

        if(isset($username)==false){
            $respuesta["Error"] = "ERROR 201915. NO SE HA ENVIADO VALOR DE Username";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        $getHeaderToken = $request->header('Token');

        if(isset($getHeaderToken)==false){
            $respuesta["Error"] = "ERROR 201916. NO SE HA ENVIADO VALOR DE Token";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        $header = explode(" ", $getHeaderToken);
        $token = $header[1];

        $funcionesAutenticacion = new ServiceAutenticacionFunctions;
        $funcionesMulticonsulta = new ServiceMulticonsultaFunctions;

        ###Valida Token asociado a Cliente
        $tokenValidate = $funcionesAutenticacion->validaUserToken($username,$token);

        if(count($tokenValidate)==0){
            //$respuesta = "Token invalido de usuario";
            //return $mensaje;
            $respuesta["Error"] = "ERROR 201910. TOKEN INVALIDO DE USUARIO.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }

        ###Proceso para validar el tiempo de expiracion de Token
        $obtenerTimeToken = $funcionesAutenticacion->tiempoiniToken($token);

        //$timeIniToken = $obtenerTimeToken[0]->time;
        $timeIniToken = Carbon::parse($obtenerTimeToken[0]->time);

        $diferencia_hora = $timeIniToken->diffInMinutes(Carbon::now());

        $configApi = config('api.api_config');
        $token_time = $configApi["token_time"];

        if ($diferencia_hora >= $token_time) {
            //$mensaje = "Token Expiro";
            //return $mensaje;
            $respuesta["Error"] = "ERROR 201911. TIEMPO DE TOKEN EXPIRO.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }


        ###Realiza proceso para obtener informacion solicitada
        $datosCliente = $funcionesMulticonsulta->getClienteHFC($idCliente);

        $cantidad = count($datosCliente);

        $respuesta = array();

        if($cantidad == ""){
            $respuesta["Error"] = "ERROR 201906. CODIGO DE CLIENTE NO EXISTE.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }else {

            for ($i=0; $i < $cantidad; $i++) {

                $servicio = $i+1;

                $ipcpe = $funcionesMulticonsulta->obtenerSnmp($datosCliente[$i]->Fabricante,$datosCliente[$i]->IPAddress);
                
                $mensaje = $funcionesMulticonsulta->validaNiveles($datosCliente[$i]->DSPwr,$datosCliente[$i]->USMER_SNR,$datosCliente[$i]->DSMER_SNR,$datosCliente[$i]->USPwr,$datosCliente[$i]->cliente_alerta,
                            $datosCliente[$i]->nodo,$datosCliente[$i]->troba,$datosCliente[$i]->MACState,
                            $datosCliente[$i]->num_masiva,$datosCliente[$i]->NumCPE,$ipcpe["Publica"],$ipcpe["MacCpe"]);

                $respuesta["Servicio".$servicio]["Cmts"] = $datosCliente[$i]->cmts;
                $respuesta["Servicio".$servicio]["Nodo"] = $datosCliente[$i]->nodo;
                $respuesta["Servicio".$servicio]["Troba"] = $datosCliente[$i]->troba;
                $respuesta["Servicio".$servicio]["ServicePackage"] = $datosCliente[$i]->SERVICEPACKAGE;
                $respuesta["Servicio".$servicio]["Ussnr"] = $datosCliente[$i]->USMER_SNR;
                $respuesta["Servicio".$servicio]["Dssnr"] = $datosCliente[$i]->DSMER_SNR;
                $respuesta["Servicio".$servicio]["IpCm"] = $datosCliente[$i]->IPAddress;
                $respuesta["Servicio".$servicio]["MacAddress"] = $datosCliente[$i]->MACADDRESS;
                $respuesta["Servicio".$servicio]["Fabricante"] = $datosCliente[$i]->Fabricante;
                $respuesta["Servicio".$servicio]["Modelo"] = $datosCliente[$i]->Modelo;
                $respuesta["Servicio".$servicio]["Firmware"] = $datosCliente[$i]->Version_firmware;
                $respuesta["Servicio".$servicio]["IspCpe"] = $datosCliente[$i]->scopesgroup;
                $respuesta["Servicio".$servicio]["EstadoModem"] = $datosCliente[$i]->estado;
                $respuesta["Servicio".$servicio]["PowerUp"] = $datosCliente[$i]->USPwr;
                $respuesta["Servicio".$servicio]["PowerDown"] = $datosCliente[$i]->DSPwr;
                $respuesta["Servicio".$servicio]["MACState"] = $datosCliente[$i]->MACState;
                $respuesta["Servicio".$servicio]["Mensaje"] = $mensaje;

            }
            

            /*
            $XML_RETURN = "<CLIENTE>
                                    <RETURN>
                                        <ERROR>0</ERROR>
                                        <ERMSG>NULL</ERMSG>
                                        <CANTIDAD>".$cantidad."</CANTIDAD>";

            for ($i=0; $i < $cantidad; $i++) { 

                $servicio = $i+1;

                $ipcpe = $funcionesMulticonsulta->obtenerSnmp($datosCliente[$i]->Fabricante,$datosCliente[$i]->IPAddress);
                
                $mensaje = $funcionesMulticonsulta->validaNiveles($datosCliente[$i]->DSPwr,$datosCliente[$i]->USMER_SNR,$datosCliente[$i]->DSMER_SNR,$datosCliente[$i]->USPwr,$datosCliente[$i]->cliente_alerta,
                            $datosCliente[$i]->nodo,$datosCliente[$i]->troba,$datosCliente[$i]->MACState,
                            $datosCliente[$i]->num_masiva,$datosCliente[$i]->NumCPE,$ipcpe["Publica"],$ipcpe["MacCpe"]);
                
                $XML_RETURN .= "<SERVICIO".$servicio.">
                                    <Cmts>".$datosCliente[$i]->cmts."</Cmts>
                                    <Nodo>".$datosCliente[$i]->nodo."</Nodo>
                                    <Troba>".$datosCliente[$i]->troba."</Troba>
                                    <ServicePackage>".$datosCliente[$i]->SERVICEPACKAGE."</ServicePackage>
                                    <Ussnr>".$datosCliente[$i]->USMER_SNR."</Ussnr>
                                    <Dssnr>".$datosCliente[$i]->DSMER_SNR."</Dssnr>
                                    <IpCm>".$datosCliente[$i]->IPAddress."</IpCm>
                                    <MacAddress>".$datosCliente[$i]->MACADDRESS."</MacAddress>
                                    <Fabricante>".$datosCliente[$i]->Fabricante."</Fabricante>
                                    <Modelo>".$datosCliente[$i]->Modelo."</Modelo>
                                    <Firmware>".$datosCliente[$i]->Version_firmware."</Firmware>
                                    <IspCpe>".$datosCliente[$i]->scopesgroup."</IspCpe>
                                    <EstadoModem>".$datosCliente[$i]->estado."</EstadoModem>
                                    <PowerUp>".$datosCliente[$i]->USPwr."</PowerUp>
                                    <PowerDown>".$datosCliente[$i]->DSPwr."</PowerDown>
                                    <MACState>".$datosCliente[$i]->MACState."</MACState>
                                    <Mensaje>".$mensaje."</Mensaje>                                
                                </SERVICIO".$servicio.">";

            }

            $XML_RETURN .= "</RETURN></CLIENTE>";
            */

        }

        return response()->json([
            "mensaje" => $respuesta
        ], 201);

        //return $XML_RETURN;

    }


    public function getInfoEstadoServicioHFCxCliente(Request $request)
    {
        $idCliente = $request->idCliente;

        $username = $request->header('Username');
        $getHeaderToken = $request->header('Token');
        $header = explode(" ", $getHeaderToken);
        $token = $header[1];

        $funcionesAutenticacion = new ServiceAutenticacionFunctions;
        $funcionesMulticonsulta = new ServiceMulticonsultaFunctions;

        ###Valida Token asociado a Cliente
        $tokenValidate = $funcionesAutenticacion->validaUserToken($username,$token);

        if(count($tokenValidate)==0){
            //$respuesta = "Token invalido de usuario";
            //return $mensaje;
            $respuesta["Error"] = "ERROR 201910. TOKEN INVALIDO DE USUARIO.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }

        ###Proceso para validar el tiempo de expiracion de Token
        $obtenerTimeToken = $funcionesAutenticacion->tiempoiniToken($token);

        //$timeIniToken = $obtenerTimeToken[0]->time;
        $timeIniToken = Carbon::parse($obtenerTimeToken[0]->time);

        $diferencia_hora = $timeIniToken->diffInMinutes(Carbon::now());

        $configApi = config('api.api_config');
        $token_time = $configApi["token_time"];

        if ($diferencia_hora >= $token_time) {
            //$mensaje = "Token Expiro";
            //return $mensaje;
            $respuesta["Error"] = "ERROR 201911. TIEMPO DE TOKEN EXPIRO.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }


        ###Realiza proceso para obtener informacion solicitada

        #VALIDA FECHA DEMORA 
        $time_start = microtime(true);
        #END VALIDA FECHA DEMORA

        #START IPS
        $verificaIPS = $funcionesMulticonsulta->getIPS($idCliente);
        $validaResultIPS = json_decode($verificaIPS);
        if($validaResultIPS->error){
            /*
            $XML_ERROR = "<CLIENTE>
                            <RETURN>
                                <ERROR>1</ERROR>
                                    <ERMSG>".$validaResultIPS->mensaje."</ERMSG>
                            </RETURN>
                        </CLIENTE>";

            return $XML_ERROR;
            */
            $respuesta["Error"] = "ERROR:201906. CODIGO DE CLIENTE NO EXISTE.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        #START TRABAJOS PROGRAMADOS
        
        $verificaTrabaP = $funcionesMulticonsulta->getTrabajosProgramados($idCliente);
        $validaResultTrabP = json_decode($verificaTrabaP);
        if($validaResultTrabP->error){
            /*
            $XML_ERROR = "<CLIENTE>
                                <RETURN>
                                    <ERROR>1</ERROR>
                                    <ERMSG>".$validaResultTrabP->mensaje."</ERMSG>
                                </RETURN>
                            </CLIENTE>";

            return $XML_ERROR;
            */
        }
        

        #START CM MASIVAS y ALERTADOS
        $verificaCM = $funcionesMulticonsulta->getMasivaCMByClient($idCliente);
        $validaResultCM = json_decode($verificaCM);
        if($validaResultCM->error){
            /*
            $XML_ERROR = "<CLIENTE>
                                <RETURN>
                                    <ERROR>1</ERROR>
                                    <ERMSG>".$validaResultCM->mensaje."</ERMSG>
                                </RETURN>
                            </CLIENTE>";

            return $XML_ERROR;
            */
        }


        #INICIO DE PROCESO

        if($validaResultIPS->IPS == null || count($validaResultIPS->IPS) == 0){
            //$XML_IPS = "<IPS>";
            //$XML_IPS .= "<DisponibilidadIP>OK</DisponibilidadIP>";
            //$XML_IPS .= "</IPS>";
            $respuesta["IPS"]["DisponibilidadIP"] = "OK";

        }else{
            
            //$XML_IPS = "<IPS>";
            $cantidadIPS_CMTS = count($validaResultIPS->IPS);
            //$XML_IPS .= "<DisponibilidadIP>NOK</DisponibilidadIP>";
            
            $respuesta["IPS"]["DisponibilidadIP"] = "NOK";

            //$XML_IPS .= "<DataIPS>";

            for ($i=0; $i < $cantidadIPS_CMTS; $i++) { //ingresando al array por cmts
                
                $cantidadScopeGroup = count($validaResultIPS->IPS[$i]); //ingresando por scopegroup dentro

                if($cantidadScopeGroup > 0){
                    //$XML_IPS .= "<DetalleIPS CMTS='".$validaResultIPS->IPS[$i][0]->cmts."'>";
                    $reg = $i+1;
                    $servicio = "Servicio".$reg;
                    $respuesta["IPS"]["Servicios"][$servicio]["DetalleIPS CMTS"] = $validaResultIPS->IPS[$i][0]->cmts;
                }
                
                for ($j=0; $j < $cantidadScopeGroup; $j++) { 
                    //$XML_IPS .= "<DetalleTipoIPS SCOPEGROUP='".$validaResultIPS->IPS[$i][$j]->scopesgroup."'>";
                    //$XML_IPS .= "<TOTAL>".$validaResultIPS->IPS[$i][$j]->tot."</TOTAL>";
                    //$XML_IPS .= "<DISPONIBLES>".$validaResultIPS->IPS[$i][$j]->disp."</DISPONIBLES>";
                    //$XML_IPS .= "</DetalleTipoIPS>";
                    $respuesta["IPS"]["Servicios"][$servicio]["DetalleTipoIPS SCOPEGROUP"] = $validaResultIPS->IPS[$i][$j]->scopesgroup;
                    $respuesta["IPS"]["Servicios"][$servicio]["TOTAL"] = $validaResultIPS->IPS[$i][$j]->tot;
                    $respuesta["IPS"]["Servicios"][$servicio]["DISPONIBLES"] = $validaResultIPS->IPS[$i][$j]->disp;
                }
                    //$XML_IPS .= "</DetalleIPS>";
            }
            //$XML_IPS .= "</DataIPS>";
            //$XML_IPS .= "</IPS>";
            
        }


        #START TRABAJOS PROGRAMADOS
        
        $XML_TP = "<TrabajoProgramado>";

        if($validaResultTrabP->TRABAJOS_PROGRAMADOS == null || count($validaResultTrabP->TRABAJOS_PROGRAMADOS) == 0){
            //$XML_TP .= "<EnTrabajoProgramado>NOK</EnTrabajoProgramado>";
            $respuesta["TrabajoProgramado"]["EnTrabajoProgramado"] = "NOK";

        }else{

            $cantidadResult_NODO_TROBA = count($validaResultTrabP->TRABAJOS_PROGRAMADOS);//Cantidad Nodo Trobas
            //$XML_TP .= "<EnTrabajoProgramado>OK</EnTrabajoProgramado>";
            //$XML_TP .= "<DataTP>";
            $respuesta["TrabajoProgramado"]["EnTrabajoProgramado"] = "OK";

            for ($i=0; $i < $cantidadResult_NODO_TROBA; $i++) { 
                /*
                $XML_TP .= "<DetalleTP NODO='".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->NODO."' TROBA='".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->TROBA."'>";
                $XML_TP .= "<Estado>".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->estado."</Estado>";
                $XML_TP .= "<FechaInicio>".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->FINICIO." de ".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->HINICIO." a ".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->HTERMINO."</FechaInicio>";
                $XML_TP .= "<FechaCierre>".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->fecha_cierre."</FechaCierre>";
                $XML_TP .= "<FechaCancelada>".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->fecha_cancela."</FechaCancelada>";
                $XML_TP .= "<TipoTrabajoP>".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i]->TIPODETRABAJO."</TipoTrabajoP>";
                $XML_TP .= "</DetalleTP>";
                */
                //dd($validaResultTrabP);
                $reg = $i+1;
                $servicio = "Servicio".$reg;

                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["DetalleTP NODO"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->NODO;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["DetalleTP TROBA"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->TROBA;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["Estado"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->estado;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["FechaInicio"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->FINICIO." de ".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->HINICIO." a ".$validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->HTERMINO;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["FechaCierre"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->fechacierre;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["FechaCancelada"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->fechacancela;
                $respuesta["TrabajoProgramado"]["Servicios"][$servicio]["TipoTrabajoP"] = $validaResultTrabP->TRABAJOS_PROGRAMADOS[$i][0]->TIPODETRABAJO;

            }

            //$XML_TP .= "</DataTP>";
        }

        //$XML_TP .= "</TrabajoProgramado>";
        
        #END TRABAJOS PROGRAMADOS

        #START MASIVAS
        //$XML_MASIVA = "<Masiva>";
        if($validaResultCM->resultDetallesCM->MASIVAS == null || count($validaResultCM->resultDetallesCM->MASIVAS) == 0){
            //$XML_MASIVA .= "<EnMasiva>NOK</EnMasiva>";
            $respuesta["Masiva"]["EnMasiva"] = "NOK";
        }else{
            $cantidadMasivas = count($validaResultCM->resultDetallesCM->MASIVAS);

            //dd($validaResultCM->resultDetallesCM->MASIVAS);

            //$XML_MASIVA .= "<EnMasiva>OK</EnMasiva>";
            $respuesta["Masiva"]["EnMasiva"] = "OK";
            //$XML_MASIVA .= "<DataMasiva>";
                for ($i=0; $i < $cantidadMasivas; $i++) { 
                    $cantidadPorNodos= count($validaResultCM->resultDetallesCM->MASIVAS[$i]);
                    for ($j=0; $j < $cantidadPorNodos ; $j++) { 
                        /*
                        $XML_MASIVA .= "<DetalleMasiva NODO='".$validaResultCM->resultDetallesCM->MASIVAS[$i][$j]->nodo."' TROBA='".$validaResultCM->resultDetallesCM->MASIVAS[$i][$j]->troba."'>";
                        //$XML_MASIVA .= "<EsMasiva>".$validaResultCM->resultDetallesCM->MASIVAS[$i][$j]->Esmasiva."</EsMasiva>";
                        $XML_MASIVA .= "<MAC>".$validaResultCM->resultDetallesCM->MASIVAS[$i][$j]->macaddress."</MAC>";
                        $XML_MASIVA .= "<MacState>".$validaResultCM->resultDetallesCM->MASIVAS[$i][$j]->MACState."</MacState>";
                        $XML_MASIVA .= "</DetalleMasiva>";
                        */
                        $reg = $i+1;
                        $servicio = "Servicio".$reg;
                        $respuesta["Masiva"]["Servicios"][$servicio]["DetalleMasiva NODO"] = $validaResultCM->resultDetallesCM->MASIVAS[$i][$j][0]->nodo;
                        $respuesta["Masiva"]["Servicios"][$servicio]["DetalleMasiva TROBA"] = $validaResultCM->resultDetallesCM->MASIVAS[$i][$j][0]->troba;
                        $respuesta["Masiva"]["Servicios"][$servicio]["MAC"] = $validaResultCM->resultDetallesCM->MASIVAS[$i][$j][0]->macaddress;
                        $respuesta["Masiva"]["Servicios"][$servicio]["MacState"] = $validaResultCM->resultDetallesCM->MASIVAS[$i][$j][0]->MACState;

                    }
                }
            //$XML_MASIVA .= "</DataMasiva>";
        }

        //$XML_MASIVA .= "</Masiva>";
        #END MASIVAS

        #START ALERTADO
        //$XML_ALERTADO = "<Alertado>";

        if($validaResultCM->resultDetallesCM->ALERTADOS == null || count($validaResultCM->resultDetallesCM->ALERTADOS) == 0){
            //$XML_ALERTADO .= "<EstaAlertado>NOK</EstaAlertado>";
            $respuesta["Alertado"]["EstaAlertado"] = "NOK";
        }else{
            $cantidadAlertados = count($validaResultCM->resultDetallesCM->ALERTADOS);

            //$XML_ALERTADO .= "<EstaAlertado>OK</EstaAlertado>";
            $respuesta["Alertado"]["EstaAlertado"] = "OK";

            //$XML_ALERTADO .= "<DataAlertado>";
                for ($i=0; $i < $cantidadAlertados; $i++) { 
                    $cantidadPorNodos= count($validaResultCM->resultDetallesCM->ALERTADOS[$i]);
                    for ($j=0; $j < $cantidadPorNodos ; $j++) { 
                        /*
                        $XML_ALERTADO .= "<DetalleAlertado NODO='".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->NODO."' TROBA='".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->TROBA."'>";
                        //$XML_ALERTADO .= "<EstaAlertado>".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->alertado."</EstaAlertado>";
                        $XML_ALERTADO .= "<MAC>".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->MACAddress."</MAC>";
                        $XML_ALERTADO .= "<MacState>".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->MacState."</MacState>";
                        $XML_ALERTADO .= "<TIPOALERTA>".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->tipoAlerta."</TIPOALERTA>";
                        $XML_ALERTADO .= "<ELEMENTOAFECTADO>".$validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->elementoAfectado."</ELEMENTOAFECTADO>";
                        $XML_ALERTADO .= "</DetalleAlertado>";
                        */
                        $reg = $i+1;
                        $servicio = "Servicio".$reg;
                        $respuesta["Alertado"]["Servicios"][$servicio]["DetalleAlertado NODO"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->NODO;
                        $respuesta["Alertado"]["Servicios"][$servicio]["DetalleAlertado TROBA"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->TROBA;
                        $respuesta["Alertado"]["Servicios"][$servicio]["MAC"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->MACAddress;
                        $respuesta["Alertado"]["Servicios"][$servicio]["MacState"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->MacState;
                        $respuesta["Alertado"]["Servicios"][$servicio]["TIPOALERTA"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->tipoAlerta;
                        $respuesta["Alertado"]["Servicios"][$servicio]["ELEMENTOAFECTADO"] = $validaResultCM->resultDetallesCM->ALERTADOS[$i][$j]->elementoAfectado;

                    }
                }
            //$XML_ALERTADO .= "</DataAlertado>";
        }

        //$XML_ALERTADO .= "</Alertado>";
        #END ALERTADO

        #VALIDA FECHA DEMORA 

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $minutes = floor($time / 60);
        $seconds = $time % 60;

        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);


        #RETORNO DE RESULTADO
        /*
        $XML_RETURN = "<CLIENTE>
                        <RETURN>
                            <ERROR>0</ERROR>
                            <ERMSG>NULL</ERMSG>		
                            <TimeResponse>".$minutes.":".$seconds."</TimeResponse>
                            <IDCliente>".$idCliente."</IDCliente>
                            $XML_IPS
                            $XML_TP
                            $XML_MASIVA
                            $XML_ALERTADO
                        </RETURN>
                    </CLIENTE>";
                 
        return $XML_RETURN;
        */

        return response()->json([
            "mensaje" => $respuesta
        ], 201);

    }










}