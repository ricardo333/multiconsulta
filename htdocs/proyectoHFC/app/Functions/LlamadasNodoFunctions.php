<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class LlamadasNodoFunctions {

    function getListaLlamadasNodo($filtroJefatura)
    {
        try {                      
            $estados = DB::select(" SELECT zo.`jefatura`,a.nodo,a.cant,a.trobas,a.promediocall,a.ultimallamada,
                                    (SELECT SUM(aver) AS aver FROM catalogos.`averias_resum` WHERE nodo=a.nodo  AND DATEDIFF(NOW(),dia)<=0) AS aver
                                    FROM alertasx.`alertas_dmpe_nodo_view` a
                                    LEFT JOIN catalogos.`jefaturas` zo ON a.nodo=zo.nodo
                                    WHERE a.nodo<>'Nodo' AND a.nodo<>'' AND zo.jefatura <>''
                                    $filtroJefatura AND  zo.jefatura NOT IN ('PROV_PUN','PROV_SUR','PROV_SMA','PROV_IQU','PROV_JUN')
                                    ORDER BY a.cant DESC");
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

    function getProcesarLlamadasNodo($llamadas)
    {

        try {
           #INICIO
                
                $parametrosColores = ParametroColores::getLlamadasNodoParametros();
                //dd($parametrosColores);
                
                $colores = $parametrosColores->COLORES->segunCantidad->colores;
                //dd($llamadas);
                
                $cantidadLlamadas = count($llamadas);
                $acumulandoRespuestaLlamadas = array();
                
                for ($i=0; $i < $cantidadLlamadas ; $i++) {
                    
                    $nodo = $llamadas[$i]->nodo;
                    $troba = $llamadas[$i]->trobas;
                    
                    //$alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='".$nodo."' AND troba='".$troba."' AND estado<>'Problema Comercial' AND datediff(now(),fechahora)=0 order by fechahora desc limit 1");
                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='".$nodo."' AND troba='".$troba."' AND datediff(now(),fechahora)=0 order by fechahora desc limit 1");
                    
                    $llamadas[$i]->alertasGestion = array();
                    if (isset($alertasGestionQuery[0])) {
                        $llamadas[$i]->alertasGestion = $alertasGestionQuery;
                    }
                    
                    $acumulandoRespuestaLlamadas[] = $this->procesoLlamadaGeneral($llamadas[$i],$colores);
                    
                }
                //dd($acumulandoRespuestaLlamadas);
                return $acumulandoRespuestaLlamadas;
                
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

    private function procesoLlamadaGeneral($llamadas,$colores)
    { 

        if ( $llamadas->alertasGestion !== "Problema Comercial" ) {

            if ( $llamadas->cant <= 5 ){
                $colorItem=$colores[0]->color;
            }else if($llamadas->cant > 5 && $llamadas->cant < 10){
                $colorItem=$colores[1]->color;
            }else if($llamadas->cant >= 11){
                $colorItem=$colores[2]->color;
            }
            $llamadas->colorItem = $colorItem;
            $llamadas->colorxDefect = '#191970';

        }

        $llamadas->fondo = $colores[3]->background;
        $llamadas->letra = $colores[3]->color;
    
        return $llamadas;
                    
    }

}