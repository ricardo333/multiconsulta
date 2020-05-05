<?php

namespace App\Http\Controllers\Modulos\DiagnosticoMasivo;

use Illuminate\Http\Request;
use App\Administrador\Parametrosrf;
use App\Administrador\ParametroColores;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class DiagnosticoMasivoController extends GeneralController
{
    public function lista(Request $request)
     {
          if($request->ajax()){
               #INICIO

                    $nodo = $request->n;
                    $troba = $request->t;
          
                    $peticionGeneral = new peticionesGeneralesFunctions;
          
                    $diagnosticoMasivo = $peticionGeneral->getDiagnosticoMasivo($nodo,$troba);
          
                    //Parametros RF 
                    $parametrosRF = new Parametrosrf; 
                    $paramDiagMasi_detalle = $parametrosRF->getDiagnosMasiNivelesRF();
                    $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);
          
                    //Parametros DM
                    $DMParametros = ParametroColores::getDiagnosticoMasivoParametros();
                    $coloresEstadoDM = $DMParametros->COLORES->estado;
                    $coloresAveriaDM = $DMParametros->COLORES->averias;
                  
                     
                    $modificandoResult = $peticionGeneral->procesarDiagnosticoMasivoResult($diagnosticoMasivo,$dataParametrosRF,$coloresEstadoDM,$coloresAveriaDM);
                    //dd($modificandoResult);
                    $dataJson = datatables($modificandoResult)->toJson();
               
                    return $dataJson;

               #END
          }
          return abort(404); 
         
     }

}
