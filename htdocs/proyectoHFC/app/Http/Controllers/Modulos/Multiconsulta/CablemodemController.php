<?php

namespace App\Http\Controllers\Modulos\Multiconsulta;

use Illuminate\Http\Request;
use App\Functions\LogsFunctions;
use App\Functions\IntrawayFunctions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Functions\CablemodemDmzFunctions;
use App\Functions\CablemodemDhcpFunctions;
use App\Functions\CablemodemUpnpFunctions;
use App\Functions\CablemodemWifiFunctions;
use App\Functions\CablemodemResetFunctions;
use App\Http\Controllers\GeneralController;
use App\Functions\CablemodemMapingFunctions;
use App\Functions\CablemodemStatusFunctions;
use App\Functions\CablemodemUpdateDmzFunctions;
use App\Functions\CablemodemUpdateUpnpFunctions;
use App\Functions\CablemodemUpdateWifiFunctions;
use App\Functions\CablemodemWifiVecinoFunctions;
use App\Functions\CablemodemDiagnosticoFunctions;
use App\Functions\CablemodemUpdateMapingFunctions;

class CablemodemController extends GeneralController
{
    public function status(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $mac = $request->mac;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        if($fabricante=="Ubee" and $modelo=="DDW262" ){
            return $this->errorMessage("No se encuentra disponible esta opcion",500);
        }
    
        $statusCablemodem = new CablemodemStatusFunctions; 
        $logsFunctions = new LogsFunctions;

        $status = $statusCablemodem->statusPrincipal($codCliente,$ipaddress,$fabricante,$modelo);

        if($status=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        if($status=="Error Codigo"){
            return $this->errorMessage("Error en codigo, cambio de firmware",500);
        }

        //dd($status);

        $cantUp = count($status["Upstream"]);
        $cantDown = count($status["Downstream"]);

        if($cantUp==0){
            $frecuenciaUp1 = "";
            $powerUp1 = "";
            $frecuenciaUp2 = "";
            $powerUp2 = "";
        }elseif ($cantUp==1) {
            $frecuenciaUp1 = $status["Upstream"][0]["Frecuencia"];
            $powerUp1 = $status["Upstream"][0]["Power"];
            $frecuenciaUp2 = "";
            $powerUp2 = "";
        }else {
            $frecuenciaUp1 = $status["Upstream"][0]["Frecuencia"];
            $powerUp1 = $status["Upstream"][0]["Power"];
            $frecuenciaUp2 = $status["Upstream"][1]["Frecuencia"];
            $powerUp2 = $status["Upstream"][1]["Power"];
        }

        //Datos obtenidos del CableModem
        $frecuenciaDown1 = $status["Downstream"][0]["Frecuencia"];
        $snrDown1 = $status["Downstream"][0]["SNR"];
        $powerDown1 = $status["Downstream"][0]["Power"];
        $frecuenciaDown2 = $status["Downstream"][1]["Frecuencia"];
        $snrDown2 = $status["Downstream"][1]["SNR"];
        $powerDown2 = $status["Downstream"][1]["Power"];
        $frecuenciaDown3 = $status["Downstream"][2]["Frecuencia"];
        $snrDown3 = $status["Downstream"][2]["SNR"];
        $powerDown3 = $status["Downstream"][2]["Power"];
        $frecuenciaDown4 = $status["Downstream"][3]["Frecuencia"];
        $snrDown4 = $status["Downstream"][3]["SNR"];
        $powerDown4 = $status["Downstream"][3]["Power"];
        $frecuenciaDown5 = $status["Downstream"][4]["Frecuencia"];
        $snrDown5 = $status["Downstream"][4]["SNR"];
        $powerDown5 = $status["Downstream"][4]["Power"];
        $frecuenciaDown6 = $status["Downstream"][5]["Frecuencia"];
        $snrDown6 = $status["Downstream"][5]["SNR"];
        $powerDown6 = $status["Downstream"][5]["Power"];
        $frecuenciaDown7 = $status["Downstream"][6]["Frecuencia"];
        $snrDown7 = $status["Downstream"][6]["SNR"];
        $powerDown7 = $status["Downstream"][6]["Power"];
        $frecuenciaDown8 = $status["Downstream"][7]["Frecuencia"];
        $snrDown8 = $status["Downstream"][7]["SNR"];
        $powerDown8 = $status["Downstream"][7]["Power"];

        
          $logsFunctions->registroLog($logsFunctions::LOG_MODEM_STATUS,array(
                                   "usuario"=>$usuario,
                                   "perfil"=>$rolNombre,
                                   "codCliente"=>$codCliente,
                                   "mac"=>$mac,
                                   "fabricante"=>$fabricante,
                                   "modelo"=>$modelo,
                                   "firmware"=>$firmware,
                                   "frecuenciaUp1"=>$frecuenciaUp1,
                                   "powerUp1"=>$powerUp1,
                                   "frecuenciaUp2"=>$frecuenciaUp2,
                                   "powerUp2"=>$powerUp2,
                                   "frecuenciaDown1"=>$frecuenciaDown1,
                                   "snrDown1"=>$snrDown1,
                                   "powerDown1"=>$powerDown1,
                                   "frecuenciaDown2"=>$frecuenciaDown2,
                                   "snrDown2"=>$snrDown2,
                                   "powerDown2"=>$powerDown2,
                                   "frecuenciaDown3"=>$frecuenciaDown3,
                                   "snrDown3"=>$snrDown3,
                                   "powerDown3"=>$powerDown3,
                                   "frecuenciaDown4"=>$frecuenciaDown4,
                                   "snrDown4"=>$snrDown4,
                                   "powerDown4"=>$powerDown4,
                                   "frecuenciaDown5"=>$frecuenciaDown5,
                                   "snrDown5"=>$snrDown5,
                                   "powerDown5"=>$powerDown5,
                                   "frecuenciaDown6"=>$frecuenciaDown6,
                                   "snrDown6" => $snrDown6,
                                   "powerDown6" => $powerDown6,
                                   "frecuenciaDown7" => $frecuenciaDown7,
                                   "snrDown7" => $snrDown7,
                                   "powerDown7" => $powerDown7,
                                   "frecuenciaDown8" => $frecuenciaDown8,
                                   "snrDown8" => $snrDown8,
                                   "powerDown8" => $powerDown8
                                   ));
         
        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.status',
                                     ["upstream"=>$status["Upstream"],"downstream"=>$status["Downstream"],
                                     "correct"=>$status["Correct"],"uncorrect"=>$status["UnCorrect"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );

        //return view('administrador.modulos.multiconsulta.cablemodem.status',["upstream"=>$status["Upstream"],"downstream"=>$status["Downstream"],"correct"=>$status["Correct"],"uncorrect"=>$status["UnCorrect"]]);
                    
    }


    public function dhcp(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $mac = $request->mac;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $dhcpCablemodem = new CablemodemDhcpFunctions;
        $logsFunctions = new LogsFunctions;

        if ($fabricante=="Askey") {
            if ($modelo=="TCG220-TdP") {
                $dhcp = $dhcpCablemodem->obtenerDhcpAskey3($codCliente,$ipaddress,$fabricante);
            } else {
                $dhcp = $dhcpCablemodem->obtenerDhcpAskey($codCliente,$ipaddress,$fabricante);
            }
        } elseif (substr($fabricante,0,3)=="Hit") {
            $dhcp = $dhcpCablemodem->obtenerDhcpHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $dhcp = $dhcpCablemodem->obtenerDhcpUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $dhcp = $dhcpCablemodem->obtenerDhcpSagem($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $dhcp = $dhcpCablemodem->obtenerDhcpCastlenet($codCliente,$ipaddress,$fabricante);
        }

        if($dhcp=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        //dd($dhcp);

        $cantEther = count($dhcp["Ethernet"]);
        $cantWifi = count($dhcp["Wifi"]);

        if($cantEther>0){

            for ($i=0; $i < $cantEther; $i++) {

                $dhcpHost = $dhcp["Ethernet"][$i]["host"];
                $dhcpInterface = $dhcp["Ethernet"][$i]["interface"];
                $dhcpMac = $dhcp["Ethernet"][$i]["mac"];
                $dhcpIp = $dhcp["Ethernet"][$i]["ipaddress"];
                $dhcpNivel = "";

                $logsFunctions->registroLog($logsFunctions::LOG_MODEM_DHCP,array(
                    "usuario"=>$usuario,
                    "perfil"=>$rolNombre,
                    "codCliente"=>$codCliente,
                    "macaddress"=>$mac,
                    "fabricante"=>$fabricante,
                    "modelo"=>$modelo,
                    "firmware"=>$firmware,
                    "dhcp_host"=>$dhcpHost,
                    "dhcp_interface"=>$dhcpInterface,
                    "dhcp_mac"=>$dhcpMac,
                    "dhcp_ip"=>$dhcpIp,
                    "dhcp_nivel"=>$dhcpNivel
                    ));
 
              

            }

        }

        if($cantWifi>0){

            for ($i=0; $i < $cantWifi; $i++) {

                $dhcpHost = $dhcp["Wifi"][$i]["host"];
                $dhcpInterface = $dhcp["Wifi"][$i]["interface"];
                $dhcpMac = $dhcp["Wifi"][$i]["mac"];
                $dhcpIp = $dhcp["Wifi"][$i]["ipaddress"];
                $dhcpNivel = $dhcp["Wifi"][$i]["nivel"];

                $logsFunctions->registroLog($logsFunctions::LOG_MODEM_DHCP,array(
                    "usuario"=>$usuario,
                    "perfil"=>$rolNombre,
                    "codCliente"=>$codCliente,
                    "macaddress"=>$mac,
                    "fabricante"=>$fabricante,
                    "modelo"=>$modelo,
                    "firmware"=>$firmware,
                    "dhcp_host"=>$dhcpHost,
                    "dhcp_interface"=>$dhcpInterface,
                    "dhcp_mac"=>$dhcpMac,
                    "dhcp_ip"=>$dhcpIp,
                    "dhcp_nivel"=>$dhcpNivel
                    ));

                
            }

            
        }

        //dd($cantWifi);

        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.dhcp',
                                     ["ethernet"=>$dhcp["Ethernet"],"wifi"=>$dhcp["Wifi"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );

        //return view('administrador.modulos.multiconsulta.cablemodem.dhcp',["ethernet"=>$dhcp["Ethernet"],"wifi"=>$dhcp["Wifi"]]);

    }


    public function wifivecino(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $wifivecinoCablemodem = new CablemodemWifiVecinoFunctions;

        if ($fabricante=="Askey") {
            $wifivecino = $wifivecinoCablemodem->obtenerWifiVecinoAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $wifivecino = $wifivecinoCablemodem->obtenerWifiVecinoHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $wifivecino = $wifivecinoCablemodem->obtenerWifiVecinoUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $wifivecino = $wifivecinoCablemodem->obtenerWifiVecinoSagem($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $wifivecino = $wifivecinoCablemodem->obtenerWifiVecinoCastlenet($codCliente,$ipaddress,$fabricante);
        }

        if($wifivecino=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.wifivecino',
                                     ["wifivecino"=>$wifivecino["WifiVecino"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );


        //return view('administrador.modulos.multiconsulta.cablemodem.wifivecino',["wifivecino"=>$wifivecino["WifiVecino"]]);

    }


    public function wifi(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");
        
        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $wifiCablemodem = new CablemodemWifiFunctions;

        if ($fabricante=="Askey") {
            $wifi = $wifiCablemodem->obtenerWifiAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $wifi = $wifiCablemodem->obtenerWifiHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $wifi = $wifiCablemodem->obtenerWifiUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $wifi = $wifiCablemodem->obtenerWifiSagem($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $wifi = $wifiCablemodem->obtenerWifiCastlenet($codCliente,$ipaddress,$fabricante);
        }

        if($wifi=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        
        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.wifi',
                                     ["wifi1"=>$wifi["Wifi"],"wifi2"=>$wifi["Wifi5G"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       ); 

       // return view('administrador.modulos.multiconsulta.cablemodem.wifi',);

    }




    public function updatewifi(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $interface1_original = $request->interface1_original;
        $channel1_original = $request->channel1_original;
        $bandwidth1_original = $request->bandwidth1_original;
        $power1_original = $request->power1_original;
        $seguridad1_original = $request->seguridad1_original;
        $pass1_original = $request->pass1_original;

        //valores de formulario
        $ssid = $request->ssid1;
        $ssid1 = str_replace(' ','',$ssid);

        $interface1 = $request->interface1;
        $channel1 = $request->channel1;
        $bandwidth1 = $request->bandwidth1;
        $power1 = $request->power1;
        $seguridad1 = $request->seguridad1;
        $pass1 = $request->pass1;


        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        if ($fabricante=="Askey") {
            $wifi = $wifiCablemodem->updateWifiAskey($codCliente,$ipaddress,$fabricante,$ssid1,$interface1,$channel1,$bandwidth1,$power1,$seguridad1,$pass1);
        }

        //--------------------------------------------------------------------------//
        if ($interface1_original=="1") {
            $interface = "‎802.11 b/g";
        }elseif ($interface1_original=="2") {
            $interface = "802.11 b/g/n";
        }elseif ($interface1_original=="3") {
            $interface = "802.11 n only";
        }

        if ($interface1=="1") {
            $interface_nuevo = "‎802.11 b/g";
        }elseif ($interface1=="2") {
            $interface_nuevo = "802.11 b/g/n";
        }elseif ($interface1=="3") {
            $interface_nuevo = "802.11 n only";
        }
        //----------------------------//

        if ($channel1_original=="0") {
            $channel = "Auto";
        }

        if ($channel1=="0") {
            $channel_nuevo = "Auto";
        }
        //----------------------------//
        if ($bandwidth1_original=="20") {
            $bandwidth = "‎20 MHz";
        }elseif ($bandwidth1_original=="40") {
            $bandwidth = "20/40 MHz";
        }

        if ($bandwidth1=="20") {
            $bandwidth_nuevo = "‎20 MHz";
        }elseif ($bandwidth1=="40") {
            $bandwidth_nuevo = "20/40 MHz";
        }
        //----------------------------//
        if ($power1_original=="100") {
            $power = "‎100%";
        }elseif ($power1_original=="75") {
            $power = "75%";
        }elseif ($power1_original=="50") {
            $power = "50%";
        }elseif ($power1_original=="25") {
            $power = "25%";
        }

        if ($power1=="100") {
            $power_nuevo = "‎100%";
        }elseif ($power1=="75") {
            $power_nuevo = "75%";
        }elseif ($power1=="50") {
            $power_nuevo = "50%";
        }elseif ($power1=="25") {
            $power_nuevo = "25%";
        }
        //----------------------------//
        if ($seguridad1_original=="off") {
            $secutiry1 = "OFF";
        }elseif ($seguridad1_original=="wep64") {
            $secutiry1 = "WEP-64";
        }elseif ($seguridad1_original=="wpa-tkip") {
            $secutiry1 = "WPA/TKIP";
        }elseif ($seguridad1_original=="wpa-tkip-aes") {
            $secutiry1 = "WPA/TKIP+AES";
        }elseif ($seguridad1_original=="wpa2-aes") {
            $secutiry1 = "WPA2/AES";
        }elseif ($seguridad1_original=="wpa2-tkip-aes") {
            $secutiry1 = "WPA2/TKIP+AES";
        }elseif ($seguridad1_original=="wpa%2Bwpa2") {
            $secutiry1 = "WPA+WPA2/TKIP+AES";
        }

        if ($seguridad1=="off") {
            $secutiry1_nuevo = "OFF";
        }elseif ($seguridad1=="wep64") {
            $secutiry1_nuevo = "WEP-64";
        }elseif ($seguridad1=="wpa-tkip") {
            $secutiry1_nuevo = "WPA/TKIP";
        }elseif ($seguridad1=="wpa-tkip-aes") {
            $secutiry1_nuevo = "WPA/TKIP+AES";
        }elseif ($seguridad1=="wpa2-aes") {
            $secutiry1_nuevo = "WPA2/AES";
        }elseif ($seguridad1=="wpa2-tkip-aes") {
            $secutiry1_nuevo = "WPA2/TKIP+AES";
        }elseif ($seguridad1=="wpa%2Bwpa2") {
            $secutiry1_nuevo = "WPA+WPA2/TKIP+AES";
        }
        //--------------------------------------------------------------------------//


        $ssid = $ssid1_original;
        $security2 = " ";
        $password = $pass1_original;
        $ssid5G = " ";
        $interface5G = " ";
        $channel5G = " ";
        $bandwidth5G = "";
        $power5G = " ";
        $security5G = " ";
        $password5G = " ";
        $ssid_nuevo = $ssid1;
        $security2_nuevo = " ";
        $password_nuevo = $pass1;
        $ssid5G_nuevo = " ";
        $interface5G_nuevo = " ";
        $channel5G_nuevo = " ";
        $bandwidth5G_nuevo = "";
        $power5G_nuevo = " ";
        $security5G_nuevo = " ";
        $password5G_nuevo = " ";

        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
            "usuario" => $usuario,
            "perfil" => $rolNombre,
            "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
            "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
            "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
            "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
            "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
            "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
            "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
            ));

      


        return $this->mensajeSuccess($wifi);

    }



    public function updatewifi5G(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $interface1_original = $request->interface1_original;
        $channel1_original = $request->channel1_original;
        $bandwidth1_original = $request->bandwidth1_original;
        $power1_original = $request->power1_original;
        $seguridad1_original = $request->seguridad1_original;
        $pass1_original = $request->pass1_original;

        $ssid2_original = $request->ssid2_original;
        $interface2_original = $request->interface2_original;
        $channel2_original = $request->channel2_original;
        $bandwidth2_original = $request->bandwidth2_original;
        $power2_original = $request->power2_original;
        $seguridad2_original = $request->seguridad2_original;
        $pass2_original = $request->pass2_original;

        //valores de formulario
        $ssid = $request->ssid1;
        $ssid1 = str_replace(' ','',$ssid);
        $interface1 = $request->interface1;
        $channel1 = $request->channel1;
        $bandwidth1 = $request->bandwidth1;
        $power1 = $request->power1;
        $seguridad1 = $request->seguridad1;
        $pass1 = $request->pass1;

        $ssid_2 = $request->ssid2;
        $ssid2 = str_replace(' ','',$ssid_2);
        $interface2 = $request->interface2;
        $channel2 = $request->channel2;
        $bandwidth2 = $request->bandwidth2;
        $power2 = $request->power2;
        $seguridad2 = $request->seguridad2;
        $pass2 = $request->pass2;

        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        if ($fabricante=="Askey") {
            $wifi = $wifiCablemodem->updateWifiAskey5G($codCliente,$ipaddress,$fabricante,$ssid1,$interface1,$channel1,$bandwidth1,$power1,$seguridad1,$pass1,$ssid2,$interface2,$channel2,$bandwidth2,$power2,$seguridad2,$pass2);
        }


        //--------------------------------------------------------------------------//
        if ($interface1_original=="1") {
            $interface = "‎802.11 b/g";
        }elseif ($interface1_original=="2") {
            $interface = "802.11 b/g/n";
        }elseif ($interface1_original=="3") {
            $interface = "802.11 n only";
        }

        if ($interface1=="1") {
            $interface_nuevo = "‎802.11 b/g";
        }elseif ($interface1=="2") {
            $interface_nuevo = "802.11 b/g/n";
        }elseif ($interface1=="3") {
            $interface_nuevo = "802.11 n only";
        }
        //----------------------------//

        if ($channel1_original=="0") {
            $channel = "Auto";
        }else{
            $channel = $channel1_original;
        }

        if ($channel1=="0") {
            $channel_nuevo = "Auto";
        }else{
            $channel_nuevo = $channel1;
        }
        //----------------------------//
        if ($bandwidth1_original=="20") {
            $bandwidth = "‎20 MHz";
        }elseif ($bandwidth1_original=="40") {
            $bandwidth = "20/40 MHz";
        }

        if ($bandwidth1=="20") {
            $bandwidth_nuevo = "‎20 MHz";
        }elseif ($bandwidth1=="40") {
            $bandwidth_nuevo = "20/40 MHz";
        }
        //----------------------------//
        if ($power1_original=="100") {
            $power = "‎100%";
        }elseif ($power1_original=="75") {
            $power = "75%";
        }elseif ($power1_original=="50") {
            $power = "50%";
        }elseif ($power1_original=="25") {
            $power = "25%";
        }

        if ($power1=="100") {
            $power_nuevo = "‎100%";
        }elseif ($power1=="75") {
            $power_nuevo = "75%";
        }elseif ($power1=="50") {
            $power_nuevo = "50%";
        }elseif ($power1=="25") {
            $power_nuevo = "25%";
        }
        //----------------------------//
        if ($seguridad1_original=="off") {
            $secutiry1 = "OFF";
        }elseif ($seguridad1_original=="wep64") {
            $secutiry1 = "WEP-64";
        }elseif ($seguridad1_original=="wpa-tkip") {
            $secutiry1 = "WPA/TKIP";
        }elseif ($seguridad1_original=="wpa-tkip-aes") {
            $secutiry1 = "WPA/TKIP+AES";
        }elseif ($seguridad1_original=="wpa2-aes") {
            $secutiry1 = "WPA2/AES";
        }elseif ($seguridad1_original=="wpa2-tkip-aes") {
            $secutiry1 = "WPA2/TKIP+AES";
        }elseif ($seguridad1_original=="wpa%2Bwpa2") {
            $secutiry1 = "WPA+WPA2/TKIP+AES";
        }

        if ($seguridad1=="off") {
            $secutiry1_nuevo = "OFF";
        }elseif ($seguridad1=="wep64") {
            $secutiry1_nuevo = "WEP-64";
        }elseif ($seguridad1=="wpa-tkip") {
            $secutiry1_nuevo = "WPA/TKIP";
        }elseif ($seguridad1=="wpa-tkip-aes") {
            $secutiry1_nuevo = "WPA/TKIP+AES";
        }elseif ($seguridad1=="wpa2-aes") {
            $secutiry1_nuevo = "WPA2/AES";
        }elseif ($seguridad1=="wpa2-tkip-aes") {
            $secutiry1_nuevo = "WPA2/TKIP+AES";
        }elseif ($seguridad1=="wpa%2Bwpa2") {
            $secutiry1_nuevo = "WPA+WPA2/TKIP+AES";
        }
        //-----------------------------------------------//
        if ($interface2_original=="6") {
            $interface5G = "‎802.11 a";
        }elseif ($interface1_original=="7") {
            $interface5G = "802.11 a/n";
        }elseif ($interface1_original=="8") {
            $interface5G = "802.11 a/n/ac";
        }

        if ($interface2=="6") {
            $interface5G_nuevo = "‎802.11 a";
        }elseif ($interface2=="7") {
            $interface5G_nuevo = "802.11 a/n";
        }elseif ($interface2=="8") {
            $interface5G_nuevo = "802.11 a/n/ac";
        }
        //----------------------------//
        if ($channel2_original=="0") {
            $channel5G = "Auto";
        }else{
            $channel5G = $channel2_original;
        }

        if ($channel2=="0") {
            $channel5G_nuevo = "Auto";
        }else{
            $channel5G_nuevo = $channel2;
        }
        //----------------------------//
        if ($bandwidth2_original=="20") {
            $bandwidth5G = "‎20 MHz";
        }elseif ($bandwidth2_original=="40") {
            $bandwidth5G = "20/40 MHz";
        }elseif ($bandwidth2_original=="80") {
            $bandwidth5G = "20/40/80 MHz";
        }

        if ($bandwidth2=="20") {
            $bandwidth5G_nuevo = "‎20 MHz";
        }elseif ($bandwidth2=="40") {
            $bandwidth5G_nuevo = "20/40 MHz";
        }elseif ($bandwidth2=="80") {
            $bandwidth5G_nuevo = "20/40/80 MHz";
        }
        //----------------------------//
        if ($seguridad2_original=="off") {
            $security5G = "OFF";
        }elseif($seguridad2_original=="wpa2-aes") {
            $security5G = "WPA2/AES";
        }

        if ($seguridad2=="off") {
            $security5G_nuevo = "OFF";
        }elseif($seguridad2=="wpa2-aes") {
            $security5G_nuevo = "WPA2/AES";
        }
        //--------------------------------------------------------------------------//


        $ssid = $ssid1_original;
        $security2 = " ";
        $password = $pass1_original;

        $ssid5G = $ssid2_original;
        $power5G = $power2_original;
        $password5G = $pass2_original;

        $ssid_nuevo = $ssid1;
        $security2_nuevo = " ";
        $password_nuevo = $pass1;

        $ssid5G_nuevo = $ssid2;
        $power5G_nuevo = $power2;
        $password5G_nuevo = $pass2;


       
        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
                        "usuario" => $usuario,
                        "perfil" => $rolNombre,
                        "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
                        "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
                        "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
                        "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
                        "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
                        "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
                        "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
                        ));
                    


        return $this->mensajeSuccess($wifi);




    }


    public function updatewifiHitron(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $interface1_original = $request->interface1_original;
        $channel1_original = $request->channel1_original;
        $bandwidth1_original = $request->bandwidth1_original;
        $seguridad1_original = $request->seguridad1_original;
        $seguridad2_original = $request->seguridad2_original;
        $pass1_original = $request->pass1_original;

        //valores de formulario
        $ssid = $request->ssid1;
        $ssid1 = str_replace(' ','',$ssid);
        $ssid2 = $request->ssid2;
        $ssid3 = $request->ssid3;
        $ssid4 = $request->ssid4;
        $ssid5 = $request->ssid5;
        $ssid6 = $request->ssid6;
        $ssid7 = $request->ssid7;
        $ssid8 = $request->ssid8;
        $interface1 = $request->interface1;
        $channel1 = $request->channel;
        $bandwidth1 = $request->bandwidth;
        $seguridad1 = $request->seguridad1;
        $seguridad2 = $request->seguridad2;
        $pass = $request->pass;

        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        $wifi = $wifiCablemodem->updateWifiHitron($codCliente,$ipaddress,$fabricante,$ssid1,$ssid2,
                                $ssid3,$ssid4,$ssid5,$ssid6,$ssid7,$ssid8,$interface1,
                                $channel1,$bandwidth1,$seguridad1,$seguridad2,$pass);

        

        if ($interface1_original=="0") {
            $interface = "‎‎11B/G Mixed";
        }elseif ($interface1_original=="1") {
            $interface = "11B Only";
        }elseif ($interface1_original=="4") {
            $interface = "11G Only";
        }elseif ($interface1_original=="6") {
            $interface = "11N Only";
        }elseif ($interface1_original=="7") {
            $interface = "11G/N Mixed";
        }elseif ($interface1_original=="9") {
            $interface = "11B/G/N Mixed";
        }

        if ($interface1=="0") {
            $interface_nuevo = "‎‎11B/G Mixed";
        }elseif ($interface1=="1") {
            $interface_nuevo = "11B Only";
        }elseif ($interface1=="4") {
            $interface_nuevo = "11G Only";
        }elseif ($interface1=="6") {
            $interface_nuevo = "11N Only";
        }elseif ($interface1=="7") {
            $interface_nuevo = "11G/N Mixed";
        }elseif ($interface1=="9") {
            $interface_nuevo = "11B/G/N Mixed";
        }

        if ($channel1_original=="0") {
            $channel="Auto";
        }else {
            $channel=$channel1_original;
        }

        if ($channel1=="0") {
            $channel_nuevo="Auto";
        }else {
            $channel_nuevo=$channel1;
        }

        if ($bandwidth1_original=="0") {
            $bandwidth = "‎‎20 MHz";
        }elseif ($bandwidth1_original=="1") {
            $bandwidth = "‎‎20/40 MHz";
        }

        if ($bandwidth1=="0") {
            $bandwidth_nuevo = "‎‎20 MHz";
        }elseif ($bandwidth1=="1") {
            $bandwidth_nuevo = "‎‎20/40 MHz";
        }

        if ($seguridad1_original=="4") {
            $secutiry1 = "‎‎WPA-PSK";
        }elseif ($seguridad1_original=="5") {
            $secutiry1 = "‎‎WPA2-PSK";
        }elseif ($seguridad1_original=="6") {
            $secutiry1 = "‎‎Auto (WPA-PSK or WPA2-PSK)";
        }

        if ($seguridad1=="4") {
            $secutiry1_nuevo = "‎‎WPA-PSK";
        }elseif ($seguridad1=="5") {
            $secutiry1_nuevo = "‎‎WPA2-PSK";
        }elseif ($seguridad1=="6") {
            $secutiry1_nuevo = "‎‎Auto (WPA-PSK or WPA2-PSK)";
        }


        if ($seguridad2_original=="2") {
            $security2 = "TKIP";
        }elseif ($seguridad2_original=="3") {
            $security2 = "AES";
        }elseif ($seguridad2_original=="4") {
            $security2 = "‎‎TKIP and AES";
        }

        if ($seguridad2=="2") {
            $security2_nuevo = "TKIP";
        }elseif ($seguridad2=="3") {
            $security2_nuevo = "AES";
        }elseif ($seguridad2=="4") {
            $security2_nuevo = "TKIP and AES";
        }


        $ssid = $ssid1_original;
        $power = " ";
        $password = $pass1_original;
        $ssid5G = " ";
        $interface5G = " ";
        $channel5G = " ";
        $bandwidth5G = " ";
        $power5G = " ";
        $security5G = " ";
        $password5G = " ";

        $ssid_nuevo = $ssid1;
        $power_nuevo = " ";
        $password_nuevo = $pass;
        $ssid5G_nuevo = " ";
        $interface5G_nuevo = " ";
        $channel5G_nuevo = " ";
        $bandwidth5G_nuevo = " ";
        $power5G_nuevo = " ";
        $security5G_nuevo = " ";
        $password5G_nuevo = " ";


        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
            "usuario" => $usuario,
            "perfil" => $rolNombre,
            "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
            "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
            "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
            "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
            "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
            "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
            "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
        ));

       


        return $this->mensajeSuccess($wifi);

    }

    public function updatewifiUbee(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $interface1_original = $request->interface1_original;
        $channel1_original = $request->channel1_original;
        $seguridad1_original = $request->seguridad1_original;
        $seguridad2_original = $request->seguridad2_original;
        $seguridad3_original = $request->seguridad3_original;
        $seguridad4_original = $request->seguridad4_original;
        $seguridad5_original = $request->seguridad5_original;
        $pass1_original = $request->pass1_original;

        //valores de formulario
        $ssid = $request->ssid;
        $ssid1 = str_replace(' ','',$ssid);
        $interface1 = $request->interface1;
        $channel1 = $request->channel;
        $seguridad1 = $request->seguridad1;
        $seguridad2 = $request->seguridad2;
        $seguridad3 = $request->seguridad3;
        $seguridad4 = $request->seguridad4;
        $seguridad5 = $request->seguridad5;
        $pass = $request->pass;

        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        $wifi = $wifiCablemodem->updateWifiUbee($codCliente,$ipaddress,$fabricante,$ssid1,$interface1,
                                $channel1,$seguridad1,$seguridad2,$seguridad3,$seguridad4,$seguridad5,$pass);



        if ($interface1_original=="0") {
            $interface = "‎‎bgn-mode Mixed";
        }elseif ($interface1_original=="1") {
            $interface = "n-mode Only";
        }elseif ($interface1_original=="2") {
            $interface = "‎bg-mode Mixed";
        }elseif ($interface1_original=="3") {
            $interface = "g-mode Only";
        }elseif ($interface1_original=="5") {
            $interface = "‎802.11b Only";
        }

        if ($interface1=="0") {
            $interface_nuevo = "‎‎bgn-mode Mixed";
        }elseif ($interface1=="1") {
            $interface_nuevo = "n-mode Only";
        }elseif ($interface1=="2") {
            $interface_nuevo = "‎bg-mode Mixed";
        }elseif ($interface1=="3") {
            $interface_nuevo = "g-mode Only";
        }elseif ($interface1=="5") {
            $interface_nuevo = "‎802.11b Only";
        }


        if ($channel1_original=="0") {
            $channel="Auto";
        }else {
            $channel=$channel1_original;
        }

        if ($channel1=="0") {
            $channel_nuevo="Auto";
        }else {
            $channel_nuevo=$channel1;
        }

        //------------------------------------------------------//
        if ($seguridad1_original=="1") {
            $secutiry1 = "WPA";
        }elseif ($seguridad2_original=="1") {
            $secutiry1 = "‎‎WPA-PSK";
        }elseif ($seguridad1_original=="0") {
            $secutiry1 = "‎‎Disabled";
        }

        if ($seguridad1=="1") {
            $secutiry1_nuevo = "‎‎WPA";
        }elseif ($seguridad2=="1") {
            $secutiry1_nuevo = "‎‎WPA-PSK";
        }elseif ($seguridad2=="0") {
            $secutiry1_nuevo = "‎‎‎‎Disabled";
        }


        if ($seguridad3_original=="1") {
            $security2 = "‎‎WPA2";
        }elseif ($seguridad4_original=="1") {
            $security2 = "WPA2-PSK";
        }elseif ($seguridad4_original=="0") {
            $security2 = "‎‎‎‎Disabled";
        }

        if ($seguridad3=="1") {
            $security2_nuevo = "‎‎WPA2";
        }elseif ($seguridad4=="1") {
            $security2_nuevo = "WPA2-PSK";
        }elseif ($seguridad4=="0") {
            $security2_nuevo = "‎‎‎‎Disabled";
        }

        //-----------------------------------------------------//
        
        $ssid = $ssid1_original;
        $bandwidth = "";
        $power = "";
        $password = $pass1_original;
        $ssid5G = "";
        $interface5G = "";
        $channel5G = "";
        $bandwidth5G = "";
        $power5G = "";
        $security5G = "";
        $password5G = "";

        $ssid_nuevo = $ssid1;
        $bandwidth_nuevo = "";
        $power_nuevo = "";
        $password_nuevo = $pass;
        $ssid5G_nuevo = "";
        $interface5G_nuevo = "";
        $channel5G_nuevo = "";
        $bandwidth5G_nuevo = "";
        $power5G_nuevo = "";
        $security5G_nuevo = "";
        $password5G_nuevo = "";

        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
            "usuario" => $usuario,
            "perfil" => $rolNombre,
            "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
            "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
            "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
            "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
            "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
            "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
            "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
        ));


        
        
        return $this->mensajeSuccess($wifi);

    }


    public function updatewifiSagem(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $channel1_original = $request->channel1_original;
        $bandwidth1_original = $request->bandwidth1_original;
        $power1_original = $request->power1_original;
        $seguridad1_original = $request->seguridad1_original;
        $seguridad2_original = $request->seguridad2_original;
        $seguridad3_original = $request->seguridad3_original;
        $seguridad4_original = $request->seguridad4_original;
        $seguridad5_original = $request->seguridad5_original;
        $pass1_original = $request->pass1_original;

        //valores de formulario
        $ssid = $request->ssid;
        $ssid1 = str_replace(' ','',$ssid);
        $channel1 = $request->channel;
        $bandwidth1 = $request->bandwidth;
        $power1 = $request->power;
        $seguridad1 = $request->seguridad1;
        $seguridad2 = $request->seguridad2;
        $seguridad3 = $request->seguridad3;
        $seguridad4 = $request->seguridad4;
        $seguridad5 = $request->seguridad5;
        $pass = $request->pass;

        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        $wifi = $wifiCablemodem->updateWifiSagem($codCliente,$ipaddress,$fabricante,$ssid1,$channel1,
                $bandwidth1,$power1,$seguridad1,$seguridad2,$seguridad3,$seguridad4,$seguridad5,$pass);



        if ($channel1_original=="0") {
            $channel="Auto";
        }else {
            $channel=$channel1_original;
        }

        if ($channel1=="0") {
            $channel_nuevo="Auto";
        }else {
            $channel_nuevo=$channel1;
        }


        if ($power1_original=="100") {
            $power = "‎100%";
        }elseif ($power1_original=="75") {
            $power = "75%";
        }elseif ($power1_original=="50") {
            $power = "50%";
        }elseif ($power1_original=="25") {
            $power = "25%";
        }

        if ($power1=="100") {
            $power_nuevo = "‎100%";
        }elseif ($power1=="75") {
            $power_nuevo = "75%";
        }elseif ($power1=="50") {
            $power_nuevo = "50%";
        }elseif ($power1=="25") {
            $power_nuevo = "25%";
        }

        //------------------------------------------------------//
        if ($seguridad1_original=="1") {
            $secutiry1 = "WPA";
        }elseif ($seguridad2_original=="1") {
            $secutiry1 = "‎‎WPA-PSK";
        }elseif ($seguridad2_original=="0") {
            $secutiry1 = "‎‎Disabled";
        }

        if ($seguridad1=="1") {
            $secutiry1_nuevo = "‎‎WPA";
        }elseif ($seguridad2=="1") {
            $secutiry1_nuevo = "‎‎WPA-PSK";
        }elseif ($seguridad2=="0") {
            $secutiry1_nuevo = "‎‎Disabled";
        }


        if ($seguridad3_original=="1") {
            $security2 = "‎‎WPA2";
        }elseif ($seguridad4_original=="1") {
            $security2 = "WPA2-PSK";
        }elseif ($seguridad4_original=="0") {
            $security2 = "Disabled";
        }

        if ($seguridad3=="1") {
            $security2_nuevo = "‎‎WPA2";
        }elseif ($seguridad4=="1") {
            $security2_nuevo = "WPA2-PSK";
        }elseif ($seguridad4=="0") {
            $security2_nuevo = "Disabled";
        }

        //-----------------------------------------------------//




        $ssid = $ssid1_original;
        $interface = "";
        $bandwidth = $bandwidth1_original;
        $password = $pass1_original;
        $ssid5G = "";
        $interface5G = "";
        $channel5G = "";
        $bandwidth5G = "";
        $power5G = "";
        $security5G = "";
        $password5G = "";

        $ssid_nuevo = $ssid1;
        $interface_nuevo = "";
        $bandwidth_nuevo = $bandwidth1;
        $password_nuevo = $pass;
        $ssid5G_nuevo = "";
        $interface5G_nuevo = "";
        $channel5G_nuevo = "";
        $bandwidth5G_nuevo = "";
        $power5G_nuevo = "";
        $security5G_nuevo = "";
        $password5G_nuevo = "";

        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
            "usuario" => $usuario,
            "perfil" => $rolNombre,
            "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
            "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
            "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
            "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
            "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
            "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
            "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
        ));


      
        return $this->mensajeSuccess($wifi);

    }



    public function updatewifiCastlenet(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;
        $mac = $request->mac;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //Valores originales
        $ssid1_original = $request->ssid1_original;
        $seguridad1_original = $request->seguridad1_original;
        $pass1_original = $request->pass1_original;

        //valores de formulario
        $ssid = $request->ssid;
        $ssid1 = str_replace(' ','',$ssid);
        $seguridad = $request->seguridad;
        $pass = $request->pass;

        $wifiCablemodem = new CablemodemUpdateWifiFunctions;
        $logsFunctions = new LogsFunctions;

        $wifi = $wifiCablemodem->updateWifiCastlenet($codCliente,$ipaddress,$fabricante,$ssid1,
                                            $seguridad,$pass);


        if ($seguridad1_original=="2") {
            $security2 = "AES";
        }elseif ($seguridad1_original=="3") {
            $security2 = "TKIP+AES";
        }

        if ($seguridad=="2") {
            $security2_nuevo = "AES";
        }elseif ($seguridad=="3") {
            $security2_nuevo = "TKIP+AES";
        }


        $ssid = $ssid1_original;
        $interface = "";
        $channel = "";
        $bandwidth = "";
        $power = "";
        $secutiry1 = "";
        $password = $pass1_original;
        $ssid5G = "";
        $interface5G = "";
        $channel5G = "";
        $bandwidth5G = "";
        $power5G = "";
        $security5G = "";
        $password5G = "";
        $ssid_nuevo = $ssid1;
        $interface_nuevo = "";
        $channel_nuevo = "";
        $bandwidth_nuevo = "";
        $power_nuevo = "";
        $secutiry1_nuevo = "";
        $password_nuevo = $pass;
        $ssid5G_nuevo = "";
        $interface5G_nuevo = "";
        $channel5G_nuevo = "";
        $bandwidth5G_nuevo = "";
        $power5G_nuevo = "";
        $security5G_nuevo = "";
        $password5G_nuevo = "";


        $logsFunctions->registroLog($logsFunctions::LOG_MODEM_WIFI,array(
            "usuario" => $usuario,
            "perfil" => $rolNombre,
            "codCliente" => $codCliente,"mac" => $mac,"fabricante" => $fabricante,"modelo" => $modelo,
            "firmware" => $firmware,"ssid" => $ssid,"interface" => $interface,"channel" => $channel,"bandwidth" => $bandwidth,"power" => $power,"secutiry1" => $secutiry1,"security2" => $security2,
            "password" => $password,"ssid5G" => $ssid5G,"interface5G" => $interface5G,"channel5G" => $channel5G,"bandwidth5G" => $bandwidth5G,"power5G" => $power5G,"security5G" => $security5G,
            "password5G" => $password5G,"ssid_nuevo" => $ssid_nuevo,"interface_nuevo" => $interface_nuevo,"channel_nuevo" => $channel_nuevo,"bandwidth_nuevo" => $bandwidth_nuevo,
            "power_nuevo" => $power_nuevo,"secutiry1_nuevo" => $secutiry1_nuevo,"security2_nuevo" => $security2_nuevo,"password_nuevo" => $password_nuevo,"ssid5G_nuevo" => $ssid5G_nuevo,
            "interface5G_nuevo" => $interface5G_nuevo,"channel5G_nuevo" => $channel5G_nuevo,"bandwidth5G_nuevo" => $bandwidth5G_nuevo,"power5G_nuevo" => $power5G_nuevo,
            "security5G_nuevo" => $security5G_nuevo,"password5G_nuevo" => $password5G_nuevo
        ));

        

        
        return $this->mensajeSuccess($wifi);


    }


    public function upnp(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $upnpCablemodem = new CablemodemUpnpFunctions;

        if ($fabricante=="Askey") {
            $upnp = $upnpCablemodem->obtenerUpnpAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $upnp = $upnpCablemodem->obtenerUpnpHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $upnp = $upnpCablemodem->obtenerUpnpUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $upnp = $upnpCablemodem->obtenerUpnpCastlenet($codCliente,$ipaddress,$fabricante);
        }

        if($upnp=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.upnp',
                                     ["upnp"=>$upnp["Upnp"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );

        //return view('administrador.modulos.multiconsulta.cablemodem.upnp',["upnp"=>$upnp["Upnp"]]);

    }



    public function updateUpnp(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $identi = $request->identi;
        $respuesta = $request->respuesta;
        $canal = $request->canal;
        $activacion = $request->activacion;

        $upnpCablemodem = new CablemodemUpdateUpnpFunctions;

        if ($fabricante=="Askey") {
            $upnp = $upnpCablemodem->updateUpnpAskey($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $upnp = $upnpCablemodem->updateUpnpHitron($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        } elseif ($fabricante=="Ubee") {
            $upnp = $upnpCablemodem->updateUpnpUbee($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $upnp = $upnpCablemodem->updateUpnpCastlenet($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        }

        return $this->mensajeSuccess($upnp);

    }



    public function dmz(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $dmzCablemodem = new CablemodemDmzFunctions;

        if ($fabricante=="Askey") {
            $dmz = $dmzCablemodem->obtenerDmzAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $dmz = $dmzCablemodem->obtenerDmzHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $dmz = $dmzCablemodem->obtenerDmzUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $dmz = $dmzCablemodem->obtenerDmzCastlenet($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $dmz = "SAGEM";
        }

        if($dmz=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        if($dmz=="SAGEM"){
            return $this->errorMessage("No se encuentra disponible esta funcionalidad.",500);
        }

        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.dmz',
                                     ["dmz"=>$dmz["Dmz"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );

        //return view('administrador.modulos.multiconsulta.cablemodem.dmz',["dmz"=>$dmz["Dmz"]]);

    }


    public function updateDmz(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $wanBlock = $request->wan;
        $ipDmz = $request->ipDmz;
        $activacion = $request->activacion;

        $dmzCablemodem = new CablemodemUpdateDmzFunctions;

        if ($fabricante=="Askey") {
            $dmz = $dmzCablemodem->updateDmzAskey($codCliente,$ipaddress,$fabricante,$wanBlock,$ipDmz,$activacion);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $upnp = $upnpCablemodem->updateUpnpHitron($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        } elseif ($fabricante=="Ubee") {
            $upnp = $upnpCablemodem->updateUpnpUbee($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $upnp = $upnpCablemodem->updateUpnpCastlenet($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion);
        }
        

        return $this->mensajeSuccess($dmz);

    }


    public function updateDmzHitron(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $id = $request->id;
        $rpt = $request->rpt;
        $upnp = $request->upnp;
        $wan = $request->wan;

        $ipDmz = $request->ipDmz;
        $ipValor1 = $request->ipValor1;
        $ipValor2 = $request->ipValor2;
        $ipValor3 = $request->ipValor3;
        $ipValor4 = $request->ipValor4;
        $activacion = $request->activacion;

        $dmzCablemodem = new CablemodemUpdateDmzFunctions;

        $dmz = $dmzCablemodem->updateDmzHitron($codCliente,$ipaddress,$fabricante,$id,$rpt,$upnp,$wan,$ipDmz,$ipValor1,$ipValor2,$ipValor3,$ipValor4,$activacion);


    }

    public function updateDmzUbee(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario

        $ipDmz = $request->ipDmz;
        $activacion = $request->activacion;

        $dmzCablemodem = new CablemodemUpdateDmzFunctions;

        if ($fabricante=="Ubee") {
            $dmz = $dmzCablemodem->updateDmzUbee($codCliente,$ipaddress,$fabricante,$ipDmz,$activacion);
        }elseif (substr($fabricante,0,9)=="CastleNet") {
            $dmz = $dmzCablemodem->updateDmzCastlenet($codCliente,$ipaddress,$fabricante,$ipDmz,$activacion);
        }

        return $this->mensajeSuccess($dmz);

    }



    public function diagnostico(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $ipPing = $request->ipPing;

        //dd($request->all());

        $diagnosticoCablemodem = new CablemodemDiagnosticoFunctions;

        if ($fabricante=="Askey") {
            $diagnostico = $diagnosticoCablemodem->obtenerDiagnosticoAskey($codCliente,$ipaddress,$fabricante,$ipPing);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $diagnostico = $diagnosticoCablemodem->obtenerDiagnosticoHitron($codCliente,$ipaddress,$fabricante,$ipPing);
        } elseif ($fabricante=="Ubee") {
            $diagnostico = $diagnosticoCablemodem->obtenerDiagnosticoUbee($codCliente,$ipaddress,$fabricante,$ipPing);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $diagnostico = $diagnosticoCablemodem->obtenerDiagnosticoSagem($codCliente,$ipaddress,$fabricante,$ipPing);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $diagnostico = $diagnosticoCablemodem->obtenerDiagnosticoCastlenet($codCliente,$ipaddress,$fabricante,$ipPing);
        }


        //dd($diagnostico["Resultado"]);

        

        return $this->resultData(
            array( 
                 'html' => json_encode(view(
                                     'administrador.modulos.multiconsulta.cablemodem.diagnostico',
                                     ["resultado"=>$diagnostico["Resultado"]]
                                     )->render(),JSON_UNESCAPED_UNICODE),
            )
       );

        //return $this->mensajeSuccess($diagnostico);


    }



    public function updateReset(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $codigoServicio = $request->codigoservicio;
        $codigoProducto = $request->codigoproducto;
        $codigoVenta = $request->codigoventa;

        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $reset = $request->reset;

        $resetCablemodem = new CablemodemResetFunctions;
        $intrawayPeticion = new IntrawayFunctions;

        if ($fabricante=="Askey") {
            if($reset=="reset1"){
                $respReset = $intrawayPeticion->resetOnCM($codCliente,$codigoServicio,$codigoProducto,$codigoVenta);
            }else{
                $respReset = $resetCablemodem->resetAskey($codCliente,$ipaddress,$fabricante,$reset);
            }
        } elseif (substr($fabricante,0,3)=="Hit") {
            $respReset = $resetCablemodem->resetHitron($codCliente,$ipaddress,$fabricante,$reset);
        } elseif ($fabricante=="Ubee") {
            $respReset = $resetCablemodem->resetUbee($codCliente,$ipaddress,$fabricante,$reset);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $respReset = $resetCablemodem->resetSagem($codCliente,$ipaddress,$fabricante,$reset);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $respReset = $resetCablemodem->resetCastlenet($codCliente,$ipaddress,$fabricante,$reset);
        }

        if ($respReset == "error") {
            return $this->errorMessage("Ocurrio un error..."."Si no reinicia por favor pedir al cliente que desconecte el modem y lo vuelva a conectar",500);
        }

        return $this->mensajeSuccess($respReset);

    }




    public function maping(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        $mapingCablemodem = new CablemodemMapingFunctions;

        if ($fabricante=="Askey") {
            $maping = $mapingCablemodem->obtenerMapingAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $maping = $mapingCablemodem->obtenerMapingHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $maping = $mapingCablemodem->obtenerMapingUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $maping = $mapingCablemodem->obtenerMapingCastlenet($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $maping = "SAGEM";
        }
        
        //dd($maping);

        if($maping=="Error"){
            return $this->errorMessage("No se puede conectar: Agendelo al Back",500);
        }

        if($maping=="SAGEM"){
            return $this->errorMessage("No se encuentra disponible esta funcionalidad.",500);
        }

        return $this->resultData(
            array(  
                "ipLan1"=>$maping["IpLan1"],
                "ipLan2"=>$maping["IpLan2"],
                "ipLan3"=>$maping["IpLan3"],
                "maping"=>$maping["Maping"]
            )
       );

        //return view('administrador.modulos.multiconsulta.cablemodem.dmz',["dmz"=>$dmz["Dmz"]]);

    }



    public function updateMaping(Request $request)
    {
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s");

        $codCliente = $request->codigocliente;
        $mac = $request->mac;
        $ipaddress = $request->ipaddress;

        $fabricante = $request->fabricante;
        $modelo = $request->modelo;
        $firmware = $request->firmware;

        //valores de formulario
        $mapingData = $request->maping;

        $obtenerMaping = new CablemodemMapingFunctions;
        $mapingCablemodem = new CablemodemUpdateMapingFunctions;
        $logsFunctions = new LogsFunctions;
        
        //----------------OBTENIENDO LOS VALORES DEL MODEM---------------//
        if ($fabricante=="Askey") {
            $datosMaping = $obtenerMaping->obtenerMapingAskey($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $datosMaping = $obtenerMaping->obtenerMapingHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $datosMaping = $obtenerMaping->obtenerMapingUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $datosMaping = $obtenerMaping->obtenerMapingCastlenet($codCliente,$ipaddress,$fabricante);
        }
        //-------------------------------------------------------------//
        /*
        //-----------------ENVIAR DATOS A ACTUALIZAR-------------------//
        if ($fabricante=="Askey") {
            $maping = $mapingCablemodem->updateMapingAskey($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $maping = $mapingCablemodem->updateMapingHitron($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif ($fabricante=="Ubee") {
            $maping = $mapingCablemodem->updateMapingUbee($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $maping = $mapingCablemodem->updateMapingCastlenet($codCliente,$ipaddress,$fabricante,$mapingData);
        }
        */
        //-------------Datos del Formulario----------------------//
        $tabla1 = json_decode($mapingData);
        $arrayGuardar = array();
        $cantMapingGuardar = count($tabla1);

        for ($i=0; $i < $cantMapingGuardar; $i++) {
            $arrayGuardar[$i]["service"] = $tabla1[$i][0];
            $arrayGuardar[$i]["ipLan"] = $tabla1[$i][1];
            $arrayGuardar[$i]["protocolo"] = $tabla1[$i][2];
            $arrayGuardar[$i]["privatePort"] = $tabla1[$i][3];
            $arrayGuardar[$i]["publicPort"] = $tabla1[$i][4];
        }

        //--------------------------------------------------------//

        //-------------Datos obtenidos del modem------------------//
        $tabla = $datosMaping["Maping"];
        $arrayModem = array();
        $contarRegistros = count($tabla);
        
        for ($i=0; $i < $contarRegistros ; $i++) { 
            $nuevo_array[$i] = explode("|", $tabla[$i]);
        }
        
        for ($i=0; $i < $contarRegistros; $i++) {
            if(count($nuevo_array[$i])==7){
                $arrayModem[$i]["service"] = $nuevo_array[$i][0];
                $arrayModem[$i]["ipLan"] = $nuevo_array[$i][1];
                $arrayModem[$i]["protocolo"] = $nuevo_array[$i][2];
                $arrayModem[$i]["privatePort"] = $nuevo_array[$i][3]."-".$nuevo_array[$i][4];
                $arrayModem[$i]["publicPort"] = $nuevo_array[$i][5]."-".$nuevo_array[$i][6];
            }else{
                $arrayModem[$i]["service"] = $nuevo_array[$i][0];
                $arrayModem[$i]["ipLan"] = $nuevo_array[$i][1];
                $arrayModem[$i]["protocolo"] = $nuevo_array[$i][2];
                $arrayModem[$i]["privatePort"] = $nuevo_array[$i][3];
                $arrayModem[$i]["publicPort"] = $nuevo_array[$i][4];
            }
        }
        //--------------------------------------------------------//
        
        $eliminados_nombre = array_diff(array_column($arrayModem, 'service'), array_column($arrayGuardar, 'service'));
        $nuevos_nombre = array_diff(array_column($arrayGuardar, 'service'), array_column($arrayModem, 'service'));
        
        $eliminados_nombre = array_values($eliminados_nombre);
        $nuevos_nombre = array_values($nuevos_nombre);
        
        $cantidadEliminados = count($eliminados_nombre);
        $cantidadNuevos = count($nuevos_nombre);

        
        //--------------------Registrar Eliminados-------------------//
        if($cantidadEliminados>0){
            for ($i=0; $i < $cantidadEliminados; $i++) { 
                $keyEliminados = array_search($eliminados_nombre[$i], array_column($arrayModem, 'service'));
                
                $operacion = "Eliminado";
                $service = $arrayModem[$keyEliminados]["service"];
                $ipLan = $arrayModem[$keyEliminados]["ipLan"];
                $protocolo = $arrayModem[$keyEliminados]["protocolo"];
                $privatePort = $arrayModem[$keyEliminados]["privatePort"];
                $publicPort = $arrayModem[$keyEliminados]["publicPort"];

                $logsFunctions->registroLog($logsFunctions::LOG_MODEM_MAPING,array(
                    "usuario"=>$usuario,
                    "perfil"=>$rolNombre,
                    "codCliente"=>$codCliente,
                    "macaddress"=>$mac,
                    "fabricante"=>$fabricante,
                    "modelo"=>$modelo,
                    "firmware"=>$firmware,
                    "operacion"=>$operacion,
                    "service"=>$service,
                    "ipLan"=>$ipLan,
                    "protocolo"=>$protocolo,
                    "privatePort"=>$privatePort,
                    "publicPort"=>$publicPort
                    ));

 
               

            }
        }
        //-----------------------------------------------------------//

        //----------------------Registrar Nuevos-------------------//
        if($cantidadNuevos>0){
            for ($i=0; $i < $cantidadNuevos; $i++) { 
                $keyNuevos = array_search($nuevos_nombre[$i], array_column($arrayGuardar, 'service'));
                
                $operacion = "Nuevo";
                $service = $arrayGuardar[$keyNuevos]["service"];
                $ipLan = $arrayGuardar[$keyNuevos]["ipLan"];
                $protocolo = $arrayGuardar[$keyNuevos]["protocolo"];
                $privatePort = $arrayGuardar[$keyNuevos]["privatePort"];
                $publicPort = $arrayGuardar[$keyNuevos]["publicPort"];


                $logsFunctions->registroLog($logsFunctions::LOG_MODEM_MAPING,array(
                    "usuario"=>$usuario,
                    "perfil"=>$rolNombre,
                    "codCliente"=>$codCliente,
                    "macaddress"=>$mac,
                    "fabricante"=>$fabricante,
                    "modelo"=>$modelo,
                    "firmware"=>$firmware,
                    "operacion"=>$operacion,
                    "service"=>$service,
                    "ipLan"=>$ipLan,
                    "protocolo"=>$protocolo,
                    "privatePort"=>$privatePort,
                    "publicPort"=>$publicPort
                    ));

                
            }
        }
        //-----------------------------------------------------------//

        
        //-----------------ENVIAR DATOS A ACTUALIZAR-------------------//
        if ($fabricante=="Askey") {
            $maping = $mapingCablemodem->updateMapingAskey($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif (substr($fabricante,0,3)=="Hit") {
            $maping = $mapingCablemodem->updateMapingHitron($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif ($fabricante=="Ubee") {
            $maping = $mapingCablemodem->updateMapingUbee($codCliente,$ipaddress,$fabricante,$mapingData);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $maping = $mapingCablemodem->updateMapingCastlenet($codCliente,$ipaddress,$fabricante,$mapingData);
        }
        
        return $this->mensajeSuccess($maping);

    }

























}
