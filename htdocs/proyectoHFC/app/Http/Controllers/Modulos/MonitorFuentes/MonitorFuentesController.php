<?php

namespace App\Http\Controllers\Modulos\MonitorFuentes;

use Illuminate\Http\Request;
use App\Functions\GestionFunctions;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Functions\MonitorFuentesFunctions;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MonitorFuentesController extends GeneralController
{
   public function view()
   {

    $monitorFuentesF = new MonitorFuentesFunctions;
    $functionesGestion = new GestionFunctions;

    $nodos = $monitorFuentesF->nodos();
    $estadosGestion = $functionesGestion->getEstadoAlertas();

     return view('administrador.modulos.monitorFuentes.index',["nodos"=>$nodos,"estados"=>$estadosGestion]);
   }

   public function lista(Request $request)
   { 
        if($request->ajax()){
            #INICIO
                $validarNodo = Validator::make($request->all(), [
                    "nodo" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);
                $validarTipoBateria= Validator::make($request->all(), [
                    "tipoBateria" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);
                $validarEstadoGestion= Validator::make($request->all(), [
                    "estadoDeGestion" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]);
        
                $filtroNodo = "";
                $filtroTipobateria = "";
                $filtroEstado= "";
        
                if (!$validarTipoBateria->fails()) {
                    if (isset($request->tipoBateria)) {   
                        $filtroTipobateria = trim($request->tipoBateria) != "" ? " where ou.marca='".$request->tipoBateria."' " : "";
                    }  
                }
        
                if (!$validarNodo->fails()) {
                    if (isset($request->nodo)) {   
                        $filtroNodo = trim($request->nodo) != "" ? " where zz.nodo='".$request->nodo."' " : "";
                        if (strlen($filtroNodo) > 0) { 
                            if (trim($filtroTipobateria) != "") {
                                $filtroTipobateria .= " and nodo='".$request->nodo."' ";   
                            }else{
                                $filtroTipobateria = " where nodo='".$request->nodo."' ";   
                            }
                                
                        }
                    }  
                }
        
                if (!$validarEstadoGestion->fails()) {
                    if (isset($request->estadoDeGestion)) {   
                        if (trim($filtroTipobateria) != "") {
                            if (trim($request->estadoDeGestion) != "SIN-ESTADO") {
                                $filtroEstado =  trim($request->estadoDeGestion) != "" ? " and estado_ges='".trim($request->estadoDeGestion)."' " : "";
                            }else{
                                $filtroEstado =  trim($request->estadoDeGestion) != "" ? " and estado_ges is NULL " : "";
                            }
                            
                        }else{
                            if (trim($request->estadoDeGestion) != "SIN-ESTADO") {
                                $filtroEstado =  trim($request->estadoDeGestion) != "" ? " where estado_ges='".trim($request->estadoDeGestion)."' " : "";
                            }else{
                                $filtroEstado =  trim($request->estadoDeGestion) != "" ? " where estado_ges is NULL " : "";
                            }
                        } 
        
                    } 
                }
        
                $monitorFuentesF = new MonitorFuentesFunctions;
                $cnfiguracionMF = ParametroColores::getMonitorFuentesParametros();
                
                $cantidadAfectados = $monitorFuentesF->cantidadAfectados($filtroNodo);
        
                if ($cantidadAfectados == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
                $cantidad = $cantidadAfectados[0]->i;
                if ($cantidad == 0) {
                    return datatables([])
                        ->with([
                            'coloresVoltajes' => $cnfiguracionMF->COLORES->segunVoltajes,
                            'segunEstados' => $cnfiguracionMF->COLORES->segunEstados
                        ])
                        ->toJson();
                }
                $listaSegunCantidad = $monitorFuentesF->listaSegunCantidad($cantidad,$filtroTipobateria,$filtroEstado);
        
        
                return datatables($listaSegunCantidad)
                        ->with([
                            'coloresVoltajes' => $cnfiguracionMF->COLORES->segunVoltajes,
                            'segunEstados' => $cnfiguracionMF->COLORES->segunEstados
                        ])
                        ->toJson();
            #END
        }
        
        return abort(404); 
   }

   public function graficoFuentes(Request $request)
   {
        
        $macaddress = trim($request->mac);
        //dd($macaddress );
        $monitorFuentesF = new MonitorFuentesFunctions; 

        $dataGrafico = $monitorFuentesF->getDataGraficoFuentes($macaddress);

        if ($dataGrafico == "error") {
            return $this->errorMessage("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",409);
        }

        if (count($dataGrafico) == 0) {
            return $this->errorMessage("Sin data suficiente para graficar.",500);
        }

        $coloresGeneralF = ParametroColores::getMonitorFuentesParametros();
        $coloresFuente= $coloresGeneralF->COLORES->graficoFuentes->colores;
 

        return $this->resultData(["data"=>$dataGrafico,"coloresFuente"=>$coloresFuente]);
 
   }

   public function editar(Request $request)
   {
    
        if($request->ajax()){
            #INICIO
                $validarMac = Validator::make($request->all(), [
                    "mac" => "required|regex:/^[a-zA-Z0-9.:]+$/",
                ]);

                if ($validarMac->fails()) {   
                    return $this->errorMessage($validarMac->errors()->all(),422);
                } 

                $monitorFuentesF = new MonitorFuentesFunctions; 

                $mac = $request->mac;
        
                //$mac3=substr($mac,0,2).':'.substr($mac,2,2).':'.substr($mac,4,2).':'.substr($mac,6,2).':'.substr($mac,8,2).':'.substr($mac,10,2);
                //$mac4=substr($mac,0,4).'.'.substr($mac,4,4).'.'.substr($mac,8,4);

                $fuenteDetalle = $monitorFuentesF->getDetailsFuentesByMac($mac);

                if ($fuenteDetalle == "error") {
                    return $this->errorMessage("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",409);
                }

                return $this->resultData(["data"=>$fuenteDetalle,"mac"=>$mac]);
            #END
        }

        return abort(404); 
  
   }

   public function update(Request $request)
   {
         //dd($request->all());
        $validar = Validator::make($request->all(), [
            "mac" => "required|regex:/^[a-zA-Z0-9_.:]+$/",
            "nodo" => "nullable|regex:/^[a-zA-Z0-9-_]+$/",
            "troba" => "nullable|regex:/^[a-zA-Z0-9-_]+$/",
            "zonal" => "nullable|regex:/^[a-zA-Z0-9-_]+$/",
            "distrito" => "nullable",
            "direccion" => "nullable",
            "latitudX" => "nullable|regex:/^-?[0-9]\d*(\.\d+)?$/",
            "latitudY" => "nullable|regex:/^-?[0-9]\d*(\.\d+)?$/",
            "marcaDeTroba" => "nullable",
            "respaldo" => "nullable",
            "descripcion" => "nullable",
            "tieneBateria" => "nullable",
            "segundaFuente" => "nullable"
        ]);

        if ($validar->fails()) {   
            return $this->errorMessage($validar->errors()->all(),422);
        } 

       // dd($request->all());

        $monitorFuentesF = new MonitorFuentesFunctions;   
        
        $monitorFuentesF->updateFuente($request->all());

        return $this->mensajeSuccess("Se actualizó la fuente correctamente.");
   }

   public function multilink(Request $request)
   {
        //dd($request->all());

        if($request->ajax()){
            #INICIO
                $IP = $request->ip.":8080";

                // URL
                //  $url = "http://10.164.16.90:8080";
            
            
                $cliente = curl_init();
                // curl_setopt($cliente, CURLOPT_CONNECTTIMEOUT, 8);
                curl_setopt($cliente, CURLOPT_TIMEOUT, 8);
                //curl_setopt($cliente, CURLOPT_SSL_VERIFYPEER, FALSE);
                //curl_setopt($cliente, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($cliente, CURLOPT_URL, $IP);
                curl_setopt($cliente, CURLOPT_REFERER, $IP);
                
                // curl_setopt($cliente, CURLOPT_ENCODING,"");
                curl_setopt($cliente, CURLOPT_HEADER, 0);
                curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true); 
            
                //$report=curl_getinfo($cliente);
                //print_r($report);

                $contenido = curl_exec($cliente);

                if(curl_errno($cliente)){ $contenido = "Error"; }
                
                //print_r($contenido);
                //dd($contenido);
                //dd($contenido);
                if ($contenido === false || $contenido == "" || $contenido== "Error") {
                // dd(curl_error($cliente), curl_errno($cliente));
                curl_close($cliente);
                //dd("no paso po el error");
                return $this->errorMessage("No se encontro el gráfico para la fuente indicada.",500);
                
                }
                curl_close($cliente);
                //dd("paso");
                return $this->mensajeSuccess("Se encontro el grafico de la fuente correctamente.");
            #END
        }
            
        return abort(404); 
   }

   


}
