<?php
namespace App\Functions;

use App\Library\simple_html_dom;

class CablemodemResetFunctions {

    function resetAskey($codCliente,$ipaddress,$fabricante,$reset){

        if($reset=="reset1") {
            $tipoReset = "10";
            $mensaje = "Reset de Cable Modem realizado";
        }elseif ($reset=="reset2") {
            $tipoReset = "3";
            $mensaje = "Reset de Fabrica de Cable Modem realizado";
        }

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );
        
        $postFields_reset = array(
            "AskuploadBtn" => "@",
            "AskConfiguration" => $tipoReset
        );


        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_reset="https://".$ipaddress."/RgBackup.asp";
        $url_cambio="https://".$ipaddress."/goform/AskRgBackup";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);
        $obtReset = $ingresarCablemodem->getPageAskey2($url_reset);
        $obtCambio = $ingresarCablemodem->getPageAskey1($url_cambio,$postFields_reset);
        sleep(25);
        $logout = $ingresarCablemodem->getPageAskey2($url_logout);

        //$mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;
            
    }


    function resetHitron($codCliente,$ipaddress,$fabricante,$reset){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );
        
        $postFields_Reset = array(
            "dir" => "admin/",
            "save" => "Reboot"
        );
        
        $postFields_Reset_Factory = array(
            "dir" => "admin/",
            "FReset" =>  "Factory Reset" 
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_reset="https://".$ipaddress."/admin/feat-lan-backup.asp";
        $url_cambio="https://".$ipaddress."/goform/Cable";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;

        if($reset=="reset1"){
            $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
            $obtReset = $ingresarCablemodem->getPageHitron2($url_reset);
            $obtCambio = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Reset);
            $logout = $ingresarCablemodem->getPageHitron2($url_logout);
            $mensaje = "Reset de Cable Modem realizado";
        }

        if($reset=="reset2"){
            $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);
            $obtReset = $ingresarCablemodem->getPageHitron2($url_reset);
            $obtCambio = $ingresarCablemodem->getPageHitron1($url_cambio,$postFields_Reset_Factory);
            $logout = $ingresarCablemodem->getPageHitron2($url_logout);
            $mensaje = "Reset de Fabrica de Cable Modem realizado";
        }

        //$mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function resetUbee($codCliente,$ipaddress,$fabricante,$reset){

        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );
        
        $postFields_Reset = array(
            "OldPassword" => "",
            "Password" => "",
            "PasswordReEnter" => "",
            "RestoreFactory3" => "0x03"
        );
        
        $postFields_ResetFactory = array(
            "OldPassword" => "",
            "Password" => "",
            "PasswordReEnter" => "",
            "RestoreFactory1" => "0x01"
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_reset="http://".$ipaddress."/RgSecurity.asp";
        $url_cambio="http://".$ipaddress."/goform/RgSecurity";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;

        if ($reset=="reset1") {
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtReset = $ingresarCablemodem->getPageUbee2($url_reset);
        $obtCambio = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_Reset);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);
        $mensaje = "Reset de Cable Modem realizado";
        }

        if ($reset=="reset2") {
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);
        $obtReset = $ingresarCablemodem->getPageUbee2($url_reset);
        $obtCambio = $ingresarCablemodem->getPageUbee1($url_cambio,$postFields_ResetFactory);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);
        $mensaje = "Reset de Fabrica de Cable Modem realizado";
        }

        //$mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function resetSagem($codCliente,$ipaddress,$fabricante,$reset){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );
        
        $postFields_Reset = array(
            "LocalIpAddressIP0" => "192",
            "LocalIpAddressIP1" => "168",
            "LocalIpAddressIP2" => "1",
            "LocalIpAddressIP3" => "1",
            "WanLeaseAction" => "0",
            "WanConnectionType" => "0",
            "MtuSize" => "0",
            "SpoofedMacAddressMA0" => "00",
            "SpoofedMacAddressMA1" => "00",
            "SpoofedMacAddressMA2" => "00",
            "SpoofedMacAddressMA3" => "00",
            "SpoofedMacAddressMA4" => "00",
            "SpoofedMacAddressMA5" => "00",
            "FactoryDefaultDisable" => "0x01",
            "ApplyRgSetupAction" => "0",
            "RebootAction" => "1"
        );
        
        $postFields_ResetFactory = array(
            "LocalIpAddressIP0" => "192",
            "LocalIpAddressIP1" => "168",
            "LocalIpAddressIP2" => "1",
            "LocalIpAddressIP3" => "1",
            "WanLeaseAction" => "0",
            "WanConnectionType" => "0",
            "MtuSize" => "0",
            "SpoofedMacAddressMA0" => "00",
            "SpoofedMacAddressMA1" => "00",
            "SpoofedMacAddressMA2" => "00",
            "SpoofedMacAddressMA3" => "00",
            "SpoofedMacAddressMA4" => "00",
            "SpoofedMacAddressMA5" => "00",
            "FactoryDefaultEnable" => "0x00",
            "ApplyRgSetupAction" => "1",
            "RebootAction" => "0"
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_reset="https://".$ipaddress."/RgSetup.asp";
        $url_cambio="https://".$ipaddress."/goform/RgSetup";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;

        if ($reset=="reset1") {
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);
        $obtReset = $ingresarCablemodem->getPageSagem2($url_reset);
        $obtCambio = $ingresarCablemodem->getPageSagem1($url_cambio,$postFields_Reset);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);
        $mensaje = "Reset de Cable Modem realizado";
        }

        if ($reset=="reset2") {
            $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);
            $obtReset = $ingresarCablemodem->getPageSagem2($url_reset);
            $obtCambio = $ingresarCablemodem->getPageSagem1($url_cambio,$postFields_ResetFactory);
            $logout = $ingresarCablemodem->getPageSagem2($url_logout);
            $mensaje = "Reset de Fabrica de Cable Modem realizado";
        }

        //$mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }


    function resetCastlenet($codCliente,$ipaddress,$fabricante,$reset){

        $login = 'admin';

        $postFields_Reset = array(
            "UserId" => "", 
            "Password" => "",
            "PasswordReEnter" => "", 
            "OldPassword" => "", 
            "RestoreFactoryNo" => "0x00",
            "RgRouterBridgeMode" => "1",
            "SystemReboot" => "1"
        );
        
        
        $postFields_ResetFactory = array(
            "UserId" => "",
            "Password" => "",
            "PasswordReEnter" => "",
            "OldPassword" => "",
            "RestoreFactoryYes" => "0x01",
            "RgRouterBridgeMode" => "1",
            "SystemReboot" => "0"
        );

        $url_reset="http://".$ipaddress."/goform/RgSecurity";

        $ingresarCablemodem = new CablemodemFunctions;

        if ($reset=="reset1") {
            $obtCambio = $ingresarCablemodem->getPageCastlenet2($url_reset,$login,$codCliente,$postFields_Reset);
            $mensaje = "Reset de Cable Modem realizado";
        }

        if ($reset=="reset1") {
            $obtCambio = $ingresarCablemodem->getPageCastlenet2($url_reset,$login,$codCliente,$postFields_ResetFactory);
            $mensaje = "Reset de Fabrica de Cable Modem realizado";
        }

        //$mensaje = "Datos cargados cliente...".$codCliente;

        return $mensaje;

    }




}