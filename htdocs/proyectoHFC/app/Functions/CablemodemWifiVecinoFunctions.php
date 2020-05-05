<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemWifiVecinoFunctions {

    function obtenerWifiVecinoAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_wifivecino="https://".$ipaddress."/WifiInsight.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifiVecino = $ingresarCablemodem->getPageAskey2($url_wifivecino);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //Consulta a la pagina de datos de status de Upstream
        $html = new simple_html_dom();
        $html->load($ingreso);

        $html = new simple_html_dom();
        $html->load($obtWifiVecino);

        $a=$html->find('script',6);
        $arr=$a->innertext;

        $array1=explode('a_insight = ',$arr);
        $array2=explode('a_insight',$array1[1]);

        $search1 = array("[","\"","]];");
        $reemplazo1=str_replace($search1,"",trim($array2[0]));

        $search2 = array("],");
        $reemplazo2=str_replace($search2,"=",$reemplazo1);

        $array3=preg_split('/=/',$reemplazo2);

        $modems = array();
        $cantidad = count($array3);

        if($cantidad > 1){
            for ($i=0; $i < $cantidad; $i++) { 
                $separaColumnas=preg_split('/,/',$array3[$i]);

                if($separaColumnas[4]==""){
                    $chanel = "No Registra";
                    $band = "No Registra";
                    $mac = "No Registra";
                }else{
                    $chanel = (int) $separaColumnas[4];
                    $band = $separaColumnas[5];
                    $mac = $separaColumnas[6];
                }

                $modems[$i]['Network']=$separaColumnas[0];
                $modems[$i]['Potencia']=$separaColumnas[1];
                $modems[$i]['RSSI']=$separaColumnas[2];
                $modems[$i]['Seguridad']=$separaColumnas[3];
                $modems[$i]['Chanel']=$chanel;
                $modems[$i]['Bandwidth']=$band;
                $modems[$i]['Mac']=$mac;
            }

            foreach ($modems as $clave => $fila) {
                $chan[$clave] = $fila['Chanel'];
            }

            array_multisort($chan, SORT_DESC, $modems);
        }

        return array(
            "WifiVecino" => $modems
        );

    }

    }


    function obtenerWifiVecinoHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $postScaner = array(
            "dir" => "admin/",
            "file" => "wireless_radar",
            "setRadar" => "scan"
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifivecino1="https://".$ipaddress."/admin/wireless_radar.asp";
        $url_wifivecino2="https://".$ipaddress."/goform/WlsRadar";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifiVecino1 = $ingresarCablemodem->getPageHitron2($url_wifivecino1);
        $obtWifiVecino2 = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtWifiVecino3 = $ingresarCablemodem->getPageHitron2($url_wifivecino1);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtWifiVecino3);

        $a=$html->find('script',5);
        $arr=$a->innertext;

        $reemp1=str_replace("<!-- /*","",$arr);

        $array1=explode('AllSurveylogOrigin = ',$reemp1);
        $array2=explode(' var AllSurveylog',$array1[1]);

        $search1 = array("^\"",";");
        $reemplazo1=str_replace($search1,"",$array2[0]);

        $array3=explode('^',$reemplazo1);

        unset($array3[0]);
        unset($array3[1]);

        $equipo = array();
        $array3=array_values($array3);
        $cantidad = count($array3);

        for ($i=0; $i < $cantidad; $i++) {
            $equipo[$i]=explode(' ',$array3[$i]);
        }

        for ($i=0; $i < $cantidad; $i++) {
            foreach ($equipo[$i] as $key => $value) {
                if ($value=='' or $value==' ') {
                    unset($equipo[$i][$key]);
                }
            }
        }

        $equipo=array_map('array_values', $equipo);

        $modems = array();

        $cantidad3 = count($equipo);
        for ($i=0; $i < $cantidad3; $i++) { 
            if(count($equipo[$i])==10){
                $modems[$i]['Network']=$equipo[$i][1]." ".$equipo[$i][2];
                $modems[$i]['Potencia']="No Registra";
                $modems[$i]['RSSI']=$equipo[$i][5];
                $modems[$i]['Seguridad']=$equipo[$i][4];
                $modems[$i]['Chanel']=(int) $equipo[$i][0];
                $modems[$i]['Bandwidth']="No Registra";
                $modems[$i]['Mac']=$equipo[$i][3];
            }elseif (count($equipo[$i])==11) {
                $modems[$i]['Network']=$equipo[$i][1]." ".$equipo[$i][2]." ".$equipo[$i][3];
                $modems[$i]['Potencia']="No Registra";
                $modems[$i]['RSSI']=$equipo[$i][6];
                $modems[$i]['Seguridad']=$equipo[$i][5];
                $modems[$i]['Chanel']=(int) $equipo[$i][0];
                $modems[$i]['Bandwidth']="No Registra";
                $modems[$i]['Mac']=$equipo[$i][4];
            }else{
                $modems[$i]['Network']=$equipo[$i][1];
                $modems[$i]['Potencia']="No Registra";
                $modems[$i]['RSSI']=$equipo[$i][4];
                $modems[$i]['Seguridad']=$equipo[$i][3];
                $modems[$i]['Chanel']=(int) $equipo[$i][0];
                $modems[$i]['Bandwidth']="No Registra";
                $modems[$i]['Mac']=$equipo[$i][2];
            }
        }


        return array(
            "WifiVecino" => $modems
        );

    }

    }



    function obtenerWifiVecinoUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $postFields_Scan = array(
            "restoreWirelessDefaults" => "0",
            "commitwlanRadio" => "0",
            "scanActions" => "1"
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_wifivecino1="http://".$ipaddress."/wlanRadio.asp";
        $url_wifivecino2="http://".$ipaddress."/goform/wlanRadio";
        $url_popup="http://".$ipaddress."/wlanScanPopup.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifiVecino1 = $ingresarCablemodem->getPageUbee2($url_wifivecino1);
        $obtWifiVecino2 = $ingresarCablemodem->getPageUbee1($url_wifivecino2,$postFields_Scan);
        $obtWifiVecino3 = $ingresarCablemodem->getPageUbee2($url_popup);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtWifiVecino3);

        $dataTabla = array();
        
        $tablaup = $html->find('table', 1);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla[] = $rowData1;
        }

        $modems = array();
        $cantidad = count($dataTabla);


        for ($i=0; $i < $cantidad; $i++) { 
            if (count($dataTabla[$i])>0){
                $modems[$i]['Network']=$dataTabla[$i][0];
                $modems[$i]['Potencia']="No Registra";
                $modems[$i]['RSSI']=$dataTabla[$i][4];
                $modems[$i]['Seguridad']=$dataTabla[$i][1];
                $modems[$i]['Chanel']=$dataTabla[$i][5];
                $modems[$i]['Bandwidth']=$dataTabla[$i][6];
                $modems[$i]['Mac']=$dataTabla[$i][7];
            }
        }

        foreach ($modems as $clave => $fila) {
            $chan[$clave] = $fila['Chanel'];
        }
        
        array_multisort($chan, SORT_DESC, $modems);

        return array(
            "WifiVecino" => $modems
        );

    }

    }


    function obtenerWifiVecinoSagem($codCliente,$ipaddress,$fabricante){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );
        
        $postFields_Scan = array(
            "restoreWirelessDefaults" => "0",
            "commitwlanRadio" => "0",
            "scanActions" => "1"
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_wifivecino1="https://".$ipaddress."/wlanRadio.asp";
        $url_wifivecino2="https://".$ipaddress."/goform/wlanRadio";
        $url_popup="https://".$ipaddress."/wlanScanPopup.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);

        if($ingreso=="Error"){
            return "Error";
        }else{

        $obtWifiVecino1 = $ingresarCablemodem->getPageSagem2($url_wifivecino1);
        $obtWifiVecino2 = $ingresarCablemodem->getPageSagem1($url_wifivecino2,$postFields_Scan);
        $obtWifiVecino3 = $ingresarCablemodem->getPageSagem2($url_popup);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtWifiVecino3);

        $dataTabla = array();
        
        $tablaup = $html->find('table', 1);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla[] = $rowData1;
        }

        $modems = array();
        $cantidad = count($dataTabla);

        for ($i=0; $i < $cantidad; $i++) { 
            if (count($dataTabla[$i])>0){
                        $modems[$i]['Network']=$dataTabla[$i][0];
                        $modems[$i]['Potencia']="No Registra";
                        $modems[$i]['RSSI']=$dataTabla[$i][4];
                        $modems[$i]['Seguridad']=$dataTabla[$i][1];
                        $modems[$i]['Chanel']=$dataTabla[$i][5];
                        $modems[$i]['Bandwidth']="No Registra";
                        $modems[$i]['Mac']=$dataTabla[$i][6];
            }
        }

        foreach ($modems as $clave => $fila) {
            $chan[$clave] = $fila['Chanel'];
        }
        
        array_multisort($chan, SORT_DESC, $modems);

        return array(
            "WifiVecino" => $modems
        );

    }

    }


    function obtenerWifiVecinoCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $postFields_Scan = array(
            "restoreWirelessDefaults" => "0",
            "commitwlanRadio" => "0",
            "scanActions" => "1"
        );

        $url_wifi="http://".$ipaddress."/goform/wlanRadio";
        $url_popup="http://".$ipaddress."/wlanScanPopup.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtWifiVecino1 = $ingresarCablemodem->getPageCastlenet2($url_wifi,$login,$codCliente,$postFields_Scan);

        if($obtWifiVecino1=="Error"){
            return "Error";
        }else{

        $obtWifiVecino2 = $ingresarCablemodem->getPageCastlenet1($url_popup,$login,$codCliente);


        $html = new simple_html_dom();
        $html->load($obtWifiVecino2);

        $dataTabla = array();
        
        $tablaup = $html->find('table', 1);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla[] = $rowData1;
        }

        $modems = array();
        $cantidad = count($dataTabla);

        $chan = array();

        for ($i=0; $i < $cantidad; $i++) { 
            if (count($dataTabla[$i])>0){
                        $modems[$i]['Network']=$dataTabla[$i][0];
                        $modems[$i]['Potencia']="No Registra";
                        $modems[$i]['RSSI']=$dataTabla[$i][4];
                        $modems[$i]['Seguridad']=$dataTabla[$i][1];
                        $modems[$i]['Chanel']=$dataTabla[$i][5];
                        $modems[$i]['Bandwidth']="No Registra";
                        $modems[$i]['Mac']=$dataTabla[$i][6];
            }
        }
        
        foreach ($modems as $clave => $fila) {
            $chan[$clave] = $fila['Chanel'];
        }
        
        array_multisort($chan, SORT_DESC, $modems);

        return array(
            "WifiVecino" => $modems
        );

    }

    }


//Cierre fin

}

?>

