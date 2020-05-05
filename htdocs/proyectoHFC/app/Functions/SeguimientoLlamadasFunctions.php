<?php 

namespace App\Functions;

use DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SeguimientoLlamadasFunctions {

    function getHoraTotalSeguimiento($fecha)
    {

        try {
            $registro = DB::select("SELECT MAX(a.hora) AS hora,sum(Cant) as total, SUM(IF(resultado IN ('ALERTA MASIVA HFC','ALERTA MASIVA FTTH'),cant,0)) AS contencion 
                                    FROM catalogos.dmpe_view a where fecha='".$fecha."'");
        } catch(QueryException $ex){ 
            return "error";
        }catch(\Exception $e){
            return "error";
        } 
        
        return $registro;
    }

    function getDataHistoricoSeguimientoLlamadas()
    {
        try {
            //b.hoy
            $queryHis = DB::select("select a.hora,a.desdia,a.prom AS Promedio,IF(b.hoy IS NULL,0,b.hoy) AS Hoy
                    FROM 
                    (SELECT hora,desdia,ROUND(AVG(cant),0) AS prom FROM alertasx.llamadasdmpexdia 
                    WHERE DAYOFWEEK(fecha)=DAYOFWEEK(NOW()) AND DATEDIFF(NOW(),fecha)<=30
                    GROUP BY hora) a
                    LEFT JOIN 
                    (SELECT hora,desdia,SUM(cant) AS hoy FROM alertasx.llamadasdmpexdia 
                    WHERE DATEDIFF(NOW(),fecha)=0
                    GROUP BY hora order by hora desc) b
                    ON a.hora=b.hora
                    order by a.hora asc");
            
        } catch(QueryException $ex){ 
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }catch(\Exception $e){  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }


}