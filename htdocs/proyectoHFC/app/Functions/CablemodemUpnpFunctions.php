<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemUpnpFunctions {


    function obtenerUpnpAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );
        
        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_upnp="https://".$ipaddress."/UPnP.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
         }else{

        $obtUpnp = $ingresarCablemodem->getPageAskey2($url_upnp);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //Consulta a la pagina de datos de status de Upstream
        $html = new simple_html_dom();
        $html->load($obtUpnp);

        $upnp = array();

        foreach($html->find('[id=UPnPEnablePic]') as $a)
        $valorOnOff=$a->getAttribute('class');

        if(trim($valorOnOff)=="button button-on"){
            $activado="1";
        }else{
            $activado="0";
        }

        $upnp["fabricante"] = "Askey";
        $upnp["identi"] = "No Registra";
        $upnp["respuesta"] = "No Registra";
        $upnp["canal"] = "No Registra";
        $upnp["valor"] = $activado;


        return array(
            "Upnp" => $upnp
        );

    }

    }


    function obtenerUpnpHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_upnp="https://".$ipaddress."/admin/feat-firewall.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
         }else{

        $obtUpnp = $ingresarCablemodem->getPageHitron2($url_upnp);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtUpnp);

        $upnp = array();

        $a1=$html->find('script',5);
        $arr1=$a1->innertext;
        $arrayA1=explode('/, /',$arr1);
        $arrayA2=preg_split('/[{}\"|=:;]+/',$arrayA1[0]);

        $indice_idsMode=array_search("idsMode",$arrayA2)+1;
        $indice_rPT=array_search("respPingFlag",$arrayA2)+1;
        $indice_UPnP=array_search("UPNPStatus",$arrayA2)+1;
        $indice_WanBlock=array_search("WANBlock",$arrayA2)+1;

        $ids=$arrayA2[$indice_idsMode];
        $rpt=$arrayA2[$indice_rPT];
        $activado=$arrayA2[$indice_UPnP];
        $wan=$arrayA2[$indice_WanBlock];

        $upnp["fabricante"] = "Hitron";
        $upnp["identi"] = $ids;
        $upnp["respuesta"] = $rpt;
        $upnp["canal"] = $wan;
        $upnp["valor"] = $activado;


        return array(
            "Upnp" => $upnp
        );

    }

    }


    function obtenerUpnpUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_upnp="http://".$ipaddress."/RgOptions.asp";
        $url_logout="http://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
         }else{

        $obtUpnp = $ingresarCablemodem->getPageUbee2($url_upnp);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtUpnp);

        foreach($html->find('[name=cbOptUPnP]') as $a)
	    $valorOnOff=$a->getAttribute('checked');


        if(trim($valorOnOff)=="1"){
            $activado="1";
        }else{
            $activado="0";
        }

        $upnp["fabricante"] = "Ubee";
        $upnp["identi"] = "No Registra";
        $upnp["respuesta"] = "No Registra";
        $upnp["canal"] = "No Registra";
        $upnp["valor"] = $activado;


        return array(
            "Upnp" => $upnp
        );

    }

    }


    function obtenerUpnpCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_upnp="http://".$ipaddress."/RgOptions.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtUpnp = $ingresarCablemodem->getPageCastlenet1($url_upnp,$login,$codCliente);

        if($obtUpnp=="Error"){
            return "Error";
         }else{

        $html = new simple_html_dom();
        $html->load($obtUpnp);

        foreach($html->find('[name=cbOptUPnP]') as $a)
	    $valorOnOff=$a->getAttribute('checked');

        if(trim($valorOnOff)=="1"){
            $activado="1";
        }else{
            $activado="0";
        }

        $upnp["fabricante"] = "Castlenet";
        $upnp["identi"] = "No Registra";
        $upnp["respuesta"] = "No Registra";
        $upnp["canal"] = "No Registra";
        $upnp["valor"] = $activado;


        return array(
            "Upnp" => $upnp
        );

    }

    }



}

?>