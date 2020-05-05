<?php

namespace App\Http\Controllers;

use UserFunctions;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Parametro;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralController;

class AdministradorController extends GeneralController
{
    public function index()
    {  
        $userAuth =  Auth::user();
        $usernameAuth = $userAuth->username;
 
        $userFunctions = new UserFunctions;
        $cantidadPasswordLog = $userFunctions->cantidadHistorialPasswordByIdUser($usernameAuth);
        
        if((int)$cantidadPasswordLog == 0){
            return redirect()->route('password.change.view');
        }

        $dias_ultimo_cambio_pass = $userFunctions->getCantidadDiasUltimoCambioPassword($usernameAuth);
        
        //Valida si se activa un modal anunciando cuanto tiempo le queda
        $diasCambioPass = Parametro::getDiasCambioPassword();//segun el modulo parametros  del sistema
        $anuncio_dias = (int) $diasCambioPass - (int) Parametro::ANUNCIO_DIAS_CAMBIO_PASSWORD;
        
        $dias_quedan_para_cambiar_p =  (int)$diasCambioPass - (int)$dias_ultimo_cambio_pass[0]->diascambio;

        //se valida si excedio ya el numero maximo de dias de cambio de password
        //no se muestra el anuncio, se desactiva al usuario y se desloguea
        if ((int)$dias_ultimo_cambio_pass[0]->diascambio > $diasCambioPass) {
            $userUpdate = User::findOrFail($userAuth->id);
            $userFunctions->inactivarUsuario($userUpdate);

            Auth::logout(); 

            return view('administrador.index');
        }
          
        //se valida que si aun tiene tiempo de cambiar el usuario su password, se muestre el anuncio
        if ((int)$dias_ultimo_cambio_pass[0]->diascambio > $anuncio_dias) {
            return view('administrador.index',["anuncio"=>"SI","diasCambio"=>$dias_quedan_para_cambiar_p]);
        } 
        

        //ANUNCIO_DIAS_CAMBIO_PASSWORD

        return view('administrador.index');
    }

    public function list(Request $request)
    {
         if($request->ajax()){
            $user = Auth::user();
            $resultado_modulos = User::getModulosByUserAuth($user);
            //dd($resultado_modulos);
            return $this->showContJsonAll($resultado_modulos,true);
          }
          return abort(404);
       
    }
  
}
