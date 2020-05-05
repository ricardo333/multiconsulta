<?php 

namespace App\Functions;

use DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GraficaLlamadasNodosFunctions {

    function getListNodos()
    {

        try {
            $registro = DB::select("select nodo,sum(Cant) as cant from alertasx.alerta_nodo_llamadas_view group by 1 order by cant desc");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        //return collect($registro);
        return $registro;
    }

    function getListJefaturaxNodo($sql_jefatura_nodo)
    {

        try {
            $registro = DB::select("select 
                                    n.nodo
                                    FROM ccm1.cantroba n 
                                    INNER JOIN catalogos.jefaturas j
                                    ON n.nodo=j.nodo
                                    $sql_jefatura_nodo
                                    GROUP BY n.nodo");
            //$registro = DB::select("SELECT nodo FROM alertasx.alerta_nodo_llamadas_view GROUP BY 1 ASC");
            
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        //return collect($registro);
        return $registro;
    }

    function getHoraTotalLlamadasNodos($nodo)
    {

        try {
            $registro = DB::select("SELECT sum(Cant) as total,MAX(a.minuto) AS hora FROM alertasx.alerta_nodo_llamadas_view a where nodo='".$nodo."' ");

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

    function getDataHistoricoLlamadasNodos($nodo)
    {
        try {
           
            $queryHis = DB::select("SELECT a.nodo,a.hora,a.desdia,a.prom,b.hoy FROM 
                                    (SELECT nodo,hora,desdia,ROUND(AVG(cant),0) AS prom FROM alertasx.`llamadasdmpexdia_nodo` 
                                    WHERE DAYOFWEEK(fecha)=DAYOFWEEK(NOW()) AND DATEDIFF(NOW(),fecha)<=30 and nodo='$nodo'
                                    GROUP BY nodo,hora) a
                                    LEFT JOIN 
                                    (SELECT nodo,hora,desdia,SUM(cant) AS hoy FROM alertasx.`llamadasdmpexdia_nodo` 
                                    WHERE DATEDIFF(NOW(),fecha)=0 and nodo='$nodo'
                                    GROUP BY nodo,hora) b
                                    ON a.nodo=b.nodo AND a.hora=b.hora
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

}