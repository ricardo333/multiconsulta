<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemDmzFunctions {


    function obtenerDmzAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );


        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_dmz="https://".$ipaddress."/RgDmzHost.asp";
        $url_wan="https://".$ipaddress."/RgAdvanced.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtUpnp = $ingresarCablemodem->getPageAskey2($url_dmz);
        $obtWan = $ingresarCablemodem->getPageAskey2($url_wan);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtUpnp);

        foreach($html->find('[id=exposedDmzHost]') as $a)
            $valorOnOff=$a->getAttribute('class');

        foreach($html->find('[class=table-col fR table-col2]') as $b)
            $valorIP[]=$b->innertext;

        $ipPublica=$valorIP[0];
        //$ipPrivada=$valorIP[1];

        $delimiter = array(" ","<input","=","'","\"");
        $replace = str_replace($delimiter, $delimiter[0], $valorIP[1]);
        $ipPrivada = array_values(array_filter(explode($delimiter[0], $replace)));

        $buscar_ip=array_search('value',$ipPrivada);
        $obt_ip=$buscar_ip+1;
        $ipAskey=$ipPrivada[$obt_ip];

        if(trim($valorOnOff)=="button button-on"){
            $activado="on";
        }else{
            $activado="off";
        }

        $html2 = new simple_html_dom();
        $html2->load($obtWan);

        foreach($html2->find('[id=WANBlockingClass]') as $c)
            $valorOnOff2=$c->getAttribute('class');

        if(trim($valorOnOff2)=="button button-on"){
            $wanBlock="1";
        }else{
            $wanBlock="0";
        }

        $dmz["fabricante"] = "Askey";
        $dmz["publica"] = $ipPublica;
        $dmz["privada"] = $ipPrivada[0];
        $dmz["privadaip"] = $ipAskey;
        $dmz["wan"] = $wanBlock;
        $dmz["valor"] = $activado;


        return array(
            "Dmz" => $dmz
        );

    }


    }


    function obtenerDmzHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_dmz="https://".$ipaddress."/admin/feat-firewall.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtDmz = $ingresarCablemodem->getPageHitron2($url_dmz);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtDmz);

        $a1=$html->find('script',5);
        $arr1=$a1->innertext;
        $arrayA1=explode('/, /',$arr1);
        $arrayA2=preg_split('/[{}\"|=:;]+/',$arrayA1[0]);

        $indice_idsMode=array_search("idsMode",$arrayA2)+1;
        $indice_rPT=array_search("respPingFlag",$arrayA2)+1;
        $indice_UPnP=array_search("UPNPStatus",$arrayA2)+1;
        $indice_WanBlock=array_search("WANBlock",$arrayA2)+1;
        $indice_Dmz=array_search("defaultDmzServerBase",$arrayA2)+1;
        $indice_IpDmz=array_search("defaultDmzServerBase",$arrayA2)+2;

        $ids=$arrayA2[$indice_idsMode];
        $rpt=$arrayA2[$indice_rPT];
        $upnp=$arrayA2[$indice_UPnP];
        $wan=$arrayA2[$indice_WanBlock];

        $activacionDmz=$arrayA2[$indice_Dmz];

        $ipDmzWeb=$arrayA2[$indice_IpDmz];


        $dmz["fabricante"] = "Hitron";
        $dmz["idsHitron"] = $ids;
        $dmz["rptHitron"] = $rpt;
        $dmz["upnpHitron"] = $upnp;
        $dmz["wanHitron"] = $wan;
        $dmz["activacionDmzHitron"] = $activacionDmz;
        $dmz["ipDmzWebHitron"] = $ipDmzWeb;


        return array(
            "Dmz" => $dmz
        );

    }


    }


    function obtenerDmzUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_dmz="http://".$ipaddress."/RgDmzHost.asp";
        $url_logout="http://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtDmz = $ingresarCablemodem->getPageUbee2($url_dmz);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtDmz);

        foreach($html->find('[name=DmzHostIP3]') as $a)
	    $valorIP=$a->getAttribute('value');

        if(trim($valorIP)=="0"){
            $activado="off";
        }else{
            $activado="on";
        }

        $dmz["fabricante"] = "Ubee";
        $dmz["ipUbee"] = $valorIP;
        $dmz["valorUbee"] = $activado;


        return array(
            "Dmz" => $dmz
        );

    }


    }


    function obtenerDmzCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_dmz="http://".$ipaddress."/RgDmzHost.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtUpnp = $ingresarCablemodem->getPageCastlenet1($url_dmz,$login,$codCliente);

        if($obtUpnp=="Error"){
            return "Error";
        }else {

        $html = new simple_html_dom();
        $html->load($obtUpnp);

        foreach($html->find('[name=DmzHostIP3]') as $a)
            $valorIP=$a->getAttribute('value');

        if(trim($valorIP)=="0"){
            $activado="off";
        }else{
            $activado="on";
        }

        $dmz["fabricante"] = "Castlenet";
        $dmz["ipCastlenet"] = $valorIP;
        $dmz["valorCastlenet"] = $activado;


        return array(
            "Dmz" => $dmz
        );

    }


    }




}


?>


