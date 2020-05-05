<?php

namespace App\Http\Controllers\Modulos\CaidasMasivas;


use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Reportes\Excel\ExcelAveriasNodoTroba;
use App\Reportes\Excel\ExcelDmpeNodoTroba;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelNodoTroba;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelEnergia;
use App\Reportes\Excel\CaidasMasivas\CaidasMasivasExcelTotal;
use Maatwebsite\Excel\Facades\Excel;

class CaidasMasivasExcelController extends GeneralController 
{

    public function excelAlertasDown(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $fecha=date('YmdHis');
        $archivo="alertas_down".$fecha.".xlsx";

        $output = Excel::download(new CaidasMasivasExcelNodoTroba($request->nodo,$request->troba), $archivo);

        return $output;
        
    }


    public function excelAverias(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new ExcelAveriasNodoTroba($request->nodo,$request->troba), 'averias.xlsx');

        return $output;
        
    }


    public function excelDmpe(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new ExcelDmpeNodoTroba($request->nodo,$request->troba), 'consultp_down.xlsx');

        return $output;
        
    }


    public function excelEnergia(Request $request){

        $nodo = $request->nodo;
        $troba = $request->troba;

        $output = Excel::download(new CaidasMasivasExcelEnergia($request->nodo,$request->troba), 'clientes_ttpp.xlsx');

        return $output;
        
    }


    //public function excelCaidasTotal(){
    public function excelCaidasTotal(Request $request){

        $tipoCaida = $request->tipoCaida;
        $motivo = $request->motivo;
        $nodo = $request->nodo;
        $filt1 = $request->filt1;
        $filt2 = $request->filt2;

        if ($motivo=="cuadroMando") {
            if (strlen($nodo)==2) {
                $filtro1 = "and b.codnod='$nodo'";
                $filtro2 = " ";
            } else {
                $filtro1 = " ";
                $filtro2 = " ";
            }
        }

        if ($motivo=="modulo") {
            if ($tipoCaida=="caidas_amplificador") {
                if ($filt1=="seleccionar" && $filt2=="seleccionar") {
                    $filtro1 = " ";
                    $filtro2 = " ";
                } elseif ($filt1 != "seleccionar" && $filt2=="seleccionar") {
                    $filtro1 = " HAVING jefatura='".$filt1."'";
                    $filtro2 = " ";
                } elseif ($filt1 == "seleccionar" && $filt2!="seleccionar") {
                    $filtro1 = " ";
                    $filtro2 = " WHERE CONCAT(a.nodo,a.troba) ='".$filt2."'";
                } elseif ($filt1 != "seleccionar" && $filt2!="seleccionar") {
                    $filtro1 = " HAVING jefatura='".$filt1."'";
                    $filtro2 = " WHERE CONCAT(a.nodo,a.troba) ='".$filt2."'";
                }
            } else {
                if ($filt1=="seleccionar" && $filt2=="seleccionar") {
                    $filtro1 = " ";
                    $filtro2 = " ";
                } elseif ($filt1 != "seleccionar" && $filt2=="seleccionar") {
                    $filtro1 = " and jefatura='".$filt1."'";
                    $filtro2 = " ";
                } elseif ($filt1 == "seleccionar" && $filt2!="seleccionar") {
                    $filtro1 = " ";
                    $filtro2 = " WHERE estadog='".trim($filt2)."'";
                } elseif ($filt1 != "seleccionar" && $filt2!="seleccionar") {
                    $filtro1 = " and jefatura='".$filt1."'";
                    $filtro2 = " WHERE estadog='".trim($filt2)."'";
                }
            }
            
        }
        

        $fecha=date('YmdHis');
        $archivo="alertas_report".$fecha.".xlsx";

        //$outputCaidasTotal = Excel::download(new CaidasMasivasExcelTotal(), $archivo);
        $outputCaidasTotal = Excel::download(new CaidasMasivasExcelTotal($tipoCaida,$filtro1,$filtro2), $archivo);

        return $outputCaidasTotal;

    }



}


?>