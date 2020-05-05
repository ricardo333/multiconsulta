<?php

namespace App\Http\Controllers\Modulos\EstadosModems;

use Illuminate\Http\Request;
use App\Functions\EstadosModemsFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class EstadosModemsController extends GeneralController
{
    public function view()
   {
        //return view('administrador.modulos.estadosModems.index');

        $funcionEstadosModems = new EstadosModemsFunctions;
        $total = $funcionEstadosModems->getTotalEstadosModems();
        $colores = $funcionEstadosModems->getParametroColoresEstadosModems();
        return view('administrador.modulos.estadosModems.index', compact('total','colores') );
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){
            #INICIO
                $funcionEstadosModems = new EstadosModemsFunctions;
                $retornoEstadosModems =  $funcionEstadosModems->getListaEstadosModems();
                //Depuracion de errores
                //dd($retornoEstadosModems);
                if ($retornoEstadosModems == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $estadosModemsResult = $funcionEstadosModems->getProcesarEstadosModems($retornoEstadosModems);
                //dd($estadosModemsResult);
                if ($estadosModemsResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                return datatables($estadosModemsResult)->toJson();
                //dd($caidasMasivas);

            #END

        }
        return abort(404); 
   
    }

}