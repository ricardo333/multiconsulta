<?php 

namespace App\Functions;

use DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GraficaLlamadasNodosDiaFunctions {

    function getListNodos()
    {

        try {
            $registro = DB::select("select nodo,sum(Cant) from alertasx.alerta_nodo_llamadas_view group by 1 order by 2 desc");
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
                $registro = DB::select("SELECT v.nodo,sum(v.Cant)
                                        FROM alertasx.alerta_nodo_llamadas_view v 
                                        LEFT JOIN catalogos.jefaturas c ON v.nodo=c.nodo
                                        ".$sql_jefatura_nodo."
                                        GROUP BY 1 ORDER BY 2 DESC");
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

    function getTotalLlamadasNodosDia($nodo,$hor)
    {

        try {
            $registro = DB::select("SELECT fecha FROM alertasx.llamadasdmpexdia_nodo WHERE nodo='".$nodo."' AND numdia=DAYOFWEEK(NOW()) AND hora<='".$hor."' GROUP BY fecha,numdia ORDER BY fecha DESC LIMIT 4");
            
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

    function getDataHistoricoLlamadasNodosDia($nodo,$hor,$resultLlamadasDia)
    {
        try {

            $f1='';$f2='';$f3='';$f4='';           
            $f1 = $resultLlamadasDia[0]->fecha;
            $f2 = $resultLlamadasDia[1]->fecha;
            $f3 = $resultLlamadasDia[2]->fecha;
            $f4 = $resultLlamadasDia[3]->fecha;         
            $queryHis = DB::select("SELECT a.nodo,a.desdia,a.sem1,IF(b.sem2 IS NULL,0,b.sem2) AS sem2,IF(c.sem3 IS NULL,0,c.sem3) AS sem3,IF(d.sem4 IS NULL,0,d.sem4) AS sem4  FROM
                                (SELECT nodo,desdia,sum(cant) AS sem1 FROM alertasx.`llamadasdmpexdia_nodo`
                                WHERE nodo='$nodo'  and hora<='$hor' and fecha='$f1'

                                GROUP BY nodo) a
                                LEFT JOIN
                                (SELECT nodo,desdia,SUM(cant) AS sem2 FROM alertasx.`llamadasdmpexdia_nodo`
                                WHERE nodo='$nodo' and hora<='$hor' and fecha='$f2' 
                                GROUP BY nodo) b
                                ON a.nodo=b.nodo 
                                LEFT JOIN
                                (SELECT nodo,desdia,SUM(cant) AS sem3 FROM alertasx.`llamadasdmpexdia_nodo`
                                WHERE nodo='$nodo' and hora<='$hor' and fecha='$f3'
                                GROUP BY nodo) c
                                ON a.nodo=c.nodo 
                                LEFT JOIN
                                (SELECT nodo,desdia,SUM(cant) AS sem4 FROM alertasx.`llamadasdmpexdia_nodo`
                                WHERE nodo='$nodo' and hora<='$hor' and fecha='$f4'
                                GROUP BY nodo) d
                                ON a.nodo=d.nodo");
                                
            
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