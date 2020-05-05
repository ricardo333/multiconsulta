<?php

namespace App\Http\Controllers\Modulos\LlamadasNodo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reportes\Excel\LlamadasNodo\LlamadasNodoDMPEExcel;
use App\Reportes\Excel\LlamadasNodo\LlamadasNodoAveriasExcel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Functions\LlamadasNodoFunctions;

class ExcelController extends Controller
{

    public function excelDMPE(Request $request){

        $nodo = trim($request->nodo) != "" ? $request->nodo : "";
        $troba = trim($request->troba) != "" ? " and a.troba='".$request->troba."'" : "";

        $output = LlamadasNodoDMPEExcel::queryReporteLlamadaNodoDMPE($nodo,$troba);
        return (new FastExcel($output))->download('consultp_down.xlsx');
        
    }

    public function excelAverias(Request $request){
        
        $nodo = trim($request->nodo) != "" ? $request->nodo : "";
        $troba = trim($request->troba) != "" ? $request->troba : "";

        $filtroTTPP = "";
         
       /* if (isset($request->trabajoProgramado) && isset($request->fechaHora)) { 
            //dd((int)$request->trabajoProgramado);
                $filtroTTPP = (int)$request->trabajoProgramado  == 1 ?  " a.fecreg>='".$request->fechaHora."' "  : "";
        }*/
        //dd($request->all());

        $output = LlamadasNodoAveriasExcel::queryReporteLlamadaNodoAverias($nodo,$troba,$filtroTTPP);
        return (new FastExcel($output))->download('averias.xlsx');
        
    }

    public function excelTotal(Request $request)
    {

        $filtroJefatura = trim($request->jefatura) != "" ? " and zo.jefatura='".$request->jefatura."' " : "";

        $funcionLlamadasNodo = new LlamadasNodoFunctions;
        $output =  $funcionLlamadasNodo->getListaLlamadasNodo($filtroJefatura);

        return (new FastExcel(collect($output)))->download('reporte-averias.xlsx', function ($value) {
            return [
                'Jefatura' => $value->jefatura,
                'Nodo' => $value->nodo,
                'Troba' => $value->cant,
                'Llamadas' => $value->trobas,
                'Ultima Llamada' => $value->promediocall,
                'Masiva' => $value->ultimallamada,
                'Averias' => $value->aver,
            ];
        });

    }

}