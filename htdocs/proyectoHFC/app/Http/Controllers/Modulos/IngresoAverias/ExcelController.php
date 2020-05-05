<?php

namespace App\Http\Controllers\Modulos\IngresoAverias;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reportes\Excel\IngresoAverias\IngresoAveriasReporteArbol;
use App\Reportes\Excel\IngresoAverias\IngresoAveriasResumenIngresos;
use App\Reportes\Excel\IngresoAverias\IngresoAveriasMesExcel;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
ini_set('max_execution_time', 480);
ini_set('memory_limit', '1G');

class ExcelController extends Controller
{

    //Ingreso de Averias por Jefatura y Motivos - Resumen de Ingresos - Descargar excel
    //Ingreso de Averias por Jefatura - Se compara con 1 día antes ... - Descarga de Averías del día
    public function exportAveriasResumenIngresos(Request $request){

        $motivo = trim($request->motivo) != "" ? " and tipodeingreso='".$request->motivo."' " : "";
        $jefatura = trim($request->jefatura) != "" ? " and j.jefatura='".$request->jefatura."' " : "";
        $troba = trim($request->troba) != "" ? " and CONCAT(TRIM(codnod),TRIM(nroplano)) ='".$request->troba."' " : "";
        //$output = Excel::download(new IngresoAveriasResumenIngresos($motivo), 'varse.xlsx');
        //return $output;
        $output = IngresoAveriasResumenIngresos::queryReporteAveriaResumenIngresos($motivo,$jefatura,$troba);
        return (new FastExcel($output))->download('ingreso.xlsx');
        
    }

    //Ingreso de Averias por Jefatura - Se compara con 1 día antes ... - Descarga de Detalle Arbol Última Semana - Ramas Completas
    public function excelAveriaReporte(Request $request){

        $reporte = trim($request->reporte) != "" ? $request->reporte : "";
        $output = IngresoAveriasReporteArbol::queryReporteArbol($reporte);
        //return (new FastExcel($output))->download('reporte-arbol.xlsx');

        return (new FastExcel($output))->download('reporte-arbol.xlsx', function ($value) {
               return [
                   'USUARIO' => $value->USUARIO,
                   'FECHAHORA' => $value->FECHAHORA,
                   'DIA' => $value->DIA,
                   'IDCLIENTECRM' => $value->IDCLIENTECRM,
                   'AVERIA' => $value->AVERIA,
                   'CODREQ' => $value->CODREQ,
                   'FEC_REGISTRO' => $value->FEC_REGISTRO,
                   'PASO0' => $value->PASO0,
                   'DETALLE0' => $value->DETALLE0,
                   'PASO1' => $value->PASO1,
                   'DETALLE1' => $value->DETALLE1,
                   'PASO2' => $value->PASO2,
                   'DETALLE2' => $value->DETALLE2,
                   'PASO3' => $value->PASO3,
                   'DETALLE3' => $value->DETALLE3,
                   'PASO4' => $value->PASO4,
                   'DETALLE4' => $value->DETALLE4,
                   'PASO5' => $value->PASO5,
                   'DETALLE5' => $value->DETALLE5,
                   'PASO6' => $value->PASO6,
                   'DETALLE6' => $value->DETALLE6,
                   'PASO7' => $value->PASO7,
                   'DETALLE7' => $value->DETALLE7,
                   'PASO8' => $value->PASO8,
                   'DETALLE8' => $value->DETALLE8,
                   'PASO9' => $value->PASO9,
                   'DETALLE9' => $value->DETALLE9,
                   'PASO10' => $value->PASO10,
                   'DETALLE10' => $value->DETALLE10,
                   'PASO11' => $value->PASO11,
                   'DETALLE11' => $value->DETALLE11,
                   'PASO12' => $value->PASO12,
                   'DETALLE12' => $value->DETALLE12,
                   'PASO13' => $value->PASO13,
                   'DETALLE13' => preg_replace("/\r|\n/", "", $value->DETALLE13),
                   'PASO14' => $value->PASO14,
                   'DETALLE14' => $value->DETALLE14,
                   'PASO15' => $value->PASO15,
                   'DETALLE15' => $value->DETALLE15,
                   'PASO16' => $value->PASO16,
                   'DETALLE16' => $value->DETALLE16,
                   'PASO17' => $value->PASO17,
                   'DETALLE17' => $value->DETALLE17,
                   'PASO18' => $value->PASO18,
                   'DETALLE18' => $value->DETALLE18,
                   'PASO19' => $value->PASO19,
                   'DETALLE19' => $value->DETALLE19,
                   'PASO20' => $value->PASO20,
                   'DETALLE20' => $value->DETALLE20,
                   'PASO21' => $value->PASO21,
                   'DETALLE21' => $value->DETALLE21,
                   'PASO22' => $value->PASO22,
                   'DETALLE22' => $value->DETALLE22,
               ];
           });
        
    }

    //Ingreso de Averias por Jefatura - Se compara con 1 día antes ... - Descarga de Averías del mes
    public function excelExportAveriasMes(Request $request){
        
        $mes = trim($request->mes) != "" ? $request->mes : "";
        $jefatura = trim($request->jefatura) != "" ? " and j.jefatura='".$request->jefatura."' " : "";
        $troba = trim($request->troba) != "" ? " and CONCAT(TRIM(codnod),TRIM(nroplano)) ='".$request->troba."' " : "";
        $output = IngresoAveriasMesExcel::queryReporteAveriaMesExcel($mes,$jefatura,$troba);
        return (new FastExcel($output))->download('averias-down-t.xlsx');
        
    }

}
