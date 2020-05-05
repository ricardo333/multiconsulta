<?php

namespace App\Http\Controllers\Modulos\TrabajosProgramados;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Http\Controllers\GeneralController;
use App\Reportes\Excel\TrabajosProgramados\ClientesExcel;
use App\Reportes\Excel\TrabajosProgramados\trabajosPTotalExcel;

class TrabajosProgramadosExcelController extends GeneralController
{
    public function clientesPorNodoTroba(Request $request)
    {
       // dd($request->all());
       
       if($request->ajax()){
            #INICIO
                $item = $request->item;
                $nodo = $request->nodo;
                $troba = $request->troba;

                $fecha=date('YmdHis');
                $archivo="clientes_tp".$fecha.".xlsx";

                $output = Excel::download(new ClientesExcel($item,$nodo,$troba), $archivo);

                return $output;
            #END
       }
       return abort(404); 
        

    }

    public function descargaTotal(Request $request)
    {
       
       if($request->ajax()){
            #INICIO
                 
                $fecha=date('YmdHis');
                $archivo="trabajosPTotal".$fecha.".xlsx";

                $output = Excel::download(new trabajosPTotalExcel(), $archivo);

                return $output;
            #END
       }
       return abort(404); 
        

    }
}
