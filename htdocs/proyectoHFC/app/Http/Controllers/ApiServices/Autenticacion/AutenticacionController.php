<?php

namespace App\Http\Controllers\ApiServices\Autenticacion;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Functions\Services\ServiceAutenticacionFunctions;
use App\Functions\Services\ServiceMulticonsultaFunctions;
use App\Http\Controllers\GeneralController;

class AutenticacionController extends GeneralController
{

    public function Authenticate(Request $request)
    {

        $usuario = $request->header('usuario');
        $password = $request->header('password');

        $funcionesAutenticacion = new ServiceAutenticacionFunctions;

        ###Validar si existe usuario en la Base de Datos
        $verificaUsuario = $funcionesAutenticacion->validaUsuario($usuario);

        if(count($verificaUsuario)==0){

            $respuesta["Error"] = "ERROR 201908. USUARIO NO EXISTE.";

            return response()->json([
                'estado' => false,
                'message' => $respuesta
                ], 201);

        }

        ###Validar si el Password es el correcto
        $verificaPassword = $funcionesAutenticacion->validaPassword($password);

        if(count($verificaPassword)==0){

            $respuesta["Error"] = "ERROR 201909. PASSWORD ERRADO.";

            return response()->json([
                'estado' => false,
                'message' => $respuesta
                ], 201);
        
        }

        ###Generar Token y guardarlo en Base de Datos
        $obtenerToken = $funcionesAutenticacion->generarToken($usuario);
        $token = $obtenerToken[0]->token;
        $estado = "OK";
        
        return response()->json([
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json'
                ],
            'estado' => true,
            'message' => $estado
        ], 201);

    }


}