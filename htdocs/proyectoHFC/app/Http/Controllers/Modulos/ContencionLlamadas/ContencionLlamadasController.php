<?php

namespace App\Http\Controllers\Modulos\ContencionLlamadas;

use DB;
use Illuminate\Support\Facades\Storage;
use App\Administrador\ParametroColores;
use Illuminate\Http\Request;
use App\Functions\ContencionLlamadasFunctions;
use App\Http\Controllers\GeneralController;

class ContencionLlamadasController extends GeneralController
{
    
    public function view(Request $request)
    {

        return view('administrador.modulos.contencionLlamadas.index');
        
    }   
    
    public function graficoLlamadasContenidas(Request $request)
    { 
          if($request->ajax()){
               #INICIO
                    
                    $colorestContencionLlamadas = ParametroColores::getContencionLlamadasParametros();
                    $colorContencionLlamadas = $colorestContencionLlamadas->COLORES->contencionLlamadas->colores;
                    //return ["data"=>$colorContencionLlamadas];
                    
                    $contencionLlamadasFuntions = new ContencionLlamadasFunctions;
                    
                    $fecha=date("Ymd");
                    $estado= TRUE;
                    $resultHoraTotalContencion = $contencionLlamadasFuntions->getHoraTotalContencion($fecha);
                    $resultDataContencionLlamadas = $contencionLlamadasFuntions->getDataHistoricoContencionLlamadas();
                    //return ["data"=>$resultDataContencionLlamadas];

                    if (count($resultDataContencionLlamadas) == 0) {
                        //return $this->errorMessage("No se encontró data histórica de averías por jefaturas.",500);
                        $estado = FALSE;
                   }
                    
                    return $this->resultData(["data"=>$resultDataContencionLlamadas,"resultHoraTotalContencion"=>$resultHoraTotalContencion,"colorContencionLlamadas"=>$colorContencionLlamadas,"estado"=>$estado]);
                    
               #END
          }
          return abort(404); 
  
    }

    public function descargarArchivos(Request $request)
    {

          $ruta = $request->ruta;
          $archivo = $request->archivo;
          //$extension = $request->extension;
          $origen = $ruta.$archivo;

          //Eliminamos el file llamadas_mes.csv en el servidor 220
          $remove = Storage::disk('sftpServer')->delete($origen);
          //unlink(storage_path($origen));
          
          //Ejecutamos el stored procedured que genera el file en temp/llamadas_mes.csv
          DB::select('call catalogos.sp_llamadas_mes');

          //Descargamos el excel
          $url = Storage::disk('sftpServer')->download($origen);
          return $url;
     
    }

}
