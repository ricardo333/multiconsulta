<?php

namespace App\Http\Controllers\Modulos\LlamadasMasivas;


use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Reportes\Excel\LlamadasMasivas\LlamadasMasivasExcelTotal;
use Maatwebsite\Excel\Facades\Excel;

class LlamadasMasivasExcelController extends GeneralController 
{

    public function excelLlamadasTotal(Request $request){

        $fecha=date('YmdHis');
        $archivo="llamadas_report".$fecha.".xlsx";
        
        $filtroJefatura = trim($request->jefatura) != "" ? " and zo.jefatura='".$request->jefatura."' " : "";
        $filtroTop =  trim($request->top) != "" ? " and t.top='".$request->top."' " : "";
        $filtroNodo =  trim($request->nodo) != "" ? " and a.nodo='".$request->nodo."' " : "";

        $outputCaidasTotal = Excel::download(new LlamadasMasivasExcelTotal($filtroJefatura,$filtroTop,$filtroNodo), $archivo);

        return $outputCaidasTotal;

    }

}


?>