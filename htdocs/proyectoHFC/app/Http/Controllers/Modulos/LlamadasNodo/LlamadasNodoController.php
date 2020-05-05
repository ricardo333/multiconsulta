<?php

namespace App\Http\Controllers\Modulos\LlamadasNodo;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Functions\LlamadasNodoFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MonitoreoAveriasFunctions;

class LlamadasNodoController extends GeneralController
{
    public function view()
   {
        
        $functionesJefaturas = new MonitoreoAveriasFunctions;
        $jefaturas = $functionesJefaturas->getJefaturasAverias();
        return view('administrador.modulos.llamadasNodo.index',[
            "jefaturas"=>$jefaturas
        ]);
        
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){

            #INICIO
                
                
                $validarJefatura = Validator::make($request->all(), [
                    "filtroJefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);
                
                $filtroJefatura = "";

                if (!$validarJefatura->fails()) {
                    if (isset($request->filtroJefatura)) {     
                        $filtroJefatura = trim($request->filtroJefatura) != "" ? " and zo.jefatura='".$request->filtroJefatura."' " : "";
                    }
                }

                $funcionLlamadasNodo = new LlamadasNodoFunctions;
                $retornoLlamadasNodo =  $funcionLlamadasNodo->getListaLlamadasNodo($filtroJefatura);
                //Depuracion de errores
                //dd($retornoLlamadaNodo);
                
                if ($retornoLlamadasNodo == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                
                $llamadasNodoResult = $funcionLlamadasNodo->getProcesarLlamadasNodo($retornoLlamadasNodo);
                //dd($llamadasNodoResult);
                
                if ($llamadasNodoResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
                
                return datatables($llamadasNodoResult)->toJson();
                //return datatables($retornoLlamadasNodo)->toJson();
                
            #END

        }
        return abort(404); 
   
   }

}
