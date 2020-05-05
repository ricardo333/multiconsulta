<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemUpdateDmzFunctions {


    function updateDmzAskey($codCliente,$ipaddress,$fabricante,$wanBlock,$ipDmz,$activacion)
    {

        if($activacion=="1" and $wanBlock=="1"){
            $wan="0";
        }elseif ($activacion=="1" and $wanBlock=="0") {
            $wan="0";
        }elseif ($activacion=="0" and $wanBlock=="1") {
            $wan="1";
        }

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );
        
        $postFields_Dmz = array( 
            "AskDmzValue" => $activacion,
            "DmzHostIP3" => $ipDmz
        );
        
        $postFields_Wan = array( 
            "AskWANBlocking" => $wan,
            "AskIpsecPassThrough" => "0",
            "AskPPTPPassThrough" => "0",
            "AskoptionFirewall" => "0"
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_dmz="https://".$ipaddress."/RgDmzHost.asp";
        $url_cambioDmz="https://".$ipaddress."/goform/AskDmzHost";
        $url_wan="https://".$ipaddress."/RgAdvanced.asp";
        $url_cambiowan="https://".$ipaddress."/goform/AskRgAdvanced";
        $url_logout="https://".$ipaddress."/login.asp";

        

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $logout = $ingresarCablemodem->getPageAskey2($url_wan);
        $ingreso = $ingresarCablemodem->getPageAskey1($url_cambiowan,$postFields_Wan);
        $logout = $ingresarCablemodem->getPageAskey2($url_dmz);
        $ingreso = $ingresarCablemodem->getPageAskey1($url_cambioDmz,$postFields_Dmz);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;


    }


    function updateDmzHitron($codCliente,$ipaddress,$fabricante,$id,$rpt,$upnp,$wan,$ipDmz,$ipValor1,
                                $ipValor2,$ipValor3,$ipValor4,$activacion)
    {

        if($upnp=="1"){
            $upnpEnable="on";
        }else{
            $upnpEnable="";
        }
        
        if($wan=="0"){
            $wanEnable="on";
        }else{
            $wanEnable="";
        }
        
        if($activacion=="1" and $wan=="0"){
            $dmzEnable="on";
            $wan="1";
            $wanEnable="";
        }elseif ($activacion=="0" and $wan=="1") {
            $dmzEnable="";
            $wan="1";
            $wanEnable="";
        }elseif ($activacion=="1" and $wan=="1") {
            $dmzEnable="on";
            $wan="1";
            $wanEnable="";
        }
        
        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $postFields_Upnp = array(
            "dir" => "admin/",
            "file" => "feat-firewall",
            "ids_mode" => $id,
            "rspToPing" => $rpt,
            "upnp_status" => $upnp,
            "enable_upnp" => $upnpEnable,
            "wan_block" => $wan,
            "disable_wanblock" => $wanEnable
        );
            
        $postFields_OnDmz = array(
            "dir" => "admin/",
            "file" => "feat-firewall",
            "dmz_enableCheck" => $dmzEnable,
            "dmzenable" => $activacion,
            "dmzip" => $ipDmz,
            "dmzip0" => $ipValor1,
            "dmzip1" => $ipValor2,
            "dmzip2" => $ipValor3,
            "dmzip3" => $ipValor4
        );
            
        $postFields_OffDmz = array(
                "dir" => "admin/",
                "file" => "feat-firewall",
                "dmzenable" => $activacion,
                "dmzip" => "192.168.1.10"
        );


        $url_router="https://".$ipaddress."/goform/login";
        $url_dmz="https://".$ipaddress."/admin/feat-firewall.asp";
        $url_cambio="https://".$ipaddress."/goform/Firewall"; 
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtWifi1 = $ingresarCablemodem->getPageHitron2($url_dmz);
        $updateWifi1 = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Upnp);
        if($activacion=="1"){
            $updateWifi1 = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_OnDmz);
        }else{
            $updateWifi1 = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_OffDmz);
        }

        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function updateDmzUbee($codCliente,$ipaddress,$fabricante,$ipDmz,$activacion)
    {
        if($activacion=="0"){
            $ipDmz="0";
        }
        
        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );
        
        $postFields_Dmz = array( 
            "DmzHostIP3" => $ipDmz
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_dmz="http://".$ipaddress."/RgDmzHost.asp";
        $url_cambio="http://".$ipaddress."/goform/RgDmzHost";
        $url_logout="http://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtWifi1 = $ingresarCablemodem->getPageUbee2($url_dmz);
        $updateWifi1 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Dmz);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function updateDmzCastlenet($codCliente,$ipaddress,$fabricante,$ipDmz,$activacion)
    {
        $login = 'admin';

        if($activacion=="0"){
            $ipDmz="0";
        }
        
        $postFields_Dmz = array( 
            "DmzHostIP3" => $ipDmz
        );

        $url_Dmz="http://".$ipaddress."/goform/RgDmzHost";

        $ingresarCablemodem = new CablemodemFunctions;
        $updateDmz = $ingresarCablemodem->getPageCastlenet2($url_Dmz,$login,$codCliente,$postFields_Dmz);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }













}

?>