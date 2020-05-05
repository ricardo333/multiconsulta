<?php

namespace App\Http\Controllers\Modulos\AveriasCoe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Reportes\Excel\AveriasCoe\AveriasCoeExportExcel;
 

class ExcelAveriasCoeController extends Controller
{

    public function averiasMUno(Request $request)
    {
        /*$nodo = trim($request->nodo) != "" ? $request->nodo : "";
        $troba = trim($request->troba) != "" ? $request->troba : "";
        
        $output = AveriasCoeExportExcel::queryReporteAveriasMUno($nodo,$troba);
        //dd($output);
        return (new FastExcel($output))->download('consultp_down.xlsx');*/

        $nodo = trim($request->nodo) != "" ? $request->nodo : "";
        $troba = trim($request->troba) != "" ? $request->troba : "";

         
        $output = AveriasCoeExportExcel::queryReporteAveriasMUno($nodo,$troba);
       // dd($output);
        return (new FastExcel($output))->download('averias_m1.xlsx', function ($value) {
           // dd($value);
            return [
                'codofcadm' => $value->codofcadm,
                'codreq' => $value->codreq,
                'codclasrv' => $value->codclasrv,
                'tipreqini' => $value->tipreqini,
                'destipreqini' => $value->destipreqini,
                'fec_mov' => $value->fec_mov,
                'codestado' => $value->codestado,
                'codcli' => $value->codcli,
                'codnod' => $value->codnod,
                'nroplano' => $value->nroplano,
                'dia_mov' => $value->dia_mov,
                'hora_mov' => $value->hora_mov,
                'codmotv' => $value->codmotv,
                'desmotv' => $value->desmotv,
                'tipreqfin' => $value->tipreqfin,
                'destipreqfin' => $value->destipreqfin,
                'desobsordtrab' => $value->desobsordtrab,
                'desobsordtrab_2' => $value->desobsordtrab_2,
                'canttroba' => $value->canttroba,
                'tipodeingreso' => $value->tipodeingreso,
                'tipodeliquidacion' => $value->tipodeliquidacion,
            ];
          
        });
        

       // return (new FastExcel($output))->download('consultp_down.xlsx');

    }

}
