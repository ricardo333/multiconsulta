<?php

namespace App\Reportes\Excel\CaidasMasivas;

use DB;
use Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class CaidasMasivasExcelTotal extends GeneralController implements FromCollection, WithHeadings {

    protected $tipoCaida;
    protected $filtro1;
    protected $filtro2;

    function __construct($tipoCaida,$filtro1,$filtro2) {
        $this->tipoCaida = $tipoCaida;
        $this->filtro1 = $filtro1;
        $this->filtro2 = $filtro2;
    }
    

    public function queryTotal($tipoCaida,$filtro1,$filtro2)
    {
        try {

            /*
            $query = DB::select("
                        SELECT concat(b.jefatura,'_',b.sede) as jefatura,a.nodo,a.troba,a.aver,
                        a.llamadas,a.cant,a.umbral,a.off,a.fecha_hora,a.fecha_fin,a.codmasiva 
                        FROM ccm1_temporal.alarmas_caidas_historico a 
                        INNER JOIN ccm1.zonales_nodos_eecc b ON a.nodo=b.nodo
                        WHERE DATEDIFF(NOW(),fecha_hora)<=15");
            */
            if ($tipoCaida=="caidas_masivas") {
                //QUERY PARA CAIDAS
                $query = DB::select("
                            SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt AS codmasiva,a.umbral,a.Caida,a.fecha_hora,
                            IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,
                            IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,IF(pr.troba IS NULL ,'','PREMIUM') AS premium,rm.remedy, 
                            CONCAT('TOP : ',t.top) AS top ,ad.cant AS calldmpe,ad.ultimallamada,
                            (SELECT CONCAT(b.estado,' ',b.observaciones,' ',b.usuario,' ',b.fechahora) FROM alertasx.gestion_alert b 
                            WHERE b.nodo=a.nodo AND b.troba=a.troba AND DATEDIFF(NOW(),b.fechahora)=0 ORDER BY b.fechahora DESC LIMIT 1) AS gestion
                            FROM alertasx.caidas_new a
                            LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                            LEFT JOIN catalogos.premium pr ON CONCAT(a.nodo,a.troba)=pr.troba
                            LEFT JOIN dbpext.masivas_tempx mt ON a.nodo=mt.codnod AND a.troba=mt.nroplano
                            LEFT JOIN alertasx.remedys_hfc  rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2
                            LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba
                            LEFT JOIN alertasx.alertas_dmpe_view ad ON a.nodo=ad.nodo AND a.troba=ad.troba 
                            WHERE DATEDIFF(NOW(),fecha_hora)=0 $filtro1 $filtro2
                            GROUP BY a.estado,a.jefatura,a.nodo,a.troba
                            ORDER BY a.estado,a.jefatura,a.nodo,a.troba");
            }


            if ($tipoCaida=="caidas_noc") {
                //QUERY PARA CAIDAS NOC
                $query = DB::select("
                            SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt AS codmasiva,a.umbral,a.Caida,a.fecha_hora,
                            IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,
                            IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4, 
                            IF(pr.troba IS NULL ,'','PREMIUM') AS premium,rm.remedy,(a.offline/a.cancli) AS porc_caida,
                            (SELECT COUNT(*) AS aver FROM cms.req_pend_macro_final c 
                            WHERE c.codnod=a.nodo AND c.nroplano=a.troba AND DATEDIFF(NOW(),fec_registro)=0) AS averiasc,
                            (SELECT CONCAT(b.estado,' ',b.observaciones,' ',b.usuario,' ',b.fechahora) FROM alertasx.gestion_alert b 
                            WHERE b.nodo=a.nodo AND b.troba=a.troba AND DATEDIFF(NOW(),b.fechahora)=0 ORDER BY b.fechahora DESC LIMIT 1) AS gestion
                            FROM alertasx.caidas_new a
                            LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                            LEFT JOIN catalogos.premium pr ON CONCAT(a.nodo,a.troba)=pr.troba
                            LEFT JOIN dbpext.masivas_tempx mt ON a.nodo=mt.codnod AND a.troba=mt.nroplano
                            LEFT JOIN alertasx.remedys_hfc  rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2
                            WHERE DATEDIFF(NOW(),fecha_hora)=0 $filtro1 $filtro2
                            GROUP BY a.estado,a.jefatura,a.nodo,a.troba
                            HAVING a.offline>500 OR averiasc>50 OR porc_caida >= 0.75
                            ORDER BY a.estado,a.jefatura,a.nodo,a.troba");
            }


            if ($tipoCaida=="caidas_torre") {
                //QUERY PARA CAIDAS TORRE
                $query = DB::select("
                            SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt AS codmasiva,a.umbral,a.Caida,a.fecha_hora,
                            IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,
                            IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,
                            IF(pr.troba IS NULL ,'','PREMIUM') AS premium,rm.remedy,(a.offline/a.cancli) AS porc_caida,
                            (SELECT COUNT(*) AS aver FROM cms.req_pend_macro_final c 
                            WHERE c.codnod=a.nodo AND c.nroplano=a.troba AND DATEDIFF(NOW(),fec_registro)=0) AS averiasc,
                            (SELECT CONCAT(b.estado,' ',b.observaciones,' ',b.usuario,' ',b.fechahora) FROM alertasx.gestion_alert b 
                            WHERE b.nodo=a.nodo AND b.troba=a.troba AND DATEDIFF(NOW(),b.fechahora)=0 ORDER BY b.fechahora DESC LIMIT 1) AS gestion
                            FROM alertasx.caidas_new a
                            LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                            LEFT JOIN catalogos.premium pr ON CONCAT(a.nodo,a.troba)=pr.troba
                            LEFT JOIN dbpext.masivas_tempx mt ON a.nodo=mt.codnod AND a.troba=mt.nroplano
                            LEFT JOIN alertasx.remedys_hfc  rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2
                            WHERE a.offline<500 AND (a.offline/a.cancli)<=0.75 AND DATEDIFF(NOW(),fecha_hora)=0 $filtro1 $filtro2
                            GROUP BY a.estado,a.jefatura,a.nodo,a.troba
                            ORDER BY a.estado,a.jefatura,a.nodo,a.troba");
            }


            if ($tipoCaida=="caidas_amplificador") {
                //QUERY PARA CAIDAS AMPLIFICADOR
                $query = DB::select("
                            SELECT IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal)) AS jefatura,a.nodo,a.troba,a.cancli AS cancli,
                            a.offline,a.amplificador,b.codreqmnt AS  codmasiva,a.fecha_hora,a.estado,rm.remedy, 
                            IF(a.estado='CAIDO',TIMEDIFF(NOW(),a.fecha_hora),a.tiempo) AS tiempo,c.cant AS cant1,d.tipo,a.fecha_fin,
                            IF(e.caidas >3,'CRITICA','') AS tcaidas,e.caidas AS ncaidas,d.numbor,dg.fecha AS fecha_digi,
                            IF(dg.nodo IS NOT NULL ,'Digitalizado','') AS digi,CONCAT('TOP : ',t.top) AS top,
                            IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,
                            (SELECT CONCAT(bl.estado,' ',bl.observaciones,' ',bl.usuario,' ',bl.fechahora) FROM alertasx.gestion_alert bl 
                            WHERE bl.nodo=a.nodo AND bl.troba=a.troba ORDER BY bl.fechahora DESC LIMIT 1) AS gestion
                            FROM  alertasx.`caidas_new_amplif` a
                            LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                            LEFT JOIN dbpext.masivas_temp b ON  a.nodo=b.codnod AND a.troba=b.nroplano
                            LEFT JOIN ccm1_temporal.consultasr c ON a.nodo=c.nodo AND a.troba=c.troban
                            LEFT JOIN catalogos.bornesxtroba d ON a.nodo=d.nodo AND a.troba=d.troba
                            LEFT JOIN ccm1_temporal.cant_caidas e ON a.nodo=e.nodo AND a.troba=e.troba
                            LEFT JOIN catalogos.trobas_digi_view dg ON a.nodo=dg.nodo AND a.troba=dg.troba
                            INNER JOIN ccm1.`zonales_nodos_eecc` zn1 ON zn1.`NODO`=a.`nodo`
                            LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba 
                            LEFT JOIN alertasx.remedys_hfc  rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2 $filtro2
                            GROUP BY a.estado,a.nodo,a.troba,a.amplificador
                            $filtro1
                            ORDER BY a.estado,a.nodo,a.troba,a.amplificador");
            }


            return collect($query);
            //return $query;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryTotal($this->tipoCaida,$this->filtro1,$this->filtro2));
    }


    public function headings(): array
    {
        $tipo = $this->tipoCaida;

        if ($tipo=="caidas_masivas") {
            //CABECERA PARA CAIDAS 
            $cabecera = array('jefatura','nodo','troba','clientes','offline','codmasiva','umbral',
                    'Caida','fecha_hora','tiempo','ncaidas','N.Bornes','fecha_fin','estado','tc',
                    'fuente','mac','premium','remedy','top','calldmpe','ultima_llamada','gestion');
        }

        
        if ($tipo=="caidas_noc" || $tipo=="caidas_torre") {
            //CABECERA PARA CAIDAS NOC y TORRE
            $cabecera = array('jefatura','nodo','troba','clientes','offline','codmasiva','umbral',
                    'Caida','fecha_hora','tiempo','ncaidas','N.Bornes','fecha_fin','estado','tc',
                    'fuente','mac','premium','remedy','porc_caida','averiasc','gestion');
        }


        if ($tipo=="caidas_amplificador") {
            //CABECERA PARA CAIDAS AMPLIFICADOR
            $cabecera = array('jefatura','nodo','troba','clientes','offline','amplificador','codmasiva',
                    'fecha_hora','estado','remedy','tiempo','cantl','tipo','fecha_fin','tcaidas',
                    'ncaidas','numbor','fecha_digi','digi','top','fuente','mac','gestion');
        }


        return $cabecera;

    }
 

}

?>