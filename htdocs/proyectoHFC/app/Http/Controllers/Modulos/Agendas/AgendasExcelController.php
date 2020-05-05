<?php

namespace App\Http\Controllers\Modulos\Agendas;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Reportes\Excel\Agendas\AgendaTotalExcel;
use App\Reportes\Excel\Agendas\AgendaUltimaSemanaExcel;

class AgendasExcelController extends GeneralController
{

    public function agendaTotal(Request $request)
    {
        if($request->ajax()){
            #INICIO
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "estado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]);  

                $validarCodCliente = Validator::make($request->all(), [
                    "codigoCliente" => "nullable|regex:/^[0-9]+$/"
                ]);

                $filtroEstado = "";
                $filtroCodCli = "";
                //dd($request->all());

                if (!$validarEstado->fails()) {
                    if (isset($request->estado)) {   
                        $filtroEstado = trim($request->estado) != "" ? " and a.estado='".$request->estado."' " : "";
                    }  
                }

                if (!$validarCodCliente->fails()) {
                    if (isset($request->codigoCliente)) {   
                        $filtroCodCli = trim($request->codigoCliente) != "" ? " and a.codcli='".$request->codigoCliente."' " : "";
                    }  
                }

                $fecha=date('YmdHis');
                $archivo="agendasTotal_".$fecha.".xlsx";

                $output = Excel::download(new AgendaTotalExcel($filtroEstado,$filtroCodCli), $archivo);

                return $output;
            #END
        }

        return abort(404); 
            
    }

    public function agendaUltimaSemana(Request $request)
    {
        if($request->ajax()){
            #INICIO
                 
                $fecha=date('YmdHis');
                $archivo="agenda_ultima_semana_".$fecha.".xlsx";

                $output = Excel::download(new AgendaUltimaSemanaExcel(), $archivo);

                return $output;
            #END
        }

        return abort(404); 
            
    }

}
