<?php

namespace App\Http\Controllers\Modulos\Multiconsulta;

use Illuminate\Http\Request;
use App\Functions\LogsFunctions;
use App\Functions\MapaFunctions;
use App\Functions\UserFunctions;
use App\Administrador\Parametrosrf;
use App\Functions\IntrawayFunctions;
use Illuminate\Support\Facades\Auth;
use App\Administrador\ParametroColores;
use App\Functions\ConexionSshFunctions;
use Illuminate\Database\QueryException;
use App\Functions\MulticonsultaFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\CablemodemStatusFunctions;
use App\Functions\peticionesGeneralesFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;


class MulticonsultaController extends GeneralController
{
     public function index()
     {  
          return view('administrador.modulos.multiconsulta.index');
     }

     private function procesarResultIntraway($cliente)
     {
          $multiconsultaFuntions = new MulticonsultaFunctions;
          $multiplesClientes = $cliente["report"][0]["Docsis"];
          $recordP = $multiconsultaFuntions->resBusVarios($cliente["report"][0]["idClienteCRM"]);

          //dd($recordP);
          for ($i=0; $i < count($multiplesClientes); $i++) { 

               for ($l=0; $l < count($recordP) ; $l++) { 
                    if ($multiplesClientes[$i]["Macaddress"] == $recordP[$l]->MACADDRESS) {
                         $cliente["report"][0]["Docsis"][$i]["multiconsulta"] = $recordP[$l];
                    }
               } 
          } 
          //dd($cliente);
          return $this->resultData(array(
               "cantidad"=>count($multiplesClientes),
               "resultado"=>json_encode($cliente),
               "type"=>"intraway"
          )); 

          
     }

     
     public function search(Request $request)
     { 
           
         if($request->ajax()){ 
               #INICIO
                         //obtener la cantidad de resultados de la consulta  
                         $usuarioAuth = Auth::user();
                         $rolNombre = $usuarioAuth->role->nombre;
                         $usuario = $usuarioAuth->username;
                         $fech_hor = date("Y-m-d H:i:s"); 

                         $tipoBus = $request->type_data;
                         $bus = $request->text;
                         
                         $multiconsultaFuntions = new MulticonsultaFunctions;
                         $intrawayPeticion = new IntrawayFunctions;

                         $multiconsultaFuntions->validarSearch($bus,$tipoBus);//verifica que la consulta sea valida


                         $multiconsultaParametros = ParametroColores::getMulticonsultaParametros();
                         $coloresMulticonsulta = $multiconsultaParametros->COLORES; 
                         $configuracionMulticonsulta = $multiconsultaParametros->CONFIG;
                         $registroMulticonsulta = $configuracionMulticonsulta->registro_consultas;

                         $existePermisoRegistro = false;

                         foreach ($registroMulticonsulta as $param) {
                              if ($param->nombre == $rolNombre) {
                                   $existePermisoRegistro = true;
                                   $ultimosMinutos = $param->parametro;
                                   break;
                              }
                         }

                         if ($tipoBus == 1) {

                              #MODELAR PENDIENTE MEJORAS MAS ADELTANTE
                              $clienteExcedidos = $multiconsultaFuntions->codsCliConMuchosServicios();
                              //dd($clienteExcedidos);
                              foreach ($clienteExcedidos as $codCli) {
                                   if ((int)$codCli->idclientecrm == $bus) {
                                        return $this->resultData(array(
                                             "cantidad"=>20,
                                             "resultado"=>"",
                                             "type"=>"intraway"
                                        )); 
                                   }
                              } 
                              #END

                              $cliente = $intrawayPeticion->intraway_cliente($bus);
                              
                              if ($cliente != "error") {
                                   //Multiples resultados intraway 
                                   if (count($cliente["report"][0]["Docsis"]) > 1) {
                                        //PROCESAR PETICION INTRAWAY Y BD 
                                       return  $this->procesarResultIntraway($cliente); 
                                   }
                                    
                              } 

                         }
 
                         #SI ES MAC
                              elseif ($tipoBus == 2) {  

                                   //Se optendra el codigo del cliente por MAC 
                                   $codigoCliente = $multiconsultaFuntions->getDataClientByMac($bus);
                                  
                                   if (count($codigoCliente) > 0) { //Existencia de un resultado mínimo de 1

                                        
                                        $cliente = $intrawayPeticion->intraway_cliente((double)$codigoCliente[0]->IDCLIENTECRM);

                                        
                                        
                                        if ($cliente != "error"){
                                             $cantidadResultados = count($cliente["report"][0]["Docsis"]);
                                             if ($cantidadResultados > 1) {
                                                  //Se buscará al cliente por su MACADDRESS
                                                  for ($i=0; $i < $cantidadResultados; $i++) { 
                                                        if ($cliente["report"][0]["Docsis"][$i]["Macaddress"] != $bus) {
                                                            unset($cliente["report"][0]["Docsis"][$i]);
                                                        }
                                                  } 
                                             } 

                                        }
                                        
                                         
                                   }else{
                                        $cliente = "error";
                                   }

                                   
                                   
  
                              }
                         #END SI ES MAC
                         #SI ES TELEFONO TFA, HFC, DNI o RUC(Desarrollando aún)
                              else {   
                             
                                   if ($tipoBus == 5) { //DNI
                                        $resultadoNCli = $multiconsultaFuntions->getCodClientByDNI($bus);
                                        
                                        if (count($resultadoNCli) > 0) {
                                                  $tipoBus = 1;
                                                  $bus = $resultadoNCli[0]->CLIENTE;
                                        }else{
                                             if (count($resultadoNCli) == 0) {   
                                                  return $this->resultData(array(
                                                       "cantidad"=>0,
                                                       "resultado"=>[]
                                                  )); 
                                             }
                                        }
                                   }
                                   if ($tipoBus == 6) {//RUC
                                        $resultadoNCli = $multiconsultaFuntions->getCodClientByRUC($bus);
                                        
                                        if (count($resultadoNCli) > 0) {
                                                  $tipoBus = 1;
                                                  $bus = $resultadoNCli[0]->CLIENTE;
                                        }else{
                                             if (count($resultadoNCli) == 0) {   
                                                  return $this->resultData(array(
                                                       "cantidad"=>0,
                                                       "resultado"=>[]
                                                  )); 
                                             }
                                        }
                                   }

                              //Ya se tiene el codigo del cliente...

                                   $cliente = $intrawayPeticion->intraway_cliente($bus);
                              
                                   if ($cliente != "error") {
                                        //Multiples resultados intraway 
                                        if (count($cliente["report"][0]["Docsis"]) > 1) {
                                             /*return $this->resultData(array(
                                                  "cantidad"=>count($cliente["report"][0]["Docsis"]),
                                                  "resultado"=>json_encode($cliente),
                                                  "type"=>"intraway"
                                             ));*/
                                              //PROCESAR PETICION INTRAWAY Y BD 
                                             return  $this->procesarResultIntraway($cliente); 
                                        }
                                         
                                   } 
   
                              }
                         #END TELEFONO TFA, HFC o DNI


                         #PROCESO CON CODIGO DEL CLIENTE
                                
                              //se genero un error en peticion con intraway pero debe continuar ..
                              $armado_query = $multiconsultaFuntions->ArmandoQuery($tipoBus,$bus);
                              $recordP= $multiconsultaFuntions->queryPrincipal($armado_query["filtroWhere"],$armado_query["limit"]);

                              
                              if (count($recordP) > 1) {
                                   $recordP = $multiconsultaFuntions->resBusVarios($recordP[0]->IDCLIENTECRM);
                                   return $this->resultData(array(
                                        "cantidad"=>count($recordP),
                                        "resultado"=>json_encode($recordP),
                                        "type"=>"nclientes"
                                   )); 
                              }

                                   
                              if ( (count($recordP) == 0 || $recordP == "error") &&  $armado_query["TipBus"] !="MACADDRESS") {
 
                                  
                                   $infoRequeCatv = [];
                                   $detallePlantaCl = [];
                                   $detalleAdsl = [];

                                   $requeInfo = $multiconsultaFuntions->getInfoRequerimientoCATV($bus); //Buscando CATV 
                                   
                                   if (count($requeInfo) == 1) {
                                        $infoRequeCatv = $requeInfo;
                                   }
                                   if (count($infoRequeCatv) == 0) {
                                        $clientePlantaC = $multiconsultaFuntions->buscarClientePlantaClarita($bus);//Buscando Plana Clarita 

                                        if (count($clientePlantaC) == 1) {
                                             $detallePlantaCl = $clientePlantaC;
                                        }
     
                                        if (count($clientePlantaC) == 0) {
                                             $clienteAdsl = $multiconsultaFuntions->buscarClienteAdsl($bus);//Buscando Plana Adsl 
                                             if (count($clienteAdsl) > 0) {
                                             $detalleAdsl = $clienteAdsl;
                                             }
                                        }
                                   }
                                       

                                   if (count($infoRequeCatv) == 0 && count($detallePlantaCl) == 0 && count($detalleAdsl) == 0) {
 
                                        if ($cliente != "error") {
                                             $transformCliente = $intrawayPeticion->procesarClienteIntraway($cliente["report"]);

                                             #REGISTRO DE CLIENTE
                                                  //Actualizando Resultados de busqueda por el cliente
                                                  if ($existePermisoRegistro) { //si su rol puede registrar consulta
                                                       // Cantidad de registros iguales en los ultimos minutos
                                                       
                                                       $cantidadRegistro = $multiconsultaFuntions->getCantUltRegisMulti($transformCliente[0]["idClienteCRM"],$usuario,$ultimosMinutos);
                                                       
                                                       if ((int)$cantidadRegistro < 1) { //Si no existe otro registro identico en los ultimos minutos
                                                            $multiconsultaFuntions->storeConsulta($transformCliente[0]["idClienteCRM"],$armado_query["TipBus"],$fech_hor,$usuario,$rolNombre);//Registro de consulta
                                                       }
                                                  } 
                                             #end
                              
                                             return $this->resultData( 
                                                  array( 
                                                       'cantidad' => 1,
                                                       'resultado' => json_encode(view(
                                                                           'administrador.modulos.multiconsulta.intraway.clienteIntraway',
                                                                           [
                                                                                "cliente"=>$transformCliente,
                                                                                "servicio"=>isset($cliente["report"][0]["Docsis"][0]["idServicio"])? $cliente["report"][0]["Docsis"][0]["idServicio"] : 0,
                                                                                "producto"=>isset($cliente["report"][0]["Docsis"][0]["idProducto"])? $cliente["report"][0]["Docsis"][0]["idProducto"] : 0,
                                                                                "venta"=>isset($cliente["report"][0]["Docsis"][0]["idVenta"])? $cliente["report"][0]["Docsis"][0]["idVenta"] : 0
                                                                           ]
                                                                           )->render(),JSON_UNESCAPED_UNICODE),
                                                  )
                                             );
                                        }

                                        return $this->resultData(array(
                                             "cantidad"=>0,
                                             "resultado"=>[]
                                        ));
                                             
                                             
                                   }else{ 

                                             if (count($infoRequeCatv) == 1) { 
                                                  $infoRequeCatv[0]->imgArbol = "img_masivo"; 

                                                   #REGISTRO DE CLIENTE
                                                       if ($existePermisoRegistro) { //si su rol puede registrar consulta
                                                            // Cantidad de registros iguales en los ultimos minutos
                                                            
                                                            $cantidadRegistro = $multiconsultaFuntions->getCantUltRegisMulti($bus,$usuario,$ultimosMinutos);
                                                            
                                                            if ((int)$cantidadRegistro < 1) { //Si no existe otro registro identico en los ultimos minutos
                                                                 $multiconsultaFuntions->storeConsulta($bus,$armado_query["TipBus"],$fech_hor,$usuario,$rolNombre);//Registro de consulta
                                                            }
                                                       } 
                                                  #end

                                                  return $this->resultData( 
                                                       array( 
                                                            'cantidad' => 1,
                                                            'resultado' => json_encode(view(
                                                                                'administrador.modulos.multiconsulta.searchResultCatv',
                                                                                [
                                                                                     "catv"=>$infoRequeCatv
                                                                                ]
                                                                                )->render(),JSON_UNESCAPED_UNICODE),
                                                       )
                                                  );
                                             }elseif (count($detallePlantaCl) == 1) {
                                                 
                                                  $resultProcesoPlantaC = $multiconsultaFuntions->procesarClientPlantaClarita($detallePlantaCl);

                                                  #REGISTRO DE CLIENTE
                                                       if ($existePermisoRegistro) { //si su rol puede registrar consulta
                                                            // Cantidad de registros iguales en los ultimos minutos
                                                            
                                                            $cantidadRegistro = $multiconsultaFuntions->getCantUltRegisMulti($bus,$usuario,$ultimosMinutos);
                                                            
                                                            if ((int)$cantidadRegistro < 1) { //Si no existe otro registro identico en los ultimos minutos
                                                                 $multiconsultaFuntions->storeConsulta($bus,$armado_query["TipBus"],$fech_hor,$usuario,$rolNombre);//Registro de consulta
                                                            }
                                                       } 
                                                  #end

                                                   //dd($resultProcesoPlantaC);
                                                  return $this->resultData( 
                                                       array( 
                                                            'cantidad' => 1,
                                                            'resultado' => json_encode(view(
                                                                                'administrador.modulos.multiconsulta.searchResultPlanta',
                                                                                [
                                                                                     "planta"=>$resultProcesoPlantaC
                                                                                ]
                                                                                )->render(),JSON_UNESCAPED_UNICODE),
                                                       )
                                                  );
                                             }elseif (count($detalleAdsl) == 1) {
                                                  $detalleAdsl[0]->imgArbol = "img_masivo"; 

                                                  #REGISTRO DE CLIENTE
                                                       //Actualizando Resultados de busqueda por el cliente
                                                       if ($existePermisoRegistro) { //si su rol puede registrar consulta
                                                            // Cantidad de registros iguales en los ultimos minutos
                                                            
                                                            $cantidadRegistro = $multiconsultaFuntions->getCantUltRegisMulti($bus,$usuario,$ultimosMinutos);
                                                            
                                                            if ((int)$cantidadRegistro < 1) { //Si no existe otro registro identico en los ultimos minutos
                                                                 $multiconsultaFuntions->storeConsulta($bus,$armado_query["TipBus"],$fech_hor,$usuario,$rolNombre);//Registro de consulta
                                                            }
                                                       } 
                                                  #end

                                                //  dd($detalleAdsl);
                                                  return $this->resultData( 
                                                       array( 
                                                            'cantidad' => 1,
                                                            'resultado' => json_encode(view(
                                                                                'administrador.modulos.multiconsulta.searchResultAdsl',
                                                                                [
                                                                                     "adsl"=>$detalleAdsl
                                                                                ]
                                                                                )->render(),JSON_UNESCAPED_UNICODE),
                                                       )
                                                  );
                                             }else{
                                                  return $this->resultData(array(
                                                       "cantidad"=>0,
                                                       "resultado"=>[]
                                                  ));
                                             }
                                             
                                        }
                                         
                              }
                         #END PROCESO CON CODIGO DEL CLIENTE
  
                         if (count($recordP) == 0) {   
                              return $this->resultData(array(
                                   "cantidad"=>0,
                                   "resultado"=>[]
                              )); 
                         }

                         if (count($recordP) == 1) {
                              #REGISTRO DE CLIENTE
                                   //Actualizando Resultados de busqueda por el cliente
                                   if ($existePermisoRegistro) { //si su rol puede registrar consulta
                                        // Cantidad de registros iguales en los ultimos minutos
                                        
                                        $cantidadRegistro = $multiconsultaFuntions->getCantUltRegisMulti($recordP[0]->IDCLIENTECRM,$usuario,$ultimosMinutos);
                                        
                                        if ((int)$cantidadRegistro < 1) { //Si no existe otro registro identico en los ultimos minutos
                                             $multiconsultaFuntions->storeConsulta($recordP[0]->IDCLIENTECRM,$armado_query["TipBus"],$fech_hor,$usuario,$rolNombre);//Registro de consulta
                                        }
                                   } 
                              #end
                         }

                          
                         //Procesa el multiconsulta result con blade y functions
                         //solo es un resultado 
                         
                         if ($cliente != "error") { 
                             // dd($cliente["report"][0]["Docsis"]);
                              foreach ($cliente["report"][0]["Docsis"] as $cli) {
                                   $recordP[0]->estadoserv = ($cli["Activo"] == "SI") ? "Activo" : "Inactivo";
                                   $recordP[0]->Nombre = $cliente["report"][0]["Nombre"];
                                   $recordP[0]->scopesgroup = $cli["ispCPE"];
                              } 
                         }
                         
                        
                         $newResultMulti = $multiconsultaFuntions->procesarMulticonsulta($recordP,$fech_hor,$coloresMulticonsulta);
                         

                         
                         /*if ($existePermisoRegistro) { //si su rol puede Registrar consulta
                              
                              if ((int)$cantidadRegistro < 1) { 
                                   $mensaje = $newResultMulti[0]->resultadoAlerta." | ".$newResultMulti[0]->otrasAverias;
                                   $multiconsultaFuntions->updateConsulta($bus,$mensaje,$fech_hor,$rolNombre,$usuario);//Actualización de consulta
                              }
                         }*/
                         
                         return $this->resultData(
                              array(
                                   'cantidad' => 1,
                                   'resultado' => json_encode(view('administrador.modulos.multiconsulta.searchResult',[
                                                            "resultadoMulti"=>$newResultMulti
                                                            ])->render(),JSON_UNESCAPED_UNICODE),
                                         
                              )
                         ); 
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

    public function searchClientIntraway(Request $request)
    {  
          if($request->ajax()){
               #INICIO
                    $codCliente = $request->codCliente;
                    $servicio = $request->servicio;
                    $producto = $request->producto;
                    $venta = $request->venta;
                    
                    $intrawayPeticion = new IntrawayFunctions;
          
                    $cliente = $intrawayPeticion->intraway_cliente($codCliente);
                    
                    if ($cliente == "error") {
                         //Validar el< error
                         return $this->errorMessage("Perdida de conectividad con Intraway, Intentelo nuevamente.",500);
                    }
               
                    $transformCliente = $intrawayPeticion->procesarClienteIntraway($cliente["report"]);

                     
               
                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.modulos.multiconsulta.intraway.clienteIntraway',
                                                  [
                                                       "cliente"=>$transformCliente,
                                                       "servicio"=>$servicio,
                                                       "producto"=>$producto,
                                                       "venta"=>$venta
                                                  ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    ); 
               #END
          }
          return abort(404); 
     }

     public function historicoConectIntraway(Request $request)
     {   
          if($request->ajax()){
               #INICIO
                    $servicio = $request->servicio;
                    $producto = $request->producto;
                    $venta = $request->venta;
          
                    $intrawayPeticion = new IntrawayFunctions;
          
                    $historicoNiveles = $intrawayPeticion->PeticionIntraway($servicio, $producto,$venta);
                    if ($historicoNiveles == "error") {
                         //Validar el error
                         return $this->errorMessage("Perdida de conectividad con Intraway, Intentelo nuevamente.",500);
                    }
          
                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.modulos.multiconsulta.intraway.historicoConectividad',
                                                  [
                                                       "intraway"=>$historicoNiveles
                                                  ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    );  
               #END
          }
          return abort(404); 
 
     }

   
     public function graficoDownstream(Request $request)
     {     
          if($request->ajax()){
               #INICIO
                    $interface = $request->inter;
                    $cmts=$request->cmts;
                    
                    $peticionGeneral = new peticionesGeneralesFunctions;
          
                    $down = $peticionGeneral->getDownByCmtsAndInterface(trim($cmts),trim($interface));
                    $dataGrafico = $peticionGeneral->getGraficoDownSaturadoCmts(trim($down),trim($cmts));
          
                    

                    if (count($dataGrafico) == 0) {
                         return $this->errorMessage("No hay data para graficar - Revisar App",409); 
                    }
          
                    usort($dataGrafico,array($this,'cmp_time'));//ordena el rsultado por fecha
                    
          
                    return $this->resultData(["data"=>$dataGrafico,"down"=>$down]);
               #END
          }
          return abort(404); 
 
     }

     private static function cmp_time($a, $b) {
          
		if ($a->fecha_hora == $b->fecha_hora) {
			return 0;
		}
		return ($a->fecha_hora < $b->fecha_hora) ? -1 : 1;
    }

    public function resetCmReaprovisionamiento(Request $request)
    {
      if($request->ajax()){
        
          #INICIO 
               $usuarioAuth = Auth::user(); 
               $usuario = $usuarioAuth->username;

               $multiconsultaFuntions = new MulticonsultaFunctions;
               $intrawayPeticion = new IntrawayFunctions;
               $userFunctions = new UserFunctions;
               $logsFunctions = new LogsFunctions;
               
               //dd($request->all());
               
              $resetIntraway = $intrawayPeticion->resetOnCM($request->idCliente,$request->idServicio,$request->idProducto,$request->idVenta); 

               if ($resetIntraway == "error") {
                    return $this->errorMessage("Ocurrio un error..."."Si no reinicia por favor pedir al cliente que desconecte el modem y lo vuelva a conectar",500);
               }  
 
                
                
               $logsFunctions->registroLog($logsFunctions::LOG_CM_RESET_ITW,array(
                    "usuario" => $usuario,
                    "perfil" => $usuarioAuth->role->nombre,
                    "idCliente" => $request->idCliente,
                    "servicio" => $request->idServicio,
                    "producto" => $request->idProducto,
                    "venta" => $request->idVenta
                ));

               
               return $this->mensajeSuccess($resetIntraway);
          #END
      }
      return abort(404); 
         
    }

    public function getDataResetDeco(Request $request)
    {
     if($request->ajax()){
          #INICIO
                
               $codCliente =$request->codCliente;

               $multiconsultaFuntions = new MulticonsultaFunctions;


               $decos_cablemodems = $multiconsultaFuntions->CableModemsDecosForResetOne($codCliente);
                  
               if (count($decos_cablemodems) == 0) {
                         return $this->errorMessage("No se encontraron datos del cliente.",500);
               }
          
                    return $this->resultData(
                              array( 
                                   'html' => json_encode(view(
                                                       'administrador.modulos.multiconsulta.partials.resetDecos',
                                                       [
                                                            "decos_cablemodems"=>$decos_cablemodems,
                                                            "codCliente"=>$codCliente
                                                       ]
                                                       )->render(),JSON_UNESCAPED_UNICODE),
                              )
               ); 
          #END
     }
     return abort(404); 
          
    }

    public function resetDecoTrama(Request $request)
    {
          $usuarioAuth = Auth::user();
          $usuario = $usuarioAuth->username;

          $multiconsultaFuntions = new MulticonsultaFunctions;
          $sshConexiones = new ConexionSshFunctions;

          $idcliente=$request->cliente;
          $codsrv=$request->codsrv;
          $numser=$request->numser;
          $codmat=$request->codmat; 
          $tipo='A';

          $refresh_historico = $multiconsultaFuntions->historicoRefresh($numser);

          $existe = (int)$refresh_historico[0]->es;
          $respuesta_refresh = '';
 
          $conexiones = $sshConexiones->primera_conexion(); 
           
          $con_user = $conexiones["user"];
          $con_pass = $conexiones["pass"];
          $con_ip = $conexiones["ip"];
          $con_puerto = $conexiones["puerto"];
 
          if ($existe == 0){
               $ssh_exec = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip." 'php /home/rfalla/tramasDecos3.php ".$numser." ".$codmat." ".$tipo."'";
               //dd($ssh_exec);
               exec($ssh_exec);
               $multiconsultaFuntions->insertHistoricoRefresh($idcliente,$codsrv,$numser,$codmat,$usuario);
               $respuesta_refresh ="Refresh Enviado";
          }else{
               $respuesta_refresh ="Decoder =>".$numser." : Esperar que se realice el refresh, podra enviar un nuevo refresh en 5 minutos.....";
          } 

          return $this->mensajeSuccess($respuesta_refresh);
 
    }

    public function resetDecosTrama(Request $request)
     {
          $usuarioAuth = Auth::user();
          $usuario = $usuarioAuth->username;

          $multiconsultaFuntions = new MulticonsultaFunctions;
          $sshConexiones = new ConexionSshFunctions;

          $idcliente=$request->codCliente;
          $codsrv=$request->codsrv;

          $varios1=$multiconsultaFuntions->CableModemsDecosForResetAll($codsrv,$idcliente);

          $conexiones = $sshConexiones->primera_conexion(); 
           
          $con_user = $conexiones["user"];
          $con_pass = $conexiones["pass"];
          $con_ip = $conexiones["ip"];
          $con_puerto = $conexiones["puerto"];
 
          $resultado = array();
        
          if(isset($varios1)){
               foreach($varios1 as $dec){
                    $numser=$dec->numser;
                    $codmat=$dec->codmat;
                    $tipo='A';
                    $valida=$multiconsultaFuntions->historicoRefresh($numser);
                    $existe = (int)$valida[0]->es;
                    if ($existe==0){
                         $ssh_exec = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip." 'php /home/rfalla/tramasDecos3.php ".$numser." ".$codmat." ".$tipo."'";
                         exec($ssh_exec); 
                         $refresh_historico = $multiconsultaFuntions->insertHistoricoRefresh($idcliente,$codsrv,$numser,$codmat,$usuario);
          
                         $resultado[] = "Decoder => ".$numser." Refresh enviado";
                    }else{
                         $resultado[] = "Decoder =>".$numser.": Esperar que se realice el refresh, podra enviar un nuevo refresh en 5 minutos";
                    }
               }
          }else{
               $resultado[] = "No existen decoders";
          }

          return $this->resultData([
               "data" => $resultado
          ]);
 
    }

    public function getDataVelocidadCm(Request $request)
    {
          if($request->ajax()){
               #INICIO
                    $usuarioAuth = Auth::user(); 
                    $usuario = $usuarioAuth->username;

                    $multiconsultaFuntions = new MulticonsultaFunctions;
          
                    $mac = $request->mac;
                    $velocidadActual = $request->velocidad;
               
                    
                    $velocidades = $multiconsultaFuntions->showVelocidadesDisponibles();
                    $fecha_actual = date("Y-m-d");

                    return $this->resultData(
                         array( 
                              'html' => json_encode(view(
                                                  'administrador.modulos.multiconsulta.partials.cambioVelocidad',
                                                  [
                                                       "velocidades"=>$velocidades,
                                                       "fecha_actual"=>$fecha_actual,
                                                       "mac"=>$mac,
                                                       "velocidadActual"=>$velocidadActual
                                                  ]
                                                  )->render(),JSON_UNESCAPED_UNICODE),
                         )
                    ); 
               #END
          }
          return abort(404); 
           
    }

    public function cambiarVelocidadCm(Request $request)
    {

     
          $usuarioAuth = Auth::user(); 
          $usuario = $usuarioAuth->username;
          $idUsuario = $usuarioAuth->id;

          $multiconsultaFuntions = new MulticonsultaFunctions;
          
        
          $velocidad_actual = $request->velocidad_actual;
          $nvel = $request->nueva_velocidad;
          $dias = $request->dias;
          $mac = $request->mac;
          $fini = $request->f_inicio; 
          $motivo = $request->motivo; 

          if (trim($motivo) == "") {
               return $this->errorMessage("El campo motivo del cambio de velocidad es requerido.",403);
          }
          if (strlen(trim($motivo)) < 5) {
               return $this->errorMessage("El campo motivo del cambio de velocidad tiene una longitud muy corta.",403);
          }
 
          $hoy=date("Y-m-d");
          $hoyDateTime=date("d-m-Y H:i:s");

          $fecha_cambio= strtotime($fini);
          $fecha_hoy = strtotime($hoy);
          $fechaDateTimeHoy = strtotime($hoyDateTime);
               
          $rpta_velocidad = ""; 
 
 
          #VALIDANDO MAC CLIENTE NO TENGA MAS DE 1 SEMANA EN CAMBIO

          $dataClienteLog = $multiconsultaFuntions->getUltimoRegistroLogByMac($mac);
          if (count($dataClienteLog) > 0) { 
               

              $fechaAccion = strtotime($dataClienteLog[0]->fechaAccion);
              $agregandoFechaRestriccion = strtotime ( "+7 day" , $fechaAccion);
              $nuevaFechaAgregada = date ( 'd-m-Y H:i:s' , $agregandoFechaRestriccion );
               
              if ($agregandoFechaRestriccion > $fechaDateTimeHoy) {
                    return $this->errorMessage("Se realizarón cambios con este cliente anteriormente. No puede realizarse ningún cambio hasta el: ".$nuevaFechaAgregada,403);
              } 
          }
 
          
          if($fecha_hoy < $fecha_cambio){

               $multiconsultaFuntions->replaceNuevaVelocidadBD($fini,$dias,$nvel,$mac,$idUsuario);
               
               $rpta_velocidad = "Los cambios en enviarón correctamente";

          }elseif($fecha_hoy == $fecha_cambio){

               $multiconsultaFuntions->replaceNuevaVelocidadBD($fini,$dias,$nvel,$mac,$idUsuario);

               $datosCli = $multiconsultaFuntions->updateNuevaVelocidadBD($mac,$nvel);

               $iwAction = "activar";
               $codCliente = $datosCli["dataCliente"]->idclientecrm;
               $idProducto = $datosCli["dataCliente"]->idproducto;
               $idServicio = $datosCli["dataCliente"]->idservicio;
               $serviceP = $datosCli["velocidades"]->servicepackagecrmid;
               $idISPCRM = $datosCli["dataCliente"]->SCOPESGROUP;
               $ispMtaCrmId = $datosCli["dataCliente"]->idserviciomta;
 
               $intrawayPeticion = new IntrawayFunctions;
               $resultadoITW = $intrawayPeticion->ActiveOrChangeCM($iwAction,$codCliente,$idProducto,$idServicio,$serviceP,$idISPCRM,$ispMtaCrmId);
               
               if ($resultadoITW != "error") {
                    $logsFunctions = new LogsFunctions;
                     $multiconsultaFuntions->UpdateNclienteVelocidad($datosCli["velocidades"]->servicepackagecrmid,$datosCli["velocidades"]->servicepackage,$mac);
                     $multiconsultaFuntions->insertHistoricoVelocidad($mac,$datosCli["velocidades"]->servicepackagecrmid,$datosCli["velocidades"]->servicepackage,$idUsuario);
 
                    $agregandoDias = strtotime ( "+$dias day" , $fecha_cambio) ;
                    $nuevaFechaAgregada = date ( 'Y-m-d' , $agregandoDias );
                      
                    $logsFunctions->registroLog($logsFunctions::LOG_CM_VELOCIDADES,array(
                                   "usuario"=>$usuario,
                                   "perfil"=>$usuarioAuth->role->nombre,
                                   "idcliente"=>$codCliente,
                                   "macAddress"=>$mac,
                                   "velocidad"=>$velocidad_actual,
                                   "nueva_velocidad"=>$serviceP,
                                   "fecha_inicio"=>$fini,
                                   "fecha_fin"=>$nuevaFechaAgregada,
                                   "motivo"=>$motivo
                                   ));

                    $rpta_velocidad = "Se realizarón los cambios correctamente. No se podrá realizar ninún cambio hasta dentro de 1 semana.";
               }
               else{ 
                    $rpta_velocidad = "Se generó un problema de conectividad con los cambios en Intraway. intente nuevamente en otro momento.";
                    return $this->errorMessage($rpta_velocidad,405);
               }
               
          }else{
               $rpta_velocidad = "La fecha enviada es menor a la de hoy, por lo tanto no es valida";
               return $this->errorMessage($rpta_velocidad,403);
          }

          return $this->mensajeSuccess($rpta_velocidad);
           
    }
 
    public function activarCm(Request $request){

          $usuarioAuth = Auth::user(); 
          $usuario = $usuarioAuth->username; 

          $multiconsultaFuntions = new MulticonsultaFunctions;
          $estadoServ = $request->estadoServ;
          $mac = $request->mac;
          $justificacion = $request->justificacion;

          $hoy=date("Y-m-d H:i:s"); 
          $fecha_hoy = strtotime($hoy);
 
          #VALIDA JUST.
          if (trim($justificacion) == "") {
               return $this->errorMessage("El campo justificación es requerido.",403);
          }
          if (strlen(trim($justificacion)) < 5) {
               return $this->errorMessage("El campo justificación tiene una longitud muy corta.",403);
          }

          #VALIDANDO MAC CLIENTE NO TENGA MAS DE 59 minutos EN CAMBIO
          $dataClienteParaActivar = $multiconsultaFuntions->getDataClientForActivate($mac);

          $fechaUpload    = strtotime($dataClienteParaActivar->fecha_upload);

          $agregandoMinutos = strtotime ( "+59 minutes" , $fechaUpload);

          $nuevaFechaMinutosAdd = date ( 'd-m-Y H:i:s' , $agregandoMinutos);

          if ($agregandoMinutos > $fecha_hoy) {
               return $this->errorMessage("Se realizarón cambios con este cliente anteriormente. Intente nuevamente a partir de: ".$nuevaFechaMinutosAdd,403);
          }
 
          $iwAction      = "activar";
          $codCliente    = $dataClienteParaActivar->idclientecrm;
          $idProducto    = $dataClienteParaActivar->idproducto;
          $idServicio    = $dataClienteParaActivar->idservicio;
          $serviceP      = $dataClienteParaActivar->velocidad;
          $idISPCRM      = $dataClienteParaActivar->SCOPESGROUP;
          $ispMtaCrmId   = $dataClienteParaActivar->idserviciomta;
 
          $intrawayPeticion = new IntrawayFunctions;
          $resultadoITW = $intrawayPeticion->ActiveOrChangeCM($iwAction,$codCliente,$idProducto,$idServicio,$serviceP,$idISPCRM,$ispMtaCrmId);
          
 
         if ($resultadoITW != "error") {

               $userFunctions = new UserFunctions;
               $logsFunctions = new LogsFunctions;

              
                    
               $multiconsultaFuntions->UpdateNclienteStatus("Activo",$mac); 
               
               $logsFunctions->registroLog($logsFunctions::LOG_CM_ACTIVACION,array(
                    "usuario" => $usuario,
                    "perfil" => $usuarioAuth->role->nombre,
                    "idCliente" => $codCliente,
                    "macAddress" => $mac,
                    "estado" => $estadoServ,
                    "velocidad" => $serviceP,
                    "newEstado" => "Activo",
                    "justificacion" => $justificacion
                ));

               $rpta_activacion = "Se realizarón los cambios correctamente.";
         }
          else{ 
               $rpta_activacion = "Se generó un problema con los cambios en Intraway. intente nuevamente en otro momento.";
               return $this->errorMessage($rpta_activacion,405);
          }  

          return $this->mensajeSuccess($rpta_activacion);
    }

    public function cambioScopeGroup(Request $request)
    {
          $usuarioAuth = Auth::user(); 
          $usuario = $usuarioAuth->username; 
          $idUsuario = $usuarioAuth->id;

          $multiconsultaFuntions = new MulticonsultaFunctions;
         
          $mac = $request->mac;
          $motivo = $request->motivo;
 
          $hoy=date("d-m-Y H:i:s"); 
          $fecha_hoy = strtotime($hoy);

          #VALIDA MOTIVO
          if (trim($motivo) == "" || strtolower(trim($motivo)) == "seleccionar") {
               return $this->errorMessage("El campo motivo es requerido.",403);
          }
 
          $logsFunctions = new LogsFunctions;

           
          #VALIDANDO MAC CLIENTE NO TENGA MAS DE 59 minutos EN CAMBIO
               $dataClienteScopeGroup = $multiconsultaFuntions->getDataClientForActivate($mac);
  
               $fechaUpload    = strtotime($dataClienteScopeGroup->fecha_upload);
     
               $agregandoHoras = strtotime ( "+2 hours" , $fechaUpload);

               $nuevaFechaHorasAdd = date ( 'd-m-Y H:i:s' , $agregandoHoras);
                 
               if ($agregandoHoras > $fecha_hoy) {
                    return $this->errorMessage("Se realizarón cambios con este cliente anteriormente. Intente nuevamente a partir de: ".$nuevaFechaHorasAdd,403);
               }
 
                
               $codCliente    = $dataClienteScopeGroup->idclientecrm;
               $idProducto    = $dataClienteScopeGroup->idproducto;
               $idServicio    = $dataClienteScopeGroup->idservicio;
               $serviceP      = $dataClienteScopeGroup->velocidad; 

               if ($dataClienteScopeGroup->SCOPESGROUP == "CPE") {
                    $idISPCRM = "CPE-CGNAT";
               }else{
                    $idISPCRM = "CPE";
               }
                
                
               $intrawayPeticion = new IntrawayFunctions;
               $resultadoITW = $intrawayPeticion->cambiarIPScopesGroup($codCliente,$idServicio,$idProducto,$serviceP,$idISPCRM);
               
     
               if ($resultadoITW != "error") {
                              
                    $multiconsultaFuntions->UpdateNclienteScopeGroup($mac); 

                    if ($dataClienteScopeGroup->SCOPESGROUP == "CPE-CGNAT") {
                         $multiconsultaFuntions->registerClienteCgnatToCpe($codCliente,$idUsuario,$motivo);
                    } 
                    
                    $logsFunctions->registroLog($logsFunctions::LOG_CM_SCOPESGROUP,array(
                         "usuario"=>$usuario,
                         "perfil"=>$usuarioAuth->role->nombre,
                         "idcliente"=>$codCliente,
                         "macAddress"=>$mac,
                         "scopeGroup"=>$dataClienteScopeGroup->SCOPESGROUP,
                         "nuevoScopeGroup"=>$idISPCRM,
                         "motivo"=>$motivo
                     ));
                     
                     
                    $rpta_cambioIp = "Se realizarón los cambios correctamente.";
               }
               else{ 
                    $rpta_cambioIp = "Se generó un problema con los cambios en Intraway. intente nuevamente en otro momento.";
                    return $this->errorMessage($rpta_cambioIp,405);
               }

          return $this->mensajeSuccess($rpta_cambioIp);
         
    }

    public function historicoNivelesTroba(Request $request)
    { 
          if($request->ajax()){
               #INICIO
                    $puertoCmts = $request->puertoCmts;
                    $nodoTroba = $request->nodoTroba;
                    
                    if(empty($puertoCmts)){ //preguntamos si mando un campo nombre y no esta vacio
                         throw new HttpException(402,"Para procesar el histórico del cliente, se requiere el puerto cmts.");
                    }
                    $coloresGeneralMulticonsulta = ParametroColores::getMulticonsultaParametros();
                    $colorNivelesTroba = $coloresGeneralMulticonsulta->COLORES->nivelesTrobas->colores;

                    $multiconsultaFuntions = new MulticonsultaFunctions;

                    $resultDataHist = $multiconsultaFuntions->getDataHistoricoNivelesTrobas($puertoCmts);
          
                    if (count($resultDataHist) == 0) {
                         return $this->errorMessage("No se encontró data historica de niveles en la troba del cliente.",500);
                    }

                    
                    usort($resultDataHist,array($this,'cmp_time'));//ordena el rsultado por fecha

                    $colores = ParametroColores::getMulticonsultaParametros();

          
                    return $this->resultData(["data"=>$resultDataHist,"nodoTroba"=>$nodoTroba,"coloresNiveles"=>$colorNivelesTroba]);
               #END
          }
               return abort(404); 
  
    }

    public function historicoCaidasTroba(Request $request)
    { 
          if($request->ajax()){
               #INICIO
                    $puertoCmts = $request->puertoCmts;
                    $nodoTroba = $request->nodoTroba;
                    
                    if(empty($puertoCmts)){ //preguntamos si mando un campo nombre y no esta vacio
                         throw new HttpException(402,"Para procesar el histórico del cliente, se requiere el puerto cmts.");
                    }

                    $multiconsultaFuntions = new MulticonsultaFunctions;

                    $coloresGeneralMulticonsulta = ParametroColores::getMulticonsultaParametros();
                    //dd($coloresGeneralMulticonsulta);
                    $colorCaidasTrobas= $coloresGeneralMulticonsulta->COLORES->caidasTrobas->colores;


                    $resultDataHist = $multiconsultaFuntions->getDataHistoricoCaidasTrobas($puertoCmts);
          
                    if (count($resultDataHist) == 0) {
                         return $this->errorMessage("Sin histórico para graficar. Sin caidas masivas en los últimos 15 días.",500);
                    }

                    
                    usort($resultDataHist,array($this,'cmp_time'));//ordena el rsultado por fecha

                   
                    return $this->resultData(["data"=>$resultDataHist,"nodoTroba"=>$nodoTroba,"coloresCaidas"=>$colorCaidasTrobas]);
               #END
          }
               return abort(404); 
  
    }

    public function telefonoStoreUpdate(Request $request)
    {

     $validar = Validator::make($request->all(), [
          "telefono1" => "nullable|regex:/^[0-9]+$/",
          "telefono2" => "nullable|regex:/^[0-9]+$/",
          "telefono3" => "nullable|regex:/^[0-9]+$/"
      ]); 

      if ($validar->fails()) { 
         return $this->errorMessage($validar->errors()->all(),422);
      } 

      if (isset($request->telefono1) && $request->telefono1 != 0) {
           if (strlen($request->telefono1) < 7 || strlen($request->telefono1) > 10) {
               return $this->errorMessage("El Telefono1 tiene una longitud incorrecta de un telefono.",422);
           }
      }
      if (isset($request->telefono2) && $request->telefono2 != 0) {
           if (strlen($request->telefono2) < 7 || strlen($request->telefono2) > 10) {
               return $this->errorMessage("El Telefono2 tiene una longitud incorrecta de un telefono.",422);
           }
      }
      if (isset($request->telefono3) && $request->telefono3 != 0) {
           if (strlen($request->telefono3) < 7 || strlen($request->telefono3) > 10) {
               return $this->errorMessage("El Telefono3 tiene una longitud incorrecta de un telefono.",422);
           }
      }
      
      //dd($request->all());
      
      if (!isset($request->idCliente)) {
          return $this->errorMessage("No se puede identificar al cliente, intente nuevamente recargando la web.",422);
      }

      $multiconsultaFuntions = new MulticonsultaFunctions;
 
      $telefonos=(int)$request->telefono1+(int)$request->telefono2+(int)$request->telefono3;

      $mensajeFinal = "";
      if ($telefonos > 10000) {

         

          $datosTelfMulti = $multiconsultaFuntions->getDetalleTelefonosCatalodoByCliente($request->idCliente);

          $mensajeInsert = "";

          $data = $request->all();

          if (count($datosTelfMulti) > 0) {
               if(isset($data["telefono1"]) && strlen($data["telefono1"]) > 6  ){
                   $data["telefono1"] = $request->telefono1; 
               }else{
                 $data["telefono1"] = $datosTelfMulti[0]->telef1;
                 $mensajeInsert .= " Telefono1 ";
               }
              if(isset($data["telefono2"]) && strlen($data["telefono2"]) > 6  ){
                  $data["telefono2"] = $request->telefono2; 
              }else{
                 $data["telefono2"] = $datosTelfMulti[0]->telef2;
                 $mensajeInsert .= " Telefono2 ";
              }
              if(isset($data["telefono3"]) && strlen($data["telefono3"]) > 6  ){
                  $data["telefono3"] = $request->telefono3; 
              }else{
                 $data["telefono3"] = $datosTelfMulti[0]->telef3;
                 $mensajeInsert .= " Telefono3 ";
              }
           }
         //  dd($data);

           $multiconsultaFuntions->registroCatalogosTelefonos($data);
           
          if (strlen($mensajeInsert) > 0) {
               $mensajeFinal = "<br/>Estos telefonos no se actualizaron : ".$mensajeInsert." Debido a que no pueden estar vacios.";
          }
      }

      $nuevaData = $multiconsultaFuntions->getDetalleTelefonosCatalodoByCliente($request->idCliente);

      return $this->resultData(["data"=>$nuevaData,
                                   "mensaje"=>"Los teléfonos se actualizarón Correctamente.$mensajeFinal"
      ]);

      //return $this->mensajeSuccess("Los teléfonos se actualizarón correctamente.".$mensajeFinal);
      
    }

    public function agendaDetalle(Request $request)
    {  
          // dd($request->all());
          $idCliente = htmlspecialchars($request->idCliente);
          $sw = htmlspecialchars($request->sw);
          $nodo = htmlspecialchars($request->nodo);

          $multiconsultaFuntions = new MulticonsultaFunctions;
          $getDetailAgenda = $multiconsultaFuntions->getDetailsAgenda($idCliente);
          if (count($getDetailAgenda) == 0) {
               return $this->errorMessage("No se encontraron detalles",500);
          }

          $agendasActuales =  $multiconsultaFuntions->getAgendasActuales($idCliente);
          $existeAgendado = false;
          $tipoDeAgenda = [];
          $diaDeAgenda = [];
          if (count($agendasActuales) > 0) {
               $existeAgendado = true;
               $tipoAgendaReservado = $multiconsultaFuntions->getTurnoByTipoAgenda($agendasActuales[0]->tipocliagenda);
               $agendasActuales[0]->tipoAgendaReservado = $tipoAgendaReservado;
          }else{
               $tipoDeAgenda = $multiconsultaFuntions->getTipoDeAgenda();
               $diaDeAgenda = $multiconsultaFuntions->getDiaDeAgenda();
          }

          $resultClientePorNodo = array();

          foreach ($getDetailAgenda as $agenda) {
               if ($agenda->nodo == $nodo) {
                    $resultClientePorNodo[] =$agenda;
               }
          }

          //AGENDA GRAFICA

          //dd($diaDeAgenda);


          //dd($agendasActuales);

          return $this->resultData(["data"=>$resultClientePorNodo,
                                   "estaAgendado"=>$existeAgendado,
                                   "detalleAgenda"=>$agendasActuales,
                                   "tipoDeAgenda"=>$tipoDeAgenda,
                                   "diaDeAgenda"=>$diaDeAgenda,
                                   "idCliente"=>$idCliente,
                                   "accionRealizar"=>htmlspecialchars($request->accionCarga)
                                   ]);
 
    }

    public function verificarTurnoAgenda(Request $request)
    {
          if($request->ajax()){
               #INICIO
                           //dd($request->all());
                         $tipoDeAgenda = htmlspecialchars($request->tipoDeAgenda);
                         //$diaDeAgenda = htmlspecialchars($request->diaDeAgenda);
                         $idCliente = htmlspecialchars($request->idCliente);
                         //grafico
                         $nodo = htmlspecialchars($request->nodo);

                         $hoy=date("Y-m-d");

                         $multiconsultaFuntions = new MulticonsultaFunctions;

                         $detallesTipoTurno = $multiconsultaFuntions->getTurnoByTipoAgenda($tipoDeAgenda);


                         //$tipoTurno = isset($detallesTipoTurno[0]) ? $detallesTipoTurno[0]->tipoturno : 0;
                         $idRangoHorarios = isset($detallesTipoTurno[0]) ? $detallesTipoTurno[0]->idrangohorario : 0;

                         /* $filtroTurnoHora = "";
                         if($hoy == $diaDeAgenda) $filtroTurnoHora=" substr(turno,1,2)>".date("H")." and ";

                         $rangoHorario =   $multiconsultaFuntions->getRangohorarioByTurno($filtroTurnoHora,$idRangoHorarios);

                         if (count($rangoHorario) == 0) {
                              return $this->errorMessage("No hay turnos disponibles para hoy, intente con otro día.",500);
                         }*/

                         //Detalles graficos
                         $diasDeAgenda = $multiconsultaFuntions->getDiaDeAgenda();
                         

                         $rangoHorariosCompletos = $multiconsultaFuntions->getRangohorarioByTurno("",$idRangoHorarios);
                         //dd($rangoHorariosCompletos);
                         for ($i=0; $i < count($diasDeAgenda) ; $i++) { 
                              $filtroDeTurnosHoras = "";
                              if($hoy == $diasDeAgenda[$i]->fecha) $filtroDeTurnosHoras=" substr(turno,1,2)>".date("H")." and ";

                              $rangoHorario =   $multiconsultaFuntions->getRangohorarioByTurno($filtroDeTurnosHoras,$idRangoHorarios);

                              $diasDeAgenda[$i]->rangoHorarios = array();
                              if (count($rangoHorario) != 0) {
                                   for ($j=0; $j < count($rangoHorario); $j++) { 
                                        $detalleTipoTurnos = $multiconsultaFuntions->cantidadCuposDisponiblesEnNodo($nodo,$diasDeAgenda[$i]->fecha,$rangoHorario[$j]->id);
                                        if ((int)$detalleTipoTurnos["cuposActualizar"] <= (int)$detalleTipoTurnos["cuposMaximos"]) {
                                             //return $this->errorMessage("No hay mas cupos para el nodo $nodo en este turno elija otro por favor.",500);
                                             $rangoHorario[$j]->cuposDisponibles = (int)$detalleTipoTurnos["cuposMaximos"] - ($detalleTipoTurnos["cuposActualizar"] - 1);
                                             $diasDeAgenda[$i]->rangoHorarios[] = $rangoHorario[$j];
                                        }
                                   }
                              
                              }

                              

                              
                         }

                         //dd($diasDeAgenda);

                         //Dias de Agendas con sus horarios con cupos disponibles en el nodo ->$diasDeAgenda //falta calcular el cupo por nodo
                         //Rango completo de horarios completos de la agenda como guia -> $rangoHorariosCompletos 

                         

                         return $this->resultData([
                                                  //"detallesTipoTurno"=>$detallesTipoTurno,
                                                  //"detallesRangoHorarios"=>$rangoHorario,
                                                  "idCliente"=>$idCliente,
                                                  //Graficos
                                                  "diasAgendaTotal"=>$diasDeAgenda,
                                                  "rangoHorariosCompletos"=>$rangoHorariosCompletos
                                                  ]);
               #END
          }
          return abort(404); 
          
    }

    public function verificarCuposAgenda(Request $request)
    {
          if($request->ajax()){
               #INICIO
                    // dd($request->all());
                    $tipoTurno = htmlspecialchars($request->tipoTurno);
                    $dia = htmlspecialchars($request->dia);
                    $nodo = htmlspecialchars($request->nodo);
                    $idCliente = htmlspecialchars($request->idCliente);

                    $multiconsultaFuntions = new MulticonsultaFunctions;

                    $detallesTipoTurno = $multiconsultaFuntions->cantidadCuposDisponiblesEnNodo($nodo,$dia,$tipoTurno);
                    // dd($request->all());
                    //dd($detallesTipoTurno);

                    if ((int)$detallesTipoTurno["cuposActualizar"] >= (int)$detallesTipoTurno["cuposMaximos"]) {
                         return $this->errorMessage("Vaya, parece que ya no hay cupos disponibles en este horario. Intenta con otro antes que se agoten!.",500);
                    }  

                    $multiconsultaFuntions->CreandoCupoAgenda((int)$detallesTipoTurno["cuposActualizar"] ,(int)$detallesTipoTurno["cuposMaximos"],$nodo,$dia,$tipoTurno);

                    $diaAgenda = $multiconsultaFuntions->getDiaDeAgendaByFecha($dia);
                    $horaAgenda = $multiconsultaFuntions->getHorarioById($tipoTurno);
                    
                    return $this->resultData([
                                             //"detallesTipoTurno"=>$detallesTipoTurno,
                                             "nodo"=>$nodo,
                                             "idCliente"=>$idCliente,
                                             "detalleDia"=>$diaAgenda[0],
                                             "detalleHora"=>$horaAgenda[0]
                                             ]);
               #END
          }
          return abort(404); 
           
    }

    public function retirarCupoTemporalReservado(Request $request)
    {
          if($request->ajax()){

               //dd($request->all());
          
               $tipoTurno = htmlspecialchars($request->tipoTurno);
               $dia = htmlspecialchars($request->dia);
               $nodo = htmlspecialchars($request->nodo);
     
               $multiconsultaFuntions = new MulticonsultaFunctions;
     
               $detallesTipoTurno = $multiconsultaFuntions->cantidadCuposDisponiblesEnNodo($nodo,$dia,$tipoTurno);
     
               $multiconsultaFuntions->quitarCupoTemporalReservado((int)$detallesTipoTurno["cuposActualizar"] ,(int)$detallesTipoTurno["cuposMaximos"],$nodo,$dia,$tipoTurno);
     
     
               return $this->mensajeSuccess("Se cancelo su cupo correctamente.");
               
          }
          return abort(404); 
             
    }

    public function registroPreAgenda(Request $request)
    {
         

         $validar = Validator::make($request->all(), [
          "telefonoFijo" => "nullable|min:7|max:7|regex:/^[0-9]+$/",
          "telefonoMovil" => "nullable|min:9|max:9|regex:/^[0-9]+$/",
          "codigoRequerimiento" => "nullable|min:8|regex:/^[0-9]+$/"
          ]); 

          if ($validar->fails()) { 
               return $this->errorMessage($validar->errors()->all(),422);
          } 

          //dd($request->all());
          $multiconsultaFuntions = new MulticonsultaFunctions;

          

          $multiconsultaFuntions->registrarPreAgendaMulti($request->all());

          if ($request->EstadoAgendaProcesar == "preagendar") {
               return $this->mensajeSuccess("Se reagendó su cupo correctamente.");
          }else{
               return $this->mensajeSuccess("Se registro la agenda correctamente.");
          }

         

    }

    
 

}
