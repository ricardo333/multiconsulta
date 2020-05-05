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

class ExcelTrobasPorPuertos extends GeneralController implements FromCollection,WithHeadings {

    protected $filtro;

    function __construct($filtro) {
        $this->filtro = $filtro;
    }

    public function queryaveria($filtro){

        try {
             
            $parametrosRF = new Parametrosrf;  
            $paramClientesTroba = $parametrosRF->getDiagnosMasiNivelesRF();
            $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramClientesTroba);
            // dd($dataParametrosRF);
            $mensajes = $dataParametrosRF["mensajes"];
            
            $query = DB::select(" select cmts, interface, scopesgroup, macstate, RxPwrdBmv, USPwr, USMER_SNR, DSPwr, DSMER_SNR, IDCLIENTECRM, nameclient,
                                REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(direccion,'AV AV','AV'),'CL CL','CL'),'JR JR','JR'),'PR PR','PR'),'UR UR','UR') AS direccion, 
                                nodohfc, trobahfc, nodocms,
                                trobacms, amplificador, tap, telf1,
                                telf2, movil1, mac2, SERVICEPACKAGE, FECHAACTIVACION, estado_modem, estado, numcoo_x, numcoo_y,codreq,dt.codmotv AS averia,dt.codctr,
                                dt.codedo,dt.codserv
                                FROM (
                                        SELECT rm.codreq,rm.codedo,rm.tipreqfin,rm.codmotv,rm.codctr,IF(b.MACState ='Offline',b.cmts,c.cmts) AS cmts,
                                            IF(b.MACState ='Offline',b.interface,c.interface) AS interface, a.scopesgroup,b.macstate,
                                            IF(b.MACState <>'Offline',b.RxPwrdBmv,' ') AS RxPwrdBmv, IF(b.MACState <>'Offline',c.USPwr,' ') AS USPwr,
                                            IF(b.MACState <>'Offline',c.USMER_SNR,' ') AS USMER_SNR, IF(b.MACState <>'Offline',c.DSPwr,' ') AS DSPwr,
                                            IF(b.MACState <>'Offline',c.DSMER_SNR,' ') AS DSMER_SNR, a.IDCLIENTECRM,REPLACE(a.NAMECLIENT,',','') AS nameclient,
                                            CONCAT( REPLACE(REPLACE(REPLACE(TRIM(via),',',''),'-',''),'.','') ,' ',TRIM(nro),' ',TRIM(piso),' ',IF(mz<>'',REPLACE(TRIM(mz),',',''),''),
                                            IF(lt<>'',CONCAT(' ',TRIM(lt)),''),' ',IF(tipourbani<>'', CONCAT(' ',TRIM(tipourbani)),''),IF(urbanizaci<>'',
                                            CONCAT(' ',REPLACE(TRIM(urbanizaci),',','')),''),' ') AS direccion, a.NODO AS nodohfc,a.TROBA AS trobahfc,d.nodo AS nodocms,
                                            d.plano AS trobacms,d.codlex AS amplificador,
                                            d.codtap AS tap,
                                            a.telf1,a.telf2,a.movil1,a.mac2,a.SERVICEPACKAGE,a.FECHAACTIVACION,
                                            
                                            IF(b.MACState ='Offline' AND c.macaddress IS NULL ,'".$mensajes->mensaje_uno[0]->mensaje."', 
                                            IF(c.DSMER_SNR ='-----' AND b.MACState <>'Offline','".$mensajes->mensaje_dos[0]->mensaje."',
                                            IF(c.DSMER_SNR IS NULL AND b.macstate IN ('w-online','online','operational'), '".$mensajes->mensaje_tres[0]->mensaje."',
                                            IF(b.macstate IS NULL AND c.DSMER_SNR IS NULL,'".$mensajes->mensaje_uno[0]->mensaje."',
                                            IF(b.macstate NOT IN ('w-online','ponline','p-online','online','operational','offline') AND DSMER_SNR IS NULL, 
                                                CONCAT(b.macstate,'".$mensajes->mensaje_cuatro[0]->mensaje."'), 
                                            IF((c.USPwr <=".$dataParametrosRF['up_pwr_min']." OR c.USPwr >= ".$dataParametrosRF['up_pwr_max']." ) AND USPwr<>'-' AND b.MACState <>'Offline','".$mensajes->mensaje_cinco[0]->mensaje."',
                                            IF(c.USMER_SNR <= ".$dataParametrosRF['up_snr_min']." AND c.USMER_SNR >0.00 AND USPwr<>'-' AND b.MACState <>'Offline','".$mensajes->mensaje_cinco[0]->mensaje."', 
                                            IF((c.DSPwr <  ".$dataParametrosRF['down_pwr_min']." OR c.DSPwr > ".$dataParametrosRF['down_pwr_max']." ) AND USPwr<>'-' AND b.MACState <>'Offline' ,'".$mensajes->mensaje_cinco[0]->mensaje."', 
                                            IF(c.DSPwr IS NULL AND b.MACState <>'Offline','".$mensajes->mensaje_seis[0]->mensaje."', IF(c.DSMER_SNR <=  ".$dataParametrosRF['down_snr_min']." AND USPwr<>'-' AND b.MACState <>'Offline' ,'".$mensajes->mensaje_cinco[0]->mensaje."',
                                            IF(c.DSPwr='-' AND c.DSMER_SNR='-' AND b.MACState <>'Offline','".$mensajes->mensaje_siete[0]->mensaje."','".$mensajes->mensaje_ocho[0]->mensaje."'))))))))))) AS estado, 
                                            a.numcoo_x,a.numcoo_y, a.estado AS estado_modem,codserv 
                                        FROM multiconsulta.nclientes a
                                            INNER JOIN ccm1.scm_total b ON a.mac2=b.macaddress 
                                            LEFT JOIN ccm1.scm_phy_t c ON a.mac2=c.macaddress 
                                            LEFT JOIN cms.planta_clarita_h d ON a.idclientecrm=d.cliente AND d.unico='SI'
                                            LEFT JOIN cms.req_pend_macro_final rm ON a.idclientecrm=rm.codcli
                                        WHERE $filtro AND a.idclientecrm<>969625 ORDER BY a.nodo,a.troba,a.amplificador 
                                    ) dt"
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
                        'MOVIL1' => $q->movil1,
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
        $cabecera = array(
            'CMTS','INTERFACE','MACSTATE','RXPWRDBMV','USPWR','USMER_SNR','DSPWR','DSMER_SNR','IDCLIENTECRM','NAMECLIENT','DIRECCION',
            'NODOHFC','TROBAHFC','NODOCMS','TROBACMS','AMPLIFICADOR','TAP','TELF1','TELF2','MOVIL1','MAC2','SERVICEPACKAGE','FECHAACTIVACION',
            'ESTADO_MODEM','SCOPESGROUP','ESTADO','NUMCOO_X','NUMCOO_Y','codreq','averia','codctr','codedo','codserv',
        );

        return $cabecera;
    }
    

}


?>