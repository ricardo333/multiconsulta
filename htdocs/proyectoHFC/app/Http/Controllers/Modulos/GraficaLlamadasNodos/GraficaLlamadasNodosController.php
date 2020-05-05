<?php

namespace App\Http\Controllers\Modulos\GraficaLlamadasNodos;

use DB;
use App\Administrador\ParametroColores;
use Illuminate\Http\Request;
use App\Functions\GraficaLlamadasNodosFunctions;
use App\Http\Controllers\GeneralController;
use App\Functions\ContencionLlamadasFunctions;
use App\Functions\peticionesGeneralesFunctions;

class GraficaLlamadasNodosController extends GeneralController
{
    
    public function view(Request $request)
    {

        $functionesPeticionesGenerales = new peticionesGeneralesFunctions;
        $jefaturas = $functionesPeticionesGenerales->getJefaturas();
        $nodos = $functionesPeticionesGenerales->getNodos();

        return view('administrador.modulos.graficaLlamadasNodos.index',[
            "jefaturas"=>$jefaturas,
            "nodos"=>$nodos
        ]);

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

    public function listaNodosGraficasNodosLineales(Request $request)
    {
            
        if($request->ajax()){

            #INICIO
                $jefatura = trim($request->jefatura) != "" ? $request->jefatura : "";
                $nodo = trim($request->nodo) != "" ? $request->nodo : "";

                $graficaLlamadasNodosFuntions = new GraficaLlamadasNodosFunctions;

                if($jefatura!='' || $nodo!=''){
                    $where = " WHERE 1=1 ";
                    if( $jefatura!=='' ){
                        $jefatura = " AND j.jefatura='".$jefatura."' ";
                   }
                   if( $nodo!=='' ){
                        $nodo = " AND j.nodo='".$nodo."' ";
                   }
                   $sql_jefatura_nodo = $where.$jefatura.$nodo;
                   $resultListNodos = $graficaLlamadasNodosFuntions->getListJefaturaxNodo($sql_jefatura_nodo);
                }else{
                    $resultListNodos = $graficaLlamadasNodosFuntions->getListNodos();
                }
                //return ["data"=>$resultListNodos];
                
                return $this->resultData(array(
                    "nodos"=>$resultListNodos
                ));
                
            #END

        }

        return abort(404);

    }
    
    public function graficasNodosLineales(Request $request)
    { 
          //if($request->ajax()){
               #INICIO
                    
                    $nodo = trim($request->nodo) != "" ? $request->nodo : "";
                    $coloresGraficaLlamadasNodos = ParametroColores::getGraficaLlamadasNodosParametros();
                    $colorGraficaLlamadasNodos = $coloresGraficaLlamadasNodos->COLORES->segunDescripcion->colores;
                    //return ["data"=>$colorGraficaLlamadasNodos];
                    
                    $estado= TRUE;
                    $graficaLlamadasNodosFuntions = new GraficaLlamadasNodosFunctions;
                    $resultHoraTotal = $graficaLlamadasNodosFuntions->getHoraTotalLlamadasNodos($nodo);
                    $resultDataLlamadasNodos = $graficaLlamadasNodosFuntions->getDataHistoricoLlamadasNodos($nodo);
                    //return ["data"=>$resultHoraTotal];
                    

                    if (count($resultDataLlamadasNodos) == 0) {
                        //return $this->errorMessage("No se encontró data histórica de averías por jefaturas.",500);
                        $estado = FALSE;
                   }
                    
                    return $this->resultData(["data"=>$resultDataLlamadasNodos,"resultHoraTotal"=>$resultHoraTotal,"colorGraficaLlamadasNodos"=>$colorGraficaLlamadasNodos,"estado"=>$estado]);
                    
               #END
          //}
          //return abort(404); 
  
    }

}