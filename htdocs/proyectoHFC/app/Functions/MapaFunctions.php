<?php

namespace App\Functions;
use DB; 
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Functions\CablemodemStatusFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MapaFunctions
{
    function mapa_tabs($nodo,$troba)
    {
        $tabs_consulta = DB::select(
            "select nodo,troba,amplificador,tap,coord_x AS tap_x,coord_y AS tap_y,direccion 
            FROM geoubica.geo_tap a 
            WHERE a.nodo=? AND a.troba=?", [$nodo,$troba]);
            
        return $tabs_consulta;
    }

    function mapa_amplificador($nodo,$troba)
    {
        $amplificador_consulta = DB::select(
            "SELECT nodo,troba,amplificador,coord_x AS amplif_x,coord_y AS amplif_y 
            FROM geoubica.geo_amplificador a 
            WHERE a.nodo=? AND a.troba=? ",[$nodo,$troba]);
  
        return $amplificador_consulta;
    }

    function mapa_trobas($nodo,$troba)
    {
        $trobas_consulta = DB::select(
            "select nodo,troba,X AS troba_x,Y AS troba_y 
            FROM geoubica.geo_troba a
            WHERE a.nodo=? AND a.troba=? ", [$nodo, $troba]);
    
            return $trobas_consulta;
    }

    function mapa_resultado($nodo, $troba)
    {
        // a.IPCM,b.IPAddress,f.Fabricante, f.Modelo,
        //LEFT JOIN ccm1_data.marca_modelo_docsis_total f  ON a.MACADDRESS=f.MACAddress
        //WHERE a.nodo=? AND a.troba=? AND d.x<>0 AND SUBSTR(d.x,1,1)='-' AND SUBSTR(d.y,1,1)='-'
        $mapa_re = DB::select(
            "select 
            IF(b.MACState ='Offline',b.cmts,c.cmts) AS cmts,
            IF(b.MACState ='Offline',b.interface,c.interface) AS interface,
            IF(b.macstate ='Offline','offline','online') AS macstate, 
            b.RxPwrdBmv,c.USPwr,c.USMER_SNR,c.DSPwr,c.DSMER_SNR,a.IDCLIENTECRM,
           
            REPLACE(a.NAMECLIENT,',','') AS nameclient, 
            REPLACE(a.direccion,',','') AS direccion,
            CONCAT(a.NODO,'.') AS NODO,
            CONCAT(a.TROBA,'.') AS TROBA,a.amplificador, a.tap,a.telf1,a.telf2,a.movil1,a.MACADDRESS,a.SERVICEPACKAGE,
            a.FECHAACTIVACION,             
            d.x,d.y,IF(ed.nro>0,'EDIFICIO','CASA') AS tipoed,d.desdtt,
            IF(d.nom_via='','XXX',d.nom_via) AS nom_via,IF(d.num_puer='','XXX',d.num_puer) AS num_puer
                FROM multiconsulta.nclientes a FORCE INDEX (idxcodserv)
                INNER JOIN cms.clientesm1_xy d FORCE INDEX (PRIMARY) ON a.codserv=d.servicio 
                LEFT JOIN ccm1.scm_total b FORCE INDEX (MACAddress) ON a.mac2=b.macaddress 
                LEFT JOIN ccm1.scm_phy_t c FORCE INDEX (NewIndex1) ON a.mac2=c.macaddress
                LEFT JOIN cms.planta_clarita_edificios ed FORCE INDEX (ubigeo,coddtt,via,nro) 
                ON d.desdtt=ed.desdtt AND d.NOM_VIA=ed.via AND d.num_puer=ed.nro 

                WHERE a.nodo='$nodo' AND a.troba='$troba' AND d.x<>0 AND SUBSTR(d.x,1,1)='-' AND SUBSTR(d.y,1,1)='-' and a.idclientecrm<>969625 and idclientecrm<10000000
                AND CONCAT(SUBSTR(d.x,1,3),SUBSTR(d.y,1,3))=(
                                                            SELECT za.xyr FROM (
                                                                                SELECT 
                                                                                CONCAT(SUBSTR(dd.x,1,3),SUBSTR(dd.y,1,3)) AS xyr,COUNT(*) AS cant 
                                                                                FROM multiconsulta.`nclientes` aa FORCE INDEX (idxcodserv) 
                                                                                INNER JOIN cms.clientesm1_xy dd
                                                                                FORCE INDEX (PRIMARY) ON aa.codserv=dd.servicio
                                                                                WHERE aa.nodo='$nodo' AND aa.troba='$troba'
                                                                                GROUP BY 1
                                                                                ORDER BY 2 DESC
                                                                                LIMIT 1
                                                                                ) za
                                                            )

            "); 
 

            return $mapa_re;
    
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

    function edificiosList($desdtt,$nom_via,$num_puer)
    {
        $edificios =  DB::select("SELECT 
                    IF(b.MACState ='offline',b.cmts,c.cmts) AS cmts,
                    IF(b.MACState ='offline',b.interface,c.interface) AS interface,
                    LOWER(TRIM(b.macstate)) as macstate, 
                    b.RxPwrdBmv,
                    c.USPwr,
                    c.USMER_SNR,
                    c.DSPwr,
                    c.DSMER_SNR,
                    a.IDCLIENTECRM,
                    a.idservicio, 
                    a.idproducto, 
                    a.idventa,
                    f.Modelo,
                    REPLACE(a.NAMECLIENT,',','') AS nameclient,
                    CONCAT(NMTIPVIA_TDP,' ',NOM_VIA,' ',NUM_PUER,' ',NMTIPINT,' ',NUM_INT,' ',MZ_NORM,
                        ' ',NUM_LOTE,' ',NMTIPURB_TDP,' ',URB_CONC,' ',desdpt,' ',despvc) AS direccion,
                    a.amplificador, 
                    a.tap,
                    a.telf1,
                    a.telf2,
                    TRIM(a.MACADDRESS) as MACADDRESS,
                    a.SERVICEPACKAGE,
                    b.cmts 
                    FROM multiconsulta.nclientes a 
                    INNER JOIN cms.clientesm1_xy pc 
                    ON a.codserv=pc.servicio 
                    INNER JOIN ccm1.scm_total b ON a.mac2=b.macaddress
                    LEFT JOIN ccm1.scm_phy_t c ON a.mac2=c.macaddress 
                    LEFT JOIN ccm1_data.marca_modelo_docsis_total f  ON a.MACADDRESS=f.MACAddress
                    WHERE  pc.desdtt='$desdtt' AND pc.nom_via='$nom_via' AND pc.num_puer=$num_puer
                    ORDER BY pc.num_int*1");
    return $edificios;
    }

    function procesarEdificioslist($resultEdif,$coloresEdificio,$dataParametrosRF)
    {
        //dd($resultEdif);
        $ResultFinalEdif = array_map(function($el) use ($coloresEdificio,$dataParametrosRF){ 
                           
            #ESTADO

                 $arrayEstado = Parametrosrf::getEstadoSegunNivelesRF($el->macstate,$el->MACADDRESS,(double)$el->DSMER_SNR,(double)$el->DSPwr,
                                                                             (double)$el->USMER_SNR,(double)$el->USPwr,$dataParametrosRF);

                 $el->estado = $arrayEstado["nivel"];     
                 
                      switch ($el->estado){
                           case 0:
                                $textBackground=$coloresEdificio->nivelesEstado->colores[0]->background;
                                $textColor=$coloresEdificio->nivelesEstado->colores[0]->color;
                           break;
                           case 1:
                                $textBackground=$coloresEdificio->nivelesEstado->colores[1]->background;
                                $textColor=$coloresEdificio->nivelesEstado->colores[1]->color;
                           break;
                           case 2:
                                $textBackground= $coloresEdificio->nivelesEstado->colores[2]->background;
                                $textColor= $coloresEdificio->nivelesEstado->colores[2]->color;
                           break;
                           case 3:
                                $textBackground=$coloresEdificio->nivelesEstado->colores[3]->background;
                                $textColor=$coloresEdificio->nivelesEstado->colores[3]->color;
                           break;
                      }

                      $el->estilosText = array(
                           "background"=>$textBackground,
                           "color"=>$textColor
                      );
            #END ESTADO

            #Validacion de Niveles
                if ((double)$el->DSPwr + (double)$el->DSMER_SNR == 0 && $el->macstate == "online" && (int)$el->estado==1) {
                    
                    if($el->IPAddress=='') $el->IPAddress = $el->ipcm;
                    
                    if ($el->IDCLIENTECRM <> "" && $el->IPAddress <>"" && $el->Fabricante <> "" && $el->Modelo <> "") {
                        $statusCablemodem = new CablemodemStatusFunctions; 
                        $arrMedicionesStatus = $statusCablemodem->statusPrincipal($el->IDCLIENTECRM,$el->IPAddress,$el->Fabricante,$el->Modelo);
                        if ($arrMedicionesStatus != "Error" && $arrMedicionesStatus != "Error Codigo") {
                            if(count($arrMedicionesStatus["Downstream"]) > 0)  $el->DSPwr = (double)$arrMedicionesStatus["Downstream"][0]["Power"];
                            if(count($arrMedicionesStatus["Downstream"]) > 0)  $el->DSMER_SNR = (double)$arrMedicionesStatus["Downstream"][0]["SNR"];
                            if(count($arrMedicionesStatus["Upstream"]) > 0)  $el->USPwr = (double)$arrMedicionesStatus["Upstream"][0]["Power"]; 
                        }
                    }
                    
                }

            #END VALIDACIONNiveles Ruido 
            #COLORES RF
                $el->coloresNivelesRuido= Parametrosrf::getColoresNivelesRF((double)$el->DSMER_SNR,(double)$el->DSPwr,
                    (double)$el->USMER_SNR,(double)$el->USPwr,$dataParametrosRF);

            #END COLORES RF


                return $el;

       }, $resultEdif);

       return $ResultFinalEdif;

    }

    function getDataMapaCall($nodo,$troba)
    {

        try {

            $dataCallClient = DB::select("select  
                                            concat(trim(d.nombre),' ',trim(d.ape_pat),' ',trim(d.ape_mat)) as nombre,
                                            d.cliente,
                                            d.servicio, 
                                            d.direc_inst,
                                            d.tiptec,
                                            d.codlex,
                                            d.codtap,
                                            numcoo_x as coordX ,
                                            numcoo_y as coordY 
                                            FROM alertasx.alertas_dmpe a FORCE INDEX (cliente) 
                                            INNER JOIN cms.planta_clarita d FORCE INDEX (CLIENTE)
                                                    ON a.cliente=d.CLIENTE
                                            WHERE a.nodo='$nodo' AND a.troba='$troba' AND d.numcoo_x<>0 AND SUBSTR(d.numcoo_x,1,1)='-' 
                                            AND SUBSTR(d.numcoo_y,1,1)='-'");

            return $dataCallClient;

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
       } 
 
    }

    function getjefaturasAndLatLong()
    {
        $lista = DB::select("select 'TODO' AS jefatura,'-12.045914' AS latitud,'-77.030046' AS longitud
                            UNION
                            SELECT 'LIMA' AS jefatura,'-12.045914' AS latitud,'-77.030046' AS longitud
                            UNION
                            SELECT a.jefatura,z.latitud,z.longitud FROM catalogos.jefaturas a INNER JOIN catalogos.zonasxy z ON a.jefatura=z.jefatura
                            GROUP BY z.jefatura");
        return $lista;
    }

    function getClienteByClteTelDni($ClteTelDni)
    {
        try {
            $lista =  DB::select("select a.cliente,concat(trim(c.ape_pat),' ',trim(c.ape_mat),' ',trim(c.nombre)) as nombre,
                IF(b.latitud IS NULL,c.`numcoo_x`,b.longitud) AS x , 
                IF(b.longitud IS NULL,c.`numcoo_y`,b.latitud) AS y , 
                c.servicio,
                a.nodo,
                a.troba,
                c.direc_inst,
                c.tiptec,
                c.codlex,
                c.codtap
                FROM catalogos.planta_telef_cms_new a
                LEFT JOIN analitycs.xy_nuevo2019 b
                ON a.cliente=b.codclicms
                LEFT JOIN cms.planta_clarita c
                ON a.cliente=c.cliente
                WHERE  
                a.cliente=$ClteTelDni or
                a.telf1=  $ClteTelDni OR
                a.telf2= $ClteTelDni OR
                a.telf3= $ClteTelDni OR 
                a.telf4= $ClteTelDni OR
                a.telf5= $ClteTelDni OR
                a.telf6= $ClteTelDni OR
                a.telf7= $ClteTelDni OR
                a.telf8= $ClteTelDni OR
                a.telf9= $ClteTelDni OR
                a.telf10= $ClteTelDni OR
                a.telfono2= $ClteTelDni OR
                a.telf11= $ClteTelDni OR
                a.telf12= $ClteTelDni OR 
                a.NUMERODOC =$ClteTelDni OR
                a.NUMERORUC=$ClteTelDni "
            );
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
       } 

       return $lista;
        
    }

    function getDataLlamadasPeru($filtroJefatura)
    {
        try {

            $listaData = DB::select("select 
                a.nombre,
                a.cliente,
                a.servicio,
                a.nodo,
                a.troba,
                a.direc_inst,
                a.tiptec,
                a.codlex,
                a.codtap,
                a.x as coordX,
                a.y as coordY,
                if(b.cliente is null,a.color,'call_reiterada.png') as color,
                a.SnrDN,
                a.DSPwr,
                a.SnrUP,
                a.USPwr,
                if(b.cliente is not null,b.cant,0) as cant_reit,
                if(b.cliente is not null,'R','') as call_reit,
                if(b.cliente is not null,b.tdia,0) as tdia,
                a.cmts,
                a.interface
                from alertasx.mapas_nodos a left join alertasx.llamadas_reiteradas b
                on a.cliente=b.cliente 
                $filtroJefatura ");

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
           
       }catch(\Exception $e){
            //dd($e->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
       } 

       return $listaData;
    }


    function procesarMapaCallPeruResult($mapa_resultado,$dataParametrosRF,$sumaX,$sumaY,$contarXY)
    {
        $cantidadResult = count($mapa_resultado);
        // dd($dataParametrosRF);

        for ($i=0; $i < $cantidadResult ; $i++) { 

            if ($mapa_resultado[$i]->coordX != '') {
                $sumaX += $mapa_resultado[$i]->coordX;
                $sumaY += $mapa_resultado[$i]->coordY;
                $contarXY++;
           } 

           $coloresNivelesActivo = false;
           $coloresNiveles = array();

            if($mapa_resultado[$i]->color == "puntoambar.png" || $mapa_resultado[$i]->color == "puntorosado.png"  || 
                ($mapa_resultado[$i]->color == "call_reiterada_n.png" && $mapa_resultado[$i]->tiptec == 'HFC')){
                   //dd($mapa_resultado[$i]);
                #COLORES RF
                     $coloresNiveles = Parametrosrf::getColoresNivelesRF((double)$mapa_resultado[$i]->SnrDN,(double)$mapa_resultado[$i]->DSPwr,
                        (double)$mapa_resultado[$i]->SnrUP,(double)$mapa_resultado[$i]->USPwr,$dataParametrosRF);
                   // dd($mapa_resultado[$i],$i);
                #END COLORES RF
                $coloresNivelesActivo = true;

            }else{
                $coloresNivelesActivo = false;
                
            }

            $mapa_resultado[$i]->coloresNivelesActivo = $coloresNivelesActivo;
            $mapa_resultado[$i]->coloresNivelesRuido = $coloresNiveles;

           // $mapa_resultado[$i]->direct_inst = utf8_encode($mapa_resultado[$i]->direct_inst);
             

        }

        return array(
            "resultado"=>$mapa_resultado,
            "sumaX"=>$sumaX,
            "sumaY"=>$sumaY,
            "contarXY"=>$contarXY
        );
 
    }
     

}