<?php

namespace App\Http\Controllers\Modulos\GraficaVisorAverias;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reportes\Excel\GraficaVisorAverias\VisorAveriasExcel;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function excelVisorAverias(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;
        
        $output = Excel::download(new VisorAveriasExcel($request->nodo,$request->troba), 'varse.xlsx');

        return $output;
        
    }
}
