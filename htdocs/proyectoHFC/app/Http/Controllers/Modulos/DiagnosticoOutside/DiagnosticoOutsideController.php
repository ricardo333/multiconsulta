<?php

namespace App\Http\Controllers\Modulos\DiagnosticoOutside;

use Illuminate\Http\Request;
use App\Functions\MapaDiagnosticoOutsideFunctions;
use App\Administrador\Parametrosrf;
use App\Http\Controllers\Controller;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class DiagnosticoOutsideController extends GeneralController
{

   public function view()
   {
      //$mapaFunction = new MapaDiagnosticoOutsideFunctions;
      //$jefaturas = $mapaFunction->getjefaturasAndLatLong();
      //return view('administrador.modulos.mapaLlamadasPeru.index',["jefaturas"=>$jefaturas]);
      return view('administrador.modulos.diagnosticoOutside.index');
   }


   
   public function verMapaOutside(Request $request)
   {
        
      if($request->ajax()){
 
         #INICIO   
         $mapaFunctions = new MapaDiagnosticoOutsideFunctions;

         //Parametros RF 
         $parametrosRF = new Parametrosrf;  
         $paramDiagMasi_detalle = $parametrosRF->getMapaNivelesRF();
         $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);

         $latitud = $request->latitud;
         $longitud = $request->longitud;
         $distancia = $request->distancia;

         //$id_cliente = $request->id;
                    
         $arrTap = $mapaFunctions->mapa_tabs($latitud,$longitud);
                    
         $arrAmplif = $mapaFunctions->mapa_amplificador($latitud,$longitud);
                    
         //$arrTroba = $mapaFunctions->mapa_trobas($nodo,$troba);
         //dd($arrTroba);
         /*
         $arrTroba = array("nodo" => 'xx',
                              "troba" => 'xx',
                              "troba_x" => $latitud,	
                              "troba_y" => $longitud);
                              */


         $arrUsuario = array("usuario_x" => $latitud,
                              "usuario_y" => $longitud);


         $mapa_resultado = $mapaFunctions->mapa_resultado($latitud,$longitud,$distancia);    
                    
         $sumaX = 0;
         $sumaY = 0;
         $contarXY = 0;
         //dd($arrResultado);
         $arrResultado = $mapaFunctions->procesarMapaResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY);

         $promedioX =0;
         $promedioY =0;

         if($arrResultado["contarXY"]>0){
            $promedioX = $arrResultado["sumaX"] / $arrResultado["contarXY"];
            $promedioY = $arrResultado["sumaY"] / $arrResultado["contarXY"];
         }

                      
         return $this->resultData(
                        array( 
                           'html' => json_encode(view(
                                    'administrador.partials.mapaOutside',
                                       [
                                       "arrResultado"=>$arrResultado["resultado"],
                                       //"arrTap"=>$arrTap,
                                       //"arrAmplif"=>$arrAmplif,
                                       //"arrTroba"=>$arrTroba,
                                       //"idclientecrm"=>$arrResultado["resultado"],
                                       "idclientecrm"=>"0",
                                       "arrUsuario"=>$arrUsuario,
                                       "promedioX"=>$promedioX,
                                       "promedioY"=>$promedioY
                                       ]
                                    )->render(),JSON_UNESCAPED_UNICODE),
                        )
                     ); 

         }

         return abort(404); 

    }


    public function consultaClientes(Request $request)
    {

      if($request->ajax()){
 
         #INICIO   
         $mapaFunctions = new MapaDiagnosticoOutsideFunctions;

         //Parametros RF 
         $parametrosRF = new Parametrosrf;  
         $paramDiagMasi_detalle = $parametrosRF->getMapaNivelesRF();
         $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);

         $latitud = $request->latitud;
         $longitud = $request->longitud;
         $distancia = $request->distancia;

         $arrUsuario = array("usuario_x" => $latitud, "usuario_y" => $longitud);

         $mapa_resultado = $mapaFunctions->mapa_resultado($latitud,$longitud,$distancia);    
                    
         $sumaX = 0;
         $sumaY = 0;
         $contarXY = 0;

         $arrResultado = $mapaFunctions->procesarMapaResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY);

         $promedioX =0;
         $promedioY =0;

         if($arrResultado["contarXY"]>0){
            $promedioX = $arrResultado["sumaX"] / $arrResultado["contarXY"];
            $promedioY = $arrResultado["sumaY"] / $arrResultado["contarXY"];
         }

         $clientes = $arrResultado["resultado"];
         //dd($clientes);

         $diagnosClientes = $mapaFunctions->diagnosticarClienteSnmp($clientes);

         return $this->resultData(
                     array( 
                        'html' => json_encode(view(
                                 'administrador.partials.mapaOutside',
                                    [
                                    "arrResultado"=>$diagnosClientes["resultado"],
                                    //"arrTap"=>$arrTap,
                                    //"arrAmplif"=>$arrAmplif,
                                    //"arrTroba"=>$arrTroba,
                                    //"idclientecrm"=>$arrResultado["resultado"],
                                    "idclientecrm"=>"0",
                                    "arrUsuario"=>$arrUsuario,
                                    "promedioX"=>$promedioX,
                                    "promedioY"=>$promedioY
                                    ]
                                 )->render(),JSON_UNESCAPED_UNICODE),
                     )
                  ); 

      }

      return abort(404); 


    }
    


}