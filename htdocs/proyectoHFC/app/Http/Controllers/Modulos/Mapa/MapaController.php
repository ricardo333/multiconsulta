<?php

namespace App\Http\Controllers\Modulos\Mapa;

use Illuminate\Http\Request;
use App\Functions\MapaFunctions;
use App\Administrador\Parametrosrf;
use App\Administrador\ParametroColores;
use App\Http\Controllers\GeneralController;

class MapaController extends GeneralController
{
    public function verMapa(Request $request)
    {
        
          if($request->ajax()){
 
               #INICIO
                    //$multiconsulta = new MulticonsultaFunctions;
                    $mapaFunctions = new MapaFunctions;

                    //Parametros RF 
                    $parametrosRF = new Parametrosrf;  
                    $paramMapa_detalle = $parametrosRF->getMapaNivelesRF();
                    $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramMapa_detalle);
                    
                    $nodo = $request->n;
                     
                    $troba = $request->t;
                    
                    $id_cliente = $request->id;

                    $arrTap = $mapaFunctions->mapa_tabs($nodo,$troba);
                    
                         //dd($arrTap);
                    $arrAmplif = $mapaFunctions->mapa_amplificador($nodo,$troba);
                    //dd($arrAmplif);
                    
                    $arrTroba = $mapaFunctions->mapa_trobas($nodo,$troba);
                    //dd($arrTroba);

                    $mapa_resultado = $mapaFunctions->mapa_resultado($nodo,$troba);
                    
                    // dd($mapa_resultado);
                    // dd($arrResultado);     
                    
                    $sumaX = 0;
                    $sumaY = 0;
                    $contarXY = 0;
                    // dd($arrResultado);
                    $arrResultado = $mapaFunctions->procesarMapaResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY);
                    //dd($arrResultado);

                     
                    $promedioX =0;
                    $promedioY =0;

                    if($arrResultado["contarXY"]>0){
                         $promedioX = $arrResultado["sumaX"] / $arrResultado["contarXY"];
                         $promedioY = $arrResultado["sumaY"] / $arrResultado["contarXY"];
                    }

                      //dd($arrResultado["resultado"]);
                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.partials.mapa',
                                                  [
                                                       "arrResultado"=>$arrResultado["resultado"],
                                                       "arrTap"=>$arrTap,
                                                       "arrAmplif"=>$arrAmplif,
                                                       "arrTroba"=>$arrTroba,
                                                       "idclientecrm"=>$id_cliente,
                                                       "promedioX"=>$promedioX,
                                                       "promedioY"=>$promedioY
                                                       ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    ); 
               #END
          }
          return abort(404); 
  

    }

    public function verEdificios(Request $request)
    {
          if($request->ajax()){
          
          #INICIO

                    $nom_via=$request->nom_via;
                    $desdtt=$request->desdtt;
                    $num_puer=$request->num_puer;

                    $mapaFunctions = new MapaFunctions;
                    
                    $resultEdif = $mapaFunctions->edificiosList($desdtt,$nom_via,$num_puer);

                
                    //Parametros RF 
                    $parametrosRF = new Parametrosrf;  
                    $paramEdificio_detalle = $parametrosRF->getEdificiosNivelesRF();
                    $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramEdificio_detalle);

                    //Parametros Edificios 
                    $edificiosParametros = ParametroColores::getEdificiosParametros(); 
                    $coloresEdificio = $edificiosParametros->COLORES; 
              
               
                    $ResultFinalEdif = $mapaFunctions->procesarEdificioslist($resultEdif,$coloresEdificio,$dataParametrosRF);
 
                    return datatables($ResultFinalEdif)->toJson();
                      
          #END
          }

          return abort(404); 

    }

    public function verMapaCall(Request $request)
    {
         
          if($request->ajax()){
               #INICIO
                    //dd($request->all());
                    $nodo = $request->n;
                    $troba = $request->t;
                    //$cmts = $request->cmts;
                    //$interface = $request->interface;
                    //$idclientecrm = $request->idclientecrm;

                    $mapaFunctions = new MapaFunctions;
                    $dataMapaCall = $mapaFunctions->getDataMapaCall($nodo,$troba);
                    if (count($dataMapaCall) == 0) {
                         return $this->errorMessage("No se encontraron datos de los clientes, intente con otros datos.",402);  
                    }

                    $sumaX = 0;
                    $sumaY = 0;
                    $contarXY = 0;

                    foreach ($dataMapaCall as $row ) {
                         if ($row->coordX !='') {
                              $sumaX += $row->coordX;
                              $sumaY += $row->coordY;
                              $contarXY++;
                         }
                    }
                    
                    $promedioX = $sumaX / $contarXY;
                    $promedioY = $sumaY / $contarXY;

                    // dd($dataMapaCall);

                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.partials.mapaCall',
                                                  [
                                                       "arrResultado"=>$dataMapaCall,
                                                       "promedioX"=>$promedioX,
                                                       "promedioY"=>$promedioY
                                                       ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    ); 
               #END
          }
          return abort(404); 
          
    }

    public function mapaFuentes(Request $request)
    {
          if($request->ajax()){
               #INICIO
                    $mapaFunctions = new MapaFunctions;

                    //Parametros RF 
                    $parametrosRF = new Parametrosrf;  
                    $paramMapa_detalle = $parametrosRF->getMapaNivelesRF();
                    $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramMapa_detalle);
                    
                    $nodo = $request->n;
                    
                    $troba = $request->t;
                    
                    $id_cliente = $request->id;

                    $arrTap = $mapaFunctions->mapa_tabs($nodo,$troba);
                              
                    //dd($arrTap);
                    $arrAmplif = $mapaFunctions->mapa_amplificador($nodo,$troba);
                    //dd($arrAmplif);
                    
                    $arrTroba = $mapaFunctions->mapa_trobas($nodo,$troba);
                    //dd($arrTroba);

                    $mapa_resultado = $mapaFunctions->mapa_resultado($nodo,$troba);

                    $sumaX = 0;
                    $sumaY = 0;
                    $contarXY = 0;
                    // dd($arrResultado);
                    $arrResultado = $mapaFunctions->procesarMapaResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY);
                    //dd($arrResultado);

                    
                    $promedioX =0;
                    $promedioY =0;

                    if($arrResultado["contarXY"]>0){
                         $promedioX = $arrResultado["sumaX"] / $arrResultado["contarXY"];
                         $promedioY = $arrResultado["sumaY"] / $arrResultado["contarXY"];
                    }

                    //dd($arrResultado["resultado"]);
                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.partials.mapaFuente',
                                                  [
                                                       "arrResultado"=>$arrResultado["resultado"],
                                                       "arrTap"=>$arrTap,
                                                       "arrAmplif"=>$arrAmplif,
                                                       "arrTroba"=>$arrTroba,
                                                       "idclientecrm"=>$id_cliente,
                                                       "promedioX"=>$promedioX,
                                                       "promedioY"=>$promedioY
                                                       ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    ); 

               #END
          }
          return abort(404); 
           
    }
    
}
