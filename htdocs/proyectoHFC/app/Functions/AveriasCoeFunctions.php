<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;
 
  
class AveriasCoeFunctions {

    function getListaCoe($filtroJefatura,$filtroTroba,$filtroEstado)
    {

        try {
            
            $lista = DB::select("SELECT 
                                        jy.*,tg.EstadoDelCaso,tg.fechaRegistro,tg.observacionResultado,
                                        tg.observacionVisitaTecnica,tg.usuario 
                                    FROM 
                                        (                                    
                                        SELECT 
                                                nc.idproducto,nc.idservicio,nc.idventa,
                                                ar.zonal,ar.codreq,ar.codcli,ar.tip_ing,ar.estadomdm,ar.area,
                                                ar.nodocms,ar.trobacms,ar.nodohfc,ar.trobahfc,ar.amplificador,
                                                ar.codctr,ar.desnomctr,ar.cmts
                                                ,IF(scmp.Interface IS NOT NULL,TRIM(scmp.Interface),TRIM(scmt.Interface)) AS interface
                                                ,nc.SCOPESGROUP as scopesgroup,
                                                ar.masiva,ar.macaddress,ar.fecreg,ar.codctr_final,ar.area_final,
                                                ar.ultimagestion,ar.TipoRuido,ar.observacionescms,ar.motivotransferencia,
                                                ar.telef1,ar.telef2,ar.telef3,
                                                IF(scmt.MACState LIKE '%nline%','online',IF(scmt.macstate IS NULL,IF(scmp.`MACAddress` IS NULL,'','online'),scmt.macstate)) AS MACState,
                                                scmt.IPAddress
                                                ,scmp.USPwr,scmp.USMER_SNR,
                                                scmp.DSPwr,scmp.DSMER_SNR
                                                ,rpm.codsrv
                                                ,n.SERVICEPACKAGECRMID AS SERVICEPACKAGECRMID
                                                ,f.Fabricante, f.Modelo,
                                                f.Versioon AS Version_firmware,
                                                IF(mt.clientecms IS NULL ,0,1) AS msjtot,
                                                gt.fechaMaxima AS fechaMaximaGestion,
                                                gt.mac AS macGestion
                                                ,ad.cantidadTotalLlamadas as callDmpeTotal,ad.fechaMaximaLlamada as fechaUltimallamada
                                                ,av.cantidadTotalAveria as averiasTotal, av.fechaMaximaAveria as fechaUltimaAveria
                                        FROM triaje.averias_revisadas ar FORCE INDEX(macaddress,codcli)
                                        INNER JOIN multiconsulta.`nclientes` nc FORCE INDEX(NewIndex3,NewIndex4)
                                            ON ar.macaddress = nc.MACADDRESS 
                                        LEFT JOIN catalogos.velocidades_cambios n 
                                            ON nc.SERVICEPACKAGE=n.SERVICEPACKAGE
                                        LEFT JOIN ccm1_data.marca_modelo_docsis_total f  
                                            ON nc.MACADDRESS=f.MACAddress
                                        LEFT JOIN ccm1.scm_total scmt FORCE INDEX(MACAddress) 
                                            ON  nc.mac2 = scmt.MACAddress
                                        LEFT JOIN ccm1.scm_phy_t scmp FORCE INDEX(NewIndex1)
                                            ON nc.mac2 =  scmp.MACAddress 
                                        LEFT JOIN cms.req_pend_macro_final rpm FORCE INDEX(codcli) 
                                            ON ar.codcli = rpm.codcli 
                                        LEFT JOIN catalogos.movistar_total  mt FORCE INDEX(clientecms)
                                            ON ar.codcli = mt.clientecms
                                        LEFT JOIN (
                                            SELECT nodo,troba,COUNT(*)AS cantidadTotalLlamadas,
                                            MAX(ltb.fechahora) AS fechaMaximaLlamada
                                            FROM alertasx.`alertas_dmpe` ltb 
                                            WHERE  DATEDIFF(NOW(),ltb.fechahora) < 7  
                                            GROUP BY nodo,troba
                                        ) ad 
                                            ON nc.nodo=ad.nodo and nc.troba=ad.troba
                                        LEFT JOIN (
                                                SELECT amn.`codnod`,amn.`nroplano`,COUNT(*) AS cantidadTotalAveria,
                                                    MAX(amn.`fec_mov`) AS fechaMaximaAveria
                                                FROM ccm1.`averias_m1_new` amn 
                                                WHERE  DATEDIFF(NOW(),amn.`fec_mov`) < 7  
                                                GROUP BY 1,2
                                            ) av 
                                                ON nc.nodo=av.codnod and nc.troba=av.nroplano
                                        LEFT JOIN ( 
                                                    SELECT  mac,MAX(fechaRegistro) AS fechaMaxima FROM  triaje.`gestion_triaje` 
                                                    GROUP BY 1
                                                    ) gt 
                                            ON nc.MACADDRESS = gt.mac 
                                        WHERE ar.macaddress IS NOT NULL
                                           # and ar.dias <= 7
                                            $filtroJefatura   
                                            $filtroTroba 
                                    ) jy
                                    LEFT JOIN  triaje.`gestion_triaje` tg FORCE INDEX (macAddressIndex,fechaRegistroIndex)
                                            ON jy.macGestion = tg.mac AND jy.fechaMaximaGestion = tg.fechaRegistro
                                    WHERE 1=1 $filtroEstado 
                                    limit 150");
            return $lista;

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       } catch(\Exception $e){
            //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
       } 
       
    }

    function getDataClientByIn($queryCodigosReq)
    {

        try {
            $lista = DB::select("select ar.*,rpm.codsrv from 
                                triaje.averias_revisadas ar FORCE INDEX(macaddress,codcli)
                                LEFT JOIN cms.req_pend_macro_final rpm FORCE INDEX(codcli) 
                                ON ar.codcli = rpm.codcli 
                                where ar.codreq in $queryCodigosReq");

            return $lista;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       } catch(\Exception $e){
            //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
       } 
      
    }

    function registroGestionCoe($queryInsert)
    {
        try {
            DB::insert($queryInsert);
             return "true";
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       } catch(\Exception $e){
             //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
       } 

    }

    function getDetailsByClienteCode($cliente)
    {
        try {
           $lista = DB::select("SELECT * FROM triaje.`gestion_triaje` tg WHERE tg.`codigoCliente`= ?",[$cliente]);
            return $lista;
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       } catch(\Exception $e){
             //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
       } 
    }
 
}