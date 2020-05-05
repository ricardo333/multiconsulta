<?php

namespace App\Reportes\Excel\MonitorAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

ini_set('memory_limit', '256M');
 
class MonitorAveriasExcelGestion extends GeneralController implements FromCollection {

    public function collection()
    {
        try {
            $customer_data = DB::select("
                SELECT `jefatura`,`nodo`,`troba`,`consultas`,`averias`,`ultreq`,`codmasiva`,
                REPLACE(REPLACE(REPLACE(`trabprog`,',',' '),'',''),'\r\n',' ') AS `trabprog`,`estado`,
                REPLACE(REPLACE(REPLACE(`observacion`,',',' '),'',''),'\r\n',' ') AS observacion,
                `fechahoragest`,`usuario`,`fecha_update`,porc_caida,serv_afectado
                FROM alertasx.bitacora_torre WHERE DATEDIFF(NOW(),fecha_update)<=31 AND usuario<>''"
            );

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

        $customer_array[] = array('jefatura','nodo','troba','consultas','averias','ultreq','codmasiva',
                            'trabprog','estado','observacion','fechahoragest','usuario','fecha_update',
                            'porc_caida','serv_afectado');
        
        foreach ($customer_data as $customer) {

            //$search1 = array("\t","\r\n","\n","</br>");
            $customer_array[] = array(
                'jefatura' => $customer->jefatura,
                'nodo' => $customer->nodo,
                'troba' => $customer->troba,
                'consultas' => $customer->consultas,
                'averias' => $customer->averias,
                'ultreq' => $customer->ultreq,
                'codmasiva' => $customer->codmasiva,
                'trabprog' => $customer->trabprog,
                'estado' => $customer->estado,
                'observacion' => $customer->observacion,
                'fechahoragest' => $customer->fechahoragest,
                'usuario' => $customer->usuario,
                'fecha_update' => $customer->fecha_update,
                'porc_caida' => $customer->porc_caida,
                'serv_afectado' => $customer->serv_afectado
            );
            
        }

        //dd($customer_array);

        return collect([$customer_array]);

    }


}

?>