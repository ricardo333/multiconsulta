<?php

namespace App\Reportes\Excel\IngresoAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
 
class IngresoAveriasReporteArbol extends GeneralController {

    static public function queryReporteArbol($reporte)
    {
        try {
            
            $query = DB::select("
                                SELECT usuario as USUARIO,fechahora as FECHAHORA,DIA as DIA,idclientecrm as IDCLIENTECRM,averia as AVERIA,codreq as CODREQ,fecreg as FEC_REGISTRO,paso0 as PASO0,detalle0 as DETALLE0,paso1 as PASO1,detalle1 as DETALLE1,paso2 as PASO2,detalle2 as DETALLE2,paso3 as PASO3,detalle3 as DETALLE3,paso4 as PASO4,detalle4 as DETALLE4
                                ,paso5 as PASO5,detalle5 as DETALLE5,paso6 as PASO6,detalle6 as DETALLE6,paso7 as PASO7,detalle7 as DETALLE7,paso8 as PASO8,detalle8 as DETALLE8,paso9 as PASO9,detalle9 as DETALLE9,paso10 as PASO10,detalle10 as DETALLE10,paso11 as PASO11,detalle11 as DETALLE11,paso12 as PASO12,detalle12 as DETALLE12,paso13 as PASO13,detalle13 as DETALLE13,paso14 as PASO14,detalle14 as DETALLE14
                                ,paso15 as PASO15,detalle15 as DETALLE15,paso16 as PASO16,detalle16 as DETALLE16,paso17 as PASO17,detalle17 as DETALLE17,paso18 as PASO18,detalle18 as DETALLE18,paso19 as PASO19,detalle19 as DETALLE19,paso20 as PASO20,detalle20 as DETALLE20,paso21 as PASO21,detalle21 as DETALLE21,paso22 as PASO22,detalle22 as DETALLE22
                                FROM arboldecisiones.reporte_arbol_view
                                WHERE fechahora>='2019-06-01 00:00:00'
                            ");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }
 

}

?>