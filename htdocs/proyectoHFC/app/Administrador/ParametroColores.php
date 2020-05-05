<?php

namespace App\Administrador;

use App\Administrador\ParametroColores;
use Illuminate\Database\Eloquent\Model;

class ParametroColores extends Model
{
    const IDENTIFICADOR = "namespace";
     
    const MULTICONSULTA= "MULTICONSULTA";
    const EDIFICIOS= "EDIFICIOS";
    const DIAGNOSTICO_MASIVO= "DIAGNOSTICO_MASIVO";
    const MONITOREO_AVERIAS= "MONITOREO_DE_AVERIAS";
    const CAIDAS= "CAIDAS";
    const PROBLEMA_SENAL_RF= "PROBLEMA_SENAL_RF";
    const LLAMADAS= "LLAMADAS";
    const MASIVA_CMS= "MASIVA_CMS";
    const ESTADO_CABLE_MODEMS= "ESTADO_CABLE_MODEMS"; 
    const TRABAJOS_PROGRAMADOS= "TRABAJOS_PROGRAMADOS"; 
    const CONTEO_MODEMS= "CONTEO_MODEMS";
    const MONITOR_IPS= "MONITOR_IPS"; 
    const SATURACION_DOWN= "SATURACION_DOWN"; 
    const ETIQUETADO_PUERTOS= "ETIQUETADO_PUERTOS"; 
    const INGRESO_AVERIAS= "INGRESO_AVERIAS";
    const CUARENTENA= "CUARENTENA";
    const MONITOR_FUENTES= "MONITOR_FUENTES";
    const LLAMADAS_NODO= "LLAMADAS_NODO"; 
    const CONTENCION_LLAMADAS= "CONTENCION_LLAMADAS"; 
    const MAPA_LLAMADAS_PERU= "MAPA_LLAMADAS_PERU"; 
    const GRAFICA_LLAMADAS_NODOS= "GRAFICA_LLAMADAS_NODOS"; 
    const SEGUIMIENTO_LLAMADAS= "SEGUIMIENTO_LLAMADAS";
    const GRAFICA_LLAMADAS_NODOS_DIA= "GRAFICA_LLAMADAS_NODOS_DIA"; 
    const VISOR_AVERIAS= "VISOR_AVERIAS";
    const MONITOR_APACHE= "MONITOR_APACHE";


    protected $connection = 'mysql';
    
    protected $table = 'parametros_colores';
    
    protected $fillable = [
        'namespace',
        'detalle',
        'parametros'
    ];

    public static function getMulticonsultaParametros(){
        $multiconsulta = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MULTICONSULTA)->first();
        $configMulticonsulta = json_decode($multiconsulta->parametros);
        return $configMulticonsulta;
    }

    public static function getEdificiosParametros()
    {
        $edificios = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::EDIFICIOS)->first();
        $configEdificio = json_decode($edificios->parametros);
        return $configEdificio;
    }
    public static function getDiagnosticoMasivoParametros()
    {
        $diagnosticoMasivo = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::DIAGNOSTICO_MASIVO)->first();
        $configDM = json_decode($diagnosticoMasivo->parametros);
        return $configDM;
    }
     

    public static function getColoresNivelesRX($RxPwrdBmv,$RxPwrdBmvColors)
    {

         $paramRxPwrdBmv = json_decode($RxPwrdBmvColors->parametros);

        if(( (double) $RxPwrdBmv < 4 || (double) $RxPwrdBmv  > 5.5) && trim($RxPwrdBmv) <> ''){
            $RxPwrdBmvBackground = $paramRxPwrdBmv->colores[0]->background; 
            $RxPwrdBmvColor = $paramRxPwrdBmv->colores[0]->color;
            
        }else { 
            $RxPwrdBmvBackground = $paramRxPwrdBmv->colores[1]->background; 
            $RxPwrdBmvColor = $paramRxPwrdBmv->colores[1]->color;
             
        }

        return array(
            "estiloBackRxPwrdBmv"=>$RxPwrdBmvBackground,
            "estiloColorRxPwrdBmv"=>$RxPwrdBmvColor
        );
    }

    public  static function getColoresNivelesRuido($downSnr,$downPx,$upSnr,$upPx,
                                                $SNR_DOWNColors,$POWER_DOWNColors,
                                                $SNR_UPColors,$POWER_UPColors)
    {
 
        $paramSNR_DOWNColors = json_decode($SNR_DOWNColors->parametros);
        $paramPOWER_DOWNColors = json_decode($POWER_DOWNColors->parametros);
        $paramSNR_UPColors = json_decode($SNR_UPColors->parametros);
        $paramPOWER_UPColors = json_decode($POWER_UPColors->parametros);
 
        //dd($paramSNR_DOWNColors->colores);
        $estiloDownSnr = $paramSNR_DOWNColors->colores[2]->background;
        if ( $downSnr >= 30 && $downSnr > 0) {
            $estiloDownSnr = $paramSNR_DOWNColors->colores[0]->background;
        }
        else if ($downSnr > 0) {
                $estiloDownSnr = $paramSNR_DOWNColors->colores[1]->background;
        }
        $downPx = str_replace(' ', '', $downPx);
        $estiloDownPx =  $paramPOWER_DOWNColors->colores[1]->background;
            if ($downPx >= - 5 && $downPx <= 10) {
                $estiloDownPx = $paramPOWER_DOWNColors->colores[0]->background;
            } 

        $estiloUpSnr =  $paramSNR_UPColors->colores[2]->background;
            if ($upSnr >= 27 && $upSnr > 0) {
                $estiloUpSnr = $paramSNR_UPColors->colores[0]->background;
            }
            else  if ($upSnr > 0) {
                $estiloUpSnr = $paramSNR_UPColors->colores[1]->background;
            }

        $estiloUpPx = $paramPOWER_UPColors->colores[2]->background;
            if ($upPx >= 37 && $upPx <= 55) {
                $estiloUpPx = $paramPOWER_UPColors->colores[0]->background;
            }
            else if ($upPx > 0) {
                    $estiloUpPx = $paramPOWER_UPColors->colores[1]->background;
            }

        return  array(
                "estiloDownSnr" => $estiloDownSnr,
                "estiloDownPx" => $estiloDownPx,
                "estiloUpSnr" => $estiloUpSnr,
                "estiloUpPx" => $estiloUpPx,
        );

    }

      
    public static function getColorMacstate($macState,$MacstateColors){
         
        if($macState == "online"){
            $MacStateBackground = $MacstateColors->colores[0]->background; 
            $MacStateColor= $MacstateColors->colores[0]->color; 
        }else{
            $MacStateBackground = $MacstateColors->colores[1]->background; 
            $MacStateColor= $MacstateColors->colores[1]->color; 
        }
        
        return array(
            "background"=>$MacStateBackground,
            "color"=>$MacStateColor
        );
    }


    //MONITOR AVERIAS

    public static function getMonitoreoAveriasParametros()
    {
         $monitoreoAveria = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MONITOREO_AVERIAS)->first();
         $configMoAveri =  json_decode($monitoreoAveria->parametros);
         return $configMoAveri;
    }

    //CAIDAS
    public static function getCaidasParametros()
    {
         $caidas = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::CAIDAS)->first();
         $configCaidas =  json_decode($caidas->parametros);
         return $configCaidas;
    }


    //PROBLEMA SEÑAL RF
    public static function getProblemaSenalRFParametros()
    {
         $problemaSenalRF = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::PROBLEMA_SENAL_RF)->first();
         $configProSenal =  json_decode($problemaSenalRF->parametros);
         return $configProSenal;
    }


    //LLAMADAS
    public static function getLlamadasParametros()
    {
       
         $llamadas = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::LLAMADAS)->first();
         $configLlamadas =  json_decode($llamadas->parametros);
         //dd($configLlamadas);
         return $configLlamadas;
         
    }



    //PROBLEMA SEÑAL RF
    public static function getMasivaCmsParametros()
    {
         $masivaCms = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MASIVA_CMS)->first();
         $configMasiCms =  json_decode($masivaCms->parametros);
         return $configMasiCms;
    }


    //ESTADOS MODEMS
    public static function getEstadosModemsParametros()
    {
       
         $estadosModems = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::ESTADO_CABLE_MODEMS)->first();
         $configEstadosModems =  json_decode($estadosModems->parametros);
         //dd($configLlamadas);
         return $configEstadosModems;
         
    }

    //TRABAJOS PROGRAMADOS
    public static function getTrabajosProgramadosParametros()
    {
        $trabajosProg = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::TRABAJOS_PROGRAMADOS)->first();
        $configTP = json_decode($trabajosProg->parametros);
        return $configTP;
    }
 
    //CONTEO MODEMS
    public static function getConteoModemsParametros()
    {
      
         $conteoModems = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::CONTEO_MODEMS)->first();
         $configConteoModems =  json_decode($conteoModems->parametros);
         //dd($configConteoModems);
         return $configConteoModems;
         
    }

    //CONTEO MODEMS
    public static function getMonitorIPSParametros()
    {
      
         $monitorIPS = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MONITOR_IPS)->first();
         $configMonitorIPS =  json_decode($monitorIPS->parametros);
         //dd($configConteoModems);
         return $configMonitorIPS;
         
    }

    public static function getSaturacionDownParametros(){
        $saturacionDown = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::SATURACION_DOWN)->first();
        $configSaturacionDown = json_decode($saturacionDown->parametros);
        return $configSaturacionDown;
    }

    public static function getEtiquetadoPuertosParametros(){
        $etiquetadoPuertos = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::ETIQUETADO_PUERTOS)->first();
        $configEtiquetadoPuertos = json_decode($etiquetadoPuertos->parametros);
        return $configEtiquetadoPuertos;
    }

    public static function getIngresoAveriasParametros(){
        $ingresoAverias = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::INGRESO_AVERIAS)->first();
        $configIngresoAverias = json_decode($ingresoAverias->parametros);
        return $configIngresoAverias;
    }

    //CUARENTENAS
    public static function getCuarentenasParametros()
    {
        $cuarentenasConfig = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::CUARENTENA)->first();
        $configCT = json_decode($cuarentenasConfig->parametros); 
        return $configCT;
    }
 
    //MONITOR FUENTES
    public static function getMonitorFuentesParametros()
    {
        $monitorFConfig = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MONITOR_FUENTES)->first();
        
        $configMF = json_decode($monitorFConfig->parametros); 
        return $configMF;
    }
 
    //LLAMADAS POR NODOS
    public static function getLlamadasNodoParametros(){
        $etiquetadoLlamadasNodo = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::LLAMADAS_NODO)->first();
        $configLlamadasNodo = json_decode($etiquetadoLlamadasNodo->parametros);
        return $configLlamadasNodo;
 
    }

    //CONTENCION LLAMADAS
    public static function getContencionLlamadasParametros(){
        $etiquetadContencionLlamadas = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::CONTENCION_LLAMADAS)->first();
        $configContencionLlamadas = json_decode($etiquetadContencionLlamadas->parametros);
        return $configContencionLlamadas;
 
    }

    //MAPA LLAMADAS PERU
    public static function getmapaLlamadasPeruParametros(){
        $mapaLlamP = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::GRAFICA_LLAMADAS_NODOS)->first();
        $configMapaLlamP = json_decode($mapaLlamP->parametros);
        return $configMapaLlamP;
    }

    //GRAFICA LLAMADAS NODOS
    public static function getGraficaLlamadasNodosParametros(){
        $graficaLlamNod = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::GRAFICA_LLAMADAS_NODOS)->first();
        $configraficaLlamNod = json_decode($graficaLlamNod->parametros);
        return $configraficaLlamNod;
    }

    //SEGUIMIENTO LLAMADAS
    public static function getSeguimientoLlamadasParametros(){
        $etiquetadSeguimientoLlamadas = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::SEGUIMIENTO_LLAMADAS)->first();
        $configSeguimientoLlamadas = json_decode($etiquetadSeguimientoLlamadas->parametros);
        return $configSeguimientoLlamadas;
 
    }

    //GRAFICA LLAMADAS NODOS DIA
    public static function getGraficaLlamadasNodosDiaParametros(){
        $graficaLlamNodDia = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::GRAFICA_LLAMADAS_NODOS_DIA)->first();
        $configraficaLlamNodDia = json_decode($graficaLlamNodDia->parametros);
        return $configraficaLlamNodDia;
    }


    //GRAFICA VISOR DE AVERIAS
    public static function getGraficaVisorAveriasParametros(){
        $graficaVisorAverias = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::VISOR_AVERIAS)->first();
        $configraficaVisorAverias = json_decode($graficaVisorAverias->parametros);
        return $configraficaVisorAverias;
    }

    //MONITOR APACHE
    public static function getMonitorApacheParametros(){
        $etiquetadMonitorApache = ParametroColores::where(ParametroColores::IDENTIFICADOR,ParametroColores::MONITOR_APACHE)->first();
        $configMonitorApache = json_decode($etiquetadMonitorApache->parametros);
        return $configMonitorApache;
 
    }


  
}
