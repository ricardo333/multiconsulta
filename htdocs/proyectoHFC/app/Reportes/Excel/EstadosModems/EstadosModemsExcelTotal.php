<?php

namespace App\Reportes\Excel\EstadosModems;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class EstadosModemsExcelTotal extends GeneralController implements FromCollection, WithHeadings {

    protected $state;

    function __construct($state) {
        $this->state = $state;
    }
    
    public function queryEstadosModems($state)
    {
        try {

            if($state=='sinippublica'){
                $preg="where a.MACState IN ('online','wonline', 'w-online') AND a.NumCPE*1<=0 and 	b.idclientecrm NOT IN (19082016,
                969625,
                100000011,
                123454321,
                1000000007,
                1000000257,
                9988776655,
                10000000100,
                100000000006,
                100000000008,
                100000000009,
                100000000080,
                100000000084,
                100000000085,
                100000000087,
                100000000099,
                1000000000010,
                1000000000017,
                1000000000081,
                1000000000083,
                1000000000084,
                1000000000110,
                100000000000123,
                100000000000130,
                100000000009998)
            "; 
            }else{
                $preg="WHERE a.macstate='".$state."'  and e.macaddress is null and
                b.idclientecrm NOT IN (19082016,969625,
                100000011,
                123454321,
                1000000007,
                1000000257,
                9988776655,
                10000000100,
                100000000006,
                100000000008,
                100000000009,
                100000000080,
                100000000084,
                100000000085,
                100000000087,
                100000000099,
                1000000000010,
                1000000000017,
                1000000000081,
                1000000000083,
                1000000000084,
                1000000000110,
                100000000000123,
                100000000000130,
                100000000009998)
                ";
            }

            $query = DB::select("
                                SELECT b.idclientecrm,b.nodo,b.troba,b.amplificador,b.tap,REPLACE(b.direccion,',','') AS direccion,b.macaddress,
                                a.macstate,a.cmts,
                                c.Interface,c.USPwr,c.USMER_SNR,c.DSPwr,c.DSMER_SNR,a.RxPwrdBmv,REPLACE(d.Fabricante,',','') AS Marca,d.Modelo,
                                b.scopesgroup,b.servicepackage,d.versioon,b.ESTADO 
                                FROM ccm1.scm_total a 
                                LEFT JOIN multiconsulta.nclientes b 
                                ON a.macaddress=b.mac2
                                LEFT JOIN ccm1.scm_phy_t c
                                ON a.macaddress=c.macaddress
                                LEFT JOIN catalogos.internostdp_nclientes e
                                ON a.macaddress=e.macaddress
                                LEFT JOIN ccm1_data.marca_modelo_docsis_total d
                                ON b.macaddress = d.MACAddress  $preg");

            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryEstadosModems($this->state));
    }


    public function headings(): array
    {
        
        $cabecera = array('idclientecrm','nodo','troba','amplificador','tap','direccion','macaddress','macstate','cmts','Interface','USPwr','USMER_SNR','DSPwr','DSMER_SNR','RxPwrdBmv','','Modelo','scopesgroup','servicepackage','Firmware','ESTADO');
        return $cabecera;

    }
 

}

?>