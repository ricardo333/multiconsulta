<?php

namespace App\Http\Controllers\Modulos\ConsultaDhcp;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GeneralController;
//use App\Functions\ValidacionServiciosFunctions;
//use App\Reportes\Excel\ValidacionServicios\ValidacionServiciosExcel;

class ConsultaDhcpController extends GeneralController
{
    public function index()
    {
        return view('administrador.modulos.consultaDhcp.index');
    }

    

    public function cargaArchivo(Request $request)
    { 
 
        $validaServicios = new MasivaCmsFunctions;
 
        if ($request->exportData == "false") {
            
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
                            
                        for ($i=0; $i < count($arrayRegistros) ; $i++) {

                            //$regist = $i-1;
                            //$arrayClientes[$regist] = explode(",",$arrayRegistros[$i]);
                            $arrayClientes[$i] = $arrayRegistros[$i];
                                
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