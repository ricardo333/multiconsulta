<?php
namespace App\Functions;

use DB;
use App\Library\simple_html_dom;
use App\Functions\CablemodemFunctions;

class CablemodemUpdateWifiFunctions {


    function updateWifiAskey($codCliente,$ipaddress,$fabricante,$ssid1,$interface1,$channel1,$bandwidth1,
            $power1,$seguridad1,$pass1)
    {

        $ssid01 = trim($ssid1);

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $postFields_Wifi = array(
            "AskWifiOnOffValue" => "1",
            "AskWifiSsid" => $ssid01,
            "AskWifiInterfaceType" => $interface1,
            "AskWifiChannelSelect" => $channel1,
            "AskWifiBandwidthManual" => $bandwidth1,
            "AskWifiObssValue" => "1",
            "AskWifiOutputPower" => $power1,
            "AskWifiBroadcastValue" => "1",
            "AskWifiWPASelect" => $seguridad1,
            "FakePasswordRemembered2G" =>"", 
            "wifiPassword" => $pass1
        );


        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_wifi="https://".$ipaddress."/WifiGeneral.asp";
        $url_cambio="https://".$ipaddress."/goform/AskWifiGeneral";
        $url_logout="https://".$ipaddress."/login.asp";


        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $obtWifi = $ingresarCablemodem->getPageAskey2($url_wifi);
        $updateWifi = $ingresarCablemodem->getPageAskey1($url_cambio,$postFields_Wifi);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;


    }



    function updateWifiAskey5G($codCliente,$ipaddress,$fabricante,$ssid1,$interface1,$channel1,$bandwidth1,
            $power1,$seguridad1,$pass1,$ssid2,$interface2,$channel2,$bandwidth2,$power2,$seguridad2,$pass2)
    {
        $ssid01 = trim($ssid1);
        $ssid02 = trim($ssid2);

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $postFields_5GWifi = array(
            "AskWifiOnOffValue" => "1",
            "AskWifiSsid" => $ssid01,
            "AskWifiInterfaceType" => $interface1,
            "AskWifiChannelSelect" => $channel1,
            "AskWifiBandwidthManual" => $bandwidth1,
            "AskWifiObssValue" => "1",
            "AskWifiOutputPower" => $power1,
            "AskWifiBroadcastValue" => "1",
            "AskWifiWPASelect" => $seguridad1,
            "FakePasswordRemembered2G" => "",
            "wifiPassword" => $pass1,
            "AskWifi5GOnOffValue" => "1",
            "AskWifi5GSsid" => $ssid02,
            "AskWifi5GInterfaceType" => $interface2,
            "AskWifi5GChannelSelect" => $channel2,
            "AskWifi5GBandwidthManual" => $bandwidth2,
            "AskWifi5GObssValue" => "1",
            "AskWifi5GOutputPower" => $power2,
            "AskWifi5GBroadcastValue" => "1",
            "AskWifi5GWPASelect" => $seguridad2,
            "FakePasswordRemembered5G" =>"", 
            "wifi5GPassword" => $pass2
        );


        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_wifi="https://".$ipaddress."/WifiGeneral.asp";
        $url_cambio="https://".$ipaddress."/goform/AskWifiGeneral";
        $url_logout="https://".$ipaddress."/login.asp";


        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $obtWifi = $ingresarCablemodem->getPageAskey2($url_wifi);
        $updateWifi = $ingresarCablemodem->getPageAskey1($url_cambio,$postFields_5GWifi);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;


    }


    function updateWifiHitron($codCliente,$ipaddress,$fabricante,$ssid1,$ssid2,$ssid3,$ssid4,$ssid5,
                    $ssid6,$ssid7,$ssid8,$interface1,$channel,$bandwidth,$seguridad1,$seguridad2,$pass)
    {
        $ssid01 = trim($ssid1);

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $postFields_Wifi = array(
            "dir" => "admin/",
            "file" => "wireless",
            "ssid1" => $ssid01,
            "ssid2" => $ssid2,
            "ssid3" => $ssid3,
            "ssid4" => $ssid4,
            "ssid5" => $ssid5,
            "ssid6" => $ssid6,
            "ssid7" => $ssid7,
            "ssid8" => $ssid8,
            "ssid_hidden" => "0,0,0,0,0,0,0,0",
            "ssid_service" => "1,0,0,0,0,0,0,0",
            "ssid_WMMMode" => "1,1,1,1,1,1,1,1",
            "wpsstatus" => "",
            "Encrypt_type" => "",
            "Ciphertype" => "",
            "wireless" => "1",
            "ModeAssign" => $interface1,
            "w_channel" => $channel,
            "ChannelBW" => $bandwidth,
            "SSID1" => $ssid01,
            "SSIDWMMMode1" => "0",
            "SSID2" => $ssid2,
            "SSIDWMMMode2" => "0",
            "SSID3" => $ssid3,
            "SSIDWMMMode3" => "0",
            "SSID4" => $ssid4,
            "SSIDWMMMode4" => "0",
            "SSID5" => $ssid5,
            "SSIDWMMMode5" => "0",
            "SSID6" => $ssid6,
            "SSIDWMMMode6" => "0",
            "SSID7" => $ssid7,
            "SSIDWMMMode7" => "0",
            "SSID8" => $ssid8,
            "SSIDWMMMode8" => "0"
            );
            
            $postFields_Wifi_Security = array(
            "dir" => "admin/",
            "file" => "wireless_e",
            "key1" => "0000000000",
            "key2" => "0000000000",
            "key3" => "0000000000",
            "key4" => "0000000000",
            "k128_1" => "00000000000000000000000000",
            "k128_2" => "00000000000000000000000000",
            "k128_3" => "00000000000000000000000000",
            "k128_4" => "00000000000000000000000000",
            "wpsstatus" => "", 
            "ssid_list" => "0",
            "Encrypt_type" => "2",
            "WPAMode" => $seguridad1,
            "Ciphertype" => $seguridad2, 
            "GroupKeyUdIvl" => "3600",
            "PresharedKey" => $pass
            );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifi1="https://".$ipaddress."/admin/wireless.asp";
        $url_wifi2="https://".$ipaddress."/admin/wireless_e.asp";
        $url_cambio="https://".$ipaddress."/goform/Wls";
        $url_logout="https://".$ipaddress."/login.asp";


        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtWifi1 = $ingresarCablemodem->getPageHitron2($url_wifi1);
        $updateWifi1 = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Wifi);
        $obtWifi2 = $ingresarCablemodem->getPageHitron2($url_wifi2);
        $updateWifi2 = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Wifi_Security);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function updateWifiUbee($codCliente,$ipaddress,$fabricante,$ssid,$interface1,$channel,$seguridad1,
                            $seguridad2,$seguridad3,$seguridad4,$seguridad5,$pass)
    {
        $ssid01 = trim($ssid);

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $postFields_Wifi1 = array(
            "WirelessEnable" => "1",
            "NMode" => $interface1,
            "ChannelNumber" => $channel,
            "RegulatoryMode" => "0",
            "ObssCoexistence" => "1",
            "restoreWirelessDefaults" => "0",
            "commitwlanRadio" => "1"
        );

        $postFields_Wifi2 = array(
            "PrimaryNetworkEnable" => "1",
            "ServiceSetIdentifier" => $ssid01,
            "ClosedNetwork" => "0",
            "ApIsolate" => "0",
            "WpaAuth" => $seguridad1,
            "WpaPskAuth" => $seguridad2,
            "Wpa2Auth" => $seguridad3,
            "Wpa2PskAuth" => $seguridad4,
            "WpaEncryption" => $seguridad5,
            "WpaPreSharedKey" => $pass,
            "ShowWpaKey" => "0x01",
            "WpaRekeyInterval" => "0",
            "GenerateWepKeys" => "0",
            "WepKeysGenerated" => "0",
            "commitwlanPrimaryNetwork" => "1",
            "AutoSecurity" => "1"
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_wifi="http://".$ipaddress."/wlanRadio.asp";
        $url_cambio1="http://".$ipaddress."/goform/wlanRadio";
        $url_wifi2="http://".$ipaddress."/wlanPrimaryNetwork.asp";
        $url_cambio2="http://".$ipaddress."/goform/wlanPrimaryNetwork";
        $url_logout="https://".$ipaddress."/login.asp";


        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtWifi1 = $ingresarCablemodem->getPageUbee2($url_wifi);
        $updateWifi1 = $ingresarCablemodem->getPageUbee1($url_cambio1,$postFields_Wifi1);
        $obtWifi2 = $ingresarCablemodem->getPageUbee2($url_wifi2);
        $updateWifi2 = $ingresarCablemodem->getPageUbee1($url_cambio2,$postFields_Wifi2);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);


        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }



    function updateWifiSagem($codCliente,$ipaddress,$fabricante,$ssid,$channel,$bandwidth,$power,
            $seguridad1,$seguridad2,$seguridad3,$seguridad4,$seguridad5,$pass)
    {
        $ssid01 = trim($ssid);

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );

        $postFields_Wifi1 = array(
            "WirelessMacAddress" => "0",
            "WirelessEnable" => "1",
            "OutputPower" => $power,
            "Band" => "2",
            "NMode" => "0",
            "NBandwidth" => $bandwidth,
            "ChannelNumber" => $channel,
            "RegulatoryMode" => "0",
            "ObssCoexistence" => "1",
            "STBCTx" => "0",
            "restoreWirelessDefaults" => "0",
            "commitwlanRadio" => "1",
            "scanActions" => "0"
        );
            
            $postFields_Wifi2 = array(
            "commitshowbuttonpn" => "0",
            "PrimaryNetworkEnable" => "1",
            "ServiceSetIdentifier" => $ssid01,
            "ClosedNetwork" => "0",
            "BssModeRequired" => "0",
            "ApIsolate" => "0",
            "WpaAuth" => $seguridad1,
            "WpaPskAuth" => $seguridad2,
            "Wpa2Auth" => $seguridad3,
            "Wpa2PskAuth" => $seguridad4,
            "WpaEncryption" => $seguridad5,
            "WpaPreSharedKey" => $pass,
            "ShowWpaKey" => "0x01",
            "WpaRekeyInterval" => "0",
            "GenerateWepKeys" => "0",
            "WepKeysGenerated" => "0",
            "commitwlanPrimaryNetwork" => "1",
            "AutoSecurity" => "1"
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifi1="https://".$ipaddress."/wlanRadio.asp";
        $url_cambio1="https://".$ipaddress."/goform/wlanRadio";
        $url_wifi2="https://".$ipaddress."/wlanPrimaryNetwork.asp";
        $url_cambio2="https://".$ipaddress."/goform/wlanPrimaryNetwork";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);
        $obtWifi1 = $ingresarCablemodem->getPageSagem2($url_wifi1);
        $updateWifi1 = $ingresarCablemodem->getPageSagem1($url_cambio1,$postFields_Wifi1);
        $obtWifi2 = $ingresarCablemodem->getPageSagem2($url_wifi2);
        $updateWifi2 = $ingresarCablemodem->getPageSagem1($url_cambio2,$postFields_Wifi2);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);


        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }




    function updateWifiCastlenet($codCliente,$ipaddress,$fabricante,$ssid,$seguridad,$pass)
    {
        $ssid01 = trim($ssid);

        $login = 'admin';

        $postFields_Wifi = array(
            "PrimaryNetworkEnable" => "1",
            "ServiceSetIdentifier" => $ssid01,
            "ClosedNetwork" => "0",
            "ApIsolate" => "0",
            "WpaPskAuth" => "1",
            "Wpa2PskAuth" => "0",
            "WpaEncryption" => $seguridad,
            "WpaPreSharedKey" => $pass,
            "WpaRekeyInterval" => "0",
            "GenerateWepKeys" => "0",
            "WepKeysGenerated" => "0",
            "commitwlanPrimaryNetwork" => "1"
        );

        $url_wifi="http://".$ipaddress."/goform/wlanPrimaryNetwork";

        $ingresarCablemodem = new CablemodemFunctions;
        $updateWifi = $ingresarCablemodem->getPageCastlenet2($url_wifi,$login,$codCliente,$postFields_Wifi);


        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }

 


}

?>