<?php 

namespace App\Functions;

use DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ContencionLlamadasFunctions {

    function getHoraTotalContencion($fecha)
    {

        try {
            $registro = DB::select("SELECT MAX(a.hora) AS hora,SUM(Cant) AS total,SUM(IF(resultado IN ('ALERTA MASIVA HFC','ALERTA MASIVA FTTH'),cant,0)) AS contencion 
                                    FROM catalogos.dmpe_view a WHERE fecha='".$fecha."' ");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $registro;
    }

    function getDataHistoricoContencionLlamadas()
    {
        try {
           
            $queryHis = DB::select("select hora,
                                    SUM(IF(resultado in ('ALERTA MASIVA HFC','ALERTA MASIVA FTTH'),Cant,0)) AS Alertado, 
                                    SUM(IF(resultado='Telefono no Alarmado',Cant,0)) AS NoAlarmado, 
                                    SUM(IF(resultado='No se encuentra en el inventario',Cant,0)) AS NoExiste ,
                                    sum(Cant) as Total
                                    FROM catalogos.dmpe_view a WHERE DATEDIFF(NOW(),CONCAT(SUBSTR(fecha,1,4),'-',SUBSTR(fecha,5,2),'-',SUBSTR(fecha,7,2)))=0
                                    GROUP BY hora order by hora asc
                                 ");
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }








    

    function getFechaDiaAverias($troba,$jefatura)
    {

        try {
            $registro = DB::select("SELECT MAX(a.fec_mov) AS hora,DAYOFWEEK(a.dia_mov) as numdia FROM ccm1.averias_m1_new a 
                                        inner join catalogos.jefaturas jj on a.codnod=jj.nodo
                                    where tipreqini in ('R7','RA','RP') and  datediff(now(),fec_mov)=0 $troba  $jefatura");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $registro;
    }

    function getTotalAverias($troba,$jefatura)
    {

        try {
            $registro = DB::select("SELECT COUNT(*) AS tot FROM (SELECT * FROM ccm1.averias_m1_new  a INNER JOIN catalogos.jefaturas jj
                            ON a.codnod=jj.nodo WHERE DATEDIFF(NOW(),fec_mov)=0 AND tipreqini IN ('R7','RA','RP') $troba $jefatura GROUP BY a.codreq) xx");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $registro;
    }

    function getResumenIngresosAverias($troba,$jefatura)
    {

        try {
            $registro = DB::select("SELECT x.* FROM (
                SELECT xx.tipodeingreso AS Detalle,COUNT(*) AS Cant FROM (SELECT * FROM ccm1.averias_m1_new  a INNER JOIN catalogos.jefaturas jj
                ON a.codnod=jj.nodo WHERE DATEDIFF(NOW(),fec_mov)=0 AND tipreqini IN ('R7','RA','RP') $troba $jefatura GROUP BY a.codreq)  xx 
                GROUP BY 1) x ORDER BY x.Cant DESC");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $registro;

    }

    function getDataHistoricoAveriasJefaturas($inner,$jefatura,$troba)
    {
        try {
           
            $queryHis = DB::select("SELECT f.desdia,f.hora,d.aver AS hoy,e.aver AS ayer,f.aver AS antes,ll.llamadas,lq.liq,e.desdia AS diaayer,ar.arbol,art.arboltot 
                                    FROM (SELECT c.desdia,c.hora,ROUND(AVG(c.aver)) AS aver FROM reportes.`graf_promedio` c GROUP BY 1,2) f 
                                    LEFT JOIN reportes.`graf_ayer` e ON f.hora=e.hora 
                                    LEFT JOIN reportes.`graf_hoy` d ON f.desdia=d.desdia AND f.hora=d.hora 
                                    LEFT JOIN reportes.`graf_llamadas` ll ON f.hora=ll.horallam 
                                    LEFT JOIN reportes.`graf_liquidaciones` lq ON f.hora=lq.Hora 
                                    LEFT JOIN reportes.`graf_arbol_hoy` ar ON f.hora=ar.hora AND ar.arbol>0 
                                    LEFT JOIN reportes.`graf_arbol_tot` art ON f.hora=art.hora
                                 ");
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }

    function getDataHistoricoAveriasMotivos($jefatura, $inner, $troba)
    {
        try {
            
            $queryHis = DB::select("SELECT SUBSTR(fec_mov,12,2) AS hora,
                                    SUM(IF(a.tipodeingreso='MALA SENAL/SIN SENAL',1,0)) AS MalaSenal_SinSenal, 
                                    SUM(IF(a.tipodeingreso='No Navega',1,0)) AS NoNavega,
                                    SUM(IF(a.tipodeingreso='DECODER',1,0)) AS Decoder,
                                    SUM(IF(a.tipodeingreso='MASIVA',1,0)) AS Masiva,
                                    SUM(IF(a.tipodeingreso='VOIP',1,0)) AS Voip,
                                    SUM(IF(a.tipodeingreso='LENTITUD',1,0)) AS Lentitud,
                                    SUM(IF(a.tipodeingreso IN ('OTROS','WIFI','AVERIA DTH','CONTROL REMOTO','TRABAJOS PROGRAMADOS','TV DESPROGRAMADO'),1,0)) AS Otros
                                    FROM ccm1.averias_m1_new a $inner
                                    WHERE 1=1 $jefatura $troba AND a.tipreqini IN ('R7','RA','RP') AND DATEDIFF(NOW(),dia_mov)=0
                                    GROUP BY 1");      
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }

}