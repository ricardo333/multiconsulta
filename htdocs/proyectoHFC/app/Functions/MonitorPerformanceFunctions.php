<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class MonitorPerformanceFunctions {


    function getListaMonitorSQLWeb($tipoServer)
    {
        if ($tipoServer=="monitor_bdWeb") {
            try {

                $monitor = DB::connection('mysql')->select("SELECT
                                `ID`,`DB`,`COMMAND`,`TIME`,`STATE`,`INFO`,`MEMORY_USED`
                                FROM information_schema.`PROCESSLIST`
                                where command not like '%cm_a_diario%'
                                ORDER BY TIME DESC");

                DB::disconnect('mysql');
    
            } catch(QueryException $ex){ 
                return "error";
            }catch(\Exception $e){
                return "error";
            }
            
            return $monitor;

        } elseif ($tipoServer=="monitor_bdProcesos") {
            try {

                $monitor = DB::connection('servidor_process')->select("SELECT
                                `ID`,`DB`,`COMMAND`,`TIME`,`STATE`,`INFO`,`MEMORY_USED`
                                FROM information_schema.`PROCESSLIST`
                                where command not like '%cm_a_diario%'
                                ORDER BY TIME DESC");

                DB::disconnect('servidor_process');
    
            } catch(QueryException $ex){ 
                return "error";
            }catch(\Exception $e){
                return "error";
            }
            
            return $monitor;

        } elseif ($tipoServer=="monitor_bdColector") {
            try {

                $monitor = DB::connection('servidor_colector')->select("SELECT
                                `ID`,`DB`,`COMMAND`,`TIME`,`STATE`,`INFO`,`MEMORY_USED`
                                FROM information_schema.`PROCESSLIST`
                                where command not like '%cm_a_diario%'
                                ORDER BY TIME DESC");

                DB::disconnect('servidor_colector');
    
            } catch(QueryException $ex){ 
                return "error";
            }catch(\Exception $e){
                return "error";
            }
            
            return $monitor;

        }
        

        /*
        try {

            $monitor = DB::select("SELECT
                            `ID`,`DB`,`COMMAND`,`TIME`,`STATE`,`INFO`,`MEMORY_USED`
                            FROM information_schema.`PROCESSLIST`
                            where command not like '%cm_a_diario%'
                            ORDER BY TIME DESC");

        } catch(QueryException $ex){ 
            return "error";
        }catch(\Exception $e){
            return "error";
        }
        
        return $monitor;
        */
    }


    
    function getProcesarMonitoreoSQL($performancesql)
    {
        $cantidadPerformance = count($performancesql);

        $contadorId = 0;
        
        for ($i=0; $i < $cantidadPerformance ; $i++) {

            $performancesql[$i]->it = $contadorId + 1;

            if ($performancesql[$i]->TIME > 100) {
                $performancesql[$i]->fondo = "red";
                $performancesql[$i]->letra = "white";
            } else {
                $performancesql[$i]->fondo = "white";
                $performancesql[$i]->letra = "black";
            }
            
            $performancesql[$i]->kill = "KILL";

            $contadorId++;

        }

        return $performancesql;

    }


    function getDataHistoricoApache()
    {
        try {
            
            $queryHis = DB::select("SELECT CONCAT(IF(HOUR(fechahora)*1<10,CONCAT('0',HOUR(fechahora)),HOUR(fechahora)),':',IF(MINUTE(fechahora)*1<10,CONCAT('0',MINUTE(fechahora)),MINUTE(fechahora))) AS hora ,
                            SUM(IF(parametro='Connect:   ',vmin,'')) AS Connect,
                            SUM(IF(parametro='Processing:',vmin,'')) AS Processing,
                            SUM(IF(parametro='Waiting:   ',vmin,'')) AS Waiting,
                            SUM(IF(parametro='Total:     ',vmin,'')) AS Total
                            FROM alertasx.`apacheb`
                            WHERE parametro IN ('Connect:','Processing:','Wait:','Total:') AND TIMEDIFF(NOW(),fechahora)<='00:30:00.000000'
                            GROUP BY 1");
            
        } catch(QueryException $ex){ 
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }catch(\Exception $e){  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }



    function procesarEliminacion($id,$tipoServer)
    {
        if ($tipoServer=="monitor_bdWeb") {
            try {
                
                $queryKill = DB::connection('mysql')->select("kill $id");
                DB::disconnect('mysql');
                
            } catch(QueryException $ex){ 
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            }catch(\Exception $e){  
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            } 

            $mensaje = "Eliminacion OK";

        } elseif ($tipoServer=="monitor_bdProcesos") {
            try {
                
                $queryKill = DB::connection('servidor_process')->select("kill $id");
                DB::disconnect('servidor_process');
                
            } catch(QueryException $ex){ 
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            }catch(\Exception $e){  
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            } 

            $mensaje = "Eliminacion OK";
            
        } elseif ($tipoServer=="monitor_bdColector") {
            try {
                
                $queryKill = DB::connection('servidor_colector')->select("kill $id");
                DB::disconnect('servidor_colector');
                
            } catch(QueryException $ex){ 
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            }catch(\Exception $e){  
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            } 

            $mensaje = "Eliminacion OK";
                   
        }

            return $mensaje; 
         
    }


    function getListaGuardian()
    {
        try {

            $monitorGuardian = DB::select("SELECT 'NCLIENTES' as tabla,COUNT(*) AS cant,max(FECHAACTIVACION) AS fecha  
                                    FROM multiconsulta.nclientes
                                    UNION
                                    SELECT 'NCLIENTES_C' as tabla,COUNT(*) AS cant,max(FECHAACTIVACION) AS fecha  
                                    FROM multiconsulta.nclientes_c
                                    UNION
                                    SELECT 'SCM_TOTAL' as tabla,COUNT(*) AS cant,max(FECHA_HORA) AS fecha  
                                    FROM ccm1.`scm_total`
                                    UNION
                                    SELECT 'SCM_TOTAL_F' as tabla,COUNT(*) AS cant,max(FECHA_HORA) AS fecha   
                                    FROM ccm1.`scm_total_f`
                                    UNION
                                    SELECT 'SCM_PHY_T' as tabla,COUNT(*) AS cant,max(FECHA_HORA) AS fecha   
                                    FROM ccm1.`scm_phy_t`
                                    UNION
                                    SELECT 'SCM_PHY_F' as tabla,COUNT(*) AS cant,max(FECHA_HORA) AS fecha   
                                    FROM ccm1.`scm_phy_f`");

        } catch(QueryException $ex){ 
            return "error";
        }catch(\Exception $e){
            return "error";
        } 
        
        return $monitorGuardian;
    }


    function getProcesarGuardian($performanceGuardian)
    {
        $cantidadPerformance = count($performanceGuardian);

        $contadorId = 0;
        
        for ($i=0; $i < $cantidadPerformance ; $i++) {

            $performanceGuardian[$i]->id = $contadorId + 1;
            $contadorId++;

        }

        return $performanceGuardian;

    }



    
      
    

    

    

    

    
 

}