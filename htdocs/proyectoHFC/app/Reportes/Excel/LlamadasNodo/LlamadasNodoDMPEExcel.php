<?php

namespace App\Reportes\Excel\LlamadasNodo;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;

class LlamadasNodoDMPEExcel extends GeneralController {

    static public function queryReporteLlamadaNodoDMPE($nodo,$troba)
    {
        
        try {
            
            $query = DB::select("
                                SELECT b.CLIENTE,b.SERVICIO,b.OFI_CLI,d.cmts as CMTS,d.f_v as INTERFACE,e.macstate as MACSTATE,
                                e.RxPwrdBmv as RXPWRDBMV,f.USPwr as USPWR,f.USMER_SNR,f.DSPwr AS DSPWR,f.DSMER_SNR,a.nodo,a.troba,b.CODLEX,
                                b.CODTAP,REPLACE(b.DIREC_INST,',','') AS DIREC_INST,b.NUMERODOC,b.TELEFCL1,b.TELEFCL2,b.TELEFCL3,
                                CONCAT(TRIM(b.NOMBRE),' ',TRIM(b.APE_PAT),' ',TRIM(b.APE_MAT)) AS NOMBRE ,fechahora as FECHAHORA 
                                FROM alertasx.alertas_dmpe a 
                                INNER JOIN cms.planta_clarita b ON a.cliente=b.cliente 
                                LEFT JOIN alertasx.caidas_t c ON b.nodo=c.nodo AND b.plano=c.troba AND c.Caida='SI' 
                                LEFT JOIN multiconsulta.nclientes d ON a.cliente=d.idclientecrm 
                                LEFT JOIN ccm1.scm_total e ON d.mac2=e.macaddress 
                                LEFT JOIN ccm1.scm_phy_t f ON d.mac2=f.macaddress 
                                WHERE DATEDIFF(NOW(),fechahora)=0 and a.nodo='".$nodo."' $troba
                                group by b.cliente
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

}

?>