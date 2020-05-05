<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemDiagnosticoFunctions {

    function obtenerDiagnosticoAskey($codCliente,$ipaddress,$fabricante,$ipPing){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        
        $postFields_StartPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => $ipPing,
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "100",
            "AskUtilityStatus" => "",
            "UtilityCommand" => "1"
            );
            
        $postFields_ClearPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => "",
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "100",
            "AskUtilityStatus" => "Waiting for input...",
            "UtilityCommand" => "3"
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_diagnostico="https://".$ipaddress."/RgDiagnostic.asp";
        $url_ping="https://".$ipaddress."/goform/AskRgDiagnostic";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $obtClear = $ingresarCablemodem->getPageAskey1($url_ping,$postFields_ClearPing);
        $obtPing = $ingresarCablemodem->getPageAskey1($url_ping,$postFields_StartPing);
        sleep(25);
        $obtDiagnostico = $ingresarCablemodem->getPageAskey2($url_diagnostico);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtDiagnostico);

        foreach($html->find('[name=AskUtilityStatus]') as $a)
            $diagnost=$a->plaintext;

        $caracter = array("&#13;&#10;&#13;&#10;","&#13;&#10;");
        $resultado=str_replace($caracter,"\n",$diagnost);
        
        return array(
            "Resultado" => $resultado
        );

    }


    function obtenerDiagnosticoHitron($codCliente,$ipaddress,$fabricante,$ipPing){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );
        
        $postFields_Ping = array(
            "file" => "feat-lan-debug",
            "dir" => "admin/",
            "ping" => $ipPing
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_diagnostico1="https://".$ipaddress."/admin/feat-lan-debug.asp";
        $url_cambio="https://".$ipaddress."/goform/Ping";
        $url_diagnostico2="https://".$ipaddress."/admin/popup-admin-diag.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
        $obtDiagnostico = $ingresarCablemodem->getPageHitron2($url_diagnostico1);
        $cambioDiagnostico = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Ping);
        sleep(15);
        $obtResultado = $ingresarCablemodem->getPageHitron2($url_diagnostico2);

        //dd($obtResultado);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtResultado);

        $a=$html->find('script',1);
        $arr=$a->innertext;
        $array1=explode('result = ',$arr);
        $caracter = array(";","\"","/");
        $array2=str_replace($caracter,"",$array1[1]);
        $resultado = str_replace("|","\n",$array2);

        
        return array(
            "Resultado" => $resultado
        );
        
        //return $mensaje;


    }



    function obtenerDiagnosticoUbee($codCliente,$ipaddress,$fabricante,$ipPing){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );
        
        $postFields_StartPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => $ipPing,
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "1000",
            "UtilityCommand" => "1"
        );
        
        $postFields_ClearPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => "",
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "1000",
            "UtilityCommand" => "3"
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_diagnostico="http://".$ipaddress."/RgDiagnostics.asp";
        $url_cambio="http://".$ipaddress."/goform/RgDiagnostics";
        $url_logout="https://".$ipaddress."/login.asp";


        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtLimpiar = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_ClearPing);
        $obtCambia = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_StartPing);
        sleep(25);
        $obtResultado = $ingresarCablemodem->getPageUbee2($url_diagnostico);
        //dd($obtResultado);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtResultado);

        $diagnostico = array();

        foreach($html->find('[name=UtilityStatus] option') as $a){
            $resultado1=$a->innertext;
            array_push($diagnostico,$resultado1);
        }

        $convertir = implode("|",$diagnostico);
        $resultado = str_replace("|","\n",$convertir);
        
        return array(
            "Resultado" => $resultado
        );

        
    }



    function obtenerDiagnosticoSagem($codCliente,$ipaddress,$fabricante,$ipPing){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );

        $postFields_StartPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => $ipPing,
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "100",
            "UtilityCommand" => "1"
        );
        
        $postFields_ClearPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => "",
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "100",
            "UtilityCommand" => "3",
            "UtilityStatus" => "10"
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_diagnostico="https://".$ipaddress."/RgDiagnostics.asp";
        $url_cambio="https://".$ipaddress."/goform/RgDiagnostics";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);
        //$obtLimpia = $ingresarCablemodem->getPageSagem1($url_cambio,$postFields_ClearPing);
        //sleep(10);
        $obtCambia = $ingresarCablemodem->getPageSagem1($url_cambio,$postFields_StartPing);
        sleep(10);
        $obtResultado = $ingresarCablemodem->getPageSagem2($url_diagnostico);
        $obtLimpia = $ingresarCablemodem->getPageSagem1($url_cambio,$postFields_ClearPing);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtResultado);

        $diagnostico = array();

        foreach($html->find('[name=UtilityStatus] option') as $a){
            $resultado1=$a->innertext;
            array_push($diagnostico,$resultado1);
        }

        $convertir = implode("|",$diagnostico);
        $resultado = str_replace("|","\n",$convertir);

        return array(
            "Resultado" => $resultado
        );

    }




    function obtenerDiagnosticoCastlenet($codCliente,$ipaddress,$fabricante,$ipPing){

        $login = 'admin';

        $postFields_StartPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => $ipPing,
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "1000",
            "UtilityCommand" => "1"
        );
        
        $postFields_ClearPing = array(
            "DiagnosticUtility" => "0",
            "PingDestinationIP0" => "",
            "PingSize" => "64",
            "NumberOfPings" => "10",
            "TimeBetweenPings" => "1000",
            "UtilityCommand" => "3"
        );

        $url_searchip="http://".$ipaddress."/goform/RgDiagnostics";
        $url_diagnostico="http://".$ipaddress."/RgDiagnostics.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtLimpia = $ingresarCablemodem->getPageCastlenet2($url_searchip,$login,$codCliente,$postFields_ClearPing);
        $obtCambia = $ingresarCablemodem->getPageCastlenet2($url_searchip,$login,$codCliente,$postFields_StartPing);
        sleep(25);
        $obtResultado = $ingresarCablemodem->getPageCastlenet1($url_diagnostico,$login,$codCliente);

        $html = new simple_html_dom();
        $html->load($obtResultado);

        $diagnostico = array();

        foreach($html->find('[name=UtilityStatus] option') as $a){
            $resultado1=$a->innertext;
            array_push($diagnostico,$resultado1);
        }

        $convertir = implode("|",$diagnostico);
        $resultado = str_replace("|","\n",$convertir);

        return array(
            "Resultado" => $resultado
        );

    }





    


}