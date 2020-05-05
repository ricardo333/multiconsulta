<?php

namespace App\Reportes\Excel\MonitorAverias;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

ini_set('memory_limit', '256M');

class MonitorAveriasExcelTotalGpon implements FromCollection,WithHeadings {

    public function collection()
    {
        try {
            $query = DB::select("
                SELECT a.jefatura,a.nodo,a.troba,a.cpend AS aver,a.consultas,a.ultreq,a.fec_registro,
                b.codcli,b.nomcli,b.codofcadm,b.codnod,b.nroplano,b.codlex,b.codtap,b.desobsordtrab,
                b.destipvia,b.desnomvia,b.numvia,b.despis,b.desint,b.desmzn,b.deslot,b.codmotv,b.desmotv,
                IF(p.troba IS NULL,'','PREMIUM') AS premium
                FROM alertasx.monitor_averias_gpon a
                LEFT JOIN cms.req_pend_macro_final b ON a.ultreq=b.codreq
                LEFT JOIN catalogos.premium_fases p ON CONCAT(a.nodo,a.troba)=p.troba
                WHERE a.jefatura IS NOT NULL
                GROUP BY a.nodo,a.troba ORDER BY a.cpend DESC"
            );

        $newData = array();

        foreach ($query as $q) {

                $newData[] =  
                (object)array(
                    'JEFATURA' => $q->jefatura,
                    'NODO1' => $q->nodo,
                    'TROBA1' => $q->troba,
                    'AVERIAS' => $q->aver,
                    'CONSULTAS' => $q->consultas,
                    'CODREQ' => $q->ultreq,
                    'FECREG' => $q->fec_registro,
                    'CODCLI' => $q->codcli,
                    'NOMCLI' => $q->nomcli,
                    'CODOF' => $q->codofcadm,
                    'NODO2' => $q->codnod,
                    'TROBA2' => $q->nroplano,
                    'AMPLIFICADOR' => $q->codlex,
                    'TAP' => $q->codtap,
                    'DESOBSORDTRAB' => $q->desobsordtrab,
                    'TIPODEVIA' => $q->destipvia,
                    'NOMBREDELAVIA' => $q->desnomvia,
                    'NUMERO' => $q->numvia,
                    'PISO' => $q->despis,
                    'INTERIOR' => $q->desint,
                    'MANZANA' => $q->desmzn,
                    'LOTE' => $q->deslot,
                    'CODMOTV2' => $q->codmotv,
                    'DESMOTV' => $q->desmotv,
                    'PREMIUM' => $q->premium
                );

            }

            return collect($newData);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }


    public function headings(): array
    {
        $cabecera = array('JEFATURA','NODO1','TROBA1','AVERIAS','CONSULTAS','CODREQ','FECREG','CODCLI',
        'NOMCLI','CODOF','NODO2','TROBA2','AMPLIFICADOR','TAP','DESOBSORDTRAB','TIPODEVIA','NOMBREDELAVIA',
        'NUMERO','PISO','INTERIOR','MANZANA','LOTE','CODMOTV2','DESMOTV','PREMIUM');

        return $cabecera;
    }
    
    
}

?>