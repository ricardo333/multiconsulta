<?php

namespace App\Reportes\Excel\CaidasMasivas;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class CaidasMasivasExcelNodoTroba extends GeneralController implements FromCollection, WithHeadings {

    protected $nodo;
    protected $troba;

    function __construct($nodo,$troba) {
        $this->nodo = $nodo;
        $this->troba = $troba;
    }

    public function queryaveria($nodo,$troba){

        try {
            $query = DB::select("
                SELECT IF(b.MACState ='Offline',b.cmts,c.cmts) AS cmts,
                IF(b.MACState ='Offline',b.interface,c.interface) AS interface,a.scopesgroup,b.macstate,
                IF(b.MACState <>'Offline',b.RxPwrdBmv,' ') AS RxPwrdBmv,
                IF(b.MACState <>'Offline',c.USPwr,' ') AS USPwr,
                IF(b.MACState <>'Offline',c.USMER_SNR,' ') AS USMER_SNR,
                IF(b.MACState <>'Offline',c.DSPwr,' ') AS DSPwr,
                IF(b.MACState <>'Offline',c.DSMER_SNR,' ') AS DSMER_SNR,
                a.IDCLIENTECRM,REPLACE(a.NAMECLIENT,',','') AS nameclient,
                CONCAT(TRIM(despvc),' ',TRIM(desdtt),IF(tipourbani<>'',CONCAT(' ',TRIM(tipourbani)),''),
                IF(urbanizaci<>'',CONCAT(' ',REPLACE(TRIM(urbanizaci),',','')),''),' ',
                REPLACE(REPLACE(REPLACE(TRIM(via),',',''),'-',''),'.',''),' ',TRIM(nro),' ',TRIM(piso),' ',
                IF(mz<>'',REPLACE(TRIM(mz),',',''),''),IF(lt<>'',CONCAT(' ',TRIM(lt)),'')) AS direccion,
                a.NODO,a.TROBA,d.nodo,d.plano,d.codlex AS amplifcador,d.codtap AS tap,
                d.telefcl1 AS cms_telf1,d.telefcl2 AS cms_telf2,d.telefcl3 AS cms_movil1,a.mac2,
                a.SERVICEPACKAGE,a.FECHAACTIVACION,a.numcoo_x,a.numcoo_y,b.FECHA_HORA AS FecEst,
                c.FECHA_HORA AS FecNivel,c.macaddress,
                IF(ta.movil1 IS NULL,tcms.Telefono,ta.movil1) AS movil1,
                IF(ta.movil2 IS NULL,tcms.telfono2,ta.movil2) AS movil2,
                IF(ta.movil3 IS NULL,tcms.telfono3,ta.movil3) AS movil3,
                IF(ta.movil4 IS NULL,tcms.telfono4,ta.movil4) AS movil4,ta.movil5
                FROM multiconsulta.nclientes a 
                LEFT JOIN  ccm1.scm_total b ON a.mac2=b.macaddress
                LEFT JOIN ccm1.scm_phy_t c ON a.mac2=c.macaddress
                LEFT JOIN cms.planta_clarita d ON a.idclientecrm=d.cliente
                LEFT JOIN catalogos.telefonos_atis ta ON a.idclientecrm=ta.CABLE_CLIENTE_CMS
                LEFT JOIN catalogos.telefonos_cms tcms ON a.idclientecrm=tcms.Cliente
                WHERE a.nodo='$nodo' AND a.troba='$troba' GROUP BY a.mac2"
            );
  
            $newData = array();

            //Parametros RF 
            $parametrosRF = new Parametrosrf;  
            $paramCaidaMasiva_detalle = $parametrosRF->getCaidasMasivasRFNodoTroba();
            $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramCaidaMasiva_detalle);

            foreach ($query as $q) {

                $estado = Parametrosrf::getCaidasMasivasVSegunNivelesRF($q->macstate,$q->macaddress,
                        (double)$q->USMER_SNR,(double)$q->USPwr,(double)$q->DSPwr,(double)$q->DSMER_SNR,$dataParametrosRF);
            
            //dd($q);

            $newData[] =  
                (object)array(
                    'cmts'=> $q->cmts,
                    'interface'=> $q->interface,
                    'scopesgroup'=> $q->scopesgroup,
                    'macstate'=> $q->macstate,
                    'RxPwrdBmv'=> $q->RxPwrdBmv,
                    'USPwr'=> $q->USPwr,
                    'USMER_SNR'=> $q->USMER_SNR,
                    'DSPwr'=> $q->DSPwr,
                    'DSMER_SNR'=> $q->DSMER_SNR,
                    'IDCLIENTECRM'=> $q->IDCLIENTECRM,
                    'NAMECLIENT'=> $q->nameclient,
                    'direccion'=> $q->direccion,
                    'NODO_HFC'=> $q->NODO,
                    'TROBA_HFC'=> $q->TROBA,
                    'NODO_CMS'=> $q->nodo,
                    'TROBA_CMS'=> $q->plano,
                    'amplificador'=> $q->amplifcador,
                    'tap'=> $q->tap,
                    'cms_telf1'=> $q->cms_telf1,
                    'cms_telf2'=> $q->cms_telf2,
                    'cms_movil1'=> $q->cms_movil1,
                    'MACADDRESS'=> $q->mac2,
                    'SERVICEPACKAGE'=> $q->SERVICEPACKAGE,
                    'FECHAACTIVACION'=> $q->FECHAACTIVACION,
                    'ESTADO'=> $estado["mensaje"],
                    'X'=> $q->numcoo_x,
                    'Y'=> $q->numcoo_y,
                    'FecEst'=> $q->FecEst,
                    'FecNivel'=> $q->FecNivel,
                    'MOVIL1'=> $q->movil1,
                    'MOVIL2'=> $q->movil2,
                    'MOVIL3'=> $q->movil3,
                    'MOVIL4'=> $q->movil4,
                    'MOVIL5'=> $q->movil5

                );

            }

            //return collect($newData);
            return $newData;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryaveria($this->nodo,$this->troba));
    }

    
    public function headings(): array
    {
        $cabecera = array('cmts','interface','scopesgroup','macstate','RxPwrdBmv','USPwr','USMER_SNR',
                    'DSPwr','DSMER_SNR','IDCLIENTECRM','NAMECLIENT','direccion','NODO_HFC','TROBA_HFC',
                    'NODO_CMS','TROBA_CMS','amplificador','tap','cms_telf1','cms_telf2','cms_movil1',
                    'MACADDRESS','SERVICEPACKAGE','FECHAACTIVACION','ESTADO','X','Y','FecEst','FecNivel',
                    'MOVIL1','MOVIL2','MOVIL3','MOVIL4','MOVIL5');

        return $cabecera;
    }



 

}

?>