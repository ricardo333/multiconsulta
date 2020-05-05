<?php

namespace App\Reportes\Excel;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelAveriasNodoTroba extends GeneralController implements FromCollection,WithHeadings {

    protected $nodo;
    protected $troba;

    function __construct($nodo,$troba) {
        $this->nodo = $nodo;
        $this->troba = $troba;
    }

    public function queryaveria($nodo,$troba){

        try {
            $query = DB::select("
                SELECT a.codofcadm AS CODOFCADM,a.codreq AS CODREQ,CONCAT(fec_registro,' ',SUBSTR(fec_regist,12,5)) AS FECREG,a.codedo AS ESTADO,
                a.codcli AS CODCLI,a.codctr AS CODCTR,a.desnomctr AS DESNOMCTR,a.codnod AS NODO_CMS,a.nroplano AS TROBA_CMS,nc.nodo AS NODO_HFC,
                nc.troba AS TROBA_HFC,a.codlex AS AMPLIFICADOR,a.codtap AS TAP,a.desmotv AS CODMOTV1,a.tipreqini AS TIPREQFIN,e.Caida AS Caida1,f.Caida AS Caida2,g.Caida AS Caida3,
                REPLACE(REPLACE(REPLACE(a.desobsordtrab,',',' '),';',' '),'  ','') AS DESOBSORDTRAB,a.destipvia AS TIPODEVIA,b.cmts AS cmts1,pc.tiptec AS tiptec1,nc.idclientecrm AS idcliente1,
                REPLACE(a.desnomvia,',',' ') AS NOMBREDELAVIA,a.numvia AS NUMERO,REPLACE(a.despis ,',','') AS PISO,a.desint AS INTERIOR,
                REPLACE(a.desmzn,',','') AS MANZANA,REPLACE(a.deslot,',','') AS LOTE,
                IF(desobsordtrab LIKE '%RECIBE MENOS%' OR desobsordtrab LIKE '%LENIT%' OR desobsordtrab LIKE '%VEL%'
                OR desobsordtrab LIKE '%LENTITUD%'  OR desobsordtrab LIKE '%-LENT-%' OR desobsordtrab LIKE '%LENT%' 
                OR desobsordtrab LIKE '%PAQUETES%' ,'LENTITUD',
                IF(desobsordtrab LIKE '%LA LINEA%' OR desobsordtrab LIKE '%VOIP%' OR desobsordtrab LIKE '%SIN LIN%','VOIP',
                IF(desobsordtrab LIKE '%MALA POTENCIA%','MALA TRANSFERENCIA',IF(desobsordtrab LIKE '%TRABAJOS PR%','TRABAJOS PROGRAMADOS',
                IF(desobsordtrab LIKE '%MEDIA%NETWORK%' OR desobsordtrab LIKE '%CABECERA%' OR desobsordtrab LIKE '%EASY DIGITAL%','MASIVO DECODER',
                IF(desobsordtrab LIKE '%DIGITALIZA%' OR desobsordtrab LIKE '%ZONA DIGI%','DIGITALIZACION',
                IF(desobsordtrab LIKE '%PUERTO%SATURADO%','SATURACION',IF(desobsordtrab LIKE '%MALA TRANSFERENCIA%','MALA TRANSFERENCIA',
                IF( desobsordtrab LIKE '%MALOS PARAMETROS%' OR desobsordtrab LIKE '%PARAMETROS INESTABLES%' OR desobsordtrab LIKE '%PARAMETROS ROJO%' ,'MALOS PARAMETROS',
                IF( desobsordtrab LIKE '%CTRL%' OR desobsordtrab LIKE '%CONTROL%' OR desobsordtrab LIKE '%REMOTO%' ,'CONTROL REMOTO',
                IF( codmotv='R102' OR desobsordtrab LIKE '%DVR%' OR desobsordtrab LIKE '%TARJETA%' OR desobsordtrab LIKE '%DISCO%' OR desobsordtrab LIKE '%DECO%'
                OR desobsordtrab LIKE '%DCO%','DECODER',
                IF( desobsordtrab LIKE '%WI.FI%' OR desobsordtrab LIKE '%WI-FI%' OR desobsordtrab LIKE '%WI FI%' OR desobsordtrab LIKE '%WIFI%'
                OR desobsordtrab LIKE '%WIREL%' ,'WIFI',
                IF( desobsordtrab LIKE '%ENGANCHA%' OR desobsordtrab LIKE '%SPPEDY%' OR desobsordtrab LIKE '%SPEEDY%' OR desobsordtrab LIKE '%NO NAV%' OR desobsordtrab LIKE '%NO NVG%'
                OR desobsordtrab LIKE '%ON.LINE%' OR desobsordtrab LIKE '%SIN POTENCIA%' OR desobsordtrab LIKE '%DOCSIS%' OR desobsordtrab LIKE '%SIN REVERSA%'
                OR desobsordtrab LIKE '%ON-LINE%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%REDY%' OR desobsordtrab LIKE '% IP %' OR desobsordtrab LIKE '%OFF%'
                OR desobsordtrab LIKE '%ON LINE%' OR desobsordtrab LIKE '%ONLINE%' OR desobsordtrab LIKE '%ROUTER%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%HFC%'
                OR desobsordtrab LIKE '%NAVEGA%' OR desobsordtrab LIKE '%MODEM%' OR desobsordtrab LIKE '%MODEN%' OR desobsordtrab LIKE '%PROBLEMA CN%'
                OR desobsordtrab LIKE '%MOVISTAR 1%' OR desobsordtrab LIKE '%M1%' OR desobsordtrab LIKE '%READY%' OR desobsordtrab LIKE '%INTRA%' OR desobsordtrab LIKE '%NAVEG%'
                OR desobsordtrab LIKE '%INTER%' OR desobsordtrab LIKE '%SINCRO%','No Navega',
                IF( codmotv IN ('R040','R041','R042','R002','R001') OR desobsordtrab LIKE '%NO VISUALIZA%' OR desobsordtrab LIKE '%LLUVIA%' OR desobsordtrab LIKE '%LLUVIOSA%'
                OR desobsordtrab LIKE '%TV%' OR desobsordtrab LIKE '%AB MALASE%' OR desobsordtrab LIKE '%NO TIENE SE%' OR desobsordtrab LIKE '%AB SN SE%'
                OR desobsordtrab LIKE '%PIXELE%' OR desobsordtrab LIKE '%PROBLEMAS DE CONEX%' OR desobsordtrab LIKE '%AB SINSE%' OR desobsordtrab LIKE '%NO MUESTRA%'
                OR desobsordtrab LIKE '%NO PUEDE VER%' OR desobsordtrab LIKE '%CABLE%' OR desobsordtrab LIKE '%NO CUENTA CON SER%' OR desobsordtrab LIKE '%CATV%'
                OR desobsordtrab LIKE '%CATV-SEN%' OR desobsordtrab LIKE '%PROBL. CON SE%' OR desobsordtrab LIKE '%CANALES%' OR desobsordtrab LIKE '%CORTES%'
                OR desobsordtrab LIKE '%DADES TEC%' OR desobsordtrab = 'AVERIA APC' OR desobsordtrab = 'AB SIN SE' OR desobsordtrab LIKE '%SIN SE%' OR desobsordtrab LIKE '%SIN SE%'
                OR desobsordtrab LIKE '%RUIDO Y BAJO REN%' OR desobsordtrab LIKE '%RUIDO EN SEG%' OR desobsordtrab LIKE '%SIN  SE%'
                OR desobsordtrab LIKE '%PIXELEADA%'
                OR desobsordtrab LIKE '%POTENC%' OR desobsordtrab LIKE '%PEXT%' OR desobsordtrab LIKE '%SNR%'
                OR desobsordtrab LIKE '%MALA SE%' OR desobsordtrab LIKE '%AUDIO%' ,'MALA SENAL/SIN SENAL',
                IF( codmotv='R038','AVERIA DTH',
                IF( codmotv='R103','TV DESPROGRAMADO', IF( desobsordtrab LIKE '%CALIDAD%' ,'DATOS', 'OTROS'))))))))))))))))) AS TIP_ING,
                IF(c.macaddress IS NOT NULL,c.cmts,IF(b.MACState IS NOT NULL,b.cmts,'')) AS cmts,
                IF(b.MACState ='offline',b.interface,c.interface) AS interface,nc.scopesgroup,
                IF(c.macaddress IS NOT NULL,'online',IF(b.MACState IS NOT NULL,b.MACState,'')) AS macstate,
                IF(b.MACState <>'Offline',b.RxPwrdBmv,' ') AS RxPwrdBmv, IF(b.MACState <>'Offline',c.USPwr,' ') AS USPwr,
                IF(b.MACState <>'offline',c.USMER_SNR,' ') AS USMER_SNR, IF(b.MACState <>'Offline',c.DSPwr,' ') AS DSPwr,
                IF(b.MACState <>'offline',c.DSMER_SNR,' ') AS DSMER_SNR,        
                IF(pr.troba IS NOT NULL,'PREMIUM','MASIVO') AS PREMIUM,
                DATEDIFF(NOW(),CONCAT(fec_registro,' ',SUBSTR(fec_regist,12,5))) AS DIAS,
                IF(cv.codigo IS NULL,'','CONVERGENTE') AS CONVERGENTE,codmotv AS CODMOTV2,desmotv AS DESMOTV,
                IF(m.codnod IS NULL,'Individual','Masiva') AS MASIVA,
                IF(nc.estado='Activo','Servicio Activo',IF(nc.estado='Inactivo','Servicio Suspendido','')) AS EDOSERV,
                IF(zo.jefatura='REG',CONCAT(zo.jefatura,'-',zo.zonal),IF(zo.jefatura<>'REG',CONCAT('LIM-',zo.jefatura),'')) AS ZONAL,
                IF(b.numcpe<=1,'NO','SI') AS TIENEIP,IF(ps.tipopuerto IS NULL,'NO','SI') AS PTO_DOWN_SATURADO
                FROM cms.req_pend_macro_final a
                LEFT JOIN multiconsulta.nclientes nc ON a.codcli=nc.idclientecrm
                LEFT JOIN ccm1.scm_total b ON nc.mac2=b.MACAddress
                LEFT JOIN ccm1.scm_phy_t c ON nc.mac2=c.MACAddress
                LEFT JOIN ccm1.zonales_nodos_eecc zo ON a.codnod=zo.nodo
                LEFT JOIN catalogos.premium pr ON CONCAT(nc.nodo,nc.troba)=pr.troba
                LEFT JOIN catalogos.convergente cv ON a.codcli=cv.codigo
                LEFT JOIN dbpext.masivas_temp m ON a.codnod = m.codnod AND a.nroplano=m.nroplano
                LEFT JOIN alertasx.caidas_new_amplif e
                ON nc.nodo=e.nodo AND nc.troba=e.troba AND nc.amplificador=e.amplificador AND e.Caida='SI'
                LEFT JOIN alertasx.caidas_new f
                ON nc.nodo=f.nodo AND nc.troba=f.troba AND f.Caida='SI'
                LEFT JOIN alertasx.niveles_new g
                ON nc.nodo=g.nodo AND nc.troba=g.troba AND g.Caida='SI'
                LEFT JOIN reportes.clientes_en_puerto_saturado ps
                ON a.codcli=ps.IDCLIENTECRM
                LEFT JOIN cms.planta_clarita pc
                ON a.codcli=pc.cliente
                WHERE a.codnod='$nodo' AND a.nroplano='$troba' AND a.tipreqini IN ('RA','R7','RP')
                GROUP BY codreq"
            );

            $newData = array();

            //Parametros RF 
            $parametrosRF = new Parametrosrf;  
            $paramDiagMasi_detalle = $parametrosRF->getMonitoreoAveriaRFNodoTroba();
            $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);

            foreach ($query as $q) {

            $estado = Parametrosrf::getMonitoreoAveriasVSegunNivelesRFNodoTroba($q->Caida1,$q->Caida2,$q->Caida3,$q->macstate,
                        (double)$q->USMER_SNR,(double)$q->USPwr,(double)$q->DSPwr,(double)$q->DSMER_SNR,$q->cmts1,$q->tiptec1,$q->idcliente1,$dataParametrosRF);
            
            //dd($q);

            $newData[] =  
                (object)array(
                    'CODOFCADM'=> $q->CODOFCADM,
                    'CODREQ'=> $q->CODREQ,
                    'FECREG'=> $q->FECREG,
                    'ESTADO'=> $q->ESTADO,
                    'CODCLI'=> $q->CODCLI,
                    'CODCTR'=> $q->CODCTR,
                    'DESNOMCTR'=> $q->DESNOMCTR,
                    'NODO_CMS'=> $q->NODO_CMS,
                    'TROBA_CMS'=> $q->TROBA_CMS,
                    'NODO_HFC'=> $q->NODO_HFC,
                    'TROBA_HFC'=> $q->TROBA_HFC,
                    'AMPLIFICADOR'=> $q->AMPLIFICADOR,
                    'TAP'=> $q->TAP,
                    'CODMOTV1'=> $q->CODMOTV1,
                    'TIPREQFIN'=> $q->TIPREQFIN,
                    'DESOBSORDTRAB'=> $q->DESOBSORDTRAB,
                    'TIPODEVIA'=> $q->TIPODEVIA,
                    'NOMBREDELAVIA'=> $q->NOMBREDELAVIA,
                    'NUMERO'=> $q->NUMERO,
                    'PISO'=> $q->PISO,
                    'INTERIOR'=> $q->INTERIOR,
                    'MANZANA'=> $q->MANZANA,
                    'LOTE'=> $q->LOTE,
                    'TIP_ING'=> $q->TIP_ING,
                    'cmts'=> $q->cmts,
                    'interface'=> $q->interface,
                    'scopesgroup'=> $q->scopesgroup,
                    'macstate'=> $q->macstate,
                    'RxPwrdBmv'=> $q->RxPwrdBmv,
                    'USPwr'=> $q->USPwr,
                    'USMER_SNR'=> $q->USMER_SNR,
                    'DSPwr'=> $q->DSPwr,
                    'DSMER_SNR'=> $q->DSMER_SNR,
                    'EstadoMDM'=> $estado["mensaje"],
                    'PREMIUM'=> $q->PREMIUM,
                    'DIAS'=> $q->DIAS,
                    'CONVERGENTE'=> $q->CONVERGENTE,
                    'CODMOTV2'=> $q->CODMOTV2,
                    'DESMOTV'=> $q->DESMOTV,
                    'MASIVA'=> $q->MASIVA,
                    'EDOSERV'=> $q->EDOSERV,
                    'ZONAL'=> $q->ZONAL,
                    'TIENEIP?'=> $q->TIENEIP,
                    'PTO_DOWN_SATURADO?'=> $q->PTO_DOWN_SATURADO
                );

            }

            //return collect($newData);
            return $newData;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }
        //return $query;
    }


    public function collection()
    {
        return collect($this->queryaveria($this->nodo,$this->troba));
    }

    
    public function headings(): array
    {
        $cabecera = array('CODOFCADM','CODREQ','FECREG','ESTADO','CODCLI','CODCTR','DESNOMCTR','NODO_CMS',
                        'TROBA_CMS','NODO_HFC','TROBA_HFC','AMPLIFICADOR','TAP','CODMOTV','TIPREQFIN',
                        'DESOBSORDTRAB','TIPODEVIA','NOMBREDELAVIA','NUMERO','PISO','INTERIOR','MANZANA',
                        'LOTE','TIP_ING','cmts','interface','scopesgroup','macstate','RxPwrdBmv','USPwr',
                        'USMER_SNR','DSPwr','DSMER_SNR','EstadoMDM','PREMIUM','DIAS','CONVERGENTE',
                        'CODMOTV','DESMOTV','MASIVA','EDOSERV','ZONAL','TIENEIP?','PTO_DOWN_SATURADO?');

        return $cabecera;
    }
    

}


?>