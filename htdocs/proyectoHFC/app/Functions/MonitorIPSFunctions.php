<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class MonitorIPSFunctions {

    function getListaMonitorIPS()
    {
        try {
            $estados = DB::select("select cmts, scopesgroup, total, used, available, porc, color from alertasx.alerta_ips");
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

    function getProcesarMonitorIPS($monitorIPS)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getMonitorIPSParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES->default->colores;
                //dd($colores);
                $cantidadMonitorIPS = count($monitorIPS);
                $acumulandoRespuestaMonitorIPS = array();
                //$contadorId = 0;
        
                for ($i=0; $i < $cantidadMonitorIPS ; $i++) {

                    //$monitorIPS[$i]->id = $contadorId + 1;
                    //$monitorIPS[$i]->identidad = $monitorIPS[$i]->identidad;
                    $acumulandoRespuestaMonitorIPS[] = $this->procesoMonitorIPSGeneral($monitorIPS[$i],$colores);
                    //$contadorId++;
                }
                //dd($acumulandoRespuestaMonitorIPS);
                return $acumulandoRespuestaMonitorIPS;
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

    private function procesoMonitorIPSGeneral($monitorIPS,$colores)
    { 

        // Estructura de Colores
        $monitorIPS->fondo = $colores[0]->background;
        $monitorIPS->letra = $colores[0]->color;

        return $monitorIPS;
                    
    }

}