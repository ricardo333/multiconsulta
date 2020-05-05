<?php

namespace App\Http\Controllers\Modulos\MonitorAverias;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reportes\Excel\ExcelAveriasNodoTroba;
use App\Reportes\Excel\ExcelDmpeNodoTroba;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelGestion;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelTotal;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelTotalGpon;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelReverificar;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelSuspendidos;
use App\Reportes\Excel\MonitorAverias\MonitorAveriasExcelEstadoM;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller {
 
    // Export data
    public function excel(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new ExcelAveriasNodoTroba($request->nodo,$request->troba), 'averias.xlsx');

        return $output;
        
    }


    public function excelDMPE(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new ExcelDmpeNodoTroba($request->nodo,$request->troba), 'consultp_down.xlsx');

        return $output;
        
    }


    public function excelReverificar(){

        $outputReverificar = Excel::download(new MonitorAveriasExcelReverificar(), 'ParametrosOK.xlsx');

        return $outputReverificar;

    }


    public function excelSuspendidos(){

        $outputSuspendidos = Excel::download(new MonitorAveriasExcelSuspendidos(), 'Serv_Suspendido.xlsx');

        return $outputSuspendidos;

    }


    public function excelGestion(){

        $fecha=date('YmdHis');
        $archivo="gestion_down_".$fecha.".xlsx";

        $outputGestion = Excel::download(new MonitorAveriasExcelGestion(), $archivo);

        return $outputGestion;

    }


    public function excelTotal(){

        $archivo="averias_down.xlsx";

        $outputExcelTotal = Excel::download(new MonitorAveriasExcelTotal(), $archivo);

        return $outputExcelTotal;

    }


    public function excelTotalGpon(){

        //$archivo="averias_down.csv";
        $archivo="averias_down.xlsx";

        $outputExcelTotalGpon = Excel::download(new MonitorAveriasExcelTotalGpon(), $archivo);

        return $outputExcelTotalGpon;

    }


    public function excelEstadoM(){

        $fecha=date('YmdHis');
        $archivo="estado_m".$fecha.".xlsx";

        $outputEstadoM = Excel::download(new MonitorAveriasExcelEstadoM(), $archivo);

        return $outputEstadoM;

    }


}


?>