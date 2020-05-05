<?php

namespace App\Http\Controllers\Modulos\ConteoModems;

use Illuminate\Http\Request;
use App\Functions\ConteoModemsFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class ConteoModemsController extends GeneralController
{
    public function view()
   {
        return view('administrador.modulos.conteoModems.index');
        
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){
            #INICIO
                $funcionConteoModems = new ConteoModemsFunctions;
                $retornoConteoModems =  $funcionConteoModems->getListaConteoModems();
                //Depuracion de errores
                //dd($retornoConteoModems);
                if ($retornoConteoModems == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $estadosModemsResult = $funcionConteoModems->getProcesarConteoModems($retornoConteoModems);
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
