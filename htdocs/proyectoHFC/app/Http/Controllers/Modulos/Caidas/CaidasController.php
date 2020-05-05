<?php

namespace App\Http\Controllers\Modulos\Caidas;

use Illuminate\Http\Request;
use App\Functions\CaidasFunctions;
use App\Functions\GestionFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MonitoreoAveriasFunctions;

class CaidasController extends GeneralController
{

   public function view(Request $request)
   {

        $functionesMonitoreoAv = new MonitoreoAveriasFunctions;
        $functionesGestion = new GestionFunctions;
        $funcionCaida = new CaidasFunctions;

        $jefaturas = $functionesMonitoreoAv->getJefaturasAverias();
        $estadosGestion = $functionesGestion->getEstadoAlertas();
        $trobas = $funcionCaida->getNodoTrobas();

        $resultado = array();
        $resultado["jefaturas"] = $jefaturas;
        $resultado["estados"] = $estadosGestion;
        $resultado["trobas"] = $trobas;

        if (isset($request->motivo)){
            $resultado["motivo"] = $request->motivo;
            $resultado["nodo"] = $request->nodo;
            //caidas_amplificador
        }

        return view('administrador.modulos.caidas.index',$resultado);

        /*
        return view('administrador.modulos.caidas.index',[
            "jefaturas"=>$jefaturas,
            "estados"=>$estadosGestion,
            "trobas"=>$trobas
        ]);
        */
   }

   public function lista(Request $request)
   { 
         if($request->ajax()){

            #INICIO
               
                $validaTipo = Validator::make($request->all(), [
                    "tipoCaida" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-z_]+$/"
                ]);  
                if ($validaTipo->fails()) {     
                        return $this->errorDataTable($validaTipo->errors()->all(),402);
                } 

                $validarJefatura = Validator::make($request->all(), [
                    "filtroJefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]); 
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "filtroEstado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]);  
                $validarNodo = Validator::make($request->all(), [
                    "nodo" => "nullable|regex:/^[a-zA-Z0-9_-]+$/"
                ]);  
                $validarNodoTroba = Validator::make($request->all(), [
                    "troba" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9]+$/"
                ]);  

                 
                $filtroJefatura = "";
                $filtroEstado = "";
                $filtroNodo = "";
                $filtroTroba = "";

                if (!$validarJefatura->fails()) {
                    if (isset($request->filtroJefatura)) {   
                        $filtroJefatura = trim($request->filtroJefatura) != "" ? " and a.jefatura='".$request->filtroJefatura."' " : "";
                    }  
                }
                if (!$validarEstado->fails()) {
                    if (isset($request->filtroEstado)) {   
                        $filtroEstado =  trim($request->filtroEstado) != "" ? trim($request->filtroEstado) : "";
                    } 
                }
                if (!$validarNodo->fails()) {
                    if (isset($request->nodo)) {   
                        $filtroNodo = trim($request->nodo) != "" ? " and a.nodo='".$request->nodo."' " : "";
                    } 
                }
                if (!$validarNodoTroba->fails()) {
                    if (isset($request->troba)) {   
                        $filtroTroba = trim($request->troba) != "" ? " WHERE CONCAT(a.nodo,a.troba) ='".$request->troba."' " : "";
                    } 
                }
 
                
                $tipoCaida = $request->tipoCaida;

                //dd($request->all());
                //dd($filtroJefatura);
                $funcionCaida = new CaidasFunctions;

                if ($tipoCaida == "caidas_masivas") {
                    //dd($filtroJefatura);
                    $retornoCaida =  $funcionCaida->getListaCaidaMasiva($filtroJefatura,$filtroNodo);
                    //dd($retornoCaida);
                
                    if ($retornoCaida == "error") {
                        return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    } 
                    $caidaResult = $funcionCaida->getProcesarCaidasMasivas($retornoCaida,$filtroEstado);
 
                }elseif ($tipoCaida == "caidas_noc") {

                    $retornoCaida =  $funcionCaida->getListaCaidaNoc($filtroJefatura,$filtroNodo);
                    if ($retornoCaida == "error") return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    $caidaResult = $funcionCaida->getProcesarCaidasNoc($retornoCaida,$filtroEstado); 

                }elseif ($tipoCaida == "caidas_torre") {
                    $retornoCaida =  $funcionCaida->getListaCaidaTorreHfc($filtroJefatura,$filtroNodo);
                    //print_r("aqui...");
                    //dd($retornoCaida);
                    if ($retornoCaida == "error") return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    $caidaResult = $funcionCaida->getProcesoTorreHfc($retornoCaida,$filtroEstado); 
                }elseif ($tipoCaida == "caidas_amplificador") {
                    if (!$validarJefatura->fails()) {
                        if (isset($request->filtroJefatura)) {   
                            $filtroJefatura = trim($request->filtroJefatura) != "" ? " HAVING  jefatura='".$request->filtroJefatura."' " : "";
                        }  
                    } 
                  
                    //dd($filtroJefatura);
                    $retornoCaida =  $funcionCaida->getListaCaidaAmplificador($filtroJefatura,$filtroTroba);
                     //print_r("aqui...");
                     //dd($retornoCaida);
                    if ($retornoCaida == "error") return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    $caidaResult = $funcionCaida->getProcesoAmplificador($retornoCaida,$filtroEstado); 
                    //print_r("termino proceso...");
                    //dd($caidaResult);
                }

                if ($caidaResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                
                //print_r("Termino todo...");
                return datatables($caidaResult)->toJson();
                //dd($caidasMasivas);
            #END

        }
        return abort(404); 
   
   }

   public function listaClientesCriticos(Request $request)
   {
      if($request->ajax()){
            #INICIO

                $validaNodoTroba = Validator::make($request->all(), [
                    "nodo" => "required|regex:/^[a-zA-Z0-9]+$/",
                    "troba" => "required|regex:/^[a-zA-Z0-9]+$/"
                ]);


                if ($validaNodoTroba->fails()) {   
                    return $this->errorDataTable($validaNodoTroba->errors()->all(),402);
                } 

                $funcionCaida = new CaidasFunctions;
    

                $nodo = $request->nodo;
                $troba = $request->troba;
                $amplificador = "";

                $validarJefatura = Validator::make($request->all(), [
                    "amplificador" => "nullable|regex:/^[a-zA-Z0-9\-_]+$/"
                ]); 

                $dataAmplificador = isset($request->filtroJefatura)? $request->filtroJefatura : "";

                if (!$validarJefatura->fails()) {   
                    $amplificador = trim($dataAmplificador) == ""? "" : " and a.jefatura='".$dataAmplificador."' ";
                }  

                $listaCriticos = $funcionCaida->listaClientesCriticos($nodo,$troba,$amplificador);

                //dd($listaCriticos);

                return datatables($listaCriticos)->toJson();

                
            #END
      }
      return abort(404); 
   }

   

}
