<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class LlamadasFunctions {

    function getListaLlamada($filtroJefatura, $filtroTop, $nodo='')
    {
        try {
            $estados = DB::select("SELECT zo.`jefatura`,CONCAT('[DM]: ',a.nodo,' ',a.troba) AS nodoTroba,tt.tiptec,a.cant AS calldmpe,a.cant,a.eventid,a.usuario,a.nodo,a.troba,b.codreqmnt,a.ultimallamada,b.codreqmnt AS codmasiva,a.fecha_inicio,
                                    (SELECT SUM(aver) AS aver FROM catalogos.`averias_resum` WHERE nodo=a.nodo AND troba=a.troba AND DATEDIFF(NOW(),dia)<=0) AS averiasc,t.top as Top
                                    FROM alertasx.`alertas_dmpe_view` a
                                    LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba
                                    LEFT JOIN dbpext.masivas_temp b ON a.nodo=b.codnod AND a.troba=b.nroplano 
                                    LEFT JOIN catalogos.`jefaturas` zo ON a.nodo=zo.nodo
                                    LEFT JOIN catalogos.troba_tiptec tt
                                    ON a.nodo=tt.nodo AND a.troba=tt.troba
                                    WHERE a.nodo<>'Nodo' AND SUBSTR(a.troba,1,1)<>'D' AND a.nodo<>'' AND zo.jefatura <>'' $nodo $filtroJefatura $filtroTop
                                    AND  zo.jefatura NOT IN ('PROV_PUN','PROV_SUR','PROV_SMA','PROV_IQU','PROV_JUN')
                                    GROUP BY a.nodo,a.troba
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

    function getProcesarLlamada($llamadas)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getLlamadasParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES->segunCantidad->colores;
                //dd($llamadas);
                $cantidadLlamadas = count($llamadas);
                $acumulandoRespuestaLlamadas = array();
                $contadorId = 0;
        
                for ($i=0; $i < $cantidadLlamadas ; $i++) {

                    $nodo = $llamadas[$i]->nodo;
                    $troba = $llamadas[$i]->troba;
                     
                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='$nodo' and troba='$troba' and datediff(now(),fechahora)=0 order by fechahora desc limit 1");
                    
                    $llamadas[$i]->alertasGestion = array();
                    if (isset($alertasGestionQuery[0])) {  //$txtobs = $detalleGestionAlertQuery[0]->observaciones;
                        $llamadas[$i]->alertasGestion = $alertasGestionQuery;
                    }
                    $llamadas[$i]->id = $contadorId + 1;

                    $acumulandoRespuestaLlamadas[] = $this->procesoLlamadaGeneral($llamadas[$i],$colores);
                    $contadorId++;
                }
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
        $hoy=date("Y-m-d");
        $llamadas->hoy = $hoy;

        $nodo = $llamadas->nodo;
        $troba = $llamadas->troba;

        if ($llamadas->cant <= 5) {
            $llamadas->fondo = $colores[0]->background;
            $llamadas->letra = $colores[0]->color;

            $llamadas->tituloColorEstadoGestion       = $colores[0]->tituloColorEstadoGestion;
            $llamadas->contenidoColorEstadoGestion    = $colores[0]->contenidoColorEstadoGestion;
            $llamadas->usuarioColorEstadoGestion      = $colores[0]->usuarioColorEstadoGestion;
            $llamadas->fechaColorEstadoGestion        = $colores[0]->fechaColorEstadoGestion;

        }else if($llamadas->cant > 5 && $llamadas->cant < 10){
            $llamadas->fondo = $colores[1]->background;
            $llamadas->letra = $colores[1]->color;

            $llamadas->tituloColorEstadoGestion       = $colores[1]->tituloColorEstadoGestion;
            $llamadas->contenidoColorEstadoGestion    = $colores[1]->contenidoColorEstadoGestion;
            $llamadas->usuarioColorEstadoGestion      = $colores[1]->usuarioColorEstadoGestion;
            $llamadas->fechaColorEstadoGestion        = $colores[1]->fechaColorEstadoGestion;

        }else if($llamadas->cant >= 10){
            $llamadas->fondo = $colores[2]->background;
            $llamadas->letra = $colores[2]->color;

            $llamadas->tituloColorEstadoGestion       = $colores[2]->tituloColorEstadoGestion;
            $llamadas->contenidoColorEstadoGestion    = $colores[2]->contenidoColorEstadoGestion;
            $llamadas->usuarioColorEstadoGestion      = $colores[2]->usuarioColorEstadoGestion;
            $llamadas->fechaColorEstadoGestion        = $colores[2]->fechaColorEstadoGestion;

        }else{
            $llamadas->fondo = $colores[3]->background;
            $llamadas->letra = $colores[3]->color;

            $llamadas->tituloColorEstadoGestion       = $colores[3]->tituloColorEstadoGestion;
            $llamadas->contenidoColorEstadoGestion    = $colores[3]->contenidoColorEstadoGestion;
            $llamadas->usuarioColorEstadoGestion      = $colores[3]->usuarioColorEstadoGestion;
            $llamadas->fechaColorEstadoGestion        = $colores[3]->fechaColorEstadoGestion;

        }

        $trabajoProgQuery = DB::select("SELECT ESTADO AS estado
                                        FROM dbpext.`trabajos_programados_noc`
                                        WHERE NODO='$nodo' AND TROBA='$troba' and estado='ENPROCESO'
                                        order by finicio desc limit 1");

        $llamadas->estadoTrabajoProgramado = "";
        $txttrabajoprogramado = '';
        if (isset($trabajoProgQuery[0])) {
            $llamadas->estadoTrabajoProgramado = $trabajoProgQuery[0]->estado; 
            $txttrabajoprogramado = $trabajoProgQuery[0]->estado; 
        }
                         
        $llamadas->txttrabajoprogramado = $txttrabajoprogramado;
        return $llamadas;
                    
    }

}