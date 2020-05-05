<?php

namespace App\Functions\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Functions\IntrawayFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ServicePciFunctions
{

    public function validaUserToken($user,$token)
    {
        $queryUser = DB::select("SELECT user FROM zz_new_system.api_services WHERE user='$user' AND token='$token'");

        return $queryUser;
    }


    public function generarToken($user)
    {
        $token = Str::random(60);
        $time = Carbon::now();

        $queryToken = DB::update("UPDATE zz_new_system.api_services SET token='$token', time='$time' WHERE user='$user'");

        $getToken = DB::select("SELECT token FROM zz_new_system.api_services WHERE user='$user'");

        return $getToken;

    }


    public function procesoPruebasCablemodem($mac_address)
    {
        
        $mac_address = trim($mac_address);

        $longitud_mac = strlen($mac_address);

        if($longitud_mac < 14){
            $respuesta["Error"] = "ERROR 202001. CANTIDAD DE CARACTERES MENOR A FORMATO DE MACADDRESS.";
            return $respuesta;
        } elseif ($longitud_mac > 14) {
            $respuesta["Error"] = "ERROR 202002. CANTIDAD DE CARACTERES MAYOR A FORMATO DE MACADDRESS.";
            return $respuesta;
        }

        //dd($longitud_mac);
        $getNivelesRF = DB::select("SELECT * FROM ccm1.scm_phy_t WHERE MACAddress='".$mac_address."' LIMIT 1");

        //dd($getNivelesRF);
        if (count($getNivelesRF) < 1) {
            $respuesta["Error"] = "ERROR 202003. MACADDRESS NO EXISTE EN LA BASE DE DATOS.";
            return $respuesta;
        }

        $usPwr = $getNivelesRF[0]->USPwr;
        $dsPwr = $getNivelesRF[0]->DSPwr;
        $usSnr = $getNivelesRF[0]->USMER_SNR;
        $dsSnr = $getNivelesRF[0]->DSMER_SNR;

        $resultado1 = "USPWR|".$usPwr."|DSPWR|".$dsPwr."|USSNR|".$usSnr."|DSSNR|".$dsSnr;

        $getParametrosRF = DB::select("SELECT usPwr_min,usPwr_max,dsPwr_min,dsPwr_max,usSnr_max,dsSnr_max 
                                        FROM ccm1_data.wsccm1_parametros_rf LIMIT 1");
                   
        $usPwr_min = $getParametrosRF[0]->usPwr_min;
		$usPwr_max = $getParametrosRF[0]->usPwr_max;
		$dsPwr_min = $getParametrosRF[0]->dsPwr_min;
		$dsPwr_max = $getParametrosRF[0]->dsPwr_max;
		$usSnr_max = $getParametrosRF[0]->usSnr_max;
        $dsSnr_max = $getParametrosRF[0]->dsSnr_max;
        
        /*
        $usPwr_min = $row2[0]["usPwr_min"];  // 35
		$usPwr_max = $row2[0]["usPwr_max"];  // 55
		$dsPwr_min = $row2[0]["dsPwr_min"];  // -5
		$dsPwr_max = $row2[0]["dsPwr_max"];  // 10
		$usSnr_max = $row2[0]["usSnr_max"];  // 27
        $dsSnr_max = $row2[0]["dsSnr_max"];  // 29
        */

        $senalOk = 0;

        if ($usPwr>=$usPwr_min and $usPwr<=$usPwr_max) {
            $senalOk += 0;
        }elseif ($dsPwr>=$dsPwr_min and $dsPwr<=$dsPwr_max) {
            $senalOk += 0;
        }else {
            $senalOk += 1;
        }
        

        if ($usSnr>=$usSnr_max or $usSnr<=$usSnr_max) {
            $senalOk += 1;
        }elseif ($dsSnr>=$dsSnr_max or $dsSnr<=$dsSnr_max) {
            $senalOk += 1;
        }else {
            $senalOk += 0;
        }


        if ($senalOk>0) {
            $resultado2 = "rf-error";
        }else {
            $resultado2 = "rf-ok";
        }


        DB::insert("INSERT INTO ccm1_data.wsccm1_pruebas_cablemodem (mac_address, resultado1, resultado2, fecha_mov )
                    VALUES (?,?,?, NOW())", [$mac_address,$resultado1,$resultado2]);
                    

        //$respuesta = '0#'.$resultado1."#".$resultado2;
        
        $respuesta["SenalOK"] = $senalOk;
        $respuesta["Parametros"]["Parametro1"]["Nombre"] = "USPWR";
        $respuesta["Parametros"]["Parametro1"]["Valor"] = $usPwr;
        $respuesta["Parametros"]["Parametro2"]["Nombre"] = "DSPWR";
        $respuesta["Parametros"]["Parametro2"]["Valor"] = $dsPwr;
        $respuesta["Parametros"]["Parametro3"]["Nombre"] = "USSNR";
        $respuesta["Parametros"]["Parametro3"]["Valor"] = $usSnr;
        $respuesta["Parametros"]["Parametro4"]["Nombre"] = "DSSNR";
        $respuesta["Parametros"]["Parametro4"]["Valor"] = $dsSnr;
        $respuesta["Resultado"] = $resultado2;

	
        return $respuesta;
        //dd($resultado1);

    }


    











}