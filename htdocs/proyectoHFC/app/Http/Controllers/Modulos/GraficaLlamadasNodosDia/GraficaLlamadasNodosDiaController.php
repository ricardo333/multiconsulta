<?php

namespace App\Http\Controllers\Modulos\GraficaLlamadasNodosDia;

use App\Administrador\ParametroColores;
use Illuminate\Http\Request;
use App\Functions\GraficaLlamadasNodosDiaFunctions;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class GraficaLlamadasNodosDiaController extends GeneralController
{
    
    public function view(Request $request)
    {

        $functionesPeticionesGenerales = new peticionesGeneralesFunctions;
        $jefaturas = $functionesPeticionesGenerales->getJefaturas();
        $nodos = $functionesPeticionesGenerales->getNodos();
        
        return view('administrador.modulos.graficaLlamadasNodosDia.index',[
            "jefaturas"=>$jefaturas,
            "nodos"=>$nodos
        ]);

    }   

    public function listaNodosGraficasNodosDia(Request $request)
    {
            
        if($request->ajax()){

            #INICIO
                $jefatura = trim($request->jefatura) != "" ? $request->jefatura : "";
                $nodo = trim($request->nodo) != "" ? $request->nodo : "";

                $graficaLlamadasNodosFuntions = new GraficaLlamadasNodosDiaFunctions;

                if($jefatura!='' || $nodo!=''){
                    $where = " WHERE 1=1 ";
                    if( $jefatura!=='' ){
                        $jefatura = " AND c.jefatura='".$jefatura."' ";
                   }
                   if( $nodo!=='' ){
                        $nodo = " AND v.nodo='".$nodo."' ";
                   }
                   $sql_jefatura_nodo = $where.$jefatura.$nodo;
                   $resultListNodos = $graficaLlamadasNodosFuntions->getListJefaturaxNodo($sql_jefatura_nodo);
                   
                }else{
                    $resultListNodos = $graficaLlamadasNodosFuntions->getListNodos();
                }
                
                return $this->resultData(array(
                    "nodos"=>$resultListNodos
                ));
                
            #END

        }

        return abort(404);

    }

    public function graficasNodosBarras(Request $request)
    { 
          if($request->ajax()){   
               #INICIO
                    
                    $nodo = trim($request->nodo) != "" ? $request->nodo : "";

                    $coloresGraficaLlamadasNodosDia = ParametroColores::getGraficaLlamadasNodosDiaParametros();
                    $colorGraficaLlamadasNodosDia = $coloresGraficaLlamadasNodosDia->COLORES->segunSemana->colores;
                    //return ["data"=>$colorGraficaLlamadasNodosDia];
                    
                    $estado= TRUE;
                    $m=date("i");
                    $h=date("H");
                    if($m>='00' and $m<='10'){$hor='['.$h.']'.'00-10';}
                    if($m>='11' and $m<='20'){$hor='['.$h.']'.'11-20';}
                    if($m>='21' and $m<='30'){$hor='['.$h.']'.'21-30';}
                    if($m>='31' and $m<='40'){$hor='['.$h.']'.'31-40';}
                    if($m>='41' and $m<='50'){$hor='['.$h.']'.'41-50';}
                    if($m>='51' and $m<='59'){$hor='['.$h.']'.'51-59';}

                    $graficaLlamadasNodosFuntions = new GraficaLlamadasNodosDiaFunctions;
                    $resultLlamadasDia = $graficaLlamadasNodosFuntions->getTotalLlamadasNodosDia($nodo,$hor);
                    $resultDataLlamadasNodosDia = $graficaLlamadasNodosFuntions->getDataHistoricoLlamadasNodosDia($nodo,$hor,$resultLlamadasDia);
                    //return ["data"=>$resultDataLlamadasNodosDia];

                    if (count($resultDataLlamadasNodosDia) == 0) {
                        //return $this->errorMessage("No se encontró data histórica de averías por jefaturas.",500);
                        $estado = FALSE;
                   }
                    
                    return $this->resultData(["data"=>$resultDataLlamadasNodosDia,"resultLlamadasDia"=>$resultLlamadasDia,"colorGraficaLlamadasNodosDia"=>$colorGraficaLlamadasNodosDia,"estado"=>$estado]);
                    
               #END
          }
          return abort(404); 
  
    }

    public function nodosPorjefatura(Request $request)
    {
            
        if($request->ajax()){

            #INICIO
               /*
               // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
                $valida = Validator::make($request->all(), [
                    "jefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z_-]+$/"
                ]);
                   
                if ($valida->fails()) {   
                    return $this->errorMessage($valida->errors()->all(),422);
                } 
                */

                $peticionesGFunctions = new peticionesGeneralesFunctions;

                $jefatura = $request->jefatura;
                if($jefatura ==''){
                    $nodos = $peticionesGFunctions->getNodos();
                }else{
                    $nodos = $peticionesGFunctions->getNodosByJefatura($jefatura);
                }
        
                return $this->resultData(array(
                    //"interfaces"=>$interfaces,
                    "nodos"=>$nodos
                ));
                
            #END

        }

        return abort(404); 
        

    }

}