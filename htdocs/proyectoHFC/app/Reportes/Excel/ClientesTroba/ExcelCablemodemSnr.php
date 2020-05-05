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

class ExcelCablemodemSnr extends GeneralController implements FromCollection,WithHeadings {

    protected $puerto;

    function __construct($puerto) {
        $this->puerto = $puerto;
    }

    public function queryaveria($puerto){

        try {

          
            $query = DB::select("
                                SELECT a.idclientecrm as codcli,a.nodo,a.troba,a.amplificador,a.tap,REPLACE(a.direccion,',',' '),
                                REPLACE(a.nameclient,',',' '),telf1,telf2,b.Interface,b.cmts,a.servicepackage as paquete,
                                b.USPwr,b.USMER_SNR,b.DSPwr,b.DSMER_SNR,b.DOCSIS_Prov
                                FROM multiconsulta.nclientes a INNER JOIN ccm1.scm_phy_t b
                                ON CONCAT(SUBSTR(a.macaddress,1,2),SUBSTR(a.macaddress,4,2),'.',SUBSTR(a.macaddress,7,2),SUBSTR(a.macaddress,10,2),'.',
                                SUBSTR(a.macaddress,13,2),SUBSTR(a.macaddress,16,2))=b.MACAddress 
                                where concat(b.cmts,b.interface)='$puerto'"
                );

             
            return $query;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor."); 
        }
       
    }


    public function collection()
    {
        return collect($this->queryaveria($this->puerto));
    }

    
    public function headings(): array
    {
        $cabecera = array('codcli','nodo','troba','amplificador','tap','direccion','nombres','telefono1','telefono2','f_v','cmts','paquete',
                            'USPwr','USMER','DSPwr','DSMER','DOCSIS');

        return $cabecera;
    }
    

}


?>