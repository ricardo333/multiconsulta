<?php

namespace App\Http\Middleware;

use Closure;
use App\Administrador\Parametro;

class ExtendSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lifetime = Parametro::getMinutosBloqueoInactivadSession();//tiempo monutos segun tabla
        config(['session.lifetime' => $lifetime]);
        return $next($request);
 
    }
}
