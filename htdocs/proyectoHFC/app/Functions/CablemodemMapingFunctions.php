<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemMapingFunctions {


    function obtenerMapingAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );


        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_lan="https://".$ipaddress."/RgSetup.asp";
        $url_wifi="https://".$ipaddress."/PortMapping.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        }else {
            $obtLan = $ingresarCablemodem->getPageAskey2($url_lan);
            $obtWifi = $ingresarCablemodem->getPageAskey2($url_wifi);
            $logout = $ingresarCablemodem->getPageAskey2($url_logout);

            $html1 = new simple_html_dom();
            $html1->load($obtLan);

            $buscar=$html1->find('script',6);
            $arr=$buscar->innertext;

            $array1=explode('<div',$arr);
            $array2=preg_split('/[{(\" ,;:=+)}]/',$array1[0]);

            $buscarIp=array_search('ethIpAddress',$array2);
            $obtIp=$buscarIp+4;
            $ipEthernet=$array2[$obtIp];

            $parametros=explode(".", $ipEthernet);
            $ipLan1=$parametros[0];
            $ipLan2=$parametros[1];
            $ipLan3=$parametros[2];

            /////////////////////////////////////////////////////////////////

            $html2 = new simple_html_dom();
            $html2->load($obtWifi);

            foreach($html2->find('.table-row') as $a){
                $SSID1[]=$a->innertext;
            }

            array_splice($SSID1, 0,2);
            array_splice($SSID1, -4);
            $cantidadMaping = count($SSID1);

            $arrayMaping=array();

            if ($cantidadMaping>0) {
                for ($i=0; $i < $cantidadMaping ; $i++) { 
                    $esRango=explode('-',$SSID1[$i]);
                    $rango=count($esRango);
                    if ($rango==25) {
                        $separar=preg_split('/[{(\",;:<=>+)}]/',$SSID1[$i]);
                        $buscarServiceName=array_search("fServiceName$i",$separar);
                        $buscaIp=array_search("fLanIP$i",$separar);
                        $buscarProtocolo=array_search("fProtocol$i",$separar);
                        $buscarPuertoPrivado1=array_search("fLanPortS$i",$separar);
                        $buscarPuertoPrivado2=array_search("fLanPortE$i",$separar);
                        $buscarPuertoPublico1=array_search("fPublicPortS$i",$separar);
                        $buscarPuertoPublico2=array_search("fPublicPortE$i",$separar);
                        $nombreNuevo=$separar[$buscarServiceName-3];
                        $nombreIp=$separar[$buscaIp-3];
                        $nombreProtocolo=$separar[$buscarProtocolo-3];
                        $nombrePuertoPrivado1=$separar[$buscarPuertoPrivado1-3];
                        $nombrePuertoPrivado2=$separar[$buscarPuertoPrivado2-3];
                        $nombrePuertoPublico1=$separar[$buscarPuertoPublico1-3];
                        $nombrePuertoPublico2=$separar[$buscarPuertoPublico2-3];
                        $registro=$nombreNuevo."|".$nombreIp."|".$nombreProtocolo."|".$nombrePuertoPrivado1."|".$nombrePuertoPrivado2."|".$nombrePuertoPublico1."|".$nombrePuertoPublico2;
                        array_push($arrayMaping,$registro);
		            } else {
                        $separar=preg_split('/[{(\",;:<=>+)}]/',$SSID1[$i]);
                        $buscarServiceName=array_search("fServiceName$i",$separar);
                        $buscaIp=array_search("fLanIP$i",$separar);
                        $buscarProtocolo=array_search("fProtocol$i",$separar);
                        $buscarPuertoPrivado1=array_search("fLanPortS$i",$separar);
                        $buscarPuertoPublico1=array_search("fPublicPortS$i",$separar);
                        $nombreNuevo=$separar[$buscarServiceName-3];
                        $nombreIp=$separar[$buscaIp-3];
                        $nombreProtocolo=$separar[$buscarProtocolo-3];
                        $nombrePuertoPrivado1=$separar[$buscarPuertoPrivado1-3];
                        $nombrePuertoPublico1=$separar[$buscarPuertoPublico1-3];
                        $registro=$nombreNuevo."|".$nombreIp."|".$nombreProtocolo."|".$nombrePuertoPrivado1."|".$nombrePuertoPublico1;
                        array_push($arrayMaping,$registro);
		            }
	            }	
            } 
            
            /*else {
                $mensaje = "No hay registros";
                array_push($arrayMaping,$mensaje);
            }*/


            return array(
                "IpLan1" => $ipLan1,
                "IpLan2" => $ipLan2,
                "IpLan3" => $ipLan3,
                "Maping" => $arrayMaping
            );


        }


    }


    function obtenerMapingHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_maping1="https://".$ipaddress."/admin/feat-firewall-port-forward.asp";
        $url_maping2="https://".$ipaddress."/admin/feat-lan-ip.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else {

            $obtMaping1 = $ingresarCablemodem->getPageHitron2($url_maping1);
            $obtMaping2 = $ingresarCablemodem->getPageHitron2($url_maping2);
            $logout = $ingresarCablemodem->getPageHitron2($url_logout);

            $html2 = new simple_html_dom();
            $html2->load($obtMaping2);

            $c=$html2->find('script',5);
            $arr1=$c->innertext;

            $array10=explode('<!--',$arr1);
            $array20=preg_split('/[{\";|=+}]/',$array10[0]);

            $buscarIp=array_search(' var LanIpInfoBase ',$array20);
            $obtIp=$buscarIp+2;

            $parametros=explode(".", $array20[$obtIp]);

            $ipLan1=$parametros[0];
            $ipLan2=$parametros[1];
            $ipLan3=$parametros[2];

            $html1 = new simple_html_dom();
            $html1->load($obtMaping1);

            $a=$html1->find('script',5);
            $arr=$a->innertext;

            $array1=explode('<!--',$arr);
            $array2=preg_split('/[{\";|=+}]/',$array1[0]);

            $buscarService=array_search('serviceCgiBase',$array2);

            $obtPortMaping=$buscarService+2;
            $arrayMaping = array();

            $PortMaping=$array2[$obtPortMaping];

            for ($i=0; $i < 20 ; $i++) { 

                $PortMaping=$array2[$obtPortMaping+$i];

                if($PortMaping<>""){

                    $arrayMaping[$i]=$PortMaping;

                    //array_push($arrayMaping[$i]=$PortMaping,$arrayMaping);
                }else{
                    break;
                }
            }

            return array(
                "IpLan1" => $ipLan1,
                "IpLan2" => $ipLan2,
                "IpLan3" => $ipLan3,
                "Maping" => $arrayMaping
            );

        }

    }


    function obtenerMapingUbee($codCliente,$ipaddress,$fabricante){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_maping1="http://".$ipaddress."/RgForwarding.asp";
        $url_maping2="http://".$ipaddress."/RgSetup.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
        }else {

            $obtMaping1 = $ingresarCablemodem->getPageUbee2($url_maping1);
            $obtMaping2 = $ingresarCablemodem->getPageUbee2($url_maping2);
            $logout = $ingresarCablemodem->getPageUbee2($url_logout);

            $html = new simple_html_dom();
            $html->load($obtMaping1);

            $dataTabla1 = array();

            $tablaup = $html->find('table', 5);

            foreach($tablaup->find('tr') as $row1) {
                $rowData1 = array();
                foreach($row1->find('td') as $cell1) {
                    $rowData1[] = $cell1->innertext;
                }
                $dataTabla1[] = $rowData1;
            }

            $arrayMaping = array();
            $registro = 0;

            $cantidad = count($dataTabla1);

            if($cantidad>2){
                for ($i=2; $i < $cantidad; $i++) { 
                    if (count($dataTabla1[$i])>0){
                            $registro += 1;
                            $arrayMaping[] = array(
                                'Registro' => $registro,
                                'ipPrivada' => $dataTabla1[$i][0],
                                'rangoPrivada1' => $dataTabla1[$i][1],
                                'rangoPrivada2' => $dataTabla1[$i][2],
                                'ipPublica' => $dataTabla1[$i][3],
                                'rangoPublica1' => $dataTabla1[$i][4],
                                'rangoPublica2' => $dataTabla1[$i][5],
                                'protocolo' => $dataTabla1[$i][6],
                                'nombre' => $dataTabla1[$i][7]
                        );
                    }
                }
            }

            //dd($dataTabla1);

            $html2 = new simple_html_dom();
            $html2->load($obtMaping2);

            foreach($html2->find('[name=LocalIpAddressIP0]') as $z)
                $ipLan1=$z->getAttribute('value');

            foreach($html2->find('[name=LocalIpAddressIP1]') as $z)
                $ipLan2=$z->getAttribute('value');

            foreach($html2->find('[name=LocalIpAddressIP2]') as $z)
                $ipLan3=$z->getAttribute('value');


                return array(
                    "IpLan1" => $ipLan1,
                    "IpLan2" => $ipLan2,
                    "IpLan3" => $ipLan3,
                    "Maping" => $arrayMaping
                );

        }

    }


    function obtenerMapingCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_maping="http://".$ipaddress."/RgForwarding.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtMaping = $ingresarCablemodem->getPageCastlenet1($url_maping,$login,$codCliente);

        if($obtMaping=="Error"){
            return "Error";
        }else {
                
            $html = new simple_html_dom();
            $html->load($obtMaping);

            $dataTabla1 = array();

            $tablaup = $html->find('table', 2);

            foreach($tablaup->find('tr') as $row1) {
                $rowData1 = array();
                foreach($row1->find('td') as $cell1) {
                    $rowData1[] = $cell1->innertext;
                }
                $dataTabla1[] = $rowData1;
            }

            $arrayMaping = array();
            $registro = 0;

            $cantidad = count($dataTabla1);

            //dd($dataTabla1);

            if($cantidad>2){
                for ($i=2; $i < $cantidad; $i++) { 
                    if (count($dataTabla1[$i])>0){
                            $registro += 1;
                            $arrayMaping[] = array(
                                'Registro' => $registro,
                                'ipPrivada' => $dataTabla1[$i][0],
                                'rangoPrivada1' => $dataTabla1[$i][1],
                                'rangoPrivada2' => $dataTabla1[$i][2],
                                'ipPublica' => "No Registra",
                                'rangoPublica1' => $dataTabla1[$i][3],
                                'rangoPublica2' => $dataTabla1[$i][4],
                                'protocolo' => $dataTabla1[$i][5],
                                'nombre' => $dataTabla1[$i][6]
                        );
                    }
                }
            }

            return array(
                "IpLan1" => "192",
                "IpLan2" => "168",
                "IpLan3" => "1",
                "Maping" => $arrayMaping
            );

        }

    }





}

?>