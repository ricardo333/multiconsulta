<?php

namespace App\Functions;
use DB; 
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Functions\CablemodemStatusFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;

ini_set('max_execution_time', 900);

class MapaDiagnosticoOutsideFunctions
{

    function mapa_tabs($latitud, $longitud)
    {
        $tab_consulta = DB::select(
            "select ((SQRT((coord_x-$latitud)*(coord_x-$latitud) + (coord_y-$longitud)*(coord_y-$longitud)) ) * 111180) as distancia,
            nodo,troba,amplificador,tap,coord_x as tap_x,coord_y as tap_y,direccion 
            FROM geoubica.geo_tap a 
            WHERE ((SQRT((coord_x-$latitud)*(coord_x-$latitud) + (coord_y-$longitud)*(coord_y-$longitud)) ) * 111180)<=200 order by 1 limit 0");

        return $tab_consulta;

    }


    function mapa_amplificador($latitud, $longitud)
    {
        $amplif_consulta = DB::select(
            "select ((SQRT((coord_x-$latitud)*(coord_x-$latitud) + (coord_y-$longitud)*(coord_y-$longitud)) ) * 111180) 
            as distancia,nodo,troba,amplificador,coord_x AS amplif_x,coord_y AS amplif_y 
            FROM geoubica.geo_amplificador a WHERE ((SQRT((coord_x-$latitud)*(coord_x-$latitud) + 
            (coord_y-$longitud)*(coord_y-$longitud)) ) * 111180)<=200 order by 1 limit 0");

        return $amplif_consulta;        

    }


    function mapa_resultado($latitud,$longitud,$distancia)
    {
        $mapa_rs = DB::select(
            "(SELECT ((SQRT((a.numcoo_x-$latitud)*(a.numcoo_x-$latitud) + (a.numcoo_y-$longitud)*(a.numcoo_y-$longitud)) ) * 111180) as distancia,
            IF(b.MACState ='Offline',b.cmts,c.cmts) AS cmts,IF(b.MACState ='offline',b.interface,c.interface) AS interface,
            IF(b.macstate ='offline','offline','online') AS macstate,b.RxPwrdBmv,c.USPwr,c.USMER_SNR,c.DSPwr,c.DSMER_SNR,a.IDCLIENTECRM,
            REPLACE(a.NAMECLIENT,',','') AS nameclient,REPLACE(a.direccion,',','') AS direccion,CONCAT(a.NODO,'.') AS NODO,
            CONCAT(a.TROBA,'.') AS TROBA,a.amplificador, a.tap,a.telf1,a.telf2,a.movil1,a.MACADDRESS,a.SERVICEPACKAGE,a.FECHAACTIVACION, 
            IF(b.MACState ='offline','0', IF(b.MACState <> 'offline' AND c.DSMER_SNR ='-----','1',  
            IF(b.macstate IS NULL AND c.DSMER_SNR IS NULL,'0', 
            IF(b.macstate NOT IN ('w-online','online','operational','offline') AND DSMER_SNR IS NULL,'3',
            IF(b.macstate IN ('w-online','online','operational') AND (c.USPwr <35 OR c.USPwr >57) ,'2', 
            IF(b.macstate IN ('w-online','online','operational') AND c.USMER_SNR <25 AND c.USMER_SNR >0.00 ,'2', 
            IF(b.macstate IN ('w-online','online','operational') AND c.DSPwr <-10 OR c.DSPwr >12 ,'2', 
            IF(b.macstate IN ('w-online','online','operational') AND c.DSPwr IS NULL, '3', 
            IF(b.macstate IN ('w-online','online','operational') AND c.DSMER_SNR <27 ,'2','1'))))))))) AS estado,
            m.Fabricante AS Fabricante, m.Modelo AS Modelo, m.IPCablemodem AS IpCablemodem,'HFC' AS TipoCliente, a.numcoo_x as x,a.numcoo_y as y,IF(ed.nro>0,'EDIFICIO','CASA') AS tipoed,
            d.desdtt, IF(d.nom_via='','XXX',d.nom_via) AS nom_via,IF(d.num_puer='','XXX',d.num_puer) AS num_puer
            FROM multiconsulta.nclientes a FORCE INDEX (idxcodserv)
            LEFT JOIN cms.clientesm1_xy d FORCE INDEX (PRIMARY) ON a.codserv=d.servicio and d.x<>0 and SUBSTR(d.x,1,1)='-' 
            LEFT JOIN ccm1_data.marca_modelo_docsis_total m ON a.macaddress=m.macaddress
            LEFT JOIN ccm1.scm_total b FORCE INDEX (MACAddress) ON a.mac2=b.macaddress
            LEFT JOIN ccm1.scm_phy_t c FORCE INDEX (NewIndex1) ON a.mac2=c.macaddress
            LEFT JOIN cms.planta_clarita_edificios ed FORCE INDEX (ubigeo,coddtt,via,nro) 
            ON d.desdtt=ed.desdtt AND d.NOM_VIA=ed.via AND d.num_puer=ed.nro
            WHERE  a.numcoo_x<>0 AND SUBSTR(a.numcoo_x,1,1)='-' AND SUBSTR(a.numcoo_y,1,1)='-' and
            ((SQRT((a.numcoo_x-$latitud)*(a.numcoo_x-$latitud) + (a.numcoo_y-$longitud)*(a.numcoo_y-$longitud)) ) * 111180)<=$distancia
            GROUP BY a.idclientecrm,a.codserv
            ORDER BY distancia
            )
            UNION
            (SELECT ((SQRT((a.numcoo_x-$latitud)*(a.numcoo_x-$latitud) + (a.numcoo_y-$longitud)*(a.numcoo_y-$longitud)) ) * 111180) AS distancia,
            'MonoTV' AS cmts,'MonoTV' AS interface,'CATV' AS macstate,0,0,0,0,0,a.CLIENTE,
            CONCAT(a.APE_PAT,' ',a.APE_MAT,' ',a.NOMBRE) AS nameclient,REPLACE(a.DIREC_INST,',','') AS direccion, 
            CONCAT(a.NODO,'.') AS NODO,CONCAT(a.PLANO,'.') AS TROBA,a.CODLEX AS amplificador,a.CODTAP AS tap,
            a.TELEFCL1 AS telf1,a.TELEFCL2 AS telf2,a.TELEFCL3 AS movil1,'MonoTV' AS MACADDRESS,'MonoTV' AS SERVICEPACKAGE,
            'MonoTV' AS FECHAACTIVACION,'5' AS estado,'' AS Fabricante,'' AS Modelo,'' AS IpCablemodem,'' AS TipoCliente,
            a.numcoo_x AS X,a.numcoo_y AS Y,'CASA' AS tipoed,a.desdtt,IF(a.via='','XXX',a.via) AS nom_via,
            IF(a.NRO='','XXX',a.NRO) AS num_puer 
            FROM cms.planta_clarita_monotv  a 
            WHERE a.tiptec not in ('HFC','GPON') and  a.numcoo_x<>0 AND SUBSTR(a.numcoo_x,1,1)='-' AND SUBSTR(a.numcoo_y,1,1)='-' AND
            ((SQRT((a.numcoo_x-$latitud)*(a.numcoo_x-$latitud) + (a.numcoo_y-$longitud)*(a.numcoo_y-$longitud)) ) * 111180)<=$distancia
            ORDER BY distancia)"); 
 

            return $mapa_rs;
    
    }


    function procesarMapaResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY)
    {
        $cantidadResult = count($mapa_resultado);

        for ($i=0; $i < $cantidadResult ; $i++) { 
            
             if ($mapa_resultado[$i]->x != '') {
                  $sumaX += $mapa_resultado[$i]->x;
                  $sumaY += $mapa_resultado[$i]->y;
                  $contarXY++;
             } 
             #ESTADO 
                  $arrayEstado = Parametrosrf::getEstadoSegunNivelesRF($mapa_resultado[$i]->macstate,$mapa_resultado[$i]->MACADDRESS,(double)$mapa_resultado[$i]->DSMER_SNR,(double)$mapa_resultado[$i]->DSPwr,
                                      (double)$mapa_resultado[$i]->USMER_SNR,(double)$mapa_resultado[$i]->USPwr,$dataParametrosRF);
                  
                  $mapa_resultado[$i]->estado = $arrayEstado["nivel"]; 
             #END ESTADO

             #COLORES RF
                  $mapa_resultado[$i]->coloresNivelesRuido= Parametrosrf::getColoresNivelesRF((double)$mapa_resultado[$i]->DSMER_SNR,(double)$mapa_resultado[$i]->DSPwr,
                                 (double)$mapa_resultado[$i]->USMER_SNR,(double)$mapa_resultado[$i]->USPwr,$dataParametrosRF);

             #END COLORES RF

             $mapa_resultado[$i]->direccion = utf8_encode($mapa_resultado[$i]->direccion);
             $mapa_resultado[$i]->desdtt = utf8_encode($mapa_resultado[$i]->desdtt);
             $mapa_resultado[$i]->nom_via = utf8_encode($mapa_resultado[$i]->nom_via);

        }

           return array(
               "resultado"=>$mapa_resultado,
               "sumaX"=>$sumaX,
               "sumaY"=>$sumaY,
               "contarXY"=>$contarXY
           );
    }



    function diagnosticarClienteSnmp($clientes)
    {

        //--------------------------------------------------------------------------------------//
        
        //$consultarClient = array();
        $clientesHFC = array();
        $cpe = array();
        $snmp = array();

        //$consultarClient = $clientes;
        //$cantidad = count($consultarClient);
        //dd($consultarClient);
        $cantidad = count($clientes);

        for ($i=0; $i < $cantidad; $i++) {

            if($clientes[$i]->TipoCliente=="HFC" && ($clientes[$i]->estado==1 || $clientes[$i]->estado==2)){            

                if (substr($clientes[$i]->Fabricante,0,5)=="Arris") {
                    $oidx='iso.3.6.1.2.1.4.20.1.1';
                    $ippu="snmpwalk -c MODEM8K_PILOTO -v2c ".$clientes[$i]->IpCablemodem." ".$oidx." -r 1";
                    exec($ippu,$cpe[$i]);
            
                    //$snmp[$i]->estado = $cpe[$i];
                    $resultado = count($cpe[$i]);
                    //$resultado = count($snmp[$i]->estado);
                    if ($resultado>0) {
                        $clientes[$i]->mensaje = "Activo";
                    }else {
                        $clientes[$i]->mensaje = "Inactivo";
                    }
                } elseif (substr($clientes[$i]->Fabricante,0,4)=="Hitr") {
                    $oidx='iso.3.6.1.2.1.4.22.1.1.1';
                    $ippu="snmpwalk -c MODEM8K_PILOTO -v2c ".$clientes[$i]->IpCablemodem." ".$oidx." -r 1";
                    exec($ippu,$cpe[$i]);
                    //$snmp[$i]->estado = $cpe[$i];
                    $resultado = count($cpe[$i]);
                    //$resultado = count($snmp[$i]->estado);
                    if ($resultado>0) {
                        $clientes[$i]->mensaje = "Activo";
                    }else {
                        $clientes[$i]->mensaje = "Inactivo";
                    }
                } else {
                    $oidx='iso.3.6.1.2.1.4.34.1.10.1.4';
                    $ippu="snmpwalk -c MODEM8K_PILOTO -v2c ".$clientes[$i]->IpCablemodem." ".$oidx." -r 1";
                    exec($ippu,$cpe[$i]);
                    //dd($cpe);
                    //$snmp[$i]->estado = $cpe[$i];
                    $resultado = count($cpe[$i]);
                    //$resultado = count($snmp[$i]->estado);
                    if ($resultado > 0) {
                        $clientes[$i]->mensaje = "Activo";
                    }else {
                        $clientes[$i]->mensaje = "Inactivo";
                    }
                }
            }
        }

        for ($i=0; $i < $cantidad; $i++) {
            if($clientes[$i]->TipoCliente=="HFC"){
                if ($clientes[$i]->estado==1 and $clientes[$i]->mensaje=="Inactivo") {
                    $clientes[$i]->estado = "8";
                } elseif ($clientes[$i]->estado==8 and $clientes[$i]->mensaje=="Activo"){
                    $clientes[$i]->estado = "1";
                }
            }
        }


        return array(
            "resultado"=>$clientes
        );


        /*
        return array(
            "resultado"=>$consultarClient
        );
        */
        //--------------------------------------------------------------------------------------//

    }











}