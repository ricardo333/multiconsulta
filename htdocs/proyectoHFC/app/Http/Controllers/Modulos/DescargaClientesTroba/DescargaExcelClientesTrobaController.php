<?php

namespace App\Http\Controllers\Modulos\DescargaClientesTroba;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Http\Controllers\GeneralController; 
use App\Reportes\Excel\ClientesTroba\ExcelCablemodemSnr;
use App\Reportes\Excel\ClientesTroba\ExcelClientesPorTroba;
use App\Reportes\Excel\ClientesTroba\ExcelTrobasPorPuertos;

class DescargaExcelClientesTrobaController extends GeneralController
{
    public function clientesTroba(Request $request)
    {
        $interbus = $request->interbus;
        $nodo = $request->nodo;
        $troba = $request->troba;

        if($interbus != ""){
            $filtro=" concat(c.cmts,c.interface)in ($interbus) or concat(b.cmts,b.interface) in ($interbus) ";
        }else{
            $filtro= " a.nodo='$nodo' AND a.troba='$troba' ";
        }
 
        $fecha=date('YmdHis');
        $archivo="clientes_por_troba".$fecha.".xlsx";

        $output = Excel::download(new ExcelClientesPorTroba($filtro), $archivo);

        return $output;
    }

    public function trobasPorPuerto(Request $request)
    {
       // dd($request->all());

        $interfaces = $request->interfaces;
        $preprocesarInterf = explode(",",$interfaces);
        $interfacesFinal = "'".implode("','",$preprocesarInterf)."'";
       
        $filtro = " concat(c.cmts,c.interface)in ($interfacesFinal) or concat(b.cmts,b.interface) in ($interfacesFinal) ";

        $fecha=date('YmdHis');
        $archivo="trobasPorPuertos".$fecha.".xlsx";

          
        $output = Excel::download(new ExcelTrobasPorPuertos($filtro), $archivo);

        return $output;

    }

    public function CableModemSnr(Request $request)
    {
        $puerto = $request->puerto;

        $fecha=date('YmdHis');
        $archivo="cablemodem_snr".$fecha.".xlsx";

        $output = Excel::download(new ExcelCablemodemSnr($puerto), $archivo);

        return $output;
    }
}
