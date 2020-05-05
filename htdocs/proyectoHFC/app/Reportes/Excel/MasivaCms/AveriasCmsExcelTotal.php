<?php

namespace App\Reportes\Excel\MasivaCms;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

ini_set('memory_limit', '500M');
ini_set('max_execution_time', 1000);

class AveriasCmsExcelTotal extends GeneralController implements FromCollection,WithHeadings {

    public function collection()
    {

        try {
            $query = DB::select("
            SELECT a.codofcadm,a.codreq,CONCAT(fec_registro,' ',SUBSTR(fec_regist,12,5)) AS fecreg, 
            DATEDIFF(NOW(),CONCAT(fec_registro,' ',SUBSTR(fec_regist,12,5))) AS dias,a.codedo AS estado, 
            a.codcli,a.codctr,a.desnomctr,a.codnod,a.nroplano,nc.nodo,nc.troba,a.codlex,a.codtap, 
            a.desmotv,a.tipreqini AS codigotiporeq, 
            REPLACE(REPLACE(REPLACE(a.desobsordtrab,',',' '),';',' '),' ','') AS desc_motivo, 
            a.destipvia AS tipodevia,REPLACE(a.desnomvia,',',' ') AS nombredelavia, 
            a.numvia AS numero,REPLACE(a.despis ,',','') AS piso,a.desint AS interior, 
            REPLACE(a.desmzn,',','') AS manzana,REPLACE(a.deslot,',','') AS lote, 
            IF( desobsordtrab LIKE '%RECIBE MENOS%' OR desobsordtrab LIKE '%LENIT%' OR desobsordtrab LIKE '%VEL%' OR desobsordtrab LIKE '%LENTITUD%' OR desobsordtrab LIKE '%-LENT-%' OR desobsordtrab LIKE '%LENT%' OR desobsordtrab LIKE '%PAQUETES%' ,'LENTITUD', 
            IF(desobsordtrab LIKE '%LA LINEA%' OR desobsordtrab LIKE '%VOIP%' OR desobsordtrab LIKE '%SIN LIN%','VOIP', 
            IF(desobsordtrab LIKE '%MALA POTENCIA%','MALA TRANSFERENCIA', 
            IF(desobsordtrab LIKE '%TRABAJOS PR%','TRABAJOS PROGRAMADOS', 
            IF(desobsordtrab LIKE '%MEDIA%NETWORK%' OR desobsordtrab LIKE '%CABECERA%' OR desobsordtrab LIKE '%EASY DIGITAL%','MASIVO DECODER', 
            IF(desobsordtrab LIKE '%DIGITALIZA%' OR desobsordtrab LIKE '%ZONA DIGI%','DIGITALIZACION', 
            IF(desobsordtrab LIKE '%PUERTO%SATURADO%','SATURACION', 
            IF(desobsordtrab LIKE '%MALA TRANSFERENCIA%','MALA TRANSFERENCIA', 
            IF( desobsordtrab LIKE '%MALOS PARAMETROS%' OR desobsordtrab LIKE '%PARAMETROS INESTABLES%' OR desobsordtrab LIKE '%PARAMETROS ROJO%' ,'MALOS PARAMETROS', 
            IF( desobsordtrab LIKE '%CTRL%' OR desobsordtrab LIKE '%CONTROL%' OR desobsordtrab LIKE '%REMOTO%' ,'CONTROL REMOTO', 
            IF( codmotv='R102' OR desobsordtrab LIKE '%DVR%' OR desobsordtrab LIKE '%TARJETA%' OR desobsordtrab LIKE '%DISCO%' OR desobsordtrab LIKE '%DECO%' OR desobsordtrab LIKE '%DCO%','DECODER', 
            IF( desobsordtrab LIKE '%WI.FI%' OR desobsordtrab LIKE '%WI-FI%' OR desobsordtrab LIKE '%WI FI%' OR desobsordtrab LIKE '%WIFI%' OR desobsordtrab LIKE '%WIREL%' ,'WIFI', 
            IF( desobsordtrab LIKE '%ENGANCHA%' OR desobsordtrab LIKE '%SPPEDY%' OR desobsordtrab LIKE '%SPEEDY%' OR desobsordtrab LIKE '%NO NAV%' OR desobsordtrab LIKE '%NO NVG%' OR desobsordtrab LIKE '%ON.LINE%' OR desobsordtrab LIKE '%SIN POTENCIA%' OR desobsordtrab LIKE '%DOCSIS%' OR desobsordtrab LIKE '%SIN REVERSA%' OR desobsordtrab LIKE '%ON-LINE%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%REDY%' OR desobsordtrab LIKE '% IP %' OR desobsordtrab LIKE '%OFF%' OR desobsordtrab LIKE '%ON LINE%' OR desobsordtrab LIKE '%ONLINE%' OR desobsordtrab LIKE '%ROUTER%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%HFC%' OR desobsordtrab LIKE '%NAVEGA%' OR desobsordtrab LIKE '%MODEM%' OR desobsordtrab LIKE '%MODEN%' OR desobsordtrab LIKE '%PROBLEMA CN%' OR desobsordtrab LIKE '%MOVISTAR 1%' OR desobsordtrab LIKE '%M1%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%INTRA%' OR desobsordtrab LIKE '%NAVEG%' OR desobsordtrab LIKE '%INTER%' OR desobsordtrab LIKE '%SINCRO%','No Navega', 
            IF( codmotv IN ('R040','R041','R042','R002','R001') OR desobsordtrab LIKE '%NO VISUALIZA%' OR desobsordtrab LIKE '%LLUVIA%' OR desobsordtrab LIKE '%LLUVIOSA%' OR desobsordtrab LIKE '%TV%' OR desobsordtrab LIKE '%AB MALASE%' OR desobsordtrab LIKE '%NO TIENE SE%' OR desobsordtrab LIKE '%AB SN SE%' OR desobsordtrab LIKE '%PIXELE%' OR desobsordtrab LIKE '%PROBLEMAS DE CONEX%' OR desobsordtrab LIKE '%AB SINSE%' OR desobsordtrab LIKE '%NO MUESTRA%' OR desobsordtrab LIKE '%NO PUEDE VER%' OR desobsordtrab LIKE '%CABLE%' OR desobsordtrab LIKE '%NO CUENTA CON SER%' OR desobsordtrab LIKE '%CATV%' OR desobsordtrab LIKE '%CATV-SEN%' OR desobsordtrab LIKE '%PROBL. CON SE%' OR desobsordtrab LIKE '%CANALES%' OR desobsordtrab LIKE '%CORTES%' OR desobsordtrab LIKE '%DADES TEC%' OR desobsordtrab = 'AVERIA APC' OR desobsordtrab = 'AB SIN SE' OR desobsordtrab LIKE '%SIN SE%' OR desobsordtrab LIKE '%SIN SE%' OR desobsordtrab LIKE '%RUIDO Y BAJO REN%' OR desobsordtrab LIKE '%RUIDO EN SEG%' OR desobsordtrab LIKE '%SIN SE%' OR desobsordtrab LIKE '%PIXELEADA%' OR desobsordtrab LIKE '%POTENC%' OR desobsordtrab LIKE '%PEXT%' OR desobsordtrab LIKE '%SNR%' OR desobsordtrab LIKE '%MALA SE%' OR desobsordtrab LIKE '%AUDIO%' ,'MALA SENAL/SIN SENAL', 
            IF( codmotv='R038','AVERIA DTH', IF( codmotv='R103','TV DESPROGRAMADO', IF( desobsordtrab LIKE '%CALIDAD%' ,'DATOS', 'OTROS'))))))))))))))))) AS tip_ing , 
            IF(c.macaddress IS NOT NULL,c.cmts,IF(b.MACState IS NOT NULL,b.cmts,'')) AS cmts, 
            IF(b.MACState ='offline',b.interface,c.interface) AS interface,nc.scopesgroup,
            IF(c.macaddress IS NOT NULL,'online',IF(b.MACState IS NOT NULL,b.MACState,'')) AS macstate, 
            IF(b.MACState <>'offline',b.RxPwrdBmv,' ') AS RxPwrdBmv, 
            IF(b.MACState <>'offline',c.USPwr,' ') AS USPwr, 
            IF(b.MACState <>'offline',c.USMER_SNR,' ') AS USMER_SNR, 
            IF(b.MACState <>'offline',c.DSPwr,' ') AS DSPwr, 
            IF(b.MACState <>'offline',c.DSMER_SNR,' ') AS DSMER_SNR, 
            IF(m.codreqmnt>0 , 'Averia Masiva - Problema PEXT',
            IF(e.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)'  OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)'),'Caida Modems - Problema PEXT(Amplif)',
            IF(f.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)'  OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)'),'Caida Modems - Problema PEXT',
            IF(g.Caida='SI','Niveles RF  Malos - Problema PEXT',
            IF(b.macstate='offline','Offline - NO OK',
            IF(c.USMER_SNR * 1 < 27 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USMER_SNR * 1 < 27 AND c.USPwr * 1<36 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USPwr * 1 < 36 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USPwr * 1 > 55 AND c.DSPwr > - 5 AND c.DSPwr < 10 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.DSPwr * 1 > 10 AND c.USPwr * 1 < 36  ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USPwr * 1 < 35 AND c.USPwr * 1 > 0 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USPwr * 1 < 35 AND c.DSPwr * 1 > 10 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.DSPwr * 1 > 15 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USPwr * 1 > 55 AND c.DSPwr > 10 ,'Niveles RF  Malos - Problema PEXT',
            IF(c.USMER_SNR * 1 < 30 AND c.DSPwr * 1 > 10 ,'Niveles RF  Malos - Problema PEXT',
            IF(b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)' OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)','Problema Red Cliente',
            IF(c.DSPwr < - 5 AND c.USPwr > 55,'Niveles RF  Malos - Problema Red Cliente',
            IF(c.DSPwr < - 5 OR c.DSPwr > 10,'Niveles RF  Malos - Problema Red Cliente',
            IF(c.DSPwr < - 5 AND c.DSMER_SNR < 30 ,'Niveles RF  Malos - Problema Red Cliente',
            IF(c.USPwr>53* 1 AND c.USPwr * 1 <= 58 AND c.USMER_SNR * 1 >= 30  AND c.DSPwr > - 5 AND c.DSPwr < 12,'Niveles RF  Malos - Problema Red Cliente',
            IF((c.DSMER_SNR IS NULL OR TRIM(c.DSMER_SNR) IN ('-----','-',' '))   AND (b.macstate IN  ('online','w-online') OR b.macstate IS NULL),'Incierto - Validar',
            IF(c.DSPwr='' AND c.DSMER_SNR='' AND b.macstate = '','Modem no registrado en CMTS',
            IF(b.MACState IN ('init','init(t)','init(r2)','init(r1)'),'Proceso de sincronismo - Volver a Validar',
            IF(b.MACState IN ('init(d)','DHCP','init(o)'),'Posible Problema con el DHCP' ,
            'OK')))))))))))))))))))))))) AS estadomdm,
            IF(pr.troba IS NOT NULL,'PREMIUM','MASIVO') AS premium,
            IF(mt.clientecms IS NULL,'','MOVISTAR TOTAL') AS movistar_total,codmotv,desmotv,desmotv as motivo1,
            IF(m.codnod IS NULL,'Individual','Masiva') AS masiva, 
            IF(nc.estado='Activo','Servicio Activo', IF(nc.estado='Inactivo','Servicio Suspendido','')) AS edoserv ,
            zo.jefatura AS zonal,nc.macaddress
            FROM cms.req_pend_macro_final a 
            LEFT JOIN multiconsulta.nclientes nc ON a.codcli=nc.idclientecrm 
            LEFT JOIN ccm1.scm_total b ON nc.mac2=b.MACAddress 
            LEFT JOIN ccm1.scm_phy_t c ON nc.mac2=c.MACAddress 
            LEFT JOIN catalogos.jefaturas zo ON a.codnod=zo.nodo 
            LEFT JOIN catalogos.premium pr ON CONCAT(nc.nodo,nc.troba)=pr.troba 
            LEFT JOIN catalogos.movistar_total mt ON a.codcli=mt.clientecms 
            LEFT JOIN dbpext.masivas_temp m ON a.codnod = m.codnod AND a.nroplano=m.nroplano 
            LEFT JOIN alertasx.caidas_new_amplif e ON nc.nodo=e.nodo AND nc.troba=e.troba AND nc.amplificador=e.amplificador AND e.Caida='SI'
            LEFT JOIN alertasx.caidas_new f ON nc.nodo=f.nodo AND nc.troba=f.troba AND f.Caida='SI'
            LEFT JOIN alertasx.niveles_new g  ON nc.nodo=g.nodo AND nc.troba=g.troba AND g.Caida='SI'
            GROUP BY a.codreq"
            );

            $newData = array();

            foreach ($query as $q) {

                $newData[] =  
                (object)array(
                    'CODOFCADM' => $q->codofcadm,
                    'CODREQ' => $q->codreq,
                    'FECREG' => $q->fecreg,
                    'DIAS' => $q->dias,
                    'ESTADO' => $q->estado,
                    'CODCLI' => $q->codcli,
                    'CODCTR' => $q->codctr,
                    'DESNOMCTR' => $q->desnomctr,
                    'NODO_CMS' => $q->codnod,
                    'TROBA_CMS' => $q->nroplano,
                    'NODO_HFC' => $q->nodo,
                    'TROBA_HFC' => $q->troba,
                    'AMPLIFICADOR' => $q->codlex,
                    'TAP' => $q->codtap,
                    'CODMOTV1' => $q->motivo1,
                    'TIPREQFIN' => $q->codigotiporeq,
                    'DESOBSORDTRAB' => $q->desc_motivo,
                    'TIPODEVIA' => $q->tipodevia,
                    'NOMBREDELAVIA' => $q->nombredelavia,
                    'NUMERO' => $q->numero,
                    'PISO' => $q->piso,
                    'INTERIOR' => $q->interior,
                    'MANZANA' => $q->manzana,
                    'LOTE' => $q->lote,
                    'TIP_ING' => $q->tip_ing,
                    'cmts' => $q->cmts,
                    'interface' => $q->interface,
                    'scopesgroup' => $q->scopesgroup,
                    'macstate' => $q->macstate,
                    'RxPwrdBmv' => $q->RxPwrdBmv,
                    'USPwr' => $q->USPwr,
                    'USMER_SNR' => $q->USMER_SNR,
                    'DSPwr' => $q->DSPwr,
                    'DSMER_SNR' => $q->DSMER_SNR,
                    'EstadoMDM' => $q->estadomdm,
                    'PREMIUM' => $q->premium,
                    'MOVISTAR_TOTAL' => $q->movistar_total,
                    'CODMOTV' => $q->codmotv,
                    'DESMOTV' => $q->desmotv,
                    'MASIVA' => $q->masiva,
                    'EDOSERV' => $q->edoserv,
                    'ZONAL' => $q->zonal,
                    'MACADDRESS' => $q->macaddress


                    /*
                    'CODOFCADM' => $q->codofcadm,
                    'CODREQ' => $q->codreq,
                    'FECREG' => $q->fecreg,
                    'DIAS' => $q->dias,
                    'ESTADO' => $q->estado,
                    'CODCLI' => $q->codcli,
                    'CODCTR' => $q->codctr,
                    'DESNOMCTR' => $q->desnomctr,
                    'NODO_CMS' => $q->codnod,
                    'TROBA_CMS' => $q->nroplano,
                    'NODO_HFC' => $q->nodo,
                    'TROBA_HFC' => $q->troba,
                    'AMPLIFICADOR' => $q->codlex,
                    'TAP' => $q->codtap,
                    'CODMOTV1' => $q->motivo1,
                    'TIPREQFIN' => $q->codigotiporeq,
                    'DESOBSORDTRAB' => $q->desc_motivo,
                    'TIPODEVIA' => $q->tipodevia,
                    'NOMBREDELAVIA' => $q->nombredelavia,
                    'NUMERO' => $q->numero,
                    'PISO' => $q->piso,
                    'INTERIOR' => $q->interior,
                    'MANZANA' => $q->manzana,
                    'LOTE' => $q->lote,
                    'TIP_ING' => $q->tip_ing,
                    'cmts' => $q->cmts,
                    'interface' => $q->interface,
                    'scopesgroup' => $q->scopesgroup,
                    'macstate' => $q->macstate,
                    'RxPwrdBmv' => $q->RxPwrdBmv,
                    'USPwr' => $q->USPwr,
                    'USMER_SNR' => $q->USMER_SNR,
                    'DSPwr' => $q->DSPwr,
                    'DSMER_SNR' => $q->DSMER_SNR,
                    'EstadoMDM' => $estado["mensaje"],
                    'PREMIUM' => $q->premium,
                    'MOVISTAR_TOTAL' => $q->convergente,
                    'CODMOTV2' => $q->codmotv,
                    'DESMOTV' => $q->desmotv,
                    'MASIVA' => $q->masiva,
                    'EDOSERV' => $q->edoserv,
                    'ZONAL' => $q->zonal,
                    'MACADDRESS' => $q->macaddress
                    */
                );

            }

            return collect($newData);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function headings(): array
    {
        $cabecera = array('CODOFCADM','CODREQ','FECREG','DIAS','ESTADO','CODCLI','CODCTR','DESNOMCTR',
                        'NODO_CMS','TROBA_CMS','NODO_HFC','TROBA_HFC','AMPLIFICADOR','TAP','CODMOTV',
                        'TIPREQFIN','DESOBSORDTRAB','TIPODEVIA','NOMBREDELAVIA','NUMERO','PISO',
                        'INTERIOR','MANZANA','LOTE','TIP_ING','cmts','interface','scopesgroup',
                        'macstate','RxPwrdBmv','USPwr','USMER_SNR','DSPwr','DSMER_SNR','EstadoMDM',
                        'PREMIUM','MOVISTAR_TOTAL','CODMOTV','DESMOTV','MASIVA','EDOSERV','ZONAL',
                        'MACADDRESS');

        return $cabecera;
    }
    
    


}

?>