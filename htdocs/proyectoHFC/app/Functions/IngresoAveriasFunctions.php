<?php 

namespace App\Functions;

use DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IngresoAveriasFunctions {

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

    function getDataHistoricoAveriasJefaturas($jefatura,$troba)
    {
        try {
            
            $sql_jefatura_troba_graf_hoy = '';
            $sql_jefatura_troba_graf_ayer = '';
            $sql_jefatura_troba_graf_promedio = '';
            $sql_jefatura_troba_graf_llamadas = '';
            $sql_jefatura_troba_graf_liquidaciones = '';
            $sql_jefatura_troba_graf_arbol_hoy = '';
            $sql_jefatura_troba_graf_arbol_tot = '';

            if($jefatura!='' || $troba!=''){
                $where = " WHERE 1=1 ";
                $jefatura_graf_hoy = '';
                $jefatura_graf_ayer = '';
                $jefatura_graf_promedio = '';
                $jefatura_graf_llamadas = '';
                $jefatura_graf_liquidaciones = '';
                $jefatura_graf_arbol_hoy = '';
                $jefatura_graf_arbol_tot = '';

                $troba_graf_hoy = '';
                $troba_graf_ayer = '';
                $troba_graf_promedio = '';
                $troba_graf_llamadas = '';
                $troba_graf_liquidaciones = '';
                $troba_graf_arbol_hoy = '';
                $troba_graf_arbol_tot = '';

                if($jefatura!=''){
                    $jefatura_graf_hoy = " AND d.jefatura = '".$jefatura."' ";
                    $jefatura_graf_ayer = " AND e.jefatura = '".$jefatura."' ";
                    $jefatura_graf_promedio = " AND c.jefatura = '".$jefatura."' ";
                    $jefatura_graf_llamadas = " AND ll.jefatura = '".$jefatura."' ";
                    $jefatura_graf_liquidaciones = " AND lq.jefatura = '".$jefatura."' ";
                    $jefatura_graf_arbol_hoy = " AND ar.jefatura = '".$jefatura."' ";
                    $jefatura_graf_arbol_tot = " AND art.jefatura = '".$jefatura."' ";
                }
                if($troba!=''){
                    $troba_graf_hoy = " AND d.troba = '".$troba."' ";
                    $troba_graf_ayer = " AND e.troba = '".$troba."' ";
                    $troba_graf_promedio = " AND c.troba = '".$troba."' ";
                    $troba_graf_llamadas = " AND ll.troba = '".$troba."' ";
                    $troba_graf_liquidaciones = " AND lq.troba = '".$troba."' ";
                    $troba_graf_arbol_hoy = " AND ar.troba = '".$troba."' ";
                    $troba_graf_arbol_tot = " AND art.troba = '".$troba."' ";
                }

                $sql_jefatura_troba_graf_hoy = $where.$jefatura_graf_hoy.$troba_graf_hoy;
                $sql_jefatura_troba_graf_ayer = $where.$jefatura_graf_ayer.$troba_graf_ayer;
                $sql_jefatura_troba_graf_promedio = $where.$jefatura_graf_promedio.$troba_graf_promedio;
                $sql_jefatura_troba_graf_llamadas = $where.$jefatura_graf_llamadas.$troba_graf_llamadas;
                $sql_jefatura_troba_graf_liquidaciones = $where.$jefatura_graf_liquidaciones.$troba_graf_liquidaciones;
                $sql_jefatura_troba_graf_arbol_hoy = $where.$jefatura_graf_arbol_hoy.$troba_graf_arbol_hoy;
                $sql_jefatura_troba_graf_arbol_tot = $where.$jefatura_graf_arbol_tot.$troba_graf_arbol_tot;

            }

            
            $queryHis = DB::select("SELECT hoy.desdia, hoy.hora, hoy.hoy, ayer.ayer, antes.antes, llamadas.llamadas, liquidaciones.liq, ayer.dia_ayer AS diaayer, arboldec.arbol, arboltotal.arboltot
                                    FROM
                                    (
                                    SELECT 
                                    f.desdia, f.hora, SUM(d.aver) AS hoy 
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_hoy` d ON f.desdia=d.desdia AND f.hora=d.hora  
                                    $sql_jefatura_troba_graf_hoy
                                    GROUP BY f.desdia, f.hora
                                    ) hoy
                                    LEFT JOIN 
                                    (
                                    SELECT 
                                    e.desdia AS dia_ayer, f.hora, SUM(e.aver) AS ayer
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_ayer` e ON f.hora=e.hora
                                    $sql_jefatura_troba_graf_ayer
                                    GROUP BY f.desdia, f.hora
                                    ) ayer ON hoy.hora = ayer.hora
                                    LEFT JOIN 
                                    (
                                    SELECT c.desdia, c.hora, ROUND(SUM(c.aver)/c.nro_reqs,0) AS antes
                                    FROM graf_promedio c
                                    $sql_jefatura_troba_graf_promedio
                                    GROUP BY c.desdia, c.hora
                                    
                                    ) antes ON hoy.hora = antes.hora
                                    LEFT JOIN 
                                    (
                                    SELECT 
                                    f.desdia, f.hora, SUM(ll.llamadas) AS llamadas
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_llamadas` ll ON f.hora=ll.horallam 
                                    $sql_jefatura_troba_graf_llamadas
                                    GROUP BY f.desdia, f.hora
                                    ) llamadas ON hoy.hora = llamadas.hora
                                    LEFT JOIN 
                                    (
                                    SELECT 
                                    f.desdia, f.hora, SUM(lq.liq) AS liq
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_liquidaciones` lq ON f.hora=lq.Hora 
                                    $sql_jefatura_troba_graf_liquidaciones
                                    GROUP BY f.desdia, f.hora
                                    ) liquidaciones ON hoy.hora = liquidaciones.hora
                                    LEFT JOIN 
                                    (
                                    SELECT 
                                    f.desdia, f.hora, SUM(ar.arbol) AS arbol
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_arbol_hoy` ar ON f.hora=ar.hora AND ar.arbol>0
                                    $sql_jefatura_troba_graf_arbol_hoy
                                    GROUP BY f.desdia, f.hora
                                    ) arboldec ON hoy.hora = arboldec.hora
                                    LEFT JOIN 
                                    (
                                    SELECT 
                                    f.desdia, f.hora, SUM(art.arboltot) AS arboltot
                                    FROM v_graf_promedio_x_dia_hora f 
                                    LEFT JOIN `graf_arbol_tot` art ON f.hora=art.hora
                                    $sql_jefatura_troba_graf_arbol_tot
                                    GROUP BY f.desdia, f.hora
                                    ) arboltotal ON hoy.hora = arboltotal.hora");
            
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