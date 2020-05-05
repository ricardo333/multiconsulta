<?php

namespace App\Http\Controllers\Modulos\MonitorAverias;

use DB; 
use Illuminate\Http\Request;
use App\Functions\MapaFunctions;
use App\Administrador\Parametrosrf;
use App\Functions\GestionFunctions;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MonitoreoAveriasFunctions;
use App\Functions\peticionesGeneralesFunctions;

class MonitorAveriasController extends GeneralController
{

   public function index()
   {
    
      $functionesMonitoreoAv = new MonitoreoAveriasFunctions;
      $functionesGestion = new GestionFunctions;

      $ultimoRequerimiento = $functionesMonitoreoAv->maxRegistroMonAverias();
      $jefaturas = $functionesMonitoreoAv->getJefaturasAverias();
      $estadosGestion = $functionesGestion->getEstadoAlertas();
      
        return view('administrador.modulos.monitorAverias.index',[
                                                    "fechaMaxRegistro"=>$ultimoRequerimiento[0]->act,
                                                    "jefaturas"=>$jefaturas,
                                                    "estados"=>$estadosGestion
                                                      ]);
   }

   public function lastDateUpdate(Request $request)
   {
        $functionesMonitoreoAv = new MonitoreoAveriasFunctions; 
        

        /*$monitoreo = $request->monitoreo;

        $ultimoRequerimiento = array((object)[
            "act"=>""
        ]);
        //dd($ultimoRequerimiento);

        if ($monitoreo == "monitor_averias_hfc") {
            $ultimoRequerimiento = $functionesMonitoreoAv->maxRegistroMonAverias();
        }
        if ($monitoreo == "monitor_averias_gpon"){

        }*/

        $ultimoRequerimiento = $functionesMonitoreoAv->maxRegistroMonAverias();

        return $this->resultData(
            array( 
                "datetime" => $ultimoRequerimiento[0]->act
            )
        ); 
 
   }

   public function lista(Request $request)
   {
        if($request->ajax()){

          #INICIO
 
            $filtroJefatura = "";
            $filtroEstado = "";

            $validarJefatura = Validator::make($request->all(), [
                "filtroJefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
            ]); 
            $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                "filtroEstado" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
            ]);  

            if (!$validarJefatura->fails()) {   
                $filtroJefatura = "and a.jefatura='".$request->filtroJefatura."'";
            }  
            if (!$validarEstado->fails()) {   
                $filtroEstado = "WHERE xx.estado='".trim($request->filtroEstado)."'";
            } 
           
            // dd($request->all());
            $validarfiltroHfcGpon = Validator::make($request->all(), [
                "filtroHfcGpon" => "required|in:monitor_averias_hfc,monitor_averias_gpon|regex:/^[a-z_]+$/"
            ]);
            if ($validarfiltroHfcGpon->fails()) {   
                return $this->errorMessage("El filtro HFC o GPON no es válido. Seleccione un filtro válido",402);
            } 

            $filtroHfcGpon = $request->filtroHfcGpon; //Filtrar Hfc o Gpon segun este parametro
          
            if ($filtroHfcGpon == "monitor_averias_hfc") {

                

                $functionesMonitoreoAv = new MonitoreoAveriasFunctions; 
                $averias = $functionesMonitoreoAv->getMonitorAveriasHfc($filtroJefatura,$filtroEstado); 
                $procesoaverias = $functionesMonitoreoAv->procesoListaMonitorAveriasHfc($averias);
                
                return datatables($procesoaverias)->toJson();
            }

            if ($filtroHfcGpon == "monitor_averias_gpon") {
 
                $functionesMonitoreoAv = new MonitoreoAveriasFunctions;
                $averias = $functionesMonitoreoAv->getMonitorAveriasGpon($filtroJefatura,$filtroEstado);
                $procesoaverias = $functionesMonitoreoAv->procesoListaMonitorAveriasGpon($averias);
                return datatables($procesoaverias)->toJson();
            }

            return $this->errorMessage("El filtro HFC o GPON no es válido. Seleccione un filtro válido",402);
          
           
          #END

       }
       return abort(404); 
     
   }
 
   public function verMapa(Request $request)
   {
        if($request->ajax()){
    
            #INICIO
                //$multiconsulta = new MulticonsultaFunctions;
                $mapaFunctions = new MapaFunctions;

                //Parametros RF 
                $parametrosRF = new Parametrosrf;  
                $paramDiagMasi_detalle = $parametrosRF->getMapaNivelesRF();
                $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);
                
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

                
                $promedioX =0;
                $promedioY =0;

                if($arrResultado["contarXY"]>0){
                    $promedioX = $arrResultado["sumaX"] / $arrResultado["contarXY"];
                    $promedioY = $arrResultado["sumaY"] / $arrResultado["contarXY"];
                }

                
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
 

}
