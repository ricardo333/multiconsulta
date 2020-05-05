<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemWifiFunctions {


    function obtenerWifiAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_wifi="https://".$ipaddress."/WifiGeneral.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifi = $ingresarCablemodem->getPageAskey2($url_wifi);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //Consulta a la pagina de datos de status de Upstream
        $html = new simple_html_dom();
        $html->load($obtWifi);

        $wifi1=array();
        $wifi2=array();

        $proteccion = '';

        $a=$html->find('script',6);
        $arr=$a->innertext;

        $array1=explode('WifiCtrlChannel = ',$arr);
        $array2=explode(';',$array1[1]);
        $search1 = array("\"");
        $Channel1=str_replace($search1,"",trim($array2[0]));

        foreach($html->find('[name=AskWifiSsid]') as $a)
	    $SSID1=$a->getAttribute('value');
	
        foreach($html->find('#AskWifiInterfaceType [selected]') as $b)
        $Interface1=$b->getAttribute('value');
        
        foreach($html->find('#AskWifiBandwidthManual [selected]') as $c)
	    $Bandwidth1=$c->getAttribute('value');
	
        foreach($html->find('#AskWifiOutputPower [selected]') as $d)
	    $Power1=$d->getAttribute('value');	
	
        foreach($html->find('#AskWifiWPASelect [selected]') as $e)
        $proteccion=$e->getAttribute('value');

        if ($proteccion<>'') {
            $Seguridad1=$e->getAttribute('value');
        } else {
            $Seguridad1="off";
        }
            
            
        foreach($html->find('#wpaValue') as $f)
            $Pass1=$f->getAttribute('value');


        $wifi1["fabricante"]="Askey";
        $wifi1["ssid"]=$SSID1;
        $wifi1["interface"]=$Interface1;
        $wifi1["channel"]=$Channel1;
        $wifi1["bandwidth"]=$Bandwidth1;
        $wifi1["power"]=$Power1;
        $wifi1["seguridad"]=$Seguridad1;
        $wifi1["password"]=$Pass1;


        //---------------Doble Banda (5G)----------------//
        $b=$html->find('script',6);
        $arr=$b->innertext;

        $array1=explode('Wifi5GCtrlChannel = ',$arr);
        $array2=explode(';',$array1[1]);
        $search1 = array("\"");
        $Channel2=str_replace($search1,"",trim($array2[0]));

        if ($Channel2<>"") {

            foreach($html->find('[name=AskWifi5GSsid]') as $a)
	        $SSID2=$a->getAttribute('value');
	
            foreach($html->find('#AskWifi5GInterfaceType [selected]') as $b)
            $Interface2=$b->getAttribute('value');
            
            foreach($html->find('#AskWifi5GBandwidthManual [selected]') as $c)
	        $Bandwidth2=$c->getAttribute('value');
	
            foreach($html->find('#AskWifi5GOutputPower [selected]') as $d)
                $Power2=$d->getAttribute('value');	
                
            foreach($html->find('#AskWifi5GWPASelect [selected]') as $e)
                $Seguridad2=$e->getAttribute('value');
                
            foreach($html->find('#wpa5GValue') as $f)
                $Pass2=$f->getAttribute('value');

                $wifi2["ssid"]=$SSID2;
                $wifi2["interface"]=$Interface2;
                $wifi2["channel"]=$Channel2;
                $wifi2["bandwidth"]=$Bandwidth2;
                $wifi2["power"]=$Power2;
                $wifi2["seguridad"]=$Seguridad2;
                $wifi2["password"]=$Pass2;

        }else{
            $wifi2["ssid"]='';
        }


        return array(
            "Wifi" => $wifi1,
            "Wifi5G" => $wifi2
        );

    }


    }


    function obtenerWifiHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifi1="https://".$ipaddress."/admin/wireless.asp";
        $url_wifi2="https://".$ipaddress."/admin/wireless_e.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifi1 = $ingresarCablemodem->getPageHitron2($url_wifi1);
        $obtWifi2 = $ingresarCablemodem->getPageHitron2($url_wifi2);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html1 = new simple_html_dom();
        $html1->load($obtWifi1);

        $a=$html1->find('script',5);
        $arr=$a->innertext;
        $array1=explode('<!--',$arr);
        $array2=preg_split('/[{(\",;.:=+)}]/',$array1[1]);

        $buscar_ssid=array_search('ssid',$array2);
        $buscar_interfaz=array_search('WlsMode',$array2);
        $buscar_channel=array_search('Channel',$array2);
        $buscar_bandwidth=array_search('WlsHtBW',$array2);

        $obt_ssid1=$buscar_ssid+3;
        $obt_ssid2=$buscar_ssid+6;
        $obt_ssid3=$buscar_ssid+9;
        $obt_ssid4=$buscar_ssid+12;
        $obt_ssid5=$buscar_ssid+15;
        $obt_ssid6=$buscar_ssid+18;
        $obt_ssid7=$buscar_ssid+21;
        $obt_ssid8=$buscar_ssid+24;

        $obt_interfaz=$buscar_interfaz+3;
        $obt_channel=$buscar_channel+3;
        $obt_bandwidth=$buscar_bandwidth+3;

        $SSID1=$array2[$obt_ssid1];
        $SSID2=$array2[$obt_ssid2];
        $SSID3=$array2[$obt_ssid3];
        $SSID4=$array2[$obt_ssid4];
        $SSID5=$array2[$obt_ssid5];
        $SSID6=$array2[$obt_ssid6];
        $SSID7=$array2[$obt_ssid7];
        $SSID8=$array2[$obt_ssid8];

        $Interface1=$array2[$obt_interfaz];
        $Channel1=$array2[$obt_channel];
        $Bandwidth1=$array2[$obt_bandwidth];


        $html2 = new simple_html_dom();
        $html2->load($obtWifi2);

        $b=$html2->find('script',5);
        $arr1=$b->innertext;
        $array11=explode('<!--',$arr1);
        $array21=preg_split('/[{(\",;.:=+)}]/',$array11[1]);

        $buscar_segur1=array_search('wpaMode',$array21);
        $buscar_segur2=array_search('cipherType',$array21);
        $buscar_pass=array_search('wpaPhrase',$array21);

        $obt_segur1=$buscar_segur1+3;
        $obt_segur2=$buscar_segur2+3;
        $obt_pass=$buscar_pass+3;

        $Seguridad1=$array21[$obt_segur1];
        $Seguridad2=$array21[$obt_segur2];
        $Pass1=$array21[$obt_pass];

        $wifi1 = array();
        $wifi2 = array();

        $wifi1["fabricante"]="Hitron";
        $wifi1["ssid1_hitron"]=$SSID1;
        $wifi1["ssid2_hitron"]=$SSID2;
        $wifi1["ssid3_hitron"]=$SSID3;
        $wifi1["ssid4_hitron"]=$SSID4;
        $wifi1["ssid5_hitron"]=$SSID5;
        $wifi1["ssid6_hitron"]=$SSID6;
        $wifi1["ssid7_hitron"]=$SSID7;
        $wifi1["ssid8_hitron"]=$SSID8;
        $wifi1["interface_hitron"]=$Interface1;
        $wifi1["channel_hitron"]=$Channel1;
        $wifi1["bandwidth_hitron"]=$Bandwidth1;
        $wifi1["power_hitron"]="No Registra";
        $wifi1["seguridad1_hitron"]=$Seguridad1;
        $wifi1["seguridad2_hitron"]=$Seguridad2;
        $wifi1["password_hitron"]=$Pass1;

        $wifi2["ssid"]='';


        return array(
            "Wifi" => $wifi1,
            "Wifi5G" => $wifi2
        );

    }

    }


    function obtenerWifiUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_wifi1="http://".$ipaddress."/wlanRadio.asp";
        $url_wifi2="http://".$ipaddress."/wlanPrimaryNetwork.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
           return "Error";
        }else{

        $obtWifi1 = $ingresarCablemodem->getPageUbee2($url_wifi1);
        $obtWifi2 = $ingresarCablemodem->getPageUbee2($url_wifi2);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html1 = new simple_html_dom();
        $html1->load($obtWifi1);

        foreach($html1->find('[name=NMode] [selected]') as $a)
	    $Interface1=$a->getAttribute('value');

        foreach($html1->find('[name=ChannelNumber] [selected]') as $b)
            $Channel1=$b->getAttribute('value');

        $html2 = new simple_html_dom();
        $html2->load($obtWifi2);

        foreach($html2->find('[name=ServiceSetIdentifier]') as $c)
            $SSID1=$c->getAttribute('value');
            
        foreach($html2->find('[name=WpaAuth] [selected]') as $d)
            $Seguridad1=$d->getAttribute('value');
            
        foreach($html2->find('[name=WpaPskAuth] [selected]') as $e)
            $Seguridad2=$e->getAttribute('value');
            
        foreach($html2->find('[name=Wpa2Auth] [selected]') as $f)
            $Seguridad3=$f->getAttribute('value');
            
        foreach($html2->find('[name=Wpa2PskAuth] [selected]') as $g)
            $Seguridad4=$g->getAttribute('value');
            
        foreach($html2->find('[name=WpaEncryption] [selected]') as $h)
            $Seguridad5=$h->getAttribute('value');
            
        foreach($html2->find('[name=WpaPreSharedKey]') as $i)
            $Pass1=$i->getAttribute('value');


        $wifi1 = array();
        $wifi2 = array();

        $wifi1["fabricante"]="Ubee";
        $wifi1["ssid_ubee"]=$SSID1;
        $wifi1["interface_ubee"]=$Interface1;
        $wifi1["channel_ubee"]=$Channel1;
        $wifi1["bandwidth_ubee"]="No Registra";
        $wifi1["power_ubee"]="No Registra";
        $wifi1["seguridad1_ubee"]=$Seguridad1;
        $wifi1["seguridad2_ubee"]=$Seguridad2;
        $wifi1["seguridad3_ubee"]=$Seguridad3;
        $wifi1["seguridad4_ubee"]=$Seguridad4;
        $wifi1["seguridad5_ubee"]=$Seguridad5;
        $wifi1["password_ubee"]=$Pass1;


        $wifi2["ssid"]='';


        return array(
            "Wifi" => $wifi1,
            "Wifi5G" => $wifi2
        );

    }
	

    }


    function obtenerWifiSagem($codCliente,$ipaddress,$fabricante){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifi1="https://".$ipaddress."/wlanRadio.asp";
        $url_wifi2="https://".$ipaddress."/wlanPrimaryNetwork.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);

        if($ingreso=="Error"){
           return "Error";
        }else{

        $obtWifi1 = $ingresarCablemodem->getPageSagem2($url_wifi1);
        $obtWifi2 = $ingresarCablemodem->getPageSagem2($url_wifi2);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);

        $html1 = new simple_html_dom();
        $html1->load($obtWifi1);

        foreach($html1->find('[name=ChannelNumber] [selected]') as $a)
	    $Channel1=$a->getAttribute('value');
	
        foreach($html1->find('[name=OutputPower] [selected]') as $b)
            $Power1=$b->getAttribute('value');

        foreach($html1->find('[name=NBandwidth] [selected]') as $c)
            $Bandwidth1=$c->getAttribute('value');
            

        $html2 = new simple_html_dom();
        $html2->load($obtWifi2);
            
        foreach($html2->find('[name=ServiceSetIdentifier]') as $d)
            $SSID1=$d->getAttribute('value');
            
        foreach($html2->find('[name=WpaAuth] [selected]') as $e)
            $Seguridad1=$e->getAttribute('value');
            
        foreach($html2->find('[name=WpaPskAuth] [selected]') as $f)
            $Seguridad2=$f->getAttribute('value');
            
        foreach($html2->find('[name=Wpa2Auth] [selected]') as $g)
            $Seguridad3=$g->getAttribute('value');
            
        foreach($html2->find('[name=Wpa2PskAuth] [selected]') as $h)
            $Seguridad4=$h->getAttribute('value');
            
        foreach($html2->find('[name=WpaEncryption] [selected]') as $i)
            $Seguridad5=$i->getAttribute('value');

        foreach($html2->find('[name=WpaPreSharedKey]') as $j)
            $Pass1=$j->getAttribute('value');


        $wifi1 = array();
        $wifi2 = array();

        $wifi1["fabricante"]="Sagem";
        $wifi1["ssid_sagem"]=$SSID1;
        $wifi1["interface_sagem"]="No Registra";
        $wifi1["channel_sagem"]=$Channel1;
        $wifi1["bandwidth_sagem"]=$Bandwidth1;
        $wifi1["power_sagem"]=$Power1;
        $wifi1["seguridad1_sagem"]=$Seguridad1;
        $wifi1["seguridad2_sagem"]=$Seguridad2;
        $wifi1["seguridad3_sagem"]=$Seguridad3;
        $wifi1["seguridad4_sagem"]=$Seguridad4;
        $wifi1["seguridad5_sagem"]=$Seguridad5;
        $wifi1["password_sagem"]=$Pass1;
            
        $wifi2["ssid_sagem"]='';

        return array(
            "Wifi" => $wifi1,
            "Wifi5G" => $wifi2
        );

    }

    }


    function obtenerWifiCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_wifi="http://".$ipaddress."/wlanPrimaryNetwork.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtWifi = $ingresarCablemodem->getPageCastlenet1($url_wifi,$login,$codCliente);

        if($obtWifi=="Error"){
           return "Error";
        }else{

        $html = new simple_html_dom();
        $html->load($obtWifi);

        foreach($html->find('[name=ServiceSetIdentifier]') as $a)
	        $ssid=$a->getAttribute('value');

        foreach($html->find('[name=WpaPreSharedKey]') as $b)
            $pass=$b->getAttribute('value');

        foreach($html->find('[name=WpaEncryption] [selected]') as $h)
            $encrip=$h->getAttribute('value');

            $wifi1 = array();
            $wifi2 = array();

            $wifi1["fabricante"]="Castlenet";
            $wifi1["ssid_castle"]=$ssid;
            $wifi1["interface_castle"]="No Registra";
            $wifi1["channel_castle"]="No Registra";
            $wifi1["bandwidth_castle"]="No Registra";
            $wifi1["power_castle"]="No Registra";
            $wifi1["seguridad1_castle"]=$encrip;
            $wifi1["password_castle"]=$pass;

            $wifi2["ssid_castle"]='';

            return array(
                "Wifi" => $wifi1,
                "Wifi5G" => $wifi2
            );

        }
            
    }





















    






}


?>