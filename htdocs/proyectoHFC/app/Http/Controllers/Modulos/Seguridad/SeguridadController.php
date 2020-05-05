<?php

namespace App\Http\Controllers\Modulos\Seguridad;

use Illuminate\Http\Request;
use App\Administrador\Parametro;
use App\Functions\LogsFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;

class SeguridadController extends GeneralController
{
   public function index()
   {
       $id_dias_cambio_password = Parametro::DIAS_CAMBIO_PASSWORD;
       $dias_cambio_password = Parametro::getDiasCambioPassword();

       $id_dias_reporte_sin_acceso = Parametro::DIAS_REPORTE_USUARIO_SIN_ACCEDER;
       $dias_reporte_sin_acceso = Parametro::getDiasReporteUsuarioSinAcceder();

       $id_intentos_maximo_login = Parametro::INTENTOS_MAXIMOS_LOGIN;
       $intentos_maximo_login = Parametro::getIntentosMaximosLogin();

       $id_minutos_reactivacion_login = Parametro::MINUTOS_REACTIVACION_LOGIN;
       $minutos_reactivacion_login = Parametro::getMinutosReactivacionLogin();

       $id_dias_inactividad_cuenta = Parametro::DIAS_BLOQUEO_INACTIVIDAD_CUENTA;
       $dias_inactividad_cuenta = Parametro::getDiasInactividadCuenta();

       //$minutos_inhabilitar_cambio_password = Parametro::getMinutosInhabilitarCambioPassword();
       $id_minutos_inactividad_session = Parametro::MINUTOS_INACTIVIDAD_SESION;
       $minutos_inactividad_session = Parametro::getMinutosBloqueoInactivadSession();

       //$seguridad = Parametro::where('id','!=',Parametro::MINUTOS_INHABILITAR_CAMBIO_PASSWORD)->get();
       $seguridad = Parametro::all();
        
        return view('administrador.modulos.seguridad.index',[
            "seguridad"=>$seguridad
        ]);
   }

   public function update(Parametro $parametro, Request $request)
   {
 
        $logsFunctions = new LogsFunctions;
        $usuarioAuth = Auth::user();

        $periodoAnterior = $parametro->period;

        try {
        DB::beginTransaction();
            #begin Transaction Update Parametro

            
            
                $parametro->period = $request->periodo;
    
                $parametro->save();

                $logsFunctions->registroLog($logsFunctions::LOG_SEGURIDAD,array(
                                                                        "usuario" => $usuarioAuth->username,
                                                                        "perfil" => $usuarioAuth->role->nombre,
                                                                        "id_parametro" => $parametro->id,
                                                                        "periodo_old" => $periodoAnterior,
                                                                        "periodo_new" => $parametro->period,
                                                                        "time" => $parametro->time,
                                                                        "description" => $parametro->description
                                                                        )
                                        );


            #End Begin Transaction update Parametro
        DB::commit();

        }catch(QueryException $ex){ 
         //dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
        }catch(\Exception $e){
        // dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
        }

        return $this->showModJsonOne($parametro);

   }

}
