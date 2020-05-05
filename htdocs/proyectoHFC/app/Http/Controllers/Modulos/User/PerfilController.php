<?php

namespace App\Http\Controllers\Modulos\User;

use UserFunctions;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Parametro;
use App\Functions\LogsFunctions;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PerfilRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\PerfilUpdateRequest;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PerfilController extends GeneralController
{
 
    public function detalle($username, Request $request)
    {  
        
        $usuario = User::where('username',$username)->first();
        if (empty($usuario)) {
            throw new NotFoundHttpException();//404
        }
 
        $this->authorize('detalle-perfil',$usuario); //Policy

        $usuariofunctions = new UserFunctions;
       
        $ultimoAcceso = $usuariofunctions->ultimoAccesoUser($usuario->username,", 1");
        $fechaUltimoAcceso = "";
        $erroresDesdeUltimoExitohastaHoy = array();

        if (count($ultimoAcceso) > 0) {
            $fechaUltimoAcceso = $ultimoAcceso[0]->fecha;
            $erroresDesdeUltimoExitohastaHoy = $usuariofunctions->loginFailsFromPenultimateSuccessToToday($usuario->username,$ultimoAcceso[0]->fecha);
        }
 
        return view('administrador.perfil.usuario.detalle',[
            "usuario"=>$this->showModJsonOne($usuario),
            "ultimoAcceso"=>$fechaUltimoAcceso,
            "ultimosErroresLogin"=>$erroresDesdeUltimoExitohastaHoy
        ]);
    }

    public function updatePerfil(User $usuario,PerfilUpdateRequest $request)
    {
        
        try {
            DB::beginTransaction();
              #INICIO UPDATE
                    
                $usuario->dni = $request->dni;
                $usuario->telefono = $request->telefono;
                $usuario->email = $request->email;
                  
                $usuario->save();
      
              #END UPDATE
            DB::commit();
          } catch(QueryException $ex){ 
             //dd($ex->getMessage()); 
              DB::rollback();
              return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
          }catch(\Exception $e){
              //dd($e->getMessage()); 
              DB::rollback();
              return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
          } 
      
            return $this->showModJsonOne($usuario);

    }

    public function updatePassword(User $usuario,PerfilRequest $request)
    {

        

        $rules = [
            'password'=>'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ];
    
        $this->validate($request,$rules);

        
        $userFunctions = new UserFunctions;
        $logsFunctions = new LogsFunctions;

        

        $tiempoCloqueoCambioPassword = (int)Parametro::getMinutosInhabilitarCambioPassword();
         
        if($userFunctions->CambioDePasswordBloqueadoPorTiempo($usuario->username,$tiempoCloqueoCambioPassword)){//valida password
            return $this->errorMessage("No puede actualizar la contraseña del usuario, deben pasar los $tiempoCloqueoCambioPassword minutos de su último cambio.",422);
            
        }

       
        $userFunctions->esValidoNuevoPassword($usuario,$request->password);//valida password
 
        
        try {
            DB::beginTransaction();
              #INICIO UPDATE

                $userFunctions->registrarPasswordOld($request->password,$usuario->username);

                $logsFunctions->registroLog($logsFunctions::LOG_PASSWORD,array(
                    "usuario"=>$usuario->username,
                    "rol"=>$usuario->role->nombre,
                    "newPassword"=>$request->password
                ));
                    
                $usuario->password = bcrypt($request->password);
                  
                $usuario->save();

                 //limpia ultimos registros password manteniendo solo los ultimos 8 -> "8 es variable"
                 $cantidad_historial = $userFunctions->cantidadHistorialPasswordByIdUser($usuario->username);
                 $cantidadHistorialPasswordConservar = (int) Parametro::getCantidadHistorialPassword();
                 if ($cantidad_historial > $cantidadHistorialPasswordConservar) { //si excede los 8 registros -> "8 es variable"
                     $limit = $cantidad_historial - $cantidadHistorialPasswordConservar; //conservamos los 8 ultimos -> "8 es variable"
                     $userFunctions->eliminarUltimoPasswordHistorial($usuario->username,$limit);
                 }
      
              #END UPDATE
            DB::commit();
        } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
        }catch(\Exception $e){
            //dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
        } 

        return $this->showModJsonOne($usuario);

    }

}
