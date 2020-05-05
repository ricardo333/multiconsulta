<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;

class Parametrosrf extends Model
{
    
    const IDENTIFICADOR = "namespace";
    const MULTICONSULTA = "MULTICONSULTA";
    const MAPA = "MAPA";
    const EDIFICIOS = "EDIFICIOS";
    const DIAGNOSTICO_MASIVO = "DIAGNOSTICO_MASIVO";
    const VALIDACION_SERVICIOS = "VALIDACION_SERVICIOS";
    const MONITOREO_AVERIAS = "MONITOREO_AVERIAS";
    const MONITOREO_AVERIAS_TROBA = "MONITOREO_AVERIAS_TROBA";
    const CAIDAS_MASIVAS_TROBA = "CAIDAS_MASIVAS_TROBA";
    const DESCARGA_CLIENTES_TROBA = "DESCARGA_CLIENTES_TROBA";
    const TRABAJOS_PROGRAMADOS = "TRABAJOS_PROGRAMADOS";
    const CUARENTENAS = "CUARENTENAS";
    const MAPA_CALL_PERU = "MAPA_CALL_PERU";
    const HISTORICO_RUIDOS = "HISTORICO_RUIDOS";
 
    protected $connection = 'mysql';

    protected $table = 'parametros_rf';
    
    protected $fillable = [
        'namespace',
        'power_down_min',
        'power_down_max',
        'power_up_min',
        'power_up_max',
        'snr_down_min',
        'snr_down_max',
        'snr_up_min',
        'snr_up_max',
        'colores',
        'mensajes'
    ];

    public function getMulticonsultaNivelesRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::MULTICONSULTA)->first();
    }
    public function getMapaNivelesRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::MAPA)->first();
    }
    public function getMapaCallPeruNivelesRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::MAPA_CALL_PERU)->first();
    }
    public function getEdificiosNivelesRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::EDIFICIOS)->first();
    }
    public function getDiagnosMasiNivelesRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::DIAGNOSTICO_MASIVO)->first();
    }
    public function getValidacionServicioRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::VALIDACION_SERVICIOS)->first();
    }
    public function getMonitoreoAveriaRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::MONITOREO_AVERIAS)->first();
    }
    public function getMonitoreoAveriaRFNodoTroba()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::MONITOREO_AVERIAS_TROBA)->first();
    }
    public function getCaidasMasivasRFNodoTroba()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::CAIDAS_MASIVAS_TROBA)->first();
    }
    public function getDescargaClienteTrobaRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::DESCARGA_CLIENTES_TROBA)->first();
    }
    public function getTrabajosProgramadosRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::TRABAJOS_PROGRAMADOS)->first();
    }

    public function getCuarentenasRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::CUARENTENAS)->first();
    }
    public static function getHistoricoRuidosRF()
    {
        return Parametrosrf::where(Parametrosrf::IDENTIFICADOR,Parametrosrf::HISTORICO_RUIDOS)->first();
    }
    


    public function getDecodeJsonNivelesRF($parametros){
 
        return array(
            "down_snr_min" => $parametros->snr_down_min,
            "down_snr_max" => $parametros->snr_down_max,
            "down_pwr_min" => $parametros->power_down_min,
            "down_pwr_max" => $parametros->power_down_max,
            "up_snr_min" => $parametros->snr_up_min,
            "up_snr_max" => $parametros->snr_up_max,
            "up_pwr_min" => $parametros->power_up_min,
            "up_pwr_max" => $parametros->power_up_max,
            "paramSNR_DOWNColors" => json_decode($parametros->colores)->snr_down, 
            "paramPOWER_DOWNColors" => json_decode($parametros->colores)->power_down,
            "paramSNR_UPColors" => json_decode($parametros->colores)->snr_up,
            "paramPOWER_UPColors" => json_decode($parametros->colores)->power_up,
            "mensajes" => json_decode($parametros->mensajes)
        );
    }
 
    public static function getColoresNivelesRF($downSnr,$downPx,$upSnr,$upPx,$parametros)                                   
    {
          //  dd($downSnr,$downPx,$upSnr,$upPx,$parametros);
        if ($upPx <= $parametros["up_pwr_min"] || $upPx >= $parametros["up_pwr_max"]) {
            $UpPxBackground = $parametros["paramPOWER_UPColors"][1]->background;
            $UpPxColor = $parametros["paramPOWER_UPColors"][1]->color;
        }else{
            $UpPxBackground = $parametros["paramPOWER_UPColors"][0]->background;
            $UpPxColor = $parametros["paramPOWER_UPColors"][0]->color;
        }

        if ($upSnr <= $parametros["up_snr_min"] &&  $upSnr >= $parametros["up_snr_max"]) {
            $UpSnrBackground = $parametros["paramSNR_UPColors"][1]->background;
            $UpSnrColor = $parametros["paramSNR_UPColors"][1]->color;
        }else{
            $UpSnrBackground = $parametros["paramSNR_UPColors"][0]->background;
            $UpSnrColor = $parametros["paramSNR_UPColors"][0]->color;
        }

        if ($downPx <= $parametros["down_pwr_min"] || $downPx >= $parametros["down_pwr_max"]) {
            $DownPxBackground = $parametros["paramPOWER_DOWNColors"][1]->background;
            $DownPxColor = $parametros["paramPOWER_DOWNColors"][1]->color;
        }else{
            $DownPxBackground = $parametros["paramPOWER_DOWNColors"][0]->background;
            $DownPxColor = $parametros["paramPOWER_DOWNColors"][0]->color;
        }
        
        if ($downSnr <= $parametros["down_snr_min"] && $downSnr > $parametros["down_snr_max"]) {
            $DownSnrBackground = $parametros["paramSNR_DOWNColors"][1]->background;
            $DownSnrColor = $parametros["paramSNR_DOWNColors"][1]->color;
        }else{
            $DownSnrBackground = $parametros["paramSNR_DOWNColors"][0]->background;
            $DownSnrColor = $parametros["paramSNR_DOWNColors"][0]->color;
        }
            // dd($DownSnrBackground);
        return  array(
                "DownSnrBackground" => $DownSnrBackground,
                "DownSnrColor" => $DownSnrColor,
                "DownPxBackground" => $DownPxBackground,
                "DownPxColor" => $DownPxColor,
                "UpSnrBackground" => $UpSnrBackground,
                "UpSnrColor" => $UpSnrColor,
                "UpPxBackground" => $UpPxBackground,
                "UpPxColor" => $UpPxColor
        );
          
    }
 

    public static function getEstadoSegunNivelesRF($macstate,$macaddress,$downSnr,$downPx,$upSnr,$upPx,$parametros)
    {
        if ($macstate  == "offline" && $macaddress == null) {
            $estado = "Offline - NO OK"; 
            $estadoNivel = 0;
        }elseif ($macstate  == "offline") {
            $estado = "Offline - NO OK"; 
            $estadoNivel = 0;
        }
        elseif($downSnr == 0 && $macstate != 'offline'){
            $estado = "Modem Sincronizado - Cmts no aun no lee niveles";
            $estadoNivel = 3;
       }elseif ($downSnr == null && in_array($macstate, array('w-online','online','operational'))) {
            $estado="Modem Sincronizado - No hay reporte de niveles - Validar Manualmente";
            $estadoNivel = 3;
       }elseif ($macstate == null && $downSnr == null) {
            $estado="Offline - NO OK";
            $estadoNivel = 0;
       }elseif ($downSnr == null && !in_array($macstate,array('w-online','ponline','p-online','online','operational','offline'))) {
            $estado=$macstate." Modem no Sincronizado - No hay niveles no se puede validar";
            $estadoNivel = 3;
       }elseif ($macstate != "offline") {
            if ($upPx <= $parametros["up_pwr_min"] || $upPx >= $parametros["up_pwr_max"]) {
                $estado ="Niveles NO OK";
                $estadoNivel = 2;
            }elseif ($upSnr <= $parametros["up_snr_min"] &&  $upSnr >= $parametros["up_snr_max"]) {
                $estado = "Niveles NO OK";
                $estadoNivel = 2;
            }elseif ($downPx <= $parametros["down_pwr_min"] || $downPx >= $parametros["down_pwr_max"]) {
                $estado = "Niveles NO OK";
                $estadoNivel = 2;
            }elseif ($downSnr <= $parametros["down_snr_min"] && $downSnr >=  $parametros["down_snr_max"]) {
                $estado = "Niveles NO OK";
                $estadoNivel = 2;
            }elseif ($downPx == null) {
                $estado = "Incierto - Validar";
                $estadoNivel = 3;
            }elseif($downPx == null && $downSnr==null){
                $estado = "CMTS No reporta Niveles";
                $estadoNivel = 3;
            }else{
                $estado = "OK";
                $estadoNivel = 1;
            }
       }else{
            $estado = "OK";
            $estadoNivel = 1;
       }

       return  array(
           "nivel"=>$estadoNivel,
           "mensaje"=>$estado
       );  

    }

    public static function getEstadoServiciosVSegunNivelesRF($CaidaA,$CaidaM,$CaidaS,$macstate,$downSnr,$downPx,$upSnr,$upPx,$parametros)
    {
        if ($CaidaA == 'SI' && (   $macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)'     )
        ){
            $estado  = $parametros["mensajes"]->mensaje_uno[0]->mensaje; 
       }elseif($CaidaM == 'SI' && (    $macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                    $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                    $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)'  )
        ){
            $estado = $parametros["mensajes"]->mensaje_dos[0]->mensaje; 
       }elseif ($CaidaS == 'SI') {
            $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
       }elseif ($macstate == 'offline') {
            $estado ="Offline - NO OK";
       }elseif ($upSnr < $parametros["up_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
       }elseif ($upSnr  <  $parametros["up_snr_min"] && $upPx < $parametros["up_pwr_min"] ) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_min"] && $downPx < $parametros["down_pwr_max"] ) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($downPx >  $parametros["down_pwr_max"] && $upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx < $parametros["up_pwr_min"] && $upPx  > 0) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx < $parametros["up_pwr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upSnr < $parametros["up_snr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($macstate == 'init(d)' || $macstate == 'init(i)' || $macstate == 'init(io)' || $macstate == 'init(o)' || $macstate == 'init(r)' || 
                $macstate == 'init(r1)' || $macstate == 'init(t)' || $macstate == 'bpi(wait)'){
                $estado = $parametros["mensajes"]->mensaje_cuatro[0]->mensaje; 
        }elseif ($downPx < $parametros["down_pwr_min"]  &&  $upPx > $parametros["up_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje; 
        }elseif ($downPx < $parametros["down_pwr_min"] || $downPx > $parametros["down_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje; 
        }elseif ($downPx < $parametros["down_pwr_min"] && $downSnr  < $parametros["down_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje; 
        }elseif ($downPx  == '' && $downSnr == ''  && $macstate  ==  'online') {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje; 
        }elseif ($downPx =='' && $downSnr =='' && $macstate  == '') {
            $estado = "Modem no registrado en CMTS";
        }elseif ($downPx == 0 && $macstate == 0) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje; 
        }
        else{
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje; 
       }

       return  array(
           "mensaje"=>$estado
       );  

    }


    public static function getMonitoreoAveriasVSegunNivelesRF($codreqmnt,$Caida1,$Caida2,$Caida3,$macstate,$upSnr,$upPx,$downPx,$downSnr,$parametros)
    {
        if ($Caida1 == 'SI' && ($macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)' )
        ){
            $estado  = $parametros["mensajes"]->mensaje_uno[0]->mensaje; 
       }elseif($Caida2 == 'SI' && (    $macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                    $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                    $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)'  )
        ){
            $estado = $parametros["mensajes"]->mensaje_dos[0]->mensaje; 
        }elseif ($Caida3 == 'SI') {
            $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($macstate == 'offline') {
            $estado =  $parametros["mensajes"]->mensaje_cuatro[0]->mensaje;
        }elseif ($upSnr == 0 && $upPx == 0 && $downPx == 0 && $downSnr == 0) {
            $estado =  $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }elseif ($upSnr < $parametros["up_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upSnr < $parametros["up_snr_min"] && $upPx < $parametros["up_pwr_min"] ) {
            $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje;      
        }elseif ($upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_min"] && $downPx < $parametros["down_pwr_max"] ) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($downPx >  $parametros["down_pwr_max"] && $upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx < $parametros["up_pwr_min"] && $upPx  > 0) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx < $parametros["up_pwr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje;
        }elseif($downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upSnr < $parametros["up_snr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($macstate == 'init(d)' || $macstate == 'init(i)' || $macstate == 'init(io)' || $macstate == 'init(o)' || $macstate == 'init(r)' || 
                $macstate == 'init(r1)' || $macstate == 'init(t)' || $macstate == 'bpi(wait)'){
                $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"]  &&  $upPx > $parametros["up_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"] || $downPx > $parametros["down_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"] && $downSnr  < $parametros["down_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($upPx > $parametros["up_pwr_max"] && $upPx < $parametros["up_pwr_min"] && 
            $upSnr > $parametros["up_snr_max"] && $downPx > $parametros["down_pwr_max"] &&
            $downPx < $parametros["down_pwr_min"]){
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx  == '' && $downSnr == ''  && $macstate  ==  'online') {
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }elseif ($downPx =='' && $downSnr =='' && $macstate  == '') {
            $estado = $parametros["mensajes"]->mensaje_ocho[0]->mensaje;
        }elseif ($macstate == 'init' || $macstate == 'init(t)' || $macstate == 'init(r2)' || $macstate == 'init(r1)'){        
            $estado = $parametros["mensajes"]->mensaje_nueve[0]->mensaje;
        }elseif ($macstate == 'init(d)' || $macstate == 'DHCP' || $macstate == 'init(o)'){    
            $estado = $parametros["mensajes"]->mensaje_diez[0]->mensaje;
        }elseif ($downPx == 'NULL' && $macstate == 'NULL'){    
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }else{
            $estado = $parametros["mensajes"]->mensaje_once[0]->mensaje; 
        }

       return  array(
           "mensaje"=>$estado
       );  

    }


    public static function getMonitoreoAveriasVSegunNivelesRFNodoTroba($Caida1,$Caida2,$Caida3,$macstate,$upSnr,$upPx,$downPx,$downSnr,$cmts,$tiptec,$idcliente,$parametros)
    {
        if ($Caida1 == 'SI' && ($macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)' )
        ){
            $estado  = $parametros["mensajes"]->mensaje_uno[0]->mensaje; 
       }elseif($Caida2 == 'SI' && (    $macstate =='offline' || $macstate  == 'init(d)' || $macstate  == 'init(i)' || 
                                    $macstate  == 'init(io)' || $macstate  == 'init(o)' || $macstate  == 'init(r)' || 
                                    $macstate  == 'init(r1)'  || $macstate  == 'init(t)' || $macstate  == 'bpi(wait)'  )
        ){
            $estado = $parametros["mensajes"]->mensaje_dos[0]->mensaje; 
        }elseif ($Caida3 == 'SI') {
            $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($macstate == 'offline') {
            $estado =  $parametros["mensajes"]->mensaje_cuatro[0]->mensaje;
        }elseif ($upSnr == 0 && $upPx == 0 && $downPx == 0 && $downSnr == 0) {
            $estado =  $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }elseif ($upSnr < $parametros["up_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upSnr < $parametros["up_snr_min"] && $upPx < $parametros["up_pwr_min"] ) {
            $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje;      
        }elseif ($upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_min"] && $downPx < $parametros["down_pwr_max"] ) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($downPx >  $parametros["down_pwr_max"] && $upPx < $parametros["up_pwr_min"]) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif ($upPx < $parametros["up_pwr_min"] && $upPx  > 0) {
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx < $parametros["up_pwr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje;
        }elseif($downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upPx > $parametros["up_pwr_max"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($upSnr < $parametros["up_snr_min"] && $downPx > $parametros["down_pwr_max"]){
                $estado =  $parametros["mensajes"]->mensaje_tres[0]->mensaje; 
        }elseif($macstate == 'init(d)' || $macstate == 'init(i)' || $macstate == 'init(io)' || $macstate == 'init(o)' || $macstate == 'init(r)' || 
                $macstate == 'init(r1)' || $macstate == 'init(t)' || $macstate == 'bpi(wait)'){
                $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"]  &&  $upPx > $parametros["up_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"] || $downPx > $parametros["down_pwr_max"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx < $parametros["down_pwr_min"] && $downSnr  < $parametros["down_snr_min"]) {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($upPx > $parametros["up_pwr_max"] && $upPx < $parametros["up_pwr_min"] && 
            $upSnr > $parametros["up_snr_max"] && $downPx > $parametros["down_pwr_max"] &&
            $downPx < $parametros["down_pwr_min"]){
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        }elseif ($downPx  == '' && $downSnr == ''  && $macstate  ==  'online') {
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }elseif ($downPx =='' && $downSnr =='' && $macstate  == '') {
            $estado = $parametros["mensajes"]->mensaje_ocho[0]->mensaje;
        }elseif ($macstate == 'init' || $macstate == 'init(t)' || $macstate == 'init(r2)' || $macstate == 'init(r1)'){        
            $estado = $parametros["mensajes"]->mensaje_nueve[0]->mensaje;
        }elseif ($macstate == 'init(d)' || $macstate == 'DHCP' || $macstate == 'init(o)'){    
            $estado = $parametros["mensajes"]->mensaje_diez[0]->mensaje;
        }elseif ($downPx == 'NULL' && $macstate == 'NULL'){    
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        }elseif ($cmts == 'NULL' && $tiptec <> 'HFC'){    
            $estado = $parametros["mensajes"]->mensaje_once[0]->mensaje;
        }elseif ($idcliente == 'NULL'){    
            $estado = $parametros["mensajes"]->mensaje_once[0]->mensaje;
        }else{
            $estado = $parametros["mensajes"]->mensaje_doce[0]->mensaje; 
        }

       return  array(
           "mensaje"=>$estado
       );  

    }


    public static function getCaidasMasivasVSegunNivelesRF($macstate,$mac,$upSnr,$upPx,$downPx,$downSnr,$parametros)
    {
        if ($macstate == 'Offline' && $mac == null) {
            $estado = $parametros["mensajes"]->mensaje_uno[0]->mensaje;
        } elseif ($downSnr == '-----' && $macstate != 'Offline') {
            $estado = $parametros["mensajes"]->mensaje_dos[0]->mensaje;
        } elseif ($downSnr == null && ($macstate == 'w-online' || $macstate == 'online' || $macstate  == 'operational') ) {
            $estado = $parametros["mensajes"]->mensaje_tres[0]->mensaje;
        } elseif ($macstate == null && $downSnr == null) {
            $estado = $parametros["mensajes"]->mensaje_uno[0]->mensaje;
        } elseif ( ($macstate != 'w-online' || $macstate != 'online' || $macstate != 'operational' || $macstate != 'offline') && $downSnr == null ) {
            $estado = $macstate." ".$parametros["mensajes"]->mensaje_cuatro[0]->mensaje;
        } elseif ( ($upPx < $parametros["up_pwr_min"] || $upPx > $parametros["up_pwr_max"]) && $upPx == '-' && $macstate != 'Offline' ) {
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        } elseif ($upSnr < $parametros["up_snr_min"] && $upSnr > $parametros["up_snr_max"] && $upPx != '-' && $macstate != 'Offline' ){
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        } elseif ( ($downPx < $parametros["down_pwr_min"] || $downPx > $parametros["down_pwr_max"]) && $upPx != '-' && $macstate != 'Offline' ){
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        } elseif ($downPx == null && $macstate != 'Offline') {
            $estado = $parametros["mensajes"]->mensaje_seis[0]->mensaje;
        } elseif ($downSnr < $parametros["down_snr_min"] && $upPx != '-' && $macstate != 'Offline') {
            $estado = $parametros["mensajes"]->mensaje_cinco[0]->mensaje;
        } elseif ($downPx == '-' && $downSnr == '-' && $macstate != 'Offline') {
            $estado = $parametros["mensajes"]->mensaje_siete[0]->mensaje;
        } else{
            $estado = $parametros["mensajes"]->mensaje_ocho[0]->mensaje; 
        }

        return  array(
            "mensaje"=>$estado
        ); 

    }




  
}
