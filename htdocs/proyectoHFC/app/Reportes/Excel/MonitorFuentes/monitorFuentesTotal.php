<?php

namespace App\Reportes\Excel\MonitorFuentes;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Functions\MonitorFuentesFunctions;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class monitorFuentesTotal extends GeneralController implements FromCollection,WithHeadings {

     
    protected $filtroNodo;
    protected $filtroTipobateria;
    protected $filtroEstado;
    
 
    function __construct($filtroNodo,$filtroTipobateria,$filtroEstado) { 
        $this->filtroNodo = $filtroNodo;
        $this->filtroTipobateria = $filtroTipobateria;
        $this->filtroEstado = $filtroEstado;
        
    }

    public function queryaveria($cantidad,$filtroTipobateria,$filtroEstado){

        
        if ($cantidad == 0) {
  
            $queryExe="select 
                        '' as marca,
                        '' as nodo, 
                        '' as troba,
                        0 AS cancli,
                        0 AS offline,
                        '' as direccion,
                        '' AS macaddress,
                        '' AS ipaddress,
                        '' AS InputVoltagefinal,
                        '' AS OutputVoltagefinal,
                        '' AS OutputCurrentfinal,
                        '' AS TotalStringVoltagefinal,
                        '' AS fechahora,
                        '' as estadoDeGestion,
                        '' AS resultadosnmp 
                        ";
        }else{
       
            $queryExe=" 
                    SELECT 
                        ou.marca,zz.nodo,zz.troba,zz.cancli,zz.offline,zz.direccion,
                        zz.macaddress,zz.ipaddress,zz.InputVoltagefinal,zz.OutputVoltagefinal,zz.OutputCurrentfinal, zz.TotalStringVoltagefinal,
                        zz.fechahora,if(zz.tienebateria = 'NO','NO','SI') as existeBateria,if(zz.usuario is not null,
                                concat(zz.estado_ges,': ',zz.observaciones,'-',zz.usuario,'-',zz.fechahora_ges),'') as estadoDeGestion,
                        zz.resultadosnmp
                    FROM
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
                    AND nodo<>''
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

            $lista = DB::select($queryExe);

        } catch(QueryException $ex){ 
            ///dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
            
        }catch(\Exception $e){
            ///dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 

        return $lista;
    }


    public function collection()
    {
        $monitorFuentesF = new MonitorFuentesFunctions;

        $cantidadAfectados = $monitorFuentesF->cantidadAfectados($this->filtroNodo);
  
        if ($cantidadAfectados == "error") { 
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }
  
        $cantidad = $cantidadAfectados[0]->i;
        
        return collect($this->queryaveria($cantidad,$this->filtroTipobateria,$this->filtroEstado));
    }

    
    public function headings(): array
    {
        $cabecera = array(
            "Marca Bateria",
            "Nodo",
            "Troba",
            "Cliente",
            "Off",
            "Direccion",
            "Macaddress",
            "IPaddress",
            "Volt-Ent",
            "Volt_Sal",
            "Corr_Sal",
            "Bateria",
            "FechaHora",
            "Bateria?",
            "Gestion",
            "SNMP"
        );

        return $cabecera;
    }
    

}


?>