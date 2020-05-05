<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
  
class MonitorFuentesFunctions {

    function nodos()
    {
        $estados = DB::select("select nodo from alertasx.fuentes_view group by 1 order by 1 asc");
        return $estados;
       
    }

    function cantidadAfectados($filtroNodo)
    {
        try {
            $cantidad = DB::select("select count(*) as i FROM 
                                    (SELECT mt.*,g.fechahora AS fechahora_ges,g.observaciones,g.usuario,g.tecnico,g.estado AS estado_ges,g.porc_caida,g.serv_afectado,g.numreq,g.remedy,g.idcausalert,
                                    cc.cancli,cc.offline,cc.codmasiva,cc.fecha_hora AS fechahora_caida,IF(cc.`Caida`='SI','CAIDA','') AS caida
                                    FROM
                                    (SELECT xx.* FROM 
                                    (SELECT a.*,1 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a WHERE a.tienebateria IS NULL AND  a.resultadosnmp ='SNMPOK' AND TotalStringVoltagefinal>0
                                    UNION
                                    SELECT a.*,2 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a WHERE a.tienebateria IS NULL AND  a.resultadosnmp ='SNMPOK' AND TotalStringVoltagefinal=0
                                    UNION
                                    SELECT a.*,3 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef FROM alertasx.fuentes_view a WHERE a.tienebateria='N' AND  a.resultadosnmp ='SNMPOK' 
                                    UNION
                                    SELECT a.*,4 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a WHERE a.tienebateria IS NULL  AND  a.resultadosnmp <>'SNMPOK'  AND TotalStringVoltagefinal>0
                                    UNION
                                    SELECT a.*,4 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a WHERE a.tienebateria IS NULL  AND  a.resultadosnmp <>'SNMPOK'  AND TotalStringVoltagefinal=0
                                    UNION
                                    SELECT a.*,5 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a WHERE a.tienebateria='N' AND  a.resultadosnmp <>'SNMPOK') 
                                    xx 
                                    ) mt
                                    LEFT JOIN alertasx.caidas_new cc
                                    ON mt.nodo=cc.nodo AND mt.troba=cc.troba AND cc.Caida='SI'
                                    LEFT JOIN
                                    (SELECT a.* FROM alertasx.gestion_alert a 
                                    INNER JOIN
                                    (SELECT nodo,troba,MAX(fechahora) AS fechahora 
                                    FROM alertasx.gestion_alert 
                                    WHERE DATEDIFF(NOW(),fechahora)<=10
                                    AND nodo<>''
                                    GROUP BY 1,2) b
                                    ON a.nodo=b.nodo AND a.troba=b.troba AND a.fechahora=b.fechahora) g
                                    ON cc.nodo=g.nodo AND cc.troba=g.troba AND cc.Caida='SI') zz 
                                    $filtroNodo 
                                    ORDER BY zz.caida DESC,zz.id,zz.puntajef DESC");
        } catch(QueryException $ex){ 
            ///dd($ex->getMessage());  
            // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            ///dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $cantidad;
    }

    function listaSegunCantidad($cantidad,$filtroTipobateria,$filtroEstado)
    {

        if ($cantidad == 0) {
            $queyr="select 
                    REPLACE(mac3,':','') AS macaddress,
                    '' AS ip,
                    nodo,
                    troba,
                    distrito,
                    direccion,
                    latitudx,
                    longitudy,
                    '' AS cmts,
                    '' AS interface,
                    '' AS InputVoltagefinal,
                    '' AS InputVoltagefinalcolor,
                    '' AS OutputVoltagefinal,
                    '' AS OutputVoltagefinalcolor,
                    '' AS OutputCurrentfinal,
                    '' AS OutputCurrentfinalcolor,
                    '' AS TotalStringVoltagefinal,
                    '' AS TotalStringVoltagefinalcolor,
                    0 AS EstadoInversor,
                    '' AS colorinversor,
                    0 AS puntaje,
                    '' AS fechahora,
                    '' AS resultadosnmp,
                    '' AS tienebateria,
                    0 AS id,
                    0 AS puntajef,
                    '' AS fechahora_ges,
                    '' AS observacion,
                    '' AS usuario,
                    '' AS tecnico,
                    '' AS estado_ges,
                    '' AS porc_caida,
                    '' AS serv_afectado,
                    0 AS numreq,
                    0 AS numremedy,
                    0 AS idcausalert,
                    0 AS cancli,
                    0 AS offline,
                    0 AS codmasiva,
                    '' AS fechahora_caida,
                    '' AS caida    
                    FROM catalogos.`db_fuentes` WHERE nodo<>'' AND troba<>''
                    ";
        }else{
            $queyr="
                    SELECT zz.*,ou.marca FROM
                    (SELECT mt.*,g.fechahora AS fechahora_ges,g.observaciones,g.usuario,g.tecnico,g.estado AS estado_ges,g.porc_caida,g.serv_afectado,g.numreq,g.remedy,g.idcausalert,
                    cc.cancli,cc.offline,cc.codmasiva,cc.fecha_hora AS fechahora_caida,IF(cc.`Caida`='SI','CAIDA','') AS caida
                    FROM
                    (SELECT xx.* FROM 
                    (SELECT a.*,1 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria IS NULL AND  a.resultadosnmp ='SNMPOK' AND TotalStringVoltagefinal>0
                    UNION
                    SELECT a.*,2 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria IS NULL AND  a.resultadosnmp ='SNMPOK' AND TotalStringVoltagefinal=0
                    UNION
                    SELECT a.*,3 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria='N' AND  a.resultadosnmp ='SNMPOK' 
                    UNION
                    SELECT a.*,4 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria IS NULL  AND  a.resultadosnmp <>'SNMPOK'  AND TotalStringVoltagefinal>0
                    UNION
                    SELECT a.*,4 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria IS NULL  AND  a.resultadosnmp <>'SNMPOK'  AND TotalStringVoltagefinal=0
                    UNION
                    SELECT a.*,5 AS id,IF(TotalStringVoltagefinalcolor='RED',100,IF(TotalStringVoltagefinalcolor='ORANGE',80,0))+a.puntaje AS puntajef  FROM alertasx.fuentes_view a 
                    WHERE a.tienebateria='N' AND  a.resultadosnmp <>'SNMPOK') 
                    xx 
                    ) mt
                    LEFT JOIN alertasx.caidas_new cc
                    ON mt.nodo=cc.nodo AND mt.troba=cc.troba AND cc.Caida='SI'
                    LEFT JOIN
                    (SELECT a.* FROM alertasx.gestion_alert a 
                    INNER JOIN
                    (SELECT nodo,troba,MAX(fechahora) AS fechahora 
                    FROM alertasx.gestion_alert 
                    WHERE DATEDIFF(NOW(),fechahora)<=10
                    AND nodo<>'' AND troba<>''
                    GROUP BY 1,2) b
                    ON a.nodo=b.nodo AND a.troba=b.troba AND a.fechahora=b.fechahora) g
                    ON cc.nodo=g.nodo AND cc.troba=g.troba AND cc.Caida='SI'
                    ) zz
                    inner join catalogos.oui_fuentes ou
                    on substr(replace(macaddress,'.',''),1,6)=ou.oui_fuentes 
                    $filtroTipobateria  $filtroEstado
                    ORDER BY zz.caida DESC,zz.id,zz.puntajef DESC";
        }

        try {

            $lista = DB::select($queyr);

        } catch(QueryException $ex){ 
            ///dd($ex->getMessage());  
            // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            ///dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 

        return $lista;

    }

    function getDataGraficoFuentes($macaddress)
    {
        try {
 
             $lista = DB::connection('servidor_procesos')->select("SELECT xx.*,b.nodo,b.troba FROM 
                                (SELECT
                                a.macadDress,
                                a.InputVoltagefinal,
                                a.OutputVoltagefinal,
                                a.OutputCurrentfinal,
                                a.TotalStringVoltagefinal,
                                a.EstadoInversor,
                                SUBSTR(a.fechahora,12,5) AS hora,
                                a.fechahora
                                FROM alertasx.fuentes_snmp_hist a 
                                WHERE a.macaddress='".$macaddress."' AND TIMEDIFF(NOW(),a.fechahora)<='06:00:00.000000'  
                                ORDER BY a.fechahora ASC LIMIT 120
                                ) xx
                                INNER JOIN alertasx.fuentes_snmp b ON xx.macadDress=b.macadDress"); 
            //  DB::disconnect('foo');

        } catch(QueryException $ex){ 
            ///dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            ///dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 

        return $lista;
    }

    function getDetailsFuentesByMac($mac)
    {
        try {

            $lista = DB::select("select * from catalogos.db_fuentes where mac='".$mac."'");

        } catch(QueryException $ex){ 
            ///dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            ///dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
 
        return $lista;
    }

    function updateFuente($parametros)
    { 
        
        $mac=htmlspecialchars($parametros["mac"]);
        $mac3=substr($mac,0,2).':'.substr($mac,2,2).':'.substr($mac,4,2).':'.substr($mac,6,2).':'.substr($mac,8,2).':'.substr($mac,10,2);
        $mac4=substr($mac,0,4).'.'.substr($mac,4,4).'.'.substr($mac,8,4);

        try {
            $tieneBateria =  $parametros["tieneBateria"] == "" ? null : htmlspecialchars($parametros["tieneBateria"]);
             DB::update("update catalogos.db_fuentes 
                        set nodo=?, troba=?,zonal=?,distrito=?,
                        direccion=?,latitudx=?,longitudy=?,
                        marcatroba=?,respaldo=?,descricion=?,
                        tienebateria=?,segundafuente=?,mac=?,
                        mac3='$mac3',mac4='$mac4' where mac='".$mac."'",[
                            htmlspecialchars($parametros["nodo"]),
                            htmlspecialchars($parametros["troba"]),
                            htmlspecialchars($parametros["zonal"]),
                            htmlspecialchars($parametros["distrito"]),
                            htmlspecialchars($parametros["direccion"]),
                            htmlspecialchars($parametros["latitudX"]),
                            htmlspecialchars($parametros["latitudY"]),
                            htmlspecialchars($parametros["marcaToba"]),
                            htmlspecialchars($parametros["respaldo"]),
                            htmlspecialchars($parametros["descripcion"]),
                            $tieneBateria,
                            htmlspecialchars($parametros["segundaFuente"]),
                            htmlspecialchars($parametros["mac"])
                        ]);

             DB::update("update alertasx.fuentes_snmp  
                        set nodo=?, troba=?,distrito=?,
                        direccion=?,latitudx=?,longitudy=?,
                        macaddress=?
                        where macaddress='".$mac4."'",[
                            htmlspecialchars($parametros["nodo"]),
                            htmlspecialchars($parametros["troba"]),
                            htmlspecialchars($parametros["distrito"]),
                            htmlspecialchars($parametros["direccion"]),
                            htmlspecialchars($parametros["latitudX"]),
                            htmlspecialchars($parametros["latitudY"]),
                           $mac4
                        ]);
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 
    }
 

}