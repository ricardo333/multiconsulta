<?php

namespace App\Http\Controllers\ApiServices\ServicePci;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Functions\Services\ServiceAutenticacionFunctions;
use App\Functions\Services\ServicePciFunctions;
use App\Functions\IntrawayFunctions;
use App\Http\Controllers\GeneralController;

class ServicePciController extends GeneralController
{

    public function getPruebasCablemodem(Request $request)
    {
        $mac_address = $request->mac_address;

        if(isset($mac_address)==false){
            $respuesta["Error"] = "ERROR 201920. NO SE HA ENVIADO VALOR DE mac_address";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        $cant_pto = substr_count($mac_address,".");

        if ($cant_pto < 1) {
            $respuesta["Error"] = "ERROR 201923. EL FORMATO NO ES CORRECTO, NO SE ENCUENTRAN PUNTOS.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
            
        } elseif ($cant_pto < 2) {
            $respuesta["Error"] = "ERROR 201924. EL FORMATO NO ES CORRECTO, FALTA UN PUNTO DE SEPARACION.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }



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
        $funcionesPci = new ServicePciFunctions;

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

        
        $respuesta = $funcionesPci->procesoPruebasCablemodem($mac_address);

        if (isset($respuesta["Error"])) {
            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }else {
            return response()->json([
                "mensaje" => $respuesta
            ], 201);
        }

        

    }


    public function getPruebasCablemodemIW(Request $request)
    {

        $mac_address = $request->mac_address;

        if(isset($mac_address)==false){
            $respuesta["Error"] = "ERROR 201920. NO SE HA ENVIADO VALOR DE mac_address";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        $cant_pto = substr_count($mac_address,".");

        if ($cant_pto < 1) {
            $respuesta["Error"] = "ERROR 201923. EL FORMATO NO ES CORRECTO, NO SE ENCUENTRAN PUNTOS.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
            
        } elseif ($cant_pto < 2) {
            $respuesta["Error"] = "ERROR 201924. EL FORMATO NO ES CORRECTO, FALTA UN PUNTO DE SEPARACION.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);
        }

        $longitud_mac = strlen($mac_address);

        if($longitud_mac < 14){
            $respuesta["Error"] = "ERROR 202001. CANTIDAD DE CARACTERES MENOR A FORMATO DE MACADDRESS.";
            
            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        } elseif ($longitud_mac > 14) {
            $respuesta["Error"] = "ERROR 202002. CANTIDAD DE CARACTERES MAYOR A FORMATO DE MACADDRESS.";
            
            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }


        $idservicio = $request->idservicio;

        if(isset($idservicio)==false){
            $respuesta["Error"] = "ERROR 201921. NO SE HA ENVIADO VALOR DE idservicio";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

        $idproducto = $request->idproducto;

        if(isset($idproducto)==false){
            $respuesta["Error"] = "ERROR 201922. NO SE HA ENVIADO VALOR DE idproducto";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }

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
        $funcionesPci = new ServicePciFunctions;

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


        $statusUno = "PCI";

        $funcionesIntraway = new IntrawayFunctions;

        $datosIntraway = $funcionesIntraway->PeticionIntraway($idservicio,$idproducto,$mac_address,$statusUno);
        //dd($datosIntraway);

        if($datosIntraway=="error"){
            $respuesta["Error"] = "ERROR 201925. NO SE HA ENCONTRADO INFORMACION DE LOS DATOS.";

            return response()->json([
                "respuesta" => $respuesta
            ], 201);

        }


        $dsPwr = $datosIntraway["dspl"]; 
        $usPwr = $datosIntraway["uspl"]; 
        $dsSnr = $datosIntraway["dssnr"]; 
        $usSnr = $datosIntraway["ussnr"];
        //$resultado1 = "USPWR|".$usPwr."|DSPWR|".$dsPwr."|USSNR|".$usSnr."|DSSNR|".$dsSnr;

        $senalOk = 0;
        //dd($respuesta);

        $respuesta["SenalOK"] = $senalOk;
        $respuesta["Parametros"]["Parametro1"]["Nombre"] = "USPWR";
        $respuesta["Parametros"]["Parametro1"]["Valor"] = $usPwr;
        $respuesta["Parametros"]["Parametro2"]["Nombre"] = "DSPWR";
        $respuesta["Parametros"]["Parametro2"]["Valor"] = $dsPwr;
        $respuesta["Parametros"]["Parametro3"]["Nombre"] = "USSNR";
        $respuesta["Parametros"]["Parametro3"]["Valor"] = $usSnr;
        $respuesta["Parametros"]["Parametro4"]["Nombre"] = "DSSNR";
        $respuesta["Parametros"]["Parametro4"]["Valor"] = $dsSnr;


        return response()->json([
            "mensaje" => $respuesta
        ], 201);


    }





    


}
