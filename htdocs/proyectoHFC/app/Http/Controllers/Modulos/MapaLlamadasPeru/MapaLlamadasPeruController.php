<?php

namespace App\Http\Controllers\Modulos\MapaLlamadasPeru;

use Illuminate\Http\Request;
use App\Functions\MapaFunctions;
use App\Administrador\Parametrosrf;
use App\Http\Controllers\Controller;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class MapaLlamadasPeruController extends GeneralController
{

   public function view()
   {
      $mapaFunction = new MapaFunctions;
      $jefaturas = $mapaFunction->getjefaturasAndLatLong();
      return view('administrador.modulos.mapaLlamadasPeru.index',["jefaturas"=>$jefaturas]);
   }

   public function grafico(Request $request)
   {
         $validaClteTelDni = Validator::make($request->all(), [
            "ClteTelDni" => "nullable|regex:/^[a-zA-Z0-9\-_]+$/"
         ]);

         $validaJefatura = Validator::make($request->all(), [
            "jefatura" => "nullable|regex:/^[a-zA-Z\_-]+(\s*[a-zA-Z\_-]*)*[a-zA-Z\_-]+$/"
         ]);
          
         $filtroValidaClteTelDni = false;
         $filtroJefatura = "";

         if (!$validaClteTelDni->fails()) {
            if (isset($request->ClteTelDni)) {   
                $filtroValidaClteTelDni = trim($request->ClteTelDni) != "" ? true : false;
            }  
         }
   
         $mapaFunction = new MapaFunctions;
          //Parametros RF 
          $parametrosRF = new Parametrosrf;  
          $paramMapa_detalle = $parametrosRF->getMapaCallPeruNivelesRF();
          $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramMapa_detalle);
          

         $ubicacionCliente = [];
         $centroX = "";
         $centroY = "";
         $vjefatura = 0;
          

         if ($filtroValidaClteTelDni) {
               $ubicacionCliente = $mapaFunction->getClienteByClteTelDni($request->ClteTelDni);
               if (isset($ubicacionCliente[0])) {
                  $centroX = $ubicacionCliente[0]->x;
                  $centroY = $ubicacionCliente[0]->y;
               }
         }

         if (!$validaJefatura->fails()) {
            if (isset($request->jefatura)) {
              
               if (!isset($ubicacionCliente[0])) {
                  $centroX=$request->longitud;
                  $centroY=$request->latitud;
               }

               if ($request->jefatura == "TODO") {
                  if (!isset($ubicacionCliente[0])) {
                     $centroX='-77.030046';
                     $centroY='-12.045914';
                  }
                  $filtroJefatura="";
                  $vjefatura = 5;
               }elseif ($request->jefatura == "LIMA") {
                  $filtroJefatura=" where jefatura in ('LIMA-NOR','LIMA-SUR','LIMA-OES','LIMA-EST') ";
                  $vjefatura = 12;
               }else{
                  $filtroJefatura=" where jefatura='".htmlspecialchars($request->jefatura)."' ";
                  $vjefatura = 12;
               }

               // dd($centroX);
               
            }  
         }

        // dd($filtroJefatura);
         $dataMapaCallPeru = $mapaFunction->getDataLlamadasPeru($filtroJefatura);

         if (isset($ubicacionCliente[0])) {
           // dd($ubicacionCliente,$dataMapaCallPeru[0]);
               $vjefatura = 13;
               $dataMapaCallPeru[] = (object) array(
                                                "nombre" => $ubicacionCliente[0]->nombre,
                                                "cliente" => $ubicacionCliente[0]->cliente,
                                                "servicio" => $ubicacionCliente[0]->servicio,
                                                "nodo" => $ubicacionCliente[0]->nodo,
                                                "troba" => $ubicacionCliente[0]->troba,
                                                "direc_inst" => utf8_encode($ubicacionCliente[0]->direc_inst),
                                                "tiptec" => $ubicacionCliente[0]->tiptec,
                                                "codlex" => $ubicacionCliente[0]->codlex,
                                                "codtap" => $ubicacionCliente[0]->codtap,
                                                "coordX" => $ubicacionCliente[0]->x,
                                                "coordY" => $ubicacionCliente[0]->y,
                                                "color" => "icocliente.png",
                                                "SnrDN" => "",
                                                "DSPwr" => "",
                                                "SnrUP" => "",
                                                "USPwr" => "",
                                                "cant_reit" => 0,
                                                "call_reit" => "",
                                                "rdia" => 0,
                                                "cmts" => "",
                                                "interface" => ""
                                             );
         }

         $sumaX = 0;
         $sumaY = 0;
         $contarXY = 0;


         $dataProcesada = $mapaFunction->procesarMapaCallPeruResult($dataMapaCallPeru,$dataParametrosRF,$sumaX,$sumaY,$contarXY);

         $promedioX = $centroX;
         $promedioY = $centroY;

         if($centroX == ""){
              $promedioX = $dataProcesada["sumaX"] / $dataProcesada["contarXY"];
              $promedioY = $dataProcesada["sumaY"] / $dataProcesada["contarXY"];
         }

          
        /* return view(
            'administrador.partials.mapaCallPeru',
            [
                 "arrResultado"=>$dataProcesada["resultado"],
                 "promedioX"=>$promedioX,
                 "promedioY"=>$promedioY,
                 "cliente"=>isset($ubicacionCliente[0]) ? true : false,
                 "vjefatura"=>$vjefatura
              ]
            );*/

         return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.partials.mapaCallPeru',
                                     [
                                       "arrResultado"=>$dataProcesada["resultado"],
                                       "promedioX"=>$promedioX,
                                       "promedioY"=>$promedioY,
                                       "cliente"=>isset($ubicacionCliente[0]) ? true : false,
                                       "vjefatura"=>$vjefatura
                                       ]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
         );

  
   }

   public function graficoHistoricoNivelesCmtsPorPuerto(Request $request)
   {
       if($request->ajax()){
         #INICIO 
              //  dd($request->all());
               $generalFunctions = new peticionesGeneralesFunctions;
               
               $listaNiveles = $generalFunctions->getHistoricoNivelesCmtsPorPuertos($request->puerto);
                
               
               if ($listaNiveles == "error") {
                  return $this->errorMessage("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
               }
               if (count($listaNiveles) == 0) {
                  return $this->errorMessage("No se encontró data historico de niveles en el puerto del cliente.",500);
               }

               usort($listaNiveles,array($this,'cmp_time'));//ordena el rsultado por fecha

               $parametrosColores = ParametroColores::getmapaLlamadasPeruParametros();
               $coloresNiveles = $parametrosColores->COLORES->nivelesGrafico->colores;

               //dd($coloresNiveles);
 
               return $this->resultData(["data"=>$listaNiveles,"coloresNiveles"=>$coloresNiveles]);
      
               
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



}
