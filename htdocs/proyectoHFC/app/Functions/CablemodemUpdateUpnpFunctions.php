<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemUpdateUpnpFunctions {


    function updateUpnpAskey($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion)
    {

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $postFields_Upnp = array( 
            "AskUPnPValue" => $activacion
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_cambio="https://".$ipaddress."/goform/AskUPnP";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $updateWifi = $ingresarCablemodem->getPageAskey1($url_cambio,$postFields_Upnp);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;


    }


    function updateUpnpHitron($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion)
    {

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        if($activacion=="1"){
            $upnpEnable="on";
        }else{
            $upnpEnable="";
        }
        
        if($canal=="0"){
            $wanEnable="on";
        }else{
            $wanEnable="";
        }

        $postFields_Upnp = array(
            "dir" => "admin/",
            "file" => "feat-firewall",
            "ids_mode" => $identi,
            "rspToPing" => $respuesta,
            "upnp_status" => $activacion,
            "enable_upnp" => $upnpEnable,
            "wan_block" => $canal,
            "disable_wanblock" => $wanEnable
        );


        $url_router="https://".$ipaddress."/goform/login";
        $url_upnp="https://".$ipaddress."/admin/feat-firewall.asp";
        $url_cambio="https://".$ipaddress."/goform/Firewall"; 
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtUpnp = $ingresarCablemodem->getPageHitron2($url_upnp);
        $updateUpnp = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Upnp);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function updateUpnpUbee($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion)
    {

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $postFields_On = array( 
            "cbRemoteManagement" => "0x80",
            "cbOptMulticast" => "0x20000",
            "cbOptUPnP" => "0x200000",
            "RSVP" => "0x1",
            "FTP" => "0x2",
            "TFTP" => "0x4",
            "Kerb88" => "0x8",
            "NetBios" => "0x10",
            "IKE" => "0x20",
            "RTSP" => "0x40",
            "Kerb1293" => "0x80",
            "H225" => "0x100",
            "PPTP" => "0x200",
            "MSN" => "0x400",
            "SIP" => "0x800",
            "ICQ" => "0x1000",
            "IRC666x" => "0x2000",
            "ICQTalk" => "0x4000",
            "Net2Phone" => "0x8000",
            "IRC7000" => "0x10000",
            "IRC8000" => "0x20000",
            "ApplyRgOpAction" => "1"
        );

        $postFields_Off = array( 
            "cbRemoteManagement" => "0x80",
            "cbOptMulticast" => "0x20000",
            "RSVP" => "0x1",
            "FTP" => "0x2",
            "TFTP" => "0x4",
            "Kerb88" => "0x8",
            "NetBios" => "0x10",
            "IKE" => "0x20",
            "RTSP" => "0x40",
            "Kerb1293" => "0x80",
            "H225" => "0x100",
            "PPTP" => "0x200",
            "MSN" => "0x400",
            "SIP" => "0x800",
            "ICQ" => "0x1000",
            "IRC666x" => "0x2000",
            "ICQTalk" => "0x4000",
            "Net2Phone" => "0x8000",
            "IRC7000" => "0x10000",
            "IRC8000" => "0x20000",
            "ApplyRgOpAction" => "1"
        );


        $url_router="http://".$ipaddress."/goform/login";
        $url_Upnp="http://".$ipaddress."/RgOptions.asp";
        $url_cambio="http://".$ipaddress."/goform/RgOptions";
        $url_logout="http://".$ipaddress."/login.asp";
        
        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtUpnp = $ingresarCablemodem->getPageUbee2($url_Upnp);
        
        if($activacion=="1"){
            $updateUpnp = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_On);
        }else{
            $updateUpnp = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Off);
        }

        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $mensaje = "Cambio de Upnp...".$codCliente;

        return $mensaje;

    }



    function updateUpnpCastlenet($codCliente,$ipaddress,$fabricante,$identi,$respuesta,$canal,$activacion)
    {

        $login = 'admin';

        $postFields_On = array(
            "cbWanBlocking" => "0x10",
            "cbIpsecPassThrough" => "0x20",
            "cbPptpPassThrough" => "0x40",
            "cbOptUPnP" => "0x200000",
            "RSVP" => "0x1",
            "FTP" => "0x2",
            "TFTP" => "0x4",
            "Kerb88" => "0x8",
            "NetBios" => "0x10",
            "IKE" => "0x20",
            "RTSP" => "0x40",
            "Kerb1293" => "0x80",
            "H225" => "0x100",
            "PPTP" => "0x200",
            "MSN" => "0x400",
            "SIP" => "0x800",
            "ICQ" => "0x1000",
            "IRC666x" => "0x2000",
            "ICQTalk" => "0x4000",
            "Net2Phone" => "0x8000",
            "IRC7000" => "0x10000",
            "IRC8000" => "0x20000",
            "ApplyRgOpAction" => "1",
            "NewMacAddress" => "",
            "MacAddressAction" => "0"
        );
        
        $postFields_Off = array(
            "cbWanBlocking" => "0x10",
            "cbIpsecPassThrough" => "0x20",
            "cbPptpPassThrough" => "0x40",
            "RSVP" => "0x1",
            "FTP" => "0x2",
            "TFTP" => "0x4",
            "Kerb88" => "0x8",
            "NetBios" => "0x10",
            "IKE" => "0x20",
            "RTSP" => "0x40",
            "Kerb1293" => "0x80",
            "H225" => "0x100",
            "PPTP" => "0x200",
            "MSN" => "0x400",
            "SIP" => "0x800",
            "ICQ" => "0x1000",
            "IRC666x" => "0x2000",
            "ICQTalk" => "0x4000",
            "Net2Phone" => "0x8000",
            "IRC7000" => "0x10000",
            "IRC8000" => "0x20000",
            "ApplyRgOpAction" => "1",
            "NewMacAddress" => "",
            "MacAddressAction" => "0"
        );

        $url_upnp="http://".$ipaddress."/goform/RgOptions";

        $ingresarCablemodem = new CablemodemFunctions;

        if($activacion=="1"){
            $updateUpnp = $ingresarCablemodem->getPageCastlenet2($url_upnp,$login,$codCliente,$postFields_On);
        }else{
            $updateUpnp = $ingresarCablemodem->getPageCastlenet2($url_upnp,$login,$codCliente,$postFields_Off);
        }
    
        $mensaje = "Datos cargados cliente...".$codCliente;
    
        return $mensaje;

    }




}



?>