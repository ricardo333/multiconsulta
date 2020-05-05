<?php

namespace App\Reportes\Excel\MonitorAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MonitorAveriasExcelEstadoM extends GeneralController implements FromCollection,WithHeadings {

    public function collection()
    {
        try {
            $query = DB::select("
                    SELECT a.codofcadm,a.codreq,a.codcli,a.codnod,a.nroplano,a.desmotv,a.codmotv,
                    tipreqfin,a.fec_registro,a.codedo 
                    FROM cms.req_pend_macro_final a 
                    LEFT JOIN dbpext.masivas_temp b ON a.codnod=b.codnod AND a.nroplano=b.nroplano
                    WHERE a.codedo='M' AND b.codnod IS NULL");

            $newData = array();

            foreach ($query as $q) {

                $newData[] =  
                (object)array(
                    'codofcadm'=> $q->codofcadm,
                    'codreq'=> $q->codreq,
                    'codcli'=> $q->codcli,
                    'nodo'=> $q->codnod,
                    'troba'=> $q->nroplano,
                    'desmotv'=> $q->desmotv,
                    'codmotv'=> $q->codmotv,
                    'tipreqfin'=> $q->tipreqfin,
                    'fec_registro'=> $q->fec_registro,
                    'codedo'=> $q->codedo

                    );

            }

            return collect($newData);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    
    public function headings(): array
    {
        $cabecera = array('codofcadm','codreq','codcli','nodo','troba','desmotv','codmotv',
                            'tipreqfin','fec_registro','codedo');

        return $cabecera;
    }


}



?>