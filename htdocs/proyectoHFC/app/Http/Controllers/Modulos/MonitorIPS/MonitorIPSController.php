<?php

namespace App\Http\Controllers\Modulos\MonitorIPS;

use Illuminate\Http\Request;
use App\Functions\MonitorIPSFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class MonitorIPSController extends GeneralController
{
    public function view(Request $request)
   {

        $resultado = array();
        
        if (isset($request->motivo)){
        $resultado["motivo"] = $request->motivo;
        }

        return view('administrador.modulos.monitorIPS.index',$resultado);
        //return view('administrador.modulos.monitorIPS.index');
        
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){
            #INICIO
                $funcionMonitorIPS = new MonitorIPSFunctions;
                $retornoMonitorIPS =  $funcionMonitorIPS->getListaMonitorIPS();
                //Depuracion de errores
                //dd($retornoConteoModems);
                if ($retornoMonitorIPS == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $monitorIPSResult = $funcionMonitorIPS->getProcesarMonitorIPS($retornoMonitorIPS);
                //dd($estadosModemsResult);
                if ($monitorIPSResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                return datatables($monitorIPSResult)->toJson();
                //dd($caidasMasivas);

            #END

        }
        return abort(404); 
   
    }
}
