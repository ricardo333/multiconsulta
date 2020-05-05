<?php

namespace App\Http\Controllers\Modulos\ValidacionServicio;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GeneralController;
use App\Functions\ValidacionServiciosFunctions;
use App\Reportes\Excel\ValidacionServicios\ValidacionServiciosExcel;

class ValidacionServiciosController extends GeneralController
{
    public function index()
    {
        return view('administrador.modulos.validacionServicios.index');
    }

    public function cargaArchivo(Request $request)
    { 
         

        $validator = Validator::make($request->all(), [
            "tipoDeValidacion" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
        ]);

        if ($validator->fails()) {   
            return $this->errorMessage($validator->errors()->all(),402);
        }

        $url = config('filesystems.disks.download.url'); 
        $usuarioAuth = Auth::user();
        $idClienteActivo = $usuarioAuth->id;
        $fech_hor = date("Y-m-d_H-i-s"); 
        $validaServiciosF = new ValidacionServiciosFunctions;

        $tipoBus = (int)$request->tipoDeValidacion;
        
        if ( $tipoBus != 1 && $tipoBus != 2) {
            return $this->errorMessage("El tipo de búsqueda indicado no existe.",402);
        }
 
        if ($request->exportData == "false") {
           
            #PROCESAR DATA
                $validaServiciosF->limpiaCodClientesTemporalesByIdUser($idClienteActivo);//Limpia C. T. anteriores del usuario
                $validaServiciosF->limpiaMacClientesTemporalesByIdUser($idClienteActivo);//Limpia C. T. anteriores del usuario
                
                

                if($request->hasFile('archivo')){ //valida que exista el archivo
                    if ($request->file('archivo')->isValid()) { //valida que se haya cargado el archivo correctamente
                        $archivo = $request->file('archivo');
                        $nombreArhivo = $archivo->getClientOriginalName();  
                        $sizeArchivo = $archivo->getSize(); 
                        if ($sizeArchivo < 20000) {
 
                            //$temp_file =  tempnam(sys_get_temp_dir(), $nombreArhivo);
                            $content = File::get($archivo); 
                            $arrayClientes = explode("\r\n",$content);   //por saltos de linea         
                            
                            $clientesErrados = array();
                            $clientesNoErrados = array();
                            for ($i=0; $i < count($arrayClientes) ; $i++) { 
 
                                if (trim($arrayClientes[$i]) != "") {
                                    $resultV =  $this->validaServiciosTipo($arrayClientes[$i],$tipoBus);
                                    //dd($resultV);
                                    if ($resultV["error"]) {
                                        $clientesErrados[] = $arrayClientes[$i] ." : ".$resultV["mensaje"];
                                    }else{
                                        $clientesNoErrados[] = $arrayClientes[$i];
                                    } 
                                
                                }  
                            }                           
                                
                            if (count($clientesErrados) == count($arrayClientes)) { 
                                return $this->errorMessage("Todos los datos enviados son inválidos!.",402);
                            } 

                            if (count($arrayClientes) > 5000) { 
                                return $this->errorMessage("Está superando los 5000 clientes a procesar, intente con una cantidad menor.",402);
                            }
 
                            //Guardando en los datos de las consultas en BD 
                            if ($tipoBus == 1) { //Codigo cliente
                                $resultadoInsert = $validaServiciosF->registroCodClientesTemporalesByIdUser($clientesNoErrados,$idClienteActivo);
                            }else{//Mac cliente
                                $resultadoInsert = $validaServiciosF->registroMacClientesTemporalesByIdUser($clientesNoErrados,$idClienteActivo);
                            }
                           
                            
                            if (!$resultadoInsert) {
                                return $this->errorMessage("Se generó un problema en el servidor, intente nuevamente.",402);
                            }
                             

                            if (count($clientesErrados) > 0 ) {
                                return $this->resultData(array(
                                    "procesoResult"=>false,
                                    "cantidadErrores"=>count($clientesErrados),
                                    "errores"=>$clientesErrados,
                                    "dataProcesar"=>$clientesNoErrados,
                                    "ruta"=>"",
                                    "nombre"=> ""
                                )); 
                            }

                            //En caso de no existir errores
                            return $this->resultData(array(
                                "procesoResult"=>false,
                                "cantidadErrores"=>0,
                                "errores"=>[],
                                "dataProcesar"=>$clientesNoErrados,
                                "ruta"=>"",
                                "nombre"=> ""
                            )); 

 
                        }else{
                            return $this->errorMessage("La longitud del archivo es superior a 20 KB",402);
                        } 
                    }else{
                        return $this->errorMessage("El archivo no se cargo correctamente. Intente nuevamente",402);
                    } 
                }else{
                    return $this->errorMessage("No existe un archivo que procesar. Intente nuevamente",402);
                } 
            #END PROCESO

        }elseif ($request->exportData == "true") {
            // dd("es true, se supone que ya tiene una session de data para exportar el usuario solo los validos");
            
            if ($tipoBus == 1) { //Codigo de Cliente

                $datos = $validaServiciosF->getRegistrosCodClientesTemporalesByIdUser($idClienteActivo);
                
            }else{ //Mac cliente

                $datos = $validaServiciosF->getRegistrosMacClientesTemporalesByIdUser($idClienteActivo);
               
            }

            //dd($datos);
           
            $nameArchivo = "resultadoServicio_".$idClienteActivo."_".$fech_hor.".xlsx";
             
            Excel::store(new ValidacionServiciosExcel($tipoBus,$idClienteActivo), $nameArchivo, 'download');
 
            return $this->resultData(array(
                "procesoResult"=>true,
                "cantidadErrores"=>0,
                "errores"=>[],
                "dataProcesar"=>$datos,
                "ruta"=> $url."/".$nameArchivo,
                "nombre"=> $nameArchivo
            )); 
             

        }else{
            return $this->errorMessage("No se está indicando el proceso adecuado de validacion.",402);
        }
            
    }

    private function  validaServiciosTipo($bus,$tipoBus)
    { 
        $mensaje = "";
        $error = false;
        //dd($tipoBus."-".$bus);
        switch ($tipoBus) {
            case 1://Cod Cliente
                    if (preg_match("/^[0-9\.]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "El codigo del cliente no tiene un formato válido";
                    }
                    if (substr($bus,0,1) == 0) {
                        $error = true;
                        $mensaje = "El codigo del cliente no tiene un formato válido";
                    }
                break; 
            case 2://Mac
             
                    if (preg_match("/^[a-zA-Z0-9\:.]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "La Mac Address no tiene un formato válido";
                    }
                    if (strlen(trim($bus)) < 12 || strlen(trim($bus)) > 17) {
                        $error = true;
                        $mensaje = "La longitud de la Mac Address no es correcta.";
                    }
                break;  
            default:  
                    $error = true;
                    $mensaje = "El tipo de busqueda no existe en el sistema.";
                break; 
        }

        return array(
            "error"=>$error,
            "mensaje"=>$mensaje
        );

    }
 

}
