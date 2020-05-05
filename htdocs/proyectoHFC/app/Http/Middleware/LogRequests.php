<?php

namespace App\Http\Middleware;

use Closure;
use UserFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $request->start = microtime(true);

        return $next($request);
    }
    
    public function terminate($request, $response)
    {
        $request->end = microtime(true);

        $this->log($request,$response);
    }

    protected function log($request,$response)
    {
	//dd($request);
        //-------------------------------------------//
        /*
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
        $so = $userFunctions->getOS($user_agent);
        */
        //-------------------------------------------//

        $duration = $request->end - $request->start;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
        $uri = $request->path();

        $sos = $request->server('HTTP_USER_AGENT');

        if (isset($sos)) {
            $so = $request->server('HTTP_USER_AGENT');
        } else {
            $so = " ";
        }

        $username = $request->header('Username');
        $usuario = $request->header('usuario');

        if (isset($username)) {
            $login = $request->header('Username');
        } else {
            $login = $request->header('usuario');
        }


        $log = "Descripción del motivo: Acceso \n".
                "Recurso al que se accede: Servicio \n".
                "Tipo de acceso: Consulta \n".
                "IP de cliente: {$ip} \n".
                "Login de usuario: {$login} \n".
                "Sistema Operativo: {$so} \n".
                "Metodo a que se accede: {$method} : {$uri} \n".
                "Descripcion del motivo de evento: {$response->getContent()}";

        Log::info($log);
        
     }

}