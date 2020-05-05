<?php

namespace App\Reportes\Excel\IngresoAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
 
class IngresoAveriasResumenIngresos extends GeneralController {

    static public function queryReporteAveriaResumenIngresos__inicial($motivo)
    {
        
        try {
          
            $query = DB::select("
                                SELECT a.codofcadm as CODOFCADM,a.codreq as CODREQ,a.codclasrv as CODCLASRV,a.tipreqini as TIPREQINI,a.destipreqini as DESTIPREQINI,a.fec_mov as FEC_MOV,a.codestado as CODESTADO,a.codcli as CODCLI,a.codnod as CODNOD,a.nroplano as NROPLANO,
                                a.dia_mov as DIA_MOV,a.hora_mov as HORA_MOV,a.codmotv as CODMOTV,a.desmotv as DESMOTV,a.tipreqfin as TIPREQFIN,a.destipreqfin as DESTIPREQFIN,a.desobsordtrab as DESOBSORDTRAB,a.desobsordtrab_2 as DESOBSORDTRAB_2,a.canttroba as CANTTROBA,a.tipodeingreso as TIPODEINGRESO,a.tipodeliquidacion as TIPODELIQUIDACION,d.SCOPESGROUP as SCOPESGROUP,d.SERVICEPACKAGE as PAQUETE
                                ,IF(b.nodo IS NOT NULL,'Planta Modem OFF',IF(c.nodo IS NOT NULL,'Planta Problem Signal','Individual')) AS TIPO,
                                IF(f.MACState ='Offline' AND f.macaddress IS NULL ,'Offline - NO OK',
                                    IF(e.DSMER_SNR ='-----','Modem Sincronizado - Cmts no aun no lee niveles',
                                    IF(e.DSMER_SNR IS NULL AND f.macstate IN ('w-online','online','operational'),'Modem Sincronizado - No hay reporte de niveles - Validar Manualmente',
                                    IF(f.macstate IS NULL AND e.DSMER_SNR IS NULL,'Offline - NO OK',
                                    IF(f.macstate NOT IN ('w-online','online','operational','offline')  AND DSMER_SNR IS NULL,  CONCAT(f.macstate,'Modem no Sincronizado - No hay niveles no se puede validar'),
                                    IF(e.USPwr <35 OR e.USPwr >57 ,'Niveles NO OK',
                                    IF(e.USMER_SNR <25 AND e.USMER_SNR >0.00 ,'Niveles NO OK',
                                    IF(e.DSPwr <-10  OR e.DSPwr >12 ,'Niveles NO OK',
                                    IF(e.DSMER_SNR <27 ,'Niveles NO OK','OK'))))))))) AS STATUS,
                                    gg.codigo_req as CODIGO_REQ,gg.codigo_tipo_req as CODIGO_TIPO_REQ,gg.codigo_motivo_req as CODIGO_MOTIVO_REQ,hh.des_motivo as DES_MOTIVO,gg.fecha_liquidacion as FECHA_LIQUIDACION
                                FROM ccm1.averias_m1_new a LEFT JOIN alertasx.caidas_t b
                                ON a.codnod=b.nodo AND a.nroplano=b.troba AND DATEDIFF(NOW(),b.fecha_hora)=0
                                LEFT JOIN alertasx.niveles_new c 
                                ON a.codnod=c.nodo AND a.nroplano=c.troba AND DATEDIFF(NOW(),c.fecha_hora)=0
                                LEFT JOIN multiconsulta.nclientes d ON a.codcli = d.idclientecrm
                                LEFT JOIN ccm1.scm_phy_t e
                                    ON d.mac2=e.MACAddress 
                                LEFT JOIN ccm1.scm_total f
                                ON d.mac2=f.MACAddress 
                                left join cms.prov_liq_catv_pais gg
                                on a.codcli = gg.codigo_del_cliente
                                left join cms.cms_tiporeq_motivo hh
                                on gg.codigo_motivo_req=hh.motivo
                                    WHERE DATEDIFF(NOW(),a.fec_mov)=0 $motivo
                                group by a.codreq
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    static public function queryReporteAveriaResumenIngresos($motivo,$jefatura,$troba)
    {
        
        try {
          
            $query = DB::select("
                                SELECT a.codofcadm as CODOFCADM,a.codreq as CODREQ,a.codclasrv as CODCLASRV,a.tipreqini as TIPREQINI,a.destipreqini as DESTIPREQINI,a.fec_mov as FEC_MOV,a.codestado as CODESTADO,a.codcli as CODCLI,a.codnod as CODNOD,a.nroplano as NROPLANO,
                                a.dia_mov as DIA_MOV,a.hora_mov as HORA_MOV,a.codmotv as CODMOTV,a.desmotv as DESMOTV,a.tipreqfin as TIPREQFIN,a.destipreqfin as DESTIPREQFIN,a.desobsordtrab as DESOBSORDTRAB,a.desobsordtrab_2 as DESOBSORDTRAB_2,a.canttroba as CANTTROBA,a.tipodeingreso as TIPODEINGRESO,a.tipodeliquidacion as TIPODELIQUIDACION,d.SCOPESGROUP as SCOPESGROUP,d.SERVICEPACKAGE as PAQUETE
                                ,IF(b.nodo IS NOT NULL,'Planta Modem OFF',IF(c.nodo IS NOT NULL,'Planta Problem Signal','Individual')) AS TIPO,
                                IF(f.MACState ='Offline' AND f.macaddress IS NULL ,'Offline - NO OK',
                                    IF(e.DSMER_SNR ='-----','Modem Sincronizado - Cmts no aun no lee niveles',
                                    IF(e.DSMER_SNR IS NULL AND f.macstate IN ('w-online','online','operational'),'Modem Sincronizado - No hay reporte de niveles - Validar Manualmente',
                                    IF(f.macstate IS NULL AND e.DSMER_SNR IS NULL,'Offline - NO OK',
                                    IF(f.macstate NOT IN ('w-online','online','operational','offline')  AND DSMER_SNR IS NULL,  CONCAT(f.macstate,'Modem no Sincronizado - No hay niveles no se puede validar'),
                                    IF(e.USPwr <35 OR e.USPwr >57 ,'Niveles NO OK',
                                    IF(e.USMER_SNR <25 AND e.USMER_SNR >0.00 ,'Niveles NO OK',
                                    IF(e.DSPwr <-10  OR e.DSPwr >12 ,'Niveles NO OK',
                                    IF(e.DSMER_SNR <27 ,'Niveles NO OK','OK'))))))))) AS STATUS,
                                    gg.codigo_req as CODIGO_REQ,gg.codigo_tipo_req as CODIGO_TIPO_REQ,gg.codigo_motivo_req as CODIGO_MOTIVO_REQ,hh.des_motivo as DES_MOTIVO,gg.fecha_liquidacion as FECHA_LIQUIDACION
                                FROM ccm1.averias_m1_new a LEFT JOIN alertasx.caidas_t b
                                ON a.codnod=b.nodo AND a.nroplano=b.troba AND DATEDIFF(NOW(),b.fecha_hora)=0
                                LEFT JOIN alertasx.niveles_new c 
                                ON a.codnod=c.nodo AND a.nroplano=c.troba AND DATEDIFF(NOW(),c.fecha_hora)=0
                                LEFT JOIN multiconsulta.nclientes d ON a.codcli = d.idclientecrm
                                LEFT JOIN ccm1.scm_phy_t e
                                    ON d.mac2=e.MACAddress 
                                LEFT JOIN ccm1.scm_total f
                                ON d.mac2=f.MACAddress 
                                left join cms.prov_liq_catv_pais gg
                                on a.codcli = gg.codigo_del_cliente
                                left join cms.cms_tiporeq_motivo hh
                                on gg.codigo_motivo_req=hh.motivo

                                LEFT JOIN catalogos.jefaturas j ON a.codnod = j.nodo
                                WHERE DATEDIFF(NOW(),a.fec_mov)=0 ".$jefatura." ".$troba." $motivo

                                group by a.codreq
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

}

?>