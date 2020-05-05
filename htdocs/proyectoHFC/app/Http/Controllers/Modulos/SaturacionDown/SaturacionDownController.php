<?php

namespace App\Http\Controllers\Modulos\SaturacionDown;

use Illuminate\Http\Request;
use App\Functions\SaturacionDownFunctions;
use App\Functions\peticionesGeneralesFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class SaturacionDownController extends GeneralController
{
    public function view(Request $request)
   {
          $functionesSaturacionDown = new SaturacionDownFunctions;
          $saturacionDown = $functionesSaturacionDown->getSaturacionDown();

          $resultado = array();
          $resultado["saturacionDown"] = $saturacionDown;

          //$request->motivo = "cuadroMando";
          //$request->nodo = "CASA";

          if (isset($request->motivo)){
            $resultado["motivo"] = $request->motivo;
            $resultado["nodo"] = $request->nodo;
          } 

          return view('administrador.modulos.saturacionDown.index',$resultado);

          /*
          return view('administrador.modulos.saturacionDown.index',[
          "saturacionDown"=>$saturacionDown
          ]);
          */
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){

            #INICIO
                $validarPuerto = Validator::make($request->all(), [
                    "filtroCmts" => "nullable"
                ]);
                
                $filtroCmts = "";

                if (!$validarPuerto->fails()) {
                    if (isset($request->filtroCmts)) { 
                        if ($request->filtroMotivo=="") {
                            $filtroCmts = trim($request->filtroCmts) != "" ? " and a.cmts='".$request->filtroCmts."' " : "";
                        } else {
                            $ncmts = trim($request->filtroCmts);
                            $filtroCmts = " and cm.marca='$ncmts' and a.rangosat in ('90_100','80_90','70-80')";
                        }
                    }
                }
                
                //$filtroJefatura = "";
                $funcionSaturacionDown = new SaturacionDownFunctions;
                $retornoSaturacionDown =  $funcionSaturacionDown->getListaSaturacionDown($filtroCmts);
                //Depuracion de errores
                //dd($retornoSaturacionDown);
                if ($retornoSaturacionDown == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $saturacionDownResult = $funcionSaturacionDown->getProcesarSaturacionDown($retornoSaturacionDown);
                //dd($saturacionDownResult);
                if ($saturacionDownResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                return datatables($saturacionDownResult)->toJson();
            #END

        }
        return abort(404); 
   
   }

   private static function cmp_time($a, $b) {
          
     if ($a->fecha_hora == $b->fecha_hora) {
          return 0;
     }
     return ($a->fecha_hora < $b->fecha_hora) ? -1 : 1;

   }

   public function graficoSaturacionDown(Request $request)
     {     
          
          if($request->ajax()){
               #INICIO
                    $cmts=$request->n;
                    $pto = $request->t;
                    $pto=str_replace("x","'",$pto);
                    $pto=str_replace("w","/",$pto);
                    
                    $peticionGeneral = new peticionesGeneralesFunctions;
                    $dataGrafico = $peticionGeneral->getGraficoDownSaturadoCmts(trim($pto),trim($cmts));

                    if (count($dataGrafico) == 0) {
                         return $this->errorMessage("No hay data para graficar - Revisar App",409); 
                    }
          
                    usort($dataGrafico,array($this,'cmp_time'));//ordena el rsultado por fecha
                    //return ["data"=>$dataGrafico];
          
                    return $this->resultData(["data"=>$dataGrafico,"param"=>$pto]);
                    
               #END
          }
          return abort(404); 
 
     }
     
}