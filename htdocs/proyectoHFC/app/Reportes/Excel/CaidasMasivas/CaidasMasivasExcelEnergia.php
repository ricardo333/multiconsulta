<?php

namespace App\Reportes\Excel\CaidasMasivas;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class CaidasMasivasExcelEnergia extends GeneralController implements FromCollection, WithHeadings {

    protected $nodo;
    protected $troba;

    function __construct($nodo,$troba) {
        $this->nodo = $nodo;
        $this->troba = $troba;
    }

    public function queryEnergia($nodo,$troba){

        try {
            $query = DB::select("
            SELECT nc.nodo, nc.troba, nc.amplificador, nc.tap AS tap , nc.direccion,'' AS cmts,
            '' AS interface,'' AS SCOPESGROUP,'' AS MACSTATE,'' AS RXPWRDBMV,'' AS USPWR,'' AS USMER_SNR,
            '' AS DSPWR,'' AS DSMER_SNR,'Problema de energia' AS estadomdm, 
            IF(pr.troba IS NOT NULL,'PREMIUM','MASIVO') AS premium, 
            IF(mt.clientecms IS NULL,'','MOVISTAR TOTAL') AS movistar_total,'Masiva' AS masiva,
            '' AS EDOSERV,zo.jef_cmr AS ZONAL,'' AS MACADDRESS,nc.`telf1` AS TELF1,nc.`telf2` AS TELF2,
            nc.`movil1` AS MOVIL1,nc.idclientecrm AS IDCLIENTE,nc.codserv AS IDSERVICIO,
            IF(ta.OPERADOR_MOVIL1 IS NULL,tcms.Operador,ta.OPERADOR_MOVIL1) AS OPERADOR_MOVIL1,
            IF(ta.movil1 IS NULL,tcms.Telefono,ta.movil1) AS movil1,
            IF(ta.OPERADOR_MOVIL2 IS NULL,tcms.operador_2,ta.OPERADOR_MOVIL2) AS OPERADOR_MOVIL2,
            IF(ta.movil2 IS NULL,tcms.telfono2,ta.movil2) AS movil2,
            IF(ta.OPERADOR_MOVIL3 IS NULL,tcms.operador_3,ta.OPERADOR_MOVIL3) AS OPERADOR_MOVIL3,
            IF(ta.movil3 IS NULL,tcms.telfono3,ta.movil3) AS movil3,
            IF(ta.OPERADOR_MOVIL4 IS NULL,tcms.operador_4,ta.OPERADOR_MOVIL4) AS OPERADOR_MOVIL4,
            IF(ta.movil4 IS NULL,tcms.telfono4,ta.movil4) AS movil4,ta.OPERADOR_MOVIL5,ta.movil5
            FROM multiconsulta.nclientes nc 
            INNER JOIN alertasx.caidas_new f ON nc.nodo=f.nodo AND nc.troba=f.troba 
            LEFT JOIN ccm1.scm_total st ON nc.mac2=st.`MACAddress`
            LEFT JOIN catalogos.jefaturas zo ON nc.nodo=zo.nodo 
            LEFT JOIN catalogos.premium pr ON CONCAT(nc.nodo,nc.troba)=pr.troba 
            LEFT JOIN catalogos.movistar_total mt ON nc.idclientecrm=mt.clientecms 
            LEFT JOIN dbpext.masivas_temp m ON nc.nodo = m.codnod AND nc.troba=m.nroplano 
            LEFT JOIN catalogos.telefonos_atis ta ON nc.idclientecrm=ta.CABLE_CLIENTE_CMS
            LEFT JOIN catalogos.telefonos_cms tcms ON nc.idclientecrm=tcms.Cliente
            WHERE nc.nodo='$nodo'  AND nc.troba='$troba' AND st.macstate IN ('ol-d','ol-pt','online',
            'online(d)','online(pt)','p-online','p-online(pt)','w-online','w-online(pt)')
            GROUP BY nc.idclientecrm,nc.codserv"
            );

            return collect($query);
            //return $newData;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryEnergia($this->nodo,$this->troba));
    }

    
    public function headings(): array
    {
        $cabecera = array('NODO','TROBA','AMPLIFICADOR','TAP','DIRECCION','CMTS','INTERFACE',
                    'SCOPESGROUP','MACSTATE','RXPWRDBMV','USPWR','USMER_SNR','DSPWR','DSMER_SNR',
                    'ESTADOMDM','PREMIUM','MOVISTAR_TOTAL','MASIVA','EDOSERV','ZONAL','MACADDRESS',
                    'TELF1','TELF2','MOVIL1','IDCLIENTE','IDSERVICIO','OPERADOR_MOVIL1','movil1',
                    'OPERADOR_MOVIL2','movil2','OPERADOR_MOVIL3','movil3','OPERADOR_MOVIL4','movil4',
                    'OPERADOR_MOVIL5','movil5');

        return $cabecera;
    }


}

?>