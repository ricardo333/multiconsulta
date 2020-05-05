<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class SaturacionDownFunctions {

    function getSaturacionDown()
    {
        try {
            $estados = DB::select("SELECT a.cmts
                                    FROM alertasx.`puertos_down_saturados` a
                                    INNER JOIN reportes.portadorasxpuerto_tr b
                                    ON a.cmts=b.cmts AND a.down=b.down
                                    LEFT JOIN 
                                    (SELECT cmts,interface,microzona FROM ccm1.`level_troba_hist` 
                                    WHERE microzona IS NOT NULL AND SUBSTR(interface,1,1)<>'H' 
                                    GROUP BY cmts,interface) AS mz
                                    ON b.cmts=mz.cmts AND b.interface=mz.interface
                                    LEFT JOIN ccm1.cmts_ip cm ON a.cmts=cm.cmts
                                    WHERE  a.saturado='CONTINUA'
                                    GROUP BY a.cmts
                                    ORDER BY a.rangosat DESC,mz.microzona DESC,a.cmts,a.down");
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

    function getListaSaturacionDown($filtroCmts)
    {
        try {
            $estados = DB::select("SELECT a.*,clientes AS impacto ,
                                    REPLACE(REPLACE(a.down,'\'','x'),'/','w') AS pto,
                                    mz.microzona,
                                    IF(cm.marca='CISCO',CONCAT('scmload_load_',cm.nombre,'.txt'),
                                    CONCAT('show_docsis_downstream_',cm.nombre,'.txt')) AS archivo
                                    FROM alertasx.`puertos_down_saturados` a
                                    INNER JOIN reportes.portadorasxpuerto_tr b
                                    ON a.cmts=b.cmts AND a.down=b.down
                                    LEFT JOIN 
                                    (SELECT cmts,interface,microzona FROM ccm1.`level_troba_hist` 
                                    WHERE microzona IS NOT NULL AND SUBSTR(interface,1,1)<>'H' 
                                    GROUP BY cmts,interface) AS mz
                                    ON b.cmts=mz.cmts AND b.interface=mz.interface
                                    LEFT JOIN ccm1.cmts_ip cm ON a.cmts=cm.cmts
                                    WHERE  a.saturado='CONTINUA' $filtroCmts
                                    GROUP BY a.cmts,a.down
                                    ORDER BY a.rangosat DESC,mz.microzona DESC,a.cmts,a.down");
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

    function getProcesarSaturacionDown($saturacionDown)
    {

        try {
           #INICIO
                
                $parametrosColores = ParametroColores::getSaturacionDownParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES->niveles->colores;
                //dd($colores);
                
                $cantidadSaturacionDown = count($saturacionDown);
                $acumulandoRespuestaSaturacionDown = array();
                $contadorId = 0;

                for ($i=0; $i < $cantidadSaturacionDown ; $i++) {
                    
                    $cmts = $saturacionDown[$i]->cmts;
                    $down = $saturacionDown[$i]->down;
                     
                    $alertasGestionQuery = DB::select("select description from reportes.portadorasxpuerto_tr where cmts='".$cmts."' and down=\"$down\" group by description");
                    
                    $saturacionDown[$i]->alertasGestion = array();
                    if (isset($alertasGestionQuery[0])) {  //$txtobs = $detalleGestionAlertQuery[0]->observaciones;
                        $saturacionDown[$i]->alertasGestion = $alertasGestionQuery;
                    }

                    $saturacionDown[$i]->id = $contadorId + 1;

                    $acumulandoRespuestaSaturacionDown[] = $this->procesoSaturacionDownGeneral($saturacionDown[$i],$colores);
                    $contadorId++;
                }
                //dd($saturacionDown);
                return $acumulandoRespuestaSaturacionDown;
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

    private function procesoSaturacionDownGeneral($saturacionDown,$colores)
    { 

        $trob='';
        for ($i=0;  $i < count($saturacionDown->alertasGestion) ; $i++) { 
            if($trob==''){$raya='';}else{$raya=' | ';}
              $trob=$trob.$raya.$saturacionDown->alertasGestion[$i]->description;
        }
        $saturacionDown->trob = $trob;
        
        $it = $saturacionDown->id;
        $e = $it/2;
        $d = intval($e);
        if (($e-$d)==0){
            $saturacionDown->fondo = $colores[0]->background;
            $saturacionDown->letra = $colores[0]->color;
        }else{
            $saturacionDown->fondo = $colores[1]->background;
            $saturacionDown->letra = $colores[1]->color;
        }
        if (substr($saturacionDown->microzona,0,3)=='ZON'){
            $saturacionDown->fondo = $colores[2]->background;
            $saturacionDown->letra = $colores[2]->color;
        }

        return $saturacionDown;
                    
    }

}