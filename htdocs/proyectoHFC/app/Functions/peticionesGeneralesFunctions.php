<?php 

namespace App\Functions;
use DB; 
use App\Administrador\Parametrosrf;
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class peticionesGeneralesFunctions {

    function getDiagnosticoMasivo($nodo="",$troba="",$cmts="")
    {
        #QUERY
            try { 
                    $result = DB::select("SELECT a.*,pt.telf1,pt.telf2,pt.telf9 AS movil1 FROM (
                                        select dt.codmotv as averia,dt.codctr,dt.codedo,cmts,
                                                                    interface,
                                                                    macaddress,
                                                                    scopesgroup,
                                                                    macstate,
                                                                    RxPwrdBmv,
                                                                    USPwr,
                                                                    USMER_SNR,
                                                                    DSPwr,
                                                                    DSMER_SNR,
                                                                    IDCLIENTECRM,
                                                                    nameclient,
                                                                    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(direccion,'AV AV','AV'),'CL CL','CL'),'JR JR','JR'),'PR PR','PR'),'UR UR','UR') AS direccion,
                                                                    nodohfc,
                                                                    trobahfc,
                                                                    nodocms,
                                                                    trobacms,
                                                                    amplificador,
                                                                    tap,
                                                                    mac2,
                                                                    SERVICEPACKAGE,
                                                                    FECHAACTIVACION,
                                                                    estado_modem,
                                                                    numcoo_x,
                                                                    numcoo_y,color,codreq from
                                                                    (SELECT rm.codreq,rm.codedo,rm.tipreqfin,rm.codmotv,rm.codctr,IF(c.macaddress is null,b.cmts,c.cmts) AS cmts,
                                                                    IF(c.macaddress is null,b.interface,c.interface) AS interface,
                                                                    a.scopesgroup,
                                        IF(c.macaddress IS NOT NULL,'online',IF(b.MACState IS NOT NULL,b.MACState,'')) AS macstate,
                                                                    IF(b.MACState <>'Offline' or c.macaddress IS NOT NULL ,b.RxPwrdBmv,' ') AS RxPwrdBmv,
                                                                    IF(b.MACState <>'Offline' or c.macaddress IS NOT NULL,c.USPwr,' ') AS USPwr,
                                                                    IF(b.MACState <>'Offline' or c.macaddress IS NOT NULL ,c.USMER_SNR,' ') AS USMER_SNR,
                                                                    IF(b.MACState <>'Offline' or c.macaddress IS NOT NULL ,c.DSPwr,' ') AS DSPwr,
                                                                    IF(b.MACState <>'Offline' or c.macaddress IS NOT NULL ,c.DSMER_SNR,' ') AS DSMER_SNR,
                                                                    a.IDCLIENTECRM,REPLACE(a.NAMECLIENT,',','') AS nameclient,
                                                                    a.direc_inst as direccion,
                                                                    a.NODO as nodohfc,a.TROBA as trobahfc,a.nodocms,a.trobacms,a.codlex AS amplificador,
                                                                            a.codtap AS tap,a.telf1,a.telf2,a.movil1,a.mac2,a.SERVICEPACKAGE,a.FECHAACTIVACION,

                                                                            a.numcoo_x,a.numcoo_y,
                                                                            IF(b.MACState ='Offline' AND c.macaddress IS NULL ,'#EE5F38',
                                                                            IF(c.DSPwr='-'  AND c.DSMER_SNR='-'  AND b.MACState <>'Offline','#E5F616','#24CB06')) AS  color,a.estado as estado_modem,
                                                                            c.macaddress
                                            FROM 
                                            (SELECT nc.*,pt.direc_inst,pt.nodo as nodocms,pt.plano as trobacms,pt.codlex,pt.codtap  
                                            FROM multiconsulta.nclientes nc LEFT JOIN cms.planta_clarita pt FORCE INDEX (CLIENTE) ON nc.idclientecrm=pt.cliente
                                            WHERE nc.nodo='$nodo' AND nc.troba='$troba' AND nc.idclientecrm<>969625)a
                                            LEFT JOIN  ccm1.scm_total b FORCE INDEX (MACAddress) ON a.mac2=b.macaddress
                                            LEFT JOIN ccm1.scm_phy_t c  FORCE INDEX (NewIndex1)  ON a.mac2=c.macaddress
                                            LEFT JOIN alertasx.alertasrf rf FORCE INDEX (cmts,Interface) ON c.cmts=rf.cmts AND c.interface=rf.interface
                                            LEFT JOIN cms.req_pend_macro rm FORCE INDEX (codcli) ON a.idclientecrm=rm.codcli
                                                                    ORDER BY a.nodo,a.troba,a.amplificador) dt ) a
                                    LEFT JOIN catalogos.planta_telef_cms_new pt ON a.idclientecrm=pt.cliente"
                                           
                );
    
            } catch(QueryException $ex){ 
                 //dd($ex->getMessage()); 
                throw new HttpException(500,"Problemas con la red, intente nuevamente.");
                // Note any method of class PDOException can be called on $ex.
            }
             #END QUERY
         //dd($result);
        return $result;
        
    }

    function procesarDiagnosticoMasivoResult($diagnosticoMasivo,$dataParametrosRF,$coloresEstadoDM,$coloresAveriaDM){
 
        $items = 0;
        for ($i=0; $i < count($diagnosticoMasivo); $i++) { 
            $items ++;
            $diagnosticoMasivo[$i]->items = $items;
            $diagnosticoMasivo[$i]->macstate = strtolower(trim($diagnosticoMasivo[$i]->macstate));
            $diagnosticoMasivo[$i]->macaddress = strtolower(trim($diagnosticoMasivo[$i]->macaddress));

            #ESTADO

                 $arrayEstado = Parametrosrf::getEstadoSegunNivelesRF($diagnosticoMasivo[$i]->macstate,$diagnosticoMasivo[$i]->macaddress,(double)$diagnosticoMasivo[$i]->DSMER_SNR,(double)$diagnosticoMasivo[$i]->DSPwr,
                                                                             (double)$diagnosticoMasivo[$i]->USMER_SNR,(double)$diagnosticoMasivo[$i]->USPwr,$dataParametrosRF);
                  
                 $estado = $arrayEstado["mensaje"];
                  
                 $diagnosticoMasivo[$i]->estado = $estado;

            #END ESTADO

            #COLORES SEGUN ESTADO 
           
                 if($estado == 'Niveles NO OK' || $estado =='Modem Sincronizado - No hay reporte de niveles - Validar Manualmente')
                 {			
                      
                      $estadoBackground = $coloresEstadoDM->colores[0]->background;														
                      $estadoColor = $coloresEstadoDM->colores[0]->color;														
                      
                 } else {
                           if($estado == "OK" && $diagnosticoMasivo[$i]->macstate!="offline") { 
                              // dd("aqui offline");
                                $estadoBackground = $coloresEstadoDM->colores[1]->background;														
                                $estadoColor = $coloresEstadoDM->colores[1]->color;	
                           
                           } else { 
                                $estadoBackground = $coloresEstadoDM->colores[2]->background;														
                                $estadoColor = $coloresEstadoDM->colores[2]->color;
                                
                           }
                 }
                 $diagnosticoMasivo[$i]->estadoBackground = $estadoBackground;														
                 $diagnosticoMasivo[$i]->estadoColor = $estadoColor;		
            
            #END COLORES ESTADO
            
            #COLORES RF
                 $diagnosticoMasivo[$i]->coloresNivelesRuido= Parametrosrf::getColoresNivelesRF((double)$diagnosticoMasivo[$i]->DSMER_SNR,(double)$diagnosticoMasivo[$i]->DSPwr,
                                                              (double)$diagnosticoMasivo[$i]->USMER_SNR,(double)$diagnosticoMasivo[$i]->USPwr,$dataParametrosRF);
                 
            #END COLORES RF

            #COLORES AVERIAS
                 $diagnosticoMasivo[$i]->averiasBackground = $coloresAveriaDM->colores[0]->background;														
                 $diagnosticoMasivo[$i]->averiasColor = $coloresAveriaDM->colores[0]->color;	
            #END COLORES AVERIAS
        }
 
        /*$modificandoResult = array_map(function($ele) use($dataParametrosRF,$coloresEstadoDM,$coloresAveriaDM){
           
            $ele->macstate = strtolower(trim($ele->macstate));
            $ele->macaddress = strtolower(trim($ele->macaddress));

            #ESTADO

                 $arrayEstado = Parametrosrf::getEstadoSegunNivelesRF($ele->macstate,$ele->macaddress,(double)$ele->DSMER_SNR,(double)$ele->DSPwr,
                                                                             (double)$ele->USMER_SNR,(double)$ele->USPwr,$dataParametrosRF);
                  
                 $estado = $arrayEstado["mensaje"];
                  
                 $ele->estado = $estado;

            #END ESTADO

            #COLORES SEGUN ESTADO 
           
                 if($estado == 'Niveles NO OK' || $estado =='Modem Sincronizado - No hay reporte de niveles - Validar Manualmente')
                 {			
                      
                      $estadoBackground = $coloresEstadoDM->colores[0]->background;														
                      $estadoColor = $coloresEstadoDM->colores[0]->color;														
                      
                 } else {
                           if($estado == "OK" && $ele->macstate!="offline") { 
                              // dd("aqui offline");
                                $estadoBackground = $coloresEstadoDM->colores[1]->background;														
                                $estadoColor = $coloresEstadoDM->colores[1]->color;	
                           
                           } else { 
                                $estadoBackground = $coloresEstadoDM->colores[2]->background;														
                                $estadoColor = $coloresEstadoDM->colores[2]->color;
                                
                           }
                 }
                 $ele->estadoBackground = $estadoBackground;														
                 $ele->estadoColor = $estadoColor;		
            
            #END COLORES ESTADO
            
            #COLORES RF
                 $ele->coloresNivelesRuido= Parametrosrf::getColoresNivelesRF((double)$ele->DSMER_SNR,(double)$ele->DSPwr,
                                                              (double)$ele->USMER_SNR,(double)$ele->USPwr,$dataParametrosRF);
                 
            #END COLORES RF

            #COLORES AVERIAS
                 $ele->averiasBackground = $coloresAveriaDM->colores[0]->background;														
                 $ele->averiasColor = $coloresAveriaDM->colores[0]->color;	
            #END COLORES AVERIAS
       
            return $ele;

       },$diagnosticoMasivo);*/

       //dd($diagnosticoMasivo);

       return $diagnosticoMasivo;
    }

    function getDownByCmtsAndInterface($cmts,$interface){
        $resultado = DB::select("select down from 
                                reportes.portadorasxpuerto_tr 
                                where cmts=? and interface=?",[$cmts,$interface]);

     
        $down = empty($resultado) ? "" : $resultado[0]->down;

        return $down;
    }

    function getGraficoDownSaturadoCmts($down,$cmts){

        $resultado = DB::select("select a.cmts,a.down,a.fecha_hora,a.uso,cant 
                                FROM reportes.uso_portadoras a 
                                WHERE a.down=? and cmts=? AND 
                                TIMEDIFF(NOW(),a.fecha_hora)<='1440:59:59'  
                                order by a.fecha_hora desc ",[$down,$cmts]);
        return $resultado;
    }

    //Lista de Trobas

    function getTrobasTotales()
    {
        $lista = DB::select("select concat(nodo,troba) as nodotroba from ccm1.cantroba group by nodo,troba");

        return $lista;
    }

    //Interfaces
    function getInterfaces()
    {
        $lista = DB::select("select * FROM ccm1_temporal.interfaces_lb");
        return $lista;
    }

    //Niveles por Puerto
    function getNivelesPorPuerto()
    {
        $lista = DB::select("SELECT CONCAT(a.cmts,'-',a.description) puerto,REPLACE(a.cmts,' ','') AS cmts,a.interface
                                FROM  catalogos.etiqueta_puertos a
                            WHERE  TRIM(a.interface)<>'' AND CONCAT(a.cmts,'-',a.description)  IS NOT NULL 
                            AND CONCAT(a.cmts,'-',a.description)<>'-' AND a.description<>''
                            GROUP BY 1");
        return $lista;
    }

    function getTrobasByJefatura($jefatura)
    {
        $lista = DB::select("select 
                                CONCAT(n.nodo,'-',n.troba) AS nodotroba 
                                FROM ccm1.cantroba n 
                                INNER JOIN catalogos.jefaturas j
                                ON n.nodo=j.nodo
                                WHERE j.jefatura='$jefatura'
                                GROUP BY n.nodo,n.troba");
        return $lista;
    }

    function getTrobasByJefaturaJoin($jefatura)
    {
        $lista = DB::select("select 
                                CONCAT(n.nodo,n.troba) AS clave 
                                FROM ccm1.cantroba n 
                                INNER JOIN catalogos.jefaturas j
                                ON n.nodo=j.nodo
                                WHERE j.jefatura='$jefatura'
                                GROUP BY n.nodo,n.troba");
        return $lista;
    }

    function getNodosByJefatura($jefatura)
    {
        $lista = DB::select("SELECT DISTINCT nodo FROM catalogos.`jefaturas`
                            WHERE jefatura = '$jefatura' AND tipo LIKE 'NODO/%' 
                            ORDER BY nodo");
                            return $lista;
    }

    function getNodos()
    {
        $lista = DB::select("SELECT DISTINCT nodo FROM catalogos.jefaturas
                            WHERE tipo LIKE 'NODO/%' 
                            ORDER BY nodo");
                            return $lista;
    }

    function getCmts(){
        $listaCmts = DB::select("select cmts FROM ccm1.cmts_ip GROUP BY 1");
        return $listaCmts;
    }

    function getJefaturas(){
        $listaCmts = DB::select("select jefatura FROM catalogos.jefaturas GROUP BY 1");
        return $listaCmts;
    }

    function getTrobas(){
        $listaTrobas = DB::select("SELECT nodo,troba,clave FROM ccm1.cantroba GROUP BY clave");
        return $listaTrobas;
    }

    function getServicepackageCRMID()
    {
        $lista = DB::select("SELECT SERVICEPACKAGECRMID FROM multiconsulta.nclientes_c WHERE SERVICEPACKAGECRMID != '' GROUP BY 1 ");
        return $lista;
    }

    function getScopeGroup()
    {
        $lista = DB::select("SELECT SCOPESGROUP FROM multiconsulta.nclientes_c WHERE SCOPESGROUP != '' GROUP BY 1 ");
        return $lista;
    }

    function getHistorialNodoTroba($nodo,$troba)
    {
        $historial = DB::select("SELECT
                            a.`nodo`,
                            a.`troba`,
                            a.`Max_USPwr` AS powerup_max,
                            a.`Pro_USPwr` AS powerup_prom ,
                            a.`Min_DSPwr` AS powerup_min ,
                            a.`Max_DSPwr` AS powerds_max,
                            a.`Pro_DSPwr` AS powerds_prom ,
                            a.`Min_DSPwr` AS powerds_min ,
                            a.`USSnr` AS snr_avg,
                            a.`DSSnr` AS snr_down,
                            a.fecha_hora,
                            a.`cmts`,
                            a.`interface`
                            FROM ccm1.level_troba_hist_final a
                            WHERE nodo='$nodo' AND troba='$troba' ORDER BY a.fecha_hora DESC LIMIT 1200");
        return $historial;
    }

    function getCantidadTrobasByInterfaces($interfaces)
    {
        try {
            $cantidadLista = DB::select("SELECT  nodo,troba,COUNT(*) AS cant
                            FROM multiconsulta.`nclientes` a
                            INNER JOIN  ccm1.scm_total b ON a.mac2=b.macaddress
                            LEFT JOIN ccm1.scm_phy_t c ON a.mac2=c.macaddress
                            WHERE CONCAT(c.cmts,c.interface)IN ($interfaces) OR CONCAT(b.cmts,b.interface) IN ($interfaces)
                            GROUP BY 1,2");

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           //return "error";
       } 
      

        return $cantidadLista;
    }

    function getPromediosNivelesCmtsPorPuertos($puerto)
    {
        try {
            $lista = DB::select(" select 
                                            a.cmts,
                                            a.Interface,
                                            b.description,
                                            ROUND(MAX(a.USPwr),2) AS powerup_max,
                                            ROUND(AVG(a.USPwr),2) AS powerup_prom , 
                                            ROUND(MIN(a.USPwr),2) AS powerup_min ,
                                            ROUND(MAX(a.DSPwr)*1,2) AS powerds_max,
                                            ROUND(AVG(a.DSPwr)*1,2) AS powerds_prom , 
                                            ROUND(MIN(replace(a.DSPwr,' ','')*1),2) AS powerds_min ,
                                            ROUND(AVG(a.USMER_SNR),2) AS snr_avg,
                                            a.fecha_hora,
                                            c.ip
                                            FROM ccm1.scm_phy_t a LEFT JOIN catalogos.etiqueta_puertos b
                                            ON a.cmts=b.cmts AND a.interface=b.interface
                                            LEFT JOIN ccm1.cmts_ip c ON a.cmts=c.cmts
                                            WHERE replace(concat(trim(a.cmts),trim(a.interface)),' ','')='$puerto' 
                                            AND  a.DSMER_SNR NOT IN ('-','-----')
                                            GROUP BY a.cmts,b.interface");

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
       } 
      

        return $lista;
    }

    function getProcesarNivelesCmtsPuertos($niveles)
    { 

        $parametrosRf = new Parametrosrf;
        $parametrosNivelesPuertos = $parametrosRf->getDescargaClienteTrobaRF();
        $colores= json_decode($parametrosNivelesPuertos->colores); 
        //dd($colores);

        for ($i=0; $i < count($niveles); $i++) { 


            $niveles[$i]->interfaceSubStr=substr($niveles[$i]->Interface,0,6);


            if ($niveles[$i]->powerup_prom < $parametrosNivelesPuertos->power_up_min || $niveles[$i]->powerup_prom > $parametrosNivelesPuertos->power_up_max || $niveles[$i]->powerds_prom < $parametrosNivelesPuertos->power_down_min || $niveles[$i]->powerds_prom>$parametrosNivelesPuertos->power_down_max || $niveles[$i]->snr_avg< $parametrosNivelesPuertos->snr_up_min )
            {
                $niveles[$i]->backgrounPrincipal=$colores->nivel_prom[0]->background;
                $niveles[$i]->colorPrincipal=$colores->nivel_prom[0]->color; 
            } else {
                $niveles[$i]->backgrounPrincipal=$colores->nivel_prom[5]->background;
                $niveles[$i]->colorPrincipal=$colores->nivel_prom[5]->color;
            }

            if ($niveles[$i]->powerup_prom < $parametrosNivelesPuertos->power_up_min || $niveles[$i]->powerup_prom  >$parametrosNivelesPuertos->power_up_max )
            { 
                    $niveles[$i]->backgrounPowerUpProm=$colores->nivel_prom[1]->background;
                    $niveles[$i]->colorPowerUpProm=$colores->nivel_prom[1]->color;
            } else { 
                    $niveles[$i]->backgrounPowerUpProm=$colores->nivel_prom[0]->background;
                    $niveles[$i]->colorPowerUpProm=$colores->nivel_prom[0]->color;
            }
  
            if ($niveles[$i]->powerds_prom <- 5 || $niveles[$i]->powerds_prom > $parametrosNivelesPuertos->power_down_max )
            { 
                    $niveles[$i]->backgrounPowerDowsProm = $colores->nivel_prom[2]->background;
                    $niveles[$i]->colorPowerDowsProm = $colores->nivel_prom[2]->color;
            } else { 
                    $niveles[$i]->backgrounPowerDowsProm = $colores->nivel_prom[0]->background;
                    $niveles[$i]->colorPowerDowsProm = $colores->nivel_prom[0]->color;
            }
 
            if ($niveles[$i]->snr_avg < $parametrosNivelesPuertos->snr_up_min )
            { 
                    $niveles[$i]->backgrounSnrArvg = $colores->nivel_prom[3]->background;
                    $niveles[$i]->colorSnrArvg= $colores->nivel_prom[3]->color;
            } else { 
                    $niveles[$i]->backgrounSnrArvg = $colores->nivel_prom[0]->background;
                    $niveles[$i]->colorSnrArvg= $colores->nivel_prom[0]->color;
            }

            if (isset($niveles[$i]->snr_down)) {
                if ($niveles[$i]->snr_down < $parametrosNivelesPuertos->snr_down_min && $niveles[$i]->snr_down > $parametrosNivelesPuertos->snr_down_max)
                {
                        $niveles[$i]->backgrounSnrDown=$colores->nivel_prom[4]->background;
                        $niveles[$i]->colorSnrDown=$colores->nivel_prom[4]->color;
                } else {
                        $niveles[$i]->backgrounSnrDown = $colores->nivel_prom[0]->background;
                        $niveles[$i]->colorSnrDown= $colores->nivel_prom[0]->color;
                }
            }

            

            
        }

        return $niveles;

    }

    function getHistoricoNivelesCmtsPorPuertos($puerto)
    {
        try {
            $lista = DB::select("   select
                                    a.cmts,
                                    a.Interface,
                                    a.description,
                                    a.powerup_max,
                                    a.powerup_prom ,
                                    a.powerup_min ,
                                    a.powerds_max,
                                    a.powerds_prom ,
                                    a.powerds_min ,
                                    a.snr_avg,
                                    a.snr_down,
                                    a.fecha_hora
                                    FROM ccm1.scm_phy_hist_final a
                                    WHERE CONCAT(a.cmts,a.interface)='$puerto'
                                    ORDER BY 12 DESC");//LIMIT 360

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
       } 
      

        return $lista;
    }

    function getHistoricoInterface($interface)
    {
        try {
            $lista = DB::select("select
                            a.cmts,
                            a.Interface,
                            a.description,
                            a.powerup_prom ,
                            a.powerds_prom ,
                            a.snr_avg,
                            a.snr_down,
                            a.fecha_hora
                            FROM ccm1.scm_phy_hist_final a
                            WHERE a.puerto='$interface'
                            ORDER BY fecha_hora DESC");
            return $lista;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
       } 
       
    }
    
 
}