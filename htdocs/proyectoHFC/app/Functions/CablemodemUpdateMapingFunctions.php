<?php
namespace App\Functions;

use DB;
use App\Library\simple_html_dom;

class CablemodemUpdateMapingFunctions {


    function updateMapingAskey($codCliente,$ipaddress,$fabricante,$maping)
    {
        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        //$tabla = json_decode($myTableArray);
        $tabla = json_decode($maping);

        $postFields_Port = array();

        //dd($tabla);

        for ($i=0; $i < count($tabla); $i++) {

            $esRango=explode('-',$tabla[$i][3]);
            $cantidad=count($esRango);

            $array1 = "fServiceName".$i;
            $array2 = "fLanIP".$i;
            $array3 = "fProtocol".$i;
            $array4 = "fLanPortS".$i;
            $array5 = "fPublicPortS".$i;
            $array6 = "fRuleEnable".$i;

            if($cantidad==1){
                
                $postFields_Port[$array1] = $tabla[$i][0];
                $postFields_Port[$array2] = $tabla[$i][1];
                $postFields_Port[$array3] = $tabla[$i][2];
                $postFields_Port[$array4] = $tabla[$i][3];
                $postFields_Port[$array5] = $tabla[$i][4];
                $postFields_Port[$array6] = "0";

            }else{

                $array7 = "fLanPortE".$i;
                $array8 = "fPublicPortE".$i;

                $postFields_Port[$array1] = $tabla[$i][0];
                $postFields_Port[$array2] = $tabla[$i][1];
                $postFields_Port[$array3] = $tabla[$i][2];
                $postFields_Port[$array4] = $esRango[0];
                $postFields_Port[$array7] = $esRango[1];
                $postFields_Port[$array5] = $esRango[0];
                $postFields_Port[$array8] = $esRango[1];
                $postFields_Port[$array6] = "0";

            }
        }

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_maping1="https://".$ipaddress."/PortMapping.asp";
        $url_cambio="https://".$ipaddress."/goform/AskPortMapping";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $obtWifi = $ingresarCablemodem->getPageAskey2($url_maping1);
        $updateWifi = $ingresarCablemodem->getPageAskey1($url_cambio,$postFields_Port);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;


        return $mensaje;

    }


    function updateMapingHitron($codCliente,$ipaddress,$fabricante,$maping)
    {
        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $tabla = json_decode($maping);
        $postFields_Port = array();


        if(count($tabla)==0){

            $postFields_Port["dir"] = "admin/";
            $postFields_Port["file"] = "feat-firewall-port-forward";
            $postFields_Port["ServiceAPrivate"] = "1";
            $postFields_Port["enableServiceA0"] = "on";
            
        }else{

            $postFields_Port["dir"] = "admin/";
            $postFields_Port["file"] = "feat-firewall-port-forward";
            $postFields_Port["ServiceAPrivate"] = "1";
        
            for ($i=0; $i < count($tabla); $i++) {
        
                $serviceName=$tabla[$i][0];
        
                if($tabla[$i][2]=="TCP"){
                    $protocolo="1";
                }elseif ($tabla[$i][2]=="UDP") {
                    $protocolo="2";
                }elseif ($tabla[$i][2]=="TCP/UDP") {
                    $protocolo="3";
                }
        
                $opcion1="2";
                $lanIp1=$tabla[$i][1];
                $lanIp2=$tabla[$i][1];
                $puerto1=$tabla[$i][3];
                $puerto2=$tabla[$i][4];
                $puerto3=$tabla[$i][3];
                $puerto4=$tabla[$i][3];
                $ipDefault1="0.0.0.0";
                $ipDefault2="0.0.0.0";
                $opcion2="1";
                $opcion3="1";
        
                $registro=$serviceName.",".$protocolo.",".$opcion1.",".$lanIp1.",".$lanIp2.",".$puerto1.",".$puerto2.",".$puerto3.",".
                          $puerto4.",".$ipDefault1.",".$ipDefault2.",".$opcion2.",".$opcion3;

                $service = "Service".$i;
                $postFields_Port[$service] = $registro;
        
            }
            
            $postFields_Port["enableServiceA0"]="on";
        
            for ($i=0; $i < count($tabla); $i++) {
        
                $codRegistro= $i+1;
                $enService = "enableServiceA".$codRegistro;
                $postFields_Port[$enService]="on";
        
            }
        
        }


        $url_router="https://".$ipaddress."/goform/login";
        $url_wifi="https://".$ipaddress."/admin/feat-firewall-port-forward.asp";
        $url_cambio="https://".$ipaddress."/goform/Firewall"; 
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtMaping = $ingresarCablemodem->getPageHitron2($url_wifi);
        $updateMaping = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Port);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function updateMapingUbee($codCliente,$ipaddress,$fabricante,$maping)
    {
        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );
        
        $postFields_Nuevo = array(
            "PortForwardingCreateRemove" => "1",
            "PortForwardingTable" => "0"
        );
        
        $postFields_AllDelete = array(
        "PortForwardingCreateRemove" => "4",
        "PortForwardingTable" => "0"
        );

        $tabla = json_decode($maping);
        $postFields_Port = array();

        $url_router="http://".$ipaddress."/goform/login";
        $url_wifi="http://".$ipaddress."/RgForwarding.asp";
        $url_cambio="http://".$ipaddress."/goform/RgForwarding"; 
        $url_logout="http://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtWifi1 = $ingresarCablemodem->getPageUbee2($url_wifi);
        $updateMaping1 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_AllDelete);


        if(count($tabla)>0){

            for ($i=0; $i < count($tabla); $i++) {
        
                if($tabla[$i][2]=="TCP"){
                    $protocol="4";
                }elseif($tabla[$i][2]=="UDP"){
                    $protocol="3";
                }elseif($tabla[$i][2]=="TCP/UDP"){
                    $protocol="254";
                }
        
                $esRango1=explode('-',$tabla[$i][3]);
                $esRango2=explode('-',$tabla[$i][4]);
                $cantidad=count($esRango1);
        
                if($cantidad==1){

                    $updateMaping2 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Nuevo);
                    $postFields_Port[$i]["PortForwardingCreateRemove"]="0";
                    $postFields_Port[$i]["PortForwardingLocalIp"]=$tabla[$i][1];
                    $postFields_Port[$i]["PortForwardingLocalStartPort"]=$tabla[$i][3];
                    $postFields_Port[$i]["PortForwardingLocalEndPort"]=$tabla[$i][3];
                    $postFields_Port[$i]["PortForwardingExtIp"]="0.0.0.0";
                    $postFields_Port[$i]["PortForwardingExtStartPort"]=$tabla[$i][4];
                    $postFields_Port[$i]["PortForwardingExtEndPort"]=$tabla[$i][4];
                    $postFields_Port[$i]["PortForwardingProtocol"]=$protocol;
                    $postFields_Port[$i]["PortForwardingDesc"]=$tabla[$i][0];
                    $postFields_Port[$i]["PortForwardingEnabled"]="1";
                    $postFields_Port[$i]["PortForwardingApply"]="2";
                    $postFields_Port[$i]["PortForwardingTable"]="0";
                    $updateMaping3 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Port[$i]);

                }elseif($cantidad>1){

                    $updateMaping2 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Nuevo);
                    $postFields_Port[$i]["PortForwardingCreateRemove"]="0";
                    $postFields_Port[$i]["PortForwardingLocalIp"]=$tabla[$i][1];
                    $postFields_Port[$i]["PortForwardingLocalStartPort"]=$esRango1[0];
                    $postFields_Port[$i]["PortForwardingLocalEndPort"]=$esRango1[1];
                    $postFields_Port[$i]["PortForwardingExtIp"]="0.0.0.0";
                    $postFields_Port[$i]["PortForwardingExtStartPort"]=$esRango2[0];
                    $postFields_Port[$i]["PortForwardingExtEndPort"]=$esRango2[1];
                    $postFields_Port[$i]["PortForwardingProtocol"]=$protocol;
                    $postFields_Port[$i]["PortForwardingDesc"]=$tabla[$i][0];
                    $postFields_Port[$i]["PortForwardingEnabled"]="1";
                    $postFields_Port[$i]["PortForwardingApply"]="2";
                    $postFields_Port[$i]["PortForwardingTable"]="0";
                    $updateMaping3 = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Port[$i]);
                    
                }
                
            }
        
        }
        
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }



    function updateMapingCastlenet($codCliente,$ipaddress,$fabricante,$maping)
    {
        $login = 'admin';

        $tabla = json_decode($maping);
        $postFields_Port = array();

        $postFields_AllDelete = array(
            "PortForwardingCreateRemove" => "4",
            "PortForwardingTable" => "0",
            "OverlapError" => "0x00"
        );

        $postFields_Nuevo = array(
            "PortForwardingCreateRemove" => "1",
            "PortForwardingTable" => "0",
            "OverlapError" => "0x00"
        );

        $url_cambio="http://".$ipaddress."/goform/RgForwarding"; 

        $ingresarCablemodem = new CablemodemFunctions;
        $updateMaping1 = $ingresarCablemodem->getPageCastlenet2($url_cambio,$login,$codCliente,$postFields_AllDelete);

        if(count($tabla)>0){

            for ($i=0; $i < count($tabla); $i++) {
        
                if($tabla[$i][2]=="TCP"){
                    $protocol="4";
                }elseif($tabla[$i][2]=="UDP"){
                    $protocol="3";
                }elseif($tabla[$i][2]=="TCP/UDP"){
                    $protocol="254";
                }
        
                $esRango1=explode('-',$tabla[$i][3]);
                $esRango2=explode('-',$tabla[$i][4]);
                $cantidad=count($esRango1);
        
                if($cantidad==1){

                    $updateMaping2 = $ingresarCablemodem->getPageCastlenet2($url_cambio,$login,$codCliente,$postFields_Nuevo);
                    $postFields_Port[$i]["PortForwardingCreateRemove"]="0";
                    $postFields_Port[$i]["PortForwardingLocalIp"]=$tabla[$i][1];
                    $postFields_Port[$i]["PortForwardingLocalStartPort"]=$tabla[$i][3];
                    $postFields_Port[$i]["PortForwardingLocalEndPort"]=$tabla[$i][3];
                    $postFields_Port[$i]["PortForwardingExtStartPort"]=$tabla[$i][4];
                    $postFields_Port[$i]["PortForwardingExtEndPort"]=$tabla[$i][4];
                    $postFields_Port[$i]["PortForwardingProtocol"]=$protocol;
                    $postFields_Port[$i]["PortForwardingDesc"]=$tabla[$i][0];
                    $postFields_Port[$i]["PortForwardingEnabled"]="1";
                    $postFields_Port[$i]["PortForwardingApply"]="2";
                    $postFields_Port[$i]["PortForwardingTable"]="0";
                    $postFields_Port[$i]["OverlapError"]="0x00";
                    $updateMaping3 = $ingresarCablemodem->getPageCastlenet2($url_cambio,$login,$codCliente,$postFields_Port[$i]);

                }elseif($cantidad>1){

                    $updateMaping2 = $ingresarCablemodem->getPageCastlenet2($url_cambio,$login,$codCliente,$postFields_Nuevo);
                    $postFields_Port[$i]["PortForwardingLocalIp"]=$tabla[$i][1];
                    $postFields_Port[$i]["PortForwardingCreateRemove"]="0";
                    $postFields_Port[$i]["PortForwardingLocalStartPort"]=$esRango1[0];
                    $postFields_Port[$i]["PortForwardingLocalEndPort"]=$esRango1[1];
                    $postFields_Port[$i]["PortForwardingExtStartPort"]=$esRango2[0];
                    $postFields_Port[$i]["PortForwardingExtEndPort"]=$esRango2[1];
                    $postFields_Port[$i]["PortForwardingProtocol"]=$protocol;
                    $postFields_Port[$i]["PortForwardingDesc"]=$tabla[$i][0];
                    $postFields_Port[$i]["PortForwardingEnabled"]="1";
                    $postFields_Port[$i]["PortForwardingApply"]="2";
                    $postFields_Port[$i]["PortForwardingTable"]="0";
                    $postFields_Port[$i]["OverlapError"]="0x00";
                    $updateMaping3 = $ingresarCablemodem->getPageCastlenet2($url_cambio,$login,$codCliente,$postFields_Port[$i]);
        
                }
                
            }
        
        }

        $mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }

 




}



?>