<?php

namespace App\Http\Controllers\Modulos\MensajesOperador;

use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Functions\MensajesOperadorFunctions;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;

class MensajesOperadorController extends GeneralController
{
    public function view()
   {

       return view('administrador.modulos.mensajesOperador.index');
        
   }

   public function cargaArchivo(Request $request)
   { 

       $funcionMensajesOperador = new MensajesOperadorFunctions;
       if ($request->exportData == "false") {
           
           if($request->hasFile('archivo')){ //valida que exista el archivo
               if ($request->file('archivo')->isValid()) { //valida que se haya cargado el archivo correctamente
                   $archivo = $request->file('archivo');
                   $nombreArhivo = $archivo->getClientOriginalName();
                   $sizeArchivo = $archivo->getSize(); 
                   if ($sizeArchivo < 800000000000) {
                       
                       $content = File::get($archivo);
                       //echo $content;
                       $arrayRegistros = explode("\r\n",$content);   //por saltos de linea  
                       $arrayClientes = array();
                       $arrayClientesTemp = array();
                       
                       for ($i=0; $i < count($arrayRegistros) ; $i++) {

                           $arrayClientesTemp = explode(";",$arrayRegistros[$i]);
                           if(array_filter($arrayClientesTemp)){ $arrayClientes[$i] = $arrayClientesTemp; }
                               
                       }

                       if (count($arrayClientes) == 0) { 
                            return $this->errorMessage("El archivo subido no contiene datos!.",402);
                       }
                       //var_dump($arrayClientes);
                       
                       $clientesErrados = array();
                       $registrosErrados = array();
                       $clientesObservados = array();
                       $registrosObservados = array();
                       $clientesNoErrados = array();
                       $clientesHaEliminar = array();
                       
                       for ($i=0; $i < count($arrayClientes) ; $i++) { 
                           
                            if (count($arrayClientes[$i]) < 2) {
                                $registrosErrados[] = $i+1;
                                $clientesErrados[] = $arrayClientes[$i];
                            }elseif (count($arrayClientes[$i]) > 2) {
                                $registrosObservados[] = $i+1;
                                $clientesObservados[] = $arrayClientes[$i];
                            }else{
                                $clientesNoErrados[] = $arrayClientes[$i];
                                if($arrayClientes[$i][1] === 'ELIMINAR'){
                                    $clientesHaEliminar[] = $arrayClientes[$i][0];
                                }
                            }

                       }                           
                       //var_dump($clientesHaEliminar);
                       
                       if (count($clientesErrados) == count($arrayClientes)) { 
                           return $this->errorMessage("Todos los datos enviados son inválidos !.",402);
                       }

                        if (count($clientesObservados) > 0) { 
                            return $this->resultData(array(
                                "procesoResult"=>false,
                                "cantidadErrores"=>count($clientesObservados),
                                "errores"=>json_encode($clientesObservados),
                                "dataProcesar"=>$clientesObservados,
                                "registro"=>$registrosObservados,
                                "nombre"=>""
                            ));
                        }

                        if (count($clientesErrados) > 0 ) {
                            return $this->resultData(array(
                                "procesoResult"=>false,
                                "cantidadErrores"=>count($clientesErrados),
                                "errores"=>json_encode($clientesErrados),
                                "dataProcesar"=>$clientesErrados,
                                "registro"=>$registrosErrados,
                                "nombre"=>""
                            )); 
                        }
                       
                       //Guardando en los datos de las consultas en BD
                       if (count($clientesNoErrados) > 0) { 
                            $resultadoInsert = $funcionMensajesOperador->registroMensajesOperador($clientesNoErrados);
                       }
                       if (count($clientesHaEliminar) > 0) { 
                            $resultadoEliminar = $funcionMensajesOperador->eliminarMensajesOperador($clientesHaEliminar);
                       }
                            
                       if (!$resultadoInsert) {
                           return $this->errorMessage("Se generó un problema en el servidor, intente nuevamente.",402);
                       }
                       
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
