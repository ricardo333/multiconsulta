<?php

namespace App\Http\Controllers\Modulos\Multiconsulta;

use Illuminate\Http\Request;
use App\Administrador\PasosArbol;
use Illuminate\Support\Facades\Auth;
use App\Functions\ArbolDecisionesFunctions;
use App\Http\Controllers\GeneralController;


class ArbolDecisionesController extends GeneralController
{
    
    public function index(Request $request)
    {

        $arbolDecisionesFunction = new ArbolDecisionesFunctions;

        $pasosGenerales = $arbolDecisionesFunction->getTablaDecisionesGeneral();
        $marcacionRapida = $arbolDecisionesFunction->getMarcacionRapida();
        $primeraDecision = $arbolDecisionesFunction->getListTable("paso00");

        return $this->resultData(
            [
                "cantidad"=>$pasosGenerales["cantidad"],
                "list"=>$pasosGenerales["listado"],
                "marcacionRapida"=>$marcacionRapida,
                "primeraDecision"=>$primeraDecision
            ]
        ); 
  
    }

    public function indexPorMensaje(Request $request)
    {

        //dd($request->all());

        $mensajeCliente = $request->mensajeCliente;
        $imagen = $request->imagen;

        $arbolDecisionesFunction = new ArbolDecisionesFunctions;

        $listaPorMensaje = $arbolDecisionesFunction->getListTablaPorMensaje($mensajeCliente);
 
        if(count($listaPorMensaje) == 0){ 
            return $this->errorMessage("no se encontraron decisiones segun la averia.",402);
        }

        $listaDecisionesPasos = $arbolDecisionesFunction->getTablaDecisionesGeneral();

        $arrayResultTablasAndSelect = array();
        $cantidadR = 0; 
        $estadoSeleccion = 1;

         // dd($listaPorMensaje);

         for ($i=0; $i < $listaDecisionesPasos["cantidad"]; $i++) { 

            $nombrePasoLista = $listaDecisionesPasos["listado"][$i]->nombre;
            $identificador = $listaDecisionesPasos["listado"][$i]->id;
            

            if($listaPorMensaje[0]->$nombrePasoLista > 0 || $estadoSeleccion == 1){

                
                $cantidadR++;
               
               
                $arrayResultTablasAndSelect[] = array(
                    "tabla"=>$nombrePasoLista,
                    "id"=>$identificador,
                    "seleccionado"=>$listaPorMensaje[0]->$nombrePasoLista,
                    "posicion"=>$listaDecisionesPasos["listado"][$i]->id,
                    "detalle"=>$listaDecisionesPasos["listado"][$i]->detalle,
                    "pasoAnterior"=>$listaDecisionesPasos["listado"][$i]->pasoAnterior,
                );
                if($listaPorMensaje[0]->$nombrePasoLista == 0){
                    $estadoSeleccion = 0;
                }

                
            } 
          
        }
        
        $resultadoFinalTablasAndSelects = array();

        //dd($arrayResultTablasAndSelect);

        if($cantidadR > 0){
  
            $seleccionAnterior = 0; 
            foreach ($arrayResultTablasAndSelect as $val) {
        
                
                $listaDetalleTabla = $arbolDecisionesFunction->getSelects($val["tabla"],
                                                                         $val["pasoAnterior"],
                                                                         $seleccionAnterior);

                 
                $resultadoFinalTablasAndSelects[] = array(
                    "tabla"=>$val["tabla"],
                    "seleccionado"=>$val["seleccionado"],
                    "posicion"=>$val["posicion"],
                    "tablaId"=>$val["id"],
                    "pasoText"=>$val["detalle"],
                    "pasoAnterior"=>$val["pasoAnterior"],
                    "seleccion"=>$listaDetalleTabla,
                ); 
        
                $seleccionAnterior = $val["seleccionado"];
         
                //$ServicioDecision->updatePorMensaje($idTabla,"paso".$val["posicion"],$val["seleccionado"]);
            
            }
        
        }else{  
            return $this->errorMessage("no se encontraron datos del cliente respecto a una averia.",402);
         }

       
        return $this->resultData(
            [
                "generalListado"=>$resultadoFinalTablasAndSelects
            ]
        ); 
  
    }

    public function detallesDecision(Request $request, PasosArbol $paso)
    {

        if($request->ajax()){
            #INICIO
                $imagenPeticion = $request->imagen;
                //$codCliente = $request->codCliente;
                
        
                if($request->filled('valorSelect')){ //preguntamos si mando un campo valorSelect y no esta vacio
        
                    $arbolDecisionesFunction = new ArbolDecisionesFunctions;
                    
                    $valorSelect= $request->valorSelect;
        
                    $rpta_image = $arbolDecisionesFunction->getImage($paso->nombre,$valorSelect,$imagenPeticion);
                    
                    $loadImagen = ( count($rpta_image) > 0 ) ? $rpta_image[0]->$imagenPeticion : 'sinimagen.png';
                    
                    $tablaDetalle = $arbolDecisionesFunction->getTablaPorNombre($paso->tablaSiguiente);
        
                    $rpta_selects = $arbolDecisionesFunction->getSelects($paso->tablaSiguiente,$tablaDetalle->pasoAnterior,$valorSelect);
        
        
                    return $this->resultData(
                        [
                            "imagen"=> $loadImagen,
                            "dataList"=>$rpta_selects,
                            "pasoText"=>$tablaDetalle->detalle,
                            "pasoActual"=>$tablaDetalle->id
                        ]
                    ); 
                    
                
        
                }
        
                return $this->errorMessage("Se requiere un paso seleccionado para mostrar las siguientes decisiones", 402);
            #END
        }
        return abort(404); 
        

    }

    public function registrosDecision(Request $request)
    {
        
        $usuarioAuth = Auth::user();
        $usuario = $usuarioAuth->username;
        
        $decisiones = $request->decisiones;
         
        $marcaRapida = $request->mrapida;
        $codCliente = $request->codCliente;

        $arbolDecisionesFunction = new ArbolDecisionesFunctions;
       
        $tablaDetalle = $arbolDecisionesFunction->registrandoDecisionesArbol($decisiones,$marcaRapida,$codCliente,$usuario);

        return $this->mensajeSuccess("Se registrarón las decisiones correctamente.");
    }


}
