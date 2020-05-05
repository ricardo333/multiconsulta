<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class ConteoModemsFunctions {

    function getListaConteoModems()
    {
        try {
            $estados = DB::select("SELECT cmts, interface, description, sincroniz, cm_offline, total FROM  ccm1.scm_sum_final");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getProcesarConteoModems($conteomodems)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getConteoModemsParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES->default->colores;
                //dd($colores);
                $cantidadEstadosmodems = count($conteomodems);
                $acumulandoRespuestaEstadosmodems = array();
                //$contadorId = 0;
        
                for ($i=0; $i < $cantidadEstadosmodems ; $i++) {

                    //$conteomodems[$i]->id = $contadorId + 1;
                    //$conteomodems[$i]->identidad = $conteomodems[$i]->identidad;
                    $acumulandoRespuestaEstadosmodems[] = $this->procesoConteoModemsGeneral($conteomodems[$i],$colores);
                    //$contadorId++;
                }
                //dd($acumulandoRespuestaEstadosmodems);
                return $acumulandoRespuestaEstadosmodems;
           #END
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
 

    }

    private function procesoConteoModemsGeneral($conteomodems,$colores)
    { 

        // Estructura de Colores
        $conteomodems->fondo = $colores[0]->background;
        $conteomodems->letra = $colores[0]->color;

        return $conteomodems;
                    
    }

}