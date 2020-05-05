<?php

namespace App\Reportes\Excel\MasivaCms;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

ini_set('memory_limit', '256M');

class MasivaCmsExcelTotal extends GeneralController implements FromCollection,WithHeadings {

    protected $filtro1;
    protected $filtro2;

    function __construct($filtro1,$filtro2) {
        $this->filtro1 = $filtro1;
        $this->filtro2 = $filtro2;
    }

    public function queryMasivaFiltro($filtro1,$filtro2)
    {   
        
        try {
            $query = DB::select("
                SELECT * FROM (
                SELECT zn1. jefatura,h.estado AS estadog,
                b.codnod AS nodo,b.nroplano AS troba ,coff.cancli,coff.umbral,coff.offline, p.estado AS trabajo_estado,
                DATEDIFF(NOW(),CONCAT(SUBSTR(b.fecreg,7,4),'-',SUBSTR(b.fecreg,4,2),'-',SUBSTR(b.fecreg,1,2),' ',SUBSTR(b.fecreg,12,8))) AS dias,
                b.codreqmnt AS codmasiva,CONCAT(STR_TO_DATE(fecreg, '%d/%m/%Y'),' ',TRIM((SUBSTR(fecreg,11,6))))  AS fecha_hora,coff.Caida AS estado,
                TIMEDIFF(NOW(),CONCAT(STR_TO_DATE(fecreg, '%d/%m/%Y'),' ',TRIM((SUBSTR(fecreg,11,6))))) AS tiempo,c.cant AS cant1, 
                CONCAT(c.eventid,' ',c.usuario,'-',c.fecha_inicio) AS eventid, COUNT(cr.nodo) AS ncrit,c.cant AS cantl,
                d.tipo,'' AS fecha_fin, IF(e.caidas >3 OR d.numbor>1000,'CRITICA','') AS tcaidas,e.caidas AS ncaidas,d.numbor, dg.fecha AS fecha_digi,
                IF(dg.nodo IS NOT NULL ,'Digitalizado','') AS digi,IF(tr.critica=1,'TC','') AS tc,IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,rpv.cpend,rm.remedy
                FROM  dbpext.masivas_temp b
                LEFT JOIN alertasx.alertas_dmpe_view c ON b.codnod=c.nodo AND b.nroplano=c.troba
                LEFT JOIN
                (SELECT f.* FROM alertasx.`gestion_alert` f INNER JOIN
                (SELECT nodo,troba,MAX(fechahora) AS fechahora FROM alertasx.`gestion_alert` WHERE DATEDIFF(NOW(),fechahora)=0 GROUP BY nodo,troba) g
                ON f.nodo=g.nodo AND f.troba=g.troba AND f.fechahora=g.fechahora) h ON b.codnod=h.nodo AND b.nroplano = h.troba
                LEFT JOIN catalogos.bornesxtroba d ON b.codnod=d.nodo AND b.nroplano=d.troba
                LEFT JOIN ccm1_temporal.cant_caidas e ON b.codnod=e.nodo AND b.nroplano=e.troba
                LEFT JOIN catalogos.trobas_digi_view dg ON b.codnod=dg.nodo AND b.nroplano=dg.troba
                LEFT JOIN catalogos.jefaturas zn1 ON zn1.`NODO`=b.`codnod`
                LEFT JOIN alertasx.offline_total coff ON b.codnod=coff.nodo AND b.nroplano=coff.troba
                LEFT JOIN catalogos.trobas_criticas_n tr ON b.codnod=tr.nodo AND b.nroplano=tr.troba
                LEFT JOIN catalogos.microzonas mz ON b.codnod=mz.nodo AND b.nroplano=mz.troba
                LEFT JOIN catalogos.db_fuentes fp ON b.codnod=fp.nodo AND b.nroplano=fp.troba
                LEFT JOIN cms.req_pend_view rpv ON b.codnod=rpv.nodo AND b.nroplano=rpv.troba
                LEFT JOIN alertasx.remedys_hfc  rm ON b.codnod=rm.nodo AND b.nroplano=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2
                LEFT JOIN reportes.criticos cr ON b.codnod=cr.nodo AND b.nroplano=cr.troba
                LEFT JOIN dbpext.trabajos_programados_noc p ON b.codnod=p.nodo AND b.nroplano=p.troba AND p.estado='ENPROCESO'
                WHERE b.codreqmnt>0 $filtro1 GROUP BY 1,2,3,4 ) am $filtro2
                ORDER BY cpend DESC"
            );

            $newData = array();

            foreach ($query as $q) {

                if ($q->ncrit > 0) {
                    $estado["critica"] = "SI";
                }else{
                    $estado["critica"] = "NO";
                }

                $newData[] =  
                (object)array(
                    'JEFATURA' => $q->jefatura,
                    'CRITICA' => $estado["critica"],
                    'NODO' => $q->nodo,
                    'TROBA' => $q->troba,
                    'ESTADO_TRABAJO' => $q->trabajo_estado,
                    'AVERIAS' => $q->cpend,
                    'CALL' => $q->cantl,
                    'TICKET_DMPE' => $q->eventid,
                    'CLIENTES' => $q->cancli,
                    'UMBRAL' => $q->umbral,
                    'OFFLINE > 80' => $q->offline,
                    'REMEDY' => $q->remedy,
                    'CODMASIVA' => $q->codmasiva,
                    'FECHA_INI' => $q->fecha_hora,
                    'TIEMPO' => $q->tiempo,
                    'ESTADO' => $q->estadog,
                    'ENERGIA' => $q->fuente

                );

            }

            return collect($newData);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }


    public function collection()
    {
        return collect($this->queryMasivaFiltro($this->filtro1,$this->filtro2));
    }


    public function headings(): array
    {
        $cabecera = array('JEFATURA','CRITICA','NODO','TROBA','ESTADO_TRABAJO','AVERIAS','CALL',
                            'TICKET_DMPE','CLIENTES','UMBRAL','OFFLINE > 80','REMEDY','CODMASIVA',
                            'FECHA_INI','TIEMPO','ESTADO','ENERGIA');

        return $cabecera;
    }
    
    


}

?>