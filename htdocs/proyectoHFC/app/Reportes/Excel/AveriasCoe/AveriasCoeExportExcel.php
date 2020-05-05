<?php

namespace App\Reportes\Excel\AveriasCoe;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AveriasCoeExportExcel extends GeneralController {

    static public function queryReporteAveriasMUno($nodo,$troba)
    {
        
        try {
            
            $query = DB::select(" 
                                SELECT 
                                codofcadm,codreq,codclasrv,tipreqini,destipreqini,fec_mov,
                                codestado,codcli,codnod,nroplano,dia_mov,hora_mov,codmotv,
                                desmotv,tipreqfin,destipreqfin,desobsordtrab,desobsordtrab_2,
                                canttroba,tipodeingreso,tipodeliquidacion 
                                FROM ccm1.`averias_m1_new` avn
                                WHERE avn.`codnod`='$nodo' AND avn.`nroplano`='$troba'
                                AND DATEDIFF(NOW(),avn.`fec_mov`)<7
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

}

?>