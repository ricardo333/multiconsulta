<?php

namespace App\Reportes\Excel\LlamadasNodo;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
 
class LlamadasNodoAveriasExcel extends GeneralController {

    static public function queryReporteLlamadaNodoAverias($nodo,$troba,$filtroTTPP)
    {
        
        try {

            $nodoAdd='';
            if($troba=='' && $nodo!=''){
                $nodoAdd=" where a.nodocms='".$nodo."' and datediff(now(),a.fecreg)=0 ";
            }else{
                if ($nodo!='' && $troba!='') { 
                    $nodoAdd= " where a.nodocms='".$nodo."' AND a.trobacms='".$troba."' ";
                }
            }

            if ($nodoAdd == "" && $filtroTTPP != "") {
                $filtroTTPP = " where ".$filtroTTPP;
            }
            if ($nodoAdd != "" && $filtroTTPP != "") {
                $filtroTTPP = " and ".$filtroTTPP;
            }
            
            $query = DB::select(" select a.* from triaje.averias_revisadas a $nodoAdd $filtroTTPP");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

}

?>