<?php

namespace App\Http\Controllers\Modulos\Llamada;

use Illuminate\Http\Request;
use App\Functions\LlamadasFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MonitoreoAveriasFunctions;

class LlamadaController extends GeneralController
{
    public function view(Request $request)
   {
         
        $functionesJefaturas = new MonitoreoAveriasFunctions;
        $jefaturas = $functionesJefaturas->getJefaturasAverias();
        $nodo = ( isset($request->nodo) ) ? $request->nodo : '';
        $grafica = ( isset($request->grafica) ) ? $request->grafica : '';
        $grafica_acumulado_dia = ( isset($request->grafica_acumulado_dia) ) ? $request->grafica_acumulado_dia : '';
        $grafica_llamadas_nodo_tp = ( isset($request->grafica_llamadas_nodo_tp) ) ? $request->grafica_llamadas_nodo_tp : '';

        return view('administrador.modulos.llamadas.index',[
            "jefaturas"=>$jefaturas,
            "nodo"=>$nodo,
            "grafica"=>$grafica,
            "grafica_acumulado_dia"=>$grafica_acumulado_dia,
            "grafica_llamadas_nodo_tp"=>$grafica_llamadas_nodo_tp
        ]);
        
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){

            #INICIO
                $validarJefatura = Validator::make($request->all(), [
                    "filtroJefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);

                $validarTop = Validator::make($request->all(), [
                    "filtroTop" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
                ]);
                
                $filtroJefatura = "";
                $filtroTop = "";
                $nodo = trim($request->nodo) != "" ? " and a.nodo='".$request->nodo."' " : "";

                if (!$validarJefatura->fails()) {
                    if (isset($request->filtroJefatura)) {
                        $filtroJefatura = trim($request->filtroJefatura) != "" ? " and zo.jefatura='".$request->filtroJefatura."' " : "";
                    }
                }
                
                if (!$validarTop->fails()) {
                    if (isset($request->filtroTop)) {
                        $filtroTop =  trim($request->filtroTop) != "" ? " and t.top='".$request->filtroTop."' " : "";
                    }
                }
                //$filtroJefatura = "";
                $funcionLlamada = new LlamadasFunctions;
                $retornoLlamada =  $funcionLlamada->getListaLlamada($filtroJefatura,$filtroTop,$nodo);
                //Depuracion de errores
                //dd($retornoLlamada);
                if ($retornoLlamada == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $llamadaResult = $funcionLlamada->getProcesarLlamada($retornoLlamada);
                //dd($llamadaResult);
                if ($llamadaResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                return datatables($llamadaResult)->toJson();
            #END

        }
        return abort(404); 
   
   }

}