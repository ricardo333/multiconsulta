<?php

namespace App\Reportes\Excel\IngresoAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;

class IngresoAveriasMesExcel extends GeneralController {

    static public function queryReporteAveriaMesExcel__inicial($mes,$jefatura,$troba)
    {

        try {
            
            $query = DB::select("
                                SELECT codofcadm as CODOFCADM,codreq as CODREQ,codclasrv as CODCLASRV,tipreqini as TIPREQINI,destipreqini as DESTIPREQINI,fec_mov as FEC_MOV,codestado as CODESTADO,codcli as CODCLI,codnod as CODNOD,nroplano as NROPLANO,dia_mov as DIA_MOV,hora_mov as HORA_MOV,codmotv as CODMOTV,desmotv as DESMOTV,tipreqfin as TIPREQFIN,destipreqfin as DESTIPREQFIN,
                                replace(desobsordtrab,';',' ') as DESOBSORDTRAB,
                                replace(desobsordtrab_2,';',' ') as DESOBSORDTRAB_2
                                ,canttroba as CANTTROBA,tipodeingreso as TIPODEINGRESO,tipodeliquidacion as TIPODELIQUIDACION
                                from ccm1.averias_m1_new a
                                where left(dia_mov,7)= '".$mes."' group by a.codreq
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    static public function queryReporteAveriaMesExcel($mes,$jefatura,$troba)
    {

        try {
            
            $query = DB::select("
                                SELECT codofcadm as CODOFCADM,codreq as CODREQ,codclasrv as CODCLASRV,tipreqini as TIPREQINI,destipreqini as DESTIPREQINI,fec_mov as FEC_MOV,codestado as CODESTADO,codcli as CODCLI,codnod as CODNOD,nroplano as NROPLANO,dia_mov as DIA_MOV,hora_mov as HORA_MOV,codmotv as CODMOTV,desmotv as DESMOTV,tipreqfin as TIPREQFIN,destipreqfin as DESTIPREQFIN,
                                replace(desobsordtrab,';',' ') as DESOBSORDTRAB,
                                replace(desobsordtrab_2,';',' ') as DESOBSORDTRAB_2
                                ,canttroba as CANTTROBA,tipodeingreso as TIPODEINGRESO,tipodeliquidacion as TIPODELIQUIDACION
                                FROM ccm1.averias_m1_new a
                                LEFT JOIN catalogos.jefaturas j ON a.codnod = j.nodo
                                WHERE LEFT(dia_mov,7)= '".$mes."'
                                ".$jefatura." ".$troba."
                                GROUP BY a.codreq 
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

}

?>