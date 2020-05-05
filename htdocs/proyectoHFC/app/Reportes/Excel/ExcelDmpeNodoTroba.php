<?php

namespace App\Reportes\Excel;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelDmpeNodoTroba extends GeneralController implements FromCollection,WithHeadings {

    protected $nodo;
    protected $troba;

    function __construct($nodo,$troba) {
        $this->nodo = $nodo;
        $this->troba = $troba;
    }

    public function queryaveria($nodo,$troba){

        try {
            $query = DB::select("
                SELECT b.CLIENTE,b.SERVICIO,b.OFI_CLI,d.cmts,d.f_v,e.macstate,e.RxPwrdBmv,f.USPwr,
                f.USMER_SNR,f.DSPwr,f.DSMER_SNR,b.NODO,b.PLANO,b.CODLEX,b.CODTAP,
                REPLACE(b.DIREC_INST,',','') AS DIREC_INST,b.NUMERODOC,b.TELEFCL1,b.TELEFCL2,b.TELEFCL3,
                CONCAT(TRIM(b.NOMBRE),' ',TRIM(b.APE_PAT),' ',TRIM(b.APE_MAT)) AS nombre ,fechahora 
                FROM alertasx.`alertas_dmpe` a 
                INNER JOIN cms.planta_clarita b ON a.cliente=b.cliente 
                LEFT JOIN alertasx.caidas_t c ON b.nodo=c.nodo AND b.plano=c.troba AND c.Caida='SI' 
                LEFT JOIN multiconsulta.nclientes d ON a.cliente=d.idclientecrm 
                LEFT JOIN ccm1.scm_total e ON d.mac2=e.macaddress 
                LEFT JOIN ccm1.scm_phy_t f ON d.mac2=f.macaddress 
                WHERE DATEDIFF(NOW(),fechahora)=0 AND a.nodo='$nodo' AND a.troba='$troba' 
                GROUP BY b.cliente"
            );

        $newData = array();

        foreach ($query as $q) {

            $newData[] =  
                (object)array(
                    'CLIENTE'=> $q->CLIENTE,
                    'SERVICIO'=> $q->SERVICIO,
                    'OFI_CLI'=> $q->OFI_CLI,
                    'CMTS'=> $q->cmts,
                    'INTERFACE'=> $q->f_v,
                    'MACSTATE'=> $q->macstate,
                    'RxPwrdBmv'=> $q->RxPwrdBmv,
                    'USPwr'=> $q->USPwr,
                    'USMER_SNR'=> $q->USMER_SNR,
                    'DSPwr'=> $q->DSPwr,
                    'DSMER_SNR'=> $q->DSMER_SNR,
                    'NODO'=> $q->NODO,
                    'PLANO'=> $q->PLANO,
                    'CODLEX'=> $q->CODLEX,
                    'CODTAP'=> $q->CODTAP,
                    'DIREC_INST'=> $q->DIREC_INST,
                    'NUMERODOC'=> $q->NUMERODOC,
                    'TELEFCL1'=> $q->TELEFCL1,
                    'TELEFCL2'=> $q->TELEFCL2,
                    'TELEFCL3'=> $q->TELEFCL3,
                    'nombre'=> $q->nombre,
                    'fechahora'=> $q->fechahora
                );

            }

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
        $cabecera = array('CLIENTE','SERVICIO','OFI_CLI','CMTS','INTERFACE','MACSTATE','RxPwrdBmv','USPwr',
                        'USMER_SNR','DSPwr','DSMER_SNR','NODO','PLANO','CODLEX','CODTAP',
                        'DIREC_INST','NUMERODOC','TELEFCL1','TELEFCL2','TELEFCL3','nombre','fechahora');

        return $cabecera;
    }


}

?>