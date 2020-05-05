<?php 

namespace App\Traits;
 
 
Trait AdminActions {

    public function before($user, $habilidad){
        if ($user->tienePermisoEspecial()) {
             return true;
        }
    }
  
}