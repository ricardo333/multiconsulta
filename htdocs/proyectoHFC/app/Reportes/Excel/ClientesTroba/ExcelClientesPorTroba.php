<?php

namespace App\Reportes\Excel\ClientesTroba;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExcelClientesPorTroba extends GeneralController implements FromCollection,WithHeadings {

    protected $filtro;

    function __construct($filtro) {
        $this->filtro = $filtro;
    }

    public function queryaveria($filtro){

        try {

            $parametrosRF = new Parametrosrf;  
            $paramClientesTroba = $parametrosRF->getDescargaClienteTrobaRF();
            $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramClientesTroba);
            //dd($dataParametrosRF);
            $mensajes = $dataParametrosRF["mensajes"];
            $query = DB::select("
                        SELECT cmts, interface, scopesgroup, macstate, RxPwrdBmv, USPwr, USMER_SNR, DSPwr, DSMER_SNR, IDCLIENTECRM, nameclient, 
                        REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(direccion,'AV AV','AV'),'CL CL','CL'),'JR JR','JR'),'PR PR','PR'),'UR UR','UR') AS direccion,
                        nodohfc, trobahfc, nodocms, trobacms, amplificador, tap, telf1, telf2, cmovil1,movil1,movil2,movil3,movil4,movil5, mac2, SERVICEPACKAGE, FECHAACTIVACION, estado_modem,
                        estado, numcoo_x, numcoo_y,codreq,dt.codmotv AS averia,dt.codctr,dt.codedo,dt.codserv 
                        FROM (
                                SELECT rm.codreq,rm.codedo,rm.tipreqfin,rm.codmotv,rm.codctr, 
                                IF(c.macaddress IS NOT NULL,c.cmts,IF(b.MACState IS NOT NULL,b.cmts,'')) AS cmts, 
                                IF(b.MACState ='offline',b.interface,c.interface) AS interface, a.scopesgroup, 
                                IF(c.macaddress IS NOT NULL,'online',IF(b.MACState IS NOT NULL,b.MACState,'')) AS macstate, 
                                IF(b.MACState <>'Ofine',b.RxPwrdBmv,' ') AS RxPwrdBmv, 
                                IF(b.MACState <>'Offline',c.USPwr,' ') AS USPwr, 
                                IF(b.MACState <>'Offline',c.USMER_SNR,' ') AS USMER_SNR, 
                                IF(b.MACState <>'Offline',c.DSPwr,' ') AS DSPwr, 
                                IF(b.MACState <>'Offline',c.DSMER_SNR,' ') AS DSMER_SNR,
                                a.IDCLIENTECRM,
                                REPLACE(a.NAMECLIENT,',','') AS nameclient,
                                d.direc_inst as direccion,
                                a.NODO AS nodohfc,
                                a.TROBA AS trobahfc,
                                d.nodo AS nodocms,
                                d.plano AS trobacms,
                                d.codlex AS amplificador, 
                                d.codtap AS tap, 
                                a.telf1,a.telf2,
                                a.movil1 as cmovil1,a.mac2,
                                a.SERVICEPACKAGE,
                                a.FECHAACTIVACION, 
                                IF(e.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR 
                                                b.macstate = 'init(io)' OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR 
                                                b.macstate = 'init(r1)' OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)'),
                                        '".$mensajes->mensaje_uno[0]->mensaje."', 
                                IF(f.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR 
                                                b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)' OR b.macstate = 'init(t)' OR 
                                                b.macstate = 'bpi(wait)'),
                                        '".$mensajes->mensaje_dos[0]->mensaje."', 
                                IF(g.Caida='SI',
                                        '".$mensajes->mensaje_dos[0]->mensaje."', 
                                IF(b.macstate='offline',
                                        'Offline - NO OK', 
                                IF(c.USMER_SNR * 1 < ".$dataParametrosRF['up_snr_min']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USMER_SNR * 1 < ".$dataParametrosRF['up_snr_min']." AND c.USPwr * 1 < ".$dataParametrosRF['up_pwr_min']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USPwr * 1 < ".$dataParametrosRF['up_pwr_min']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USPwr * 1 > ".$dataParametrosRF['up_pwr_max']." AND c.DSPwr > ".$dataParametrosRF['down_pwr_min']." AND c.DSPwr < ".$dataParametrosRF['down_pwr_max']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.DSPwr * 1 > ".$dataParametrosRF['down_pwr_max']." AND c.USPwr * 1 < ".$dataParametrosRF['up_pwr_min']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."',
                                IF(c.USPwr * 1 < ".$dataParametrosRF['up_pwr_min']." AND c.USPwr * 1 > 0 ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USPwr * 1 < ".$dataParametrosRF['up_pwr_min']." AND c.DSPwr * 1 > ".$dataParametrosRF['down_pwr_max']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.DSPwr * 1 > ".$dataParametrosRF['down_pwr_max']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USPwr * 1 > ".$dataParametrosRF['up_pwr_max']." AND c.DSPwr > ".$dataParametrosRF['down_pwr_max']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(c.USMER_SNR * 1 < ".$dataParametrosRF['up_snr_max']." AND c.DSPwr * 1 > ".$dataParametrosRF['down_pwr_max']." ,
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                IF(b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR b.macstate = 'init(o)' OR
                                b.macstate = 'init(r)' OR b.macstate = 'init(r1)' OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)',
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                IF(c.DSPwr < ".$dataParametrosRF['down_pwr_min']." AND c.USPwr > ".$dataParametrosRF['up_pwr_max'].",
                                        '".$mensajes->mensaje_cinco[0]->mensaje."', 
                                IF(c.DSPwr < ".$dataParametrosRF['down_pwr_min']." OR c.DSPwr > ".$dataParametrosRF['down_pwr_max'].",
                                        '".$mensajes->mensaje_cinco[0]->mensaje."', 
                                IF(c.DSPwr < ".$dataParametrosRF['down_pwr_min']." AND c.DSMER_SNR < ".$dataParametrosRF['down_snr_min']." ,
                                        '".$mensajes->mensaje_cinco[0]->mensaje."', 
                                IF(c.USPwr> ".$dataParametrosRF['up_pwr_max']." AND c.USMER_SNR * 1 >= ".$dataParametrosRF['up_snr_max']." 
                                AND c.DSPwr > ".$dataParametrosRF['down_pwr_min']." AND c.DSPwr < ".$dataParametrosRF['down_pwr_max'].",
                                        '".$mensajes->mensaje_cinco[0]->mensaje."', 
                                IF(c.DSPwr='' AND c.DSMER_SNR='' AND b.macstate = 'online',
                                        '".$mensajes->mensaje_seis[0]->mensaje."', 
                                IF(c.DSPwr='' AND c.DSMER_SNR='' AND b.macstate = '',
                                        '".$mensajes->mensaje_siete[0]->mensaje."', 
                                IF(b.MACState IN ('init','init(t)','init(r2)','init(r1)'),
                                        '".$mensajes->mensaje_ocho[0]->mensaje."', 
                                IF(b.MACState IN ('init(d)','DHCP','init(o)'),
                                        '".$mensajes->mensaje_nueve[0]->mensaje."' , 
                                IF(c.DSPwr IS NULL AND b.macstate IS NULL, 
                                        '".$mensajes->mensaje_seis[0]->mensaje."', '".$mensajes->mensaje_diez[0]->mensaje."')))))))))))))))))))))))) AS estado, 
                                a.numcoo_x,a.numcoo_y, 
                                a.estado AS estado_modem,
                                a.codserv ,
                                IF(ta.movil1 IS NULL,tcms.Telefono,ta.movil1) AS movil1,
                                IF(ta.movil2 IS NULL,tcms.telfono2,ta.movil2) AS movil2,
                                IF(ta.movil3 IS NULL,tcms.telfono3,ta.movil3) AS movil3,
                                IF(ta.movil4 IS NULL,tcms.telfono4,ta.movil4) AS movil4,
                                ta.movil5
                                FROM multiconsulta.nclientes a 
                                LEFT JOIN ccm1.scm_total b ON a.mac2=b.macaddress 
                                LEFT JOIN ccm1.scm_phy_t c ON a.mac2=c.macaddress 
                                LEFT JOIN cms.planta_clarita d ON a.idclientecrm=d.cliente AND d.unico='SI' 
                                LEFT JOIN cms.req_pend_macro_final rm ON a.idclientecrm=rm.codcli 
                                LEFT JOIN dbpext.masivas_temp m ON a.nodo = m.codnod AND a.troba=m.nroplano 
                                LEFT JOIN alertasx.caidas_new_amplif e ON a.nodo=e.nodo AND a.troba=e.troba AND a.amplificador=e.amplificador AND e.Caida='SI' 
                                LEFT JOIN alertasx.caidas_new f ON a.nodo=f.nodo AND a.troba=f.troba AND f.Caida='SI' 
                                LEFT JOIN alertasx.niveles_new g ON a.nodo=g.nodo AND a.troba=g.troba AND g.Caida='SI' 
                                LEFT JOIN catalogos.telefonos_atis ta ON a.idclientecrm=ta.CABLE_CLIENTE_CMS
                                LEFT JOIN catalogos.telefonos_cms tcms ON a.idclientecrm=tcms.Cliente
                                WHERE $filtro AND a.idclientecrm<>969625 ORDER BY a.nodo,a.troba,a.amplificador
                        ) dt
                            "
                );

             //dd($query);

            $newData = array();
  
            foreach ($query as $q) {
 

            $newData[] =  
                (object)array(
                        'CMTS' => $q->cmts,
                        'INTERFACE' => $q->interface,
                        'MACSTATE' => $q->macstate,
                        'RXPWRDBMV' => $q->RxPwrdBmv,
                        'USPWR' => $q->USPwr,
                        'USMER_SNR' => $q->USMER_SNR,
                        'DSPWR' => $q->DSPwr,
                        'DSMER_SNR' => $q->DSMER_SNR,
                        'IDCLIENTECRM' => $q->IDCLIENTECRM,
                        'NAMECLIENT' => $q->nameclient,
                        'DIRECCION' => $q->direccion,
                        'NODOHFC' => $q->nodohfc,
                        'TROBAHFC' => $q->trobahfc,
                        'NODOCMS' => $q->nodocms,
                        'TROBACMS' => $q->trobacms,
                        'AMPLIFICADOR' => $q->amplificador,
                        'TAP' => $q->tap,
                        'TELF1' => $q->telf1,
                        'TELF2' => $q->telf2,
                        'CMOVIL1' => $q->cmovil1,
                        'MAC2' => $q->mac2,
                        'SERVICEPACKAGE' => $q->SERVICEPACKAGE,
                        'FECHAACTIVACION' => $q->FECHAACTIVACION,
                        'ESTADO_MODEM' => $q->estado_modem,
                        'SCOPESGROUP' => $q->scopesgroup,
                        'ESTADO' => $q->estado,
                        'NUMCOO_X' => $q->numcoo_x,
                        'NUMCOO_Y' => $q->numcoo_y,
                        'codreq' => $q->codreq,
                        'averia' => $q->averia,
                        'codctr' => $q->codctr,
                        'codedo' => $q->codedo,
                        'codserv' => $q->codserv,
                        'Movil1' => $q->movil1,
                        'Movil2' => $q->movil2,
                        'Movil3' => $q->movil3,
                        'Movil4' => $q->movil4,
                        'Movil5' => $q->movil5
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
        return collect($this->queryaveria($this->filtro));
    }

    
    public function headings(): array
    {
        $cabecera = array('CMTS','INTERFACE','MACSTATE','RXPWRDBMV','USPWR','USMER_SNR','DSPWR','DSMER_SNR',
                        'IDCLIENTECRM','NAMECLIENT','DIRECCION','NODOHFC','TROBAHFC','NODOCMS','TROBACMS',
                        'AMPLIFICADOR','TAP','TELF1','TELF2','CMOVIL1','MAC2','SERVICEPACKAGE','FECHAACTIVACION',
                        'ESTADO_MODEM','SCOPESGROUP','ESTADO','NUMCOO_X','NUMCOO_Y','codreq','averia',
                        'codctr','codedo','codserv','Movil1','Movil2','Movil3','Movil4','Movil5');

        return $cabecera;
    }
    

}


?>