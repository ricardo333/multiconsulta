<?php

namespace App\Http\Controllers\Modulos\EstadosModems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reportes\Excel\EstadosModems\EstadosModemsExcelTotal;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function excelEstadosModems(Request $request){

        $state = $request->state;
        
        $output = Excel::download(new EstadosModemsExcelTotal($request->state), 'varse.xlsx');

        return $output;
        
    }
}
