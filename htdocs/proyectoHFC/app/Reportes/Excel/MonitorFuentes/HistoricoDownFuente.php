<?php

namespace App\Reportes\Excel\MonitorFuentes;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HistoricoDownFuente extends GeneralController implements FromCollection,WithHeadings {

     
    protected $mac;
    
 
    function __construct($mac) { 
        $this->mac = $mac;
        
    }

    public function queryaveria($mac){

        try {

            $query = DB::connection('servidor_procesos')->select("                           
                                SELECT macadDress,InputVoltagefinal,
                                OutputVoltagefinal,OutputCurrentfinal,TotalStringVoltagefinal,EstadoInversor,fechahora 
                                FROM alertasx.fuentes_snmp_hist WHERE macadDress='$mac' AND DATEDIFF(NOW(),fechahora)<=7
                        ");
 
            //return collect($newData);
            return $query;

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }
        //return $query;
    }


    public function collection()
    {
        return collect($this->queryaveria($this->mac));
    }

    
    public function headings(): array
    {
        $cabecera = array(
            'MACADDRESS',
            'INPUTVOLTAGEFINAL',
            'OUTPUTVOLTAGEFINAL',
            'OUTPUTCURRENTFINAL',
            'TOTALSTRINGVOLTAGEFINAL',
            'ESTADOINVERSOR',
            'FECHAHORA'
        );

        return $cabecera;
    }
    

}


?>