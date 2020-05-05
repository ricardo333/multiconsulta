<?php

namespace App\Http\Controllers\Modulos\ProblemaSenal;

use DB; 
use Illuminate\Http\Request;
use App\Functions\MapaFunctions;
use App\Administrador\Parametrosrf;
use App\Functions\GestionFunctions;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\ProblemaSenalFunctions;
use App\Functions\peticionesGeneralesFunctions;

class ProblemaSenalController extends GeneralController
{

    public function index()
    {
    
        $functionesProblemaSenal = new ProblemaSenalFunctions;
        $functionesGestion = new GestionFunctions;

        $jefaturas = $functionesProblemaSenal->getJefaturasNiveles();
        $estadosGestion = $functionesGestion->getEstadoAlertas();
      
        
        return view('administrador.modulos.problemaSenal.index',[
                                                    "jefaturas"=>$jefaturas,
                                                    "estados"=>$estadosGestion
                                                      ]);
                                                      
        //return view('administrador.modulos.problemaSenal.index');
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
                $filtroJefatura = "jefatura='".$request->filtroJefatura."' and ";
            }
              
            if (!$validarEstado->fails()) {   
                $filtroEstado = "WHERE xx.estado='".trim($request->filtroEstado)."'";
            }
            

            $functionesProblemaSenal = new ProblemaSenalFunctions; 
            //$problemas = $functionesProblemaSeñal->getProblemaSeñalNiveles($filtroJefatura,$filtroEstado);
            $problemas = $functionesProblemaSenal->getProblemaSenalNiveles($filtroJefatura,$filtroEstado); 
            $procesoproblemas = $functionesProblemaSenal->procesoListaProblemasSenal($problemas);

            
            if ($procesoproblemas == "error") {
                return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
            }

            if(count($procesoproblemas)>1){
                return datatables($procesoproblemas)->toJson();
            }else{
                return $this->errorDataTable("No hay datos disponibles, intente dentro de un minuto por favor.",500);
            }
               
            //return datatables($procesoproblemas)->toJson();

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

                $funcionSenal = new ProblemaSenalFunctions;
    
                $nodo = $request->nodo;
                $troba = $request->troba;

                $listaCriticos = $funcionSenal->listaClientesCriticos($nodo,$troba);

                //dd($listaCriticos);

                return datatables($listaCriticos)->toJson();

                
            #END
      }
      return abort(404); 
   }




















}