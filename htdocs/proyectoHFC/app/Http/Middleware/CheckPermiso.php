<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\GeneralController;
use Illuminate\Auth\Access\AuthorizationException;

class CheckPermiso extends GeneralController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso)
    {
        $permiso_usuario = $request->user()->HasPermiso($permiso);

       

        if(!$permiso_usuario){// Si el permiso es falso . 
            // abort(403, 'Unauthorized action.');
            throw new AuthorizationException();//403 
        }
        
        return $next($request);
         
    }
  
}
