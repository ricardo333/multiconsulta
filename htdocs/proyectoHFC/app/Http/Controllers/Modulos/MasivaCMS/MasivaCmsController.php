<?php

namespace App\Http\Controllers\Modulos\MasivaCMS;

use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Functions\MapaFunctions;
use App\Administrador\Parametrosrf;
use App\Functions\GestionFunctions;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MasivaCmsFunctions;
use App\Functions\peticionesGeneralesFunctions;

class MasivaCmsController extends GeneralController
{

    public function index(Request $request)
    {
    
        $functionesMasivaCms = new MasivaCmsFunctions;
        $functionesGestion = new GestionFunctions;

        $jefaturas = $functionesMasivaCms->getJefaturasCms();
        $estadosGestion = $functionesGestion->getEstadoAlertas();

        $resultado = array();
        $resultado["estados"] = $estadosGestion;
        $resultado["jefaturas"] = $jefaturas;

        //$request->motivo = "cuadroMando";
        //$request->nodo = "A3";
      
        if (isset($request->motivo)){
            $resultado["motivo"] = $request->motivo;
            $resultado["nodo"] = $request->nodo;
        } 

        return view('administrador.modulos.masivaCms.index',$resultado);
        /*
        return view('administrador.modulos.masivaCms.index',[
                                                    "jefaturas"=>$jefaturas,
                                                    "estados"=>$estadosGestion
                                                      ]);
                                                      */
                                                      
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

            if (isset($request->filtroMotivo)) {
                if ($request->filtroNodo=="Total") {
                    $filtroJefatura = "";
                } else {
                    $filtroJefatura = " and b.codnod='".$request->filtroNodo."'";
                }
            }
            
            if (!$validarJefatura->fails()) {   
                $filtroJefatura = " and jefatura='".$request->filtroJefatura."'";
            }
              
            if (!$validarEstado->fails()) {   
                $filtroEstado = " WHERE estadog='".trim($request->filtroEstado)."'";
            }
            

            $functionesMasivaCms = new MasivaCmsFunctions; 
            $masiva = $functionesMasivaCms->getMasivaCms($filtroJefatura,$filtroEstado); 
            $procesomasiva = $functionesMasivaCms->procesoListaMasivaCms($masiva);
            //dd($procesomasiva);

            
            if ($procesomasiva == "error") {
                return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
            }

            //if(count($procesomasiva)>1){
                return datatables($procesomasiva)->toJson();
            //}else{
                //return $this->errorDataTable("No hay datos disponibles, intente dentro de un minuto por favor.",500);
            //}
               
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

                $funcionMasiva = new MasivaCmsFunctions;
    
                $nodo = $request->nodo;
                $troba = $request->troba;

                $listaCriticos = $funcionMasiva->listaClientesCriticos($nodo,$troba);

                //dd($listaCriticos);

                return datatables($listaCriticos)->toJson();

                
            #END
      }
      return abort(404); 
   }



   public function eliminarMasivaCms(Request $request)
    {
        if($request->ajax()){

            $validaNodoTroba = Validator::make($request->all(), [
                "nodo" => "required|regex:/^[a-zA-Z0-9]+$/",
                "troba" => "required|regex:/^[a-zA-Z0-9]+$/"
            ]);

            $nodo = $request->nodo;
            $troba = $request->troba;

            $functionesMasivaCms = new MasivaCmsFunctions; 
            $elimina = $functionesMasivaCms->eliminarMasivaxNodoTroba($nodo,$troba);
    
            $mensaje = "Se elimino la masiva cuyo nodo:".$nodo." y troba:".$troba;

            //return $mensaje;
            $result = array(
                "mensaje" => $mensaje
            );


            return $this->resultData(array(
                "data"=>$result
            ));
                
        }

        return abort(404); 
    }



    public function verCargaMasiva()
    {
        return view('administrador.modulos.masivaCms.cargarMasiva');

    }



    public function cargaArchivo(Request $request)
    { 
 
        $validaServiciosF = new MasivaCmsFunctions;
 
        if ($request->exportData == "false") {
           
            #PROCESAR DATA
            //$proceso1 = $validaServiciosF->registraHistorico();//Limpia C. T. anteriores del usuario
            
            if($request->hasFile('archivo')){ //valida que exista el archivo
                if ($request->file('archivo')->isValid()) { //valida que se haya cargado el archivo correctamente
                    $archivo = $request->file('archivo');
                    $nombreArhivo = $archivo->getClientOriginalName();  
                    $sizeArchivo = $archivo->getSize(); 
                    if ($sizeArchivo < 1000000) {
                        $content = File::get($archivo);

                        //dd($content);

                        $arrayRegistros = explode("\r\n",$content);   //por saltos de linea  
                        $arrayClientes = array();
                            
                        for ($i=1; $i < count($arrayRegistros) ; $i++) {

                            $regist = $i-1;
                            $arrayClientes[$regist] = explode(",",$arrayRegistros[$i]);
                                
                        }
                            
                        //dd($arrayClientes);

                        $clientesErrados = array();
                        $registrosErrados = array();
                        $clientesObservados = array();
                        $registrosObservados = array();
                        $clientesNoErrados = array();
                        for ($i=0; $i < count($arrayClientes) ; $i++) { 
 
                            if (count($arrayClientes[$i]) < 64) {
                                $registrosErrados[] = $i+1;
                                $clientesErrados[] = $arrayClientes[$i];
                            }elseif (count($arrayClientes[$i]) > 64) {
                                $registrosObservados[] = $i+1;
                                $clientesObservados[] = $arrayClientes[$i];
                            }else{
                                $clientesNoErrados[] = $arrayClientes[$i];
                            }

                        }                           
                                
                        if (count($clientesErrados) == count($arrayClientes)) { 
                            return $this->errorMessage("Todos los datos enviados son inválidos!.",402);
                        }
                        
                        
                        if (count($clientesObservados) > 0) { 
                            return $this->resultData(array(
                                "procesoResult"=>false,
                                "cantidadErrores"=>count($clientesObservados),
                                "errores"=>json_encode($clientesObservados),
                                "dataProcesar"=>json_encode($clientesObservados),
                                "registro"=>$registrosObservados,
                                "nombre"=>""
                            ));
                        }
                        

                        if (count($clientesErrados) > 0 ) {
                            return $this->resultData(array(
                                "procesoResult"=>false,
                                "cantidadErrores"=>count($clientesErrados),
                                "errores"=>json_encode($clientesErrados),
                                "dataProcesar"=>json_encode($clientesNoErrados),
                                "registro"=>$registrosErrados,
                                "nombre"=>""
                            )); 
                        }

                        //dd($clientesNoErrados);
                        $proceso = $validaServiciosF->registraHistorico();

                        //Guardando en los datos de las consultas en BD 
                        $resultadoInsert = $validaServiciosF->registroMasivas($clientesNoErrados);
                               
                        if (!$resultadoInsert) {
                            return $this->errorMessage("Se generó un problema en el servidor, intente nuevamente.",402);
                        }
                        
                        //Guardando en los datos de las consultas en BD
                        $updateMasiva = $validaServiciosF->procesarMasiva();

                        $procesaMasiva1 = $validaServiciosF->actualizarMasiva();

                        //En caso de no existir errores
                        return $this->resultData(array(
                            "procesoResult"=>false,
                            "cantidadErrores"=>0,
                            "errores"=>"",
                            "dataProcesar"=>json_encode($clientesNoErrados),
                            "ruta"=>"",
                            "nombre"=>""
                        ));


                    }else{
                        return $this->errorMessage("La longitud del archivo es superior a 100 KB",402);
                    }
                }else{
                    return $this->errorMessage("El archivo no se cargo correctamente. Intente nuevamente",402);
                } 
            }else{
                return $this->errorMessage("No existe un archivo que procesar. Intente nuevamente",402);
            } 
            #END PROCESO

        }else{
            return $this->errorMessage("No se está indicando el proceso adecuado de validacion.",402);
        }
    }
            
        


    










}