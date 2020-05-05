<?php

namespace App\Http\Controllers\Modulos\MasivaCMS;


use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Reportes\Excel\ExcelAveriasNodoTroba;
use App\Reportes\Excel\ExcelDmpeNodoTroba;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelNodoTroba;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelEnergia;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelTotal;
use App\Reportes\Excel\MasivaCms\AveriasCmsExcelTotal;
use App\Reportes\Excel\MasivaCms\MasivaCmsExcelTotal;
use Maatwebsite\Excel\Facades\Excel;

ini_set('max_execution_time', 800);

class MasivaCmsExcelController extends GeneralController 
{
    // Export data
    public function excel(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new ExcelAveriasNodoTroba($request->nodo,$request->troba), 'averias.xlsx');

        return $output;
        
    }
    

    public function excelAlertasDown(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $fecha=date('YmdHis');
        $archivo="alertas_down".$fecha.".xlsx";

        $output = Excel::download(new CaidasMasivasExcelNodoTroba($request->nodo,$request->troba), $archivo);

        return $output;
        
    }

    //Masiva Total --- Implementar para Cuadro Mando
    public function excelMasivasTotal(Request $request){

        $motivo = $request->motivo;
        $nodo = $request->nodo;
        $jefatura = $request->jefatura;
        $estado = $request->estado;


        if ($motivo=="cuadroMando") {
            if ($nodo=="Total") {
                $filtro1 = " ";
                $filtro2 = " ";
            } else {
                $filtro1 = "and b.codnod='$nodo'";
                $filtro2 = " ";
            }
        }

        if ($motivo=="modulo") {
            if ($jefatura=="seleccionar" && $estado=="seleccionar") {
                $filtro1 = " ";
                $filtro2 = " ";
            } elseif ($jefatura != "seleccionar" && $estado=="seleccionar") {
                $filtro1 = " and jefatura='".$jefatura."'";
                $filtro2 = " ";
            } elseif ($jefatura == "seleccionar" && $estado!="seleccionar") {
                $filtro1 = " ";
                $filtro2 = " WHERE estadog='".trim($estado)."'";
            } elseif ($jefatura != "seleccionar" && $estado!="seleccionar") {
                $filtro1 = " and jefatura='".$jefatura."'";
                $filtro2 = " WHERE estadog='".trim($estado)."'";
            }
        }


        $fecha=date('YmdHis');
        $archivo="alertas_report".$fecha.".xlsx";

        $output = Excel::download(new MasivaCmsExcelTotal($filtro1,$filtro2), $archivo);
        //$output = Excel::download(new MasivaCmsExcelTotal($request->motivo,$request->nodo), $archivo);

        return $output;
        
    }

    public function excelAveriasTotal(){

        $archivo="averias_down.xlsx";

        $output = Excel::download(new AveriasCmsExcelTotal(), $archivo);

        return $output;
        
    }




}

?>
