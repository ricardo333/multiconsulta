<?php
namespace App\Functions;

use DB;
use App\Library\simple_html_dom;
use App\Functions\CablemodemFunctions;

class CablemodemDhcpFunctions {


    function obtenerDhcpAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );
        
        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_dhcp="https://".$ipaddress."/WifiClients.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else {
            
        $obtDhcp = $ingresarCablemodem->getPageAskey2($url_dhcp);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //Consulta a la pagina de datos de status de Upstream
        $html = new simple_html_dom();
        $html->load($ingreso);

        $ether=array();
        $wifii=array();

        foreach($html->find('#WifiList .rowMACAddress') as $a){
            $wifii[]=$a->innertext;
        }
        
        foreach($html->find('#EthList .rowMACAddress') as $b){
            $ether[]=$b->innertext;
        }	

        $count1=count($ether);
        $count2=count($wifii);

        $ethernet = array();
        $wifi = array();

        if($count1>0){
            for($i=0;$i<$count1;$i++){
                $replace1=str_replace("<br>","/",$ether[$i]);
                $separa1=explode("/",$replace1);

                $ethernet[$i]["host"] = $separa1[0];
                $ethernet[$i]["interface"] = "Ethernet";
                $ethernet[$i]["mac"] = strtoupper("$separa1[2]");
                $ethernet[$i]["ipaddress"] = $separa1[1];
            }
        }

        if($count2>0){

            $html2 = new simple_html_dom();
            $html2->load($obtDhcp);
            //-----------------------------------------------------------------//
            $a2=$html2->find('script',6);
            $arr2=$a2->innertext;
            $reemp1=str_replace(",","",$arr2);
            $reemp2=str_replace("0%^","",$reemp1);
            $arrayB1=explode('/, /',$reemp2);
            $arrayB2=preg_split('/[{ }\"|=;]+/',$arrayB1[0]);
            //-----------------------------------------------------------------//
            
            for($i=0;$i<$count2;$i++){
                $replace2=str_replace("<br>","/",$wifii[$i]);
                $separa2=explode("/",$replace2);
                $macWifi=strtoupper("$separa2[2]");
                $indice=array_search(trim($macWifi),$arrayB2)+3;
                $nivel=(int)$arrayB2[$indice];

                if($nivel>=-50){
                    $color="#04b45f";
                }elseif($nivel<-50 and $nivel>=-60){
                    $color="#04b45f";
                }elseif($nivel<-60 and $nivel>=-70){
                    $color="orange";
                }elseif($nivel<-70){
                    $color="red";
                }
                
                $wifi[$i]["host"] = $separa2[0];
                $wifi[$i]["interface"] = "Wifi";
                $wifi[$i]["mac"] = strtoupper("$separa2[2]");
                $wifi[$i]["ipaddress"] = $separa2[1];
                $wifi[$i]["nivel"] = $nivel;
                $wifi[$i]["color"] = $color;
            }

        }

        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }


    function obtenerDhcpAskey3($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );
        
        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_dhcp="https://".$ipaddress."/WifiClients.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else {
            
        $obtDhcp = $ingresarCablemodem->getPageAskey2($url_dhcp);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //Consulta a la pagina de datos de status de Upstream
        $html = new simple_html_dom();
        $html->load($ingreso);

        $ether=array();
        $wifii=array();

        foreach($html->find('#WifiList .rowMACAddress') as $a){
            $wifii[]=$a->innertext;
        }

        foreach($html->find('#EthList .rowMACAddress') as $b){
            $ether[]=$b->innertext;
        }	

        $count1=count($ether);
        $count2=count($wifii);

        $ethernet = array();
        $wifi = array();

        if($count1>0){
            for($i=0;$i<$count1;$i++){
                $replace1=str_replace("<br>","/",$ether[$i]);
                $separa1=explode("/",$replace1);
    
                $ethernet[$i]["host"] = $separa1[0];
                $ethernet[$i]["interface"] = "Ethernet";
                $ethernet[$i]["mac"] = strtoupper("$separa1[2]");
                $ethernet[$i]["ipaddress"] = $separa1[1];
            }
        }

        if($count2>0){

            $html2 = new simple_html_dom();
            $html2->load($obtDhcp);

            foreach($html2->find('div.table-col') as $a){
                $datos[]=$a->innertext;
            }

            $reemplazar1=str_replace(" ","",$datos);
            $reemplazar2=str_replace("&nbsp;","",$reemplazar1);

            for($i=0;$i<$count2;$i++){
                $replace2=str_replace("<br>","/",$wifii[$i]);
                $separa2=explode("/",$replace2);
                $macWifi=strtoupper("$separa2[2]");
                $indice=array_search(trim($macWifi),$reemplazar2)+2;
                $nivel=(int)$reemplazar2[$indice];

                if($nivel>=-50){
                    $color="#04b45f";
                }elseif($nivel<-50 and $nivel>=-60){
                    $color="#04b45f";
                }elseif($nivel<-60 and $nivel>=-70){
                    $color="orange";
                }elseif($nivel<-70){
                    $color="red";
                }

                $wifi[$i]["host"] = $separa2[0];
                $wifi[$i]["interface"] = "Wifi";
                $wifi[$i]["mac"] = strtoupper("$separa2[2]");
                $wifi[$i]["ipaddress"] = $separa2[1];
                $wifi[$i]["nivel"] = $nivel;
                $wifi[$i]["color"] = $color;

            }

        }

        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }


    function obtenerDhcpHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_dhcp1="https://".$ipaddress."/admin/feat-lan-ip.asp";
        $url_dhcp2="https://".$ipaddress."/admin/wireless_signal.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtDhcp1 = $ingresarCablemodem->getPageHitron2($url_dhcp1);
        $obtDhcp2 = $ingresarCablemodem->getPageHitron2($url_dhcp2);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html1 = new simple_html_dom();
        $html1->load($obtDhcp1);

        $ethernet = array();
        $wifi = array();

        $a1=$html1->find('script',5);
        $arr1=$a1->innertext;
        $arrayA1=explode('/, /',$arr1);
        $arrayA2=preg_split('/[{}\"|=;]+/',$arrayA1[0]);

        $buscar_inicio=array_search(' var CpeInfoBase ',$arrayA2);
        $buscar_termino=array_search(' var IPv6CpeInfoBase ',$arrayA2);
        $inicio=$buscar_inicio+2;


        $html2 = new simple_html_dom();
        $html2->load($obtDhcp2);

        $a2=$html2->find('script',5);
        $arr2=$a2->innertext;
        $reemp1=str_replace("<!--","",$arr2);
        $reemp2=str_replace("0%^","",$reemp1);
        $arrayB1=explode('/, /',$reemp2);
        $arrayB2=preg_split('/[{ }\"|=;]+/',$arrayB1[0]);

        for($i=$inicio;$i<$buscar_termino;$i++){
            $array13=explode(',',$arrayA2[$i],7);

            if($array13[4]=="cpmac" and $array13[5]=="active"){

                $mac=str_replace(".",":",$array13[2]);
                $ethernet[$i]["host"] = $array13[1];
                $ethernet[$i]["interface"] = "Ethernet";
                $ethernet[$i]["mac"] = $mac;
                $ethernet[$i]["ipaddress"] = $array13[0];

            }elseif($array13[4]=="wlan" and $array13[5]=="active") {

                $mac=str_replace(".",":",$array13[2]);
                $indice=array_search($mac,$arrayB2)+6;
                $nivel=(int)$arrayB2[$indice];

                if($nivel>=-50){
                    $color="#04b45f";
                }elseif($nivel<-50 and $nivel>=-60){
                    $color="#04b45f";
                }elseif($nivel<-60 and $nivel>=-70){
                    $color="orange";
                }elseif($nivel<-70){
                    $color="red";
                }

                $wifi[$i]["host"] = $array13[1];
                $wifi[$i]["interface"] = "Wifi";
                $wifi[$i]["mac"] = $mac;
                $wifi[$i]["ipaddress"] = $array13[0];
                $wifi[$i]["nivel"] = $nivel;
                $wifi[$i]["color"] = $color;

            }

        }

        $ethernet = array_values($ethernet);
        $wifi = array_values($wifi);

        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }


    function obtenerDhcpUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_ethernet="http://".$ipaddress."/RgDhcp.asp";
        $url_wifi="http://".$ipaddress."/wlanAccess.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtEthernet = $ingresarCablemodem->getPageUbee2($url_ethernet);
        $obtWifi = $ingresarCablemodem->getPageUbee2($url_wifi);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtEthernet);

        $html2 = new simple_html_dom();
        $html2->load($obtWifi);

        $dataTabla1 = array();
        $dataTabla2 = array();
                
        $tabla1 = $html->find('table', 6);
        $tabla2 = $html2->find('table', 6);

        foreach($tabla1->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }
        
        
        foreach($tabla2->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->innertext;
            }
            $dataTabla2[] = $rowData2;
        }

        $ethernet = array();
        $wifi = array();

        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);


        for ($i=0; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>0){
                if ($dataTabla1[$i][1]=="Ethernet") {
                    $registro1 += 1;
                    $ethernet[] = array(
                        'host' => $dataTabla1[$i][0],
                        'interface' => "Ethernet",
                        'mac' => $dataTabla1[$i][2],
                        'ipaddress' => $dataTabla1[$i][3]
                    );
                           
                }
            }
        }
        
        
        
        for ($i=1; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                    $registro1 += 1;

                    $nivel = (int)$dataTabla2[$i][2];
			
                    if($nivel>=-50){
                        $color="#04b45f";
                    }elseif($nivel<-50 and $nivel>=-60){
                        $color="#04b45f";
                    }elseif($nivel<-60 and $nivel>=-70){
                        $color="orange";
                    }elseif($nivel<-70){
                        $color="red";
                    }

                    $wifi[] = array(
                        'host' => $dataTabla2[$i][4],
                        'interface' => "Wifi",
                        'mac' => $dataTabla2[$i][0],
                        'ipaddress' => $dataTabla2[$i][3],
                        'nivel' => $nivel,
                        'color' => $color
                    );
            }
        }


        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }



    function obtenerDhcpSagem($codCliente,$ipaddress,$fabricante){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_ethernet="https://".$ipaddress."/RgDhcp.asp";
        $url_wifi="https://".$ipaddress."/wlanAccess.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtEthernet = $ingresarCablemodem->getPageSagem2($url_ethernet);
        $obtWifi = $ingresarCablemodem->getPageSagem2($url_wifi);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtEthernet);

        $html2 = new simple_html_dom();
        $html2->load($obtWifi);

        $dataTabla1 = array();
        $dataTabla2 = array();
                
        $tabla1 = $html->find('table', 11);
        $tabla2 = $html2->find('table', 3);

        foreach($tabla1->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }

        foreach($tabla2->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->innertext;
            }
            $dataTabla2[] = $rowData2;
        }

        $ethernet = array();
        $wifi = array();

        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);


        for ($i=0; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>0){
                if ($dataTabla1[$i][3]<>"Duration") {
                    $registro1 += 1;
                    $ethernet[] = array(
                        'host' => "Unknonw",
                        'interface' => "Ethernet",
                        'mac' => $dataTabla1[$i][0],
                        'ipaddress' => $dataTabla1[$i][1]
                    );     
                }
            }
        }



        for ($i=1; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                if ($dataTabla1[$i][5]<>"Mode") {
                    $registro1 += 1;

                    $nivel = (int)$dataTabla2[$i][2];
                    
                    if($nivel>=-50){
                        $color="#04b45f";
                    }elseif($nivel<-50 and $nivel>=-60){
                        $color="#04b45f";
                    }elseif($nivel<-60 and $nivel>=-70){
                        $color="orange";
                    }elseif($nivel<-70){
                        $color="red";
                    }

                    $wifi[] = array(
                        'host' => $dataTabla2[$i][4],
                        'interface' => "Wifi",
                        'mac' => $dataTabla2[$i][0],
                        'ipaddress' => $dataTabla2[$i][3],
                        'nivel' => $nivel,
                        'color' => $color
                    );
                }
            }
        }


        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }



    function obtenerDhcpCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_router="http://".$ipaddress;
        $url_ethernet="http://".$ipaddress."/RgDhcp.asp";
        $url_wifi="http://".$ipaddress."/wlanAccess.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtEthernet = $ingresarCablemodem->getPageCastlenet1($url_ethernet,$login,$codCliente);

        if($obtEthernet=="Error"){
            return "Error";
        }else {

        $obtWifi = $ingresarCablemodem->getPageCastlenet1($url_wifi,$login,$codCliente);

        $html = new simple_html_dom();
        $html->load($obtEthernet);

        $html2 = new simple_html_dom();
        $html2->load($obtWifi);

        $dataTabla1 = array();
        $dataTabla2 = array();
                
        $tabla1 = $html->find('table', 3);
        $tabla2 = $html2->find('table', 2);

        foreach($tabla1->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }
        
        
        foreach($tabla2->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->plaintext;
            }
            $dataTabla2[] = $rowData2;
        }

        $ethernet = array();
        $wifi = array();

        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);


        for ($i=1; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>1){
                    $registro1 += 1;
                    $ethernet[] = array(
                        'host' => "Unknonw",
                        'interface' => "Ethernet",
                        'mac' => $dataTabla1[$i][0],
                        'ipaddress' => $dataTabla1[$i][1]
                    );     
            }
        }

        for ($i=1; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                    $registro1 += 1;
        
                    $nivel = (int)$dataTabla2[$i][2];
                    
                    if($nivel>=-50){
                        $color="#04b45f";
                    }elseif($nivel<-50 and $nivel>=-60){
                        $color="#04b45f";
                    }elseif($nivel<-60 and $nivel>=-70){
                        $color="orange";
                    }elseif($nivel<-70){
                        $color="red";
                    }
        
        
                    $wifi[] = array(
                        'host' => $dataTabla2[$i][4],
                        'interface' => "Wifi",
                        'mac' => $dataTabla2[$i][0],
                        'ipaddress' => $dataTabla2[$i][3],
                        'nivel' => $nivel,
                        'color' => $color
                    );
            }
        }

        return array(
            "Ethernet" => $ethernet,
            "Wifi" => $wifi
        );

    }

    }

  








//Cierre fin

}

?>


