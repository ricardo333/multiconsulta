<?php

namespace App\Http\Controllers\Modulos\MonitorFuentes;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Functions\MonitorFuentesFunctions;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Reportes\Excel\MonitorFuentes\HistoricoDownFuente;
use App\Reportes\Excel\MonitorFuentes\monitorFuentesTotal;

class MonitorFuentesExcelController extends GeneralController
{
   public function fuenteHistoricaDown(Request $request)
   {
         if($request->ajax()){
            #INICIO
                $fecha=date('YmdHis');
                $archivo="alertas_down".$fecha.".xlsx";

                $output = Excel::download(new HistoricoDownFuente($request->mac), $archivo);

                return $output;
            #END
         }
         return abort(404); 
      


   }

   public function descargaTotal(Request $request)
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
      
            
            $fecha=date('YmdHis');
            $archivo="monitor_fuentes_total_".$fecha.".xlsx";
            
            $output = Excel::download(new monitorFuentesTotal($filtroNodo,$filtroTipobateria,$filtroEstado), $archivo);
            
            return $output;
         #END
      }

      return abort(404); 
   
   }

}
