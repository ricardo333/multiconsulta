<?php 

namespace App\Functions;
use DB; 
use App\Administrador\Parametrosrf;
use App\Functions\GestionFunctions;
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
use App\Administrador\GestionCuarentena;
use App\Functions\GestionCuarentenaFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;
  
class CuarentenaFunctions {

    function nombres($idCuarentena)
    {
        if ($idCuarentena=="") {
            $nombres = DB::select("SELECT id, nombre, tipo FROM zz_new_system.gestion_cuarentena WHERE estado='Activo' order by nombre asc");
        } else {
            $nombres = DB::select("SELECT id, nombre, tipo FROM zz_new_system.gestion_cuarentena WHERE id='$idCuarentena'");
        }
        
        //$nombres = DB::select("SELECT id, nombre, tipo FROM zz_new_system.gestion_cuarentena WHERE estado='Activo' order by nombre asc");
        return $nombres;
    }

    function getlistaAveriasCuarentenas($idCuarentena,$preguntaHoy,$averiaReiteradaPendiente,$filtroJefatura,$codmotv,$tipoEstado,$segunColor)
    {
        
        try {

            $lista = DB::select(" 
            SELECT zx.* FROM 
                    (
                    SELECT 
                        b.jefatura,a.*,SUBSTR(a.status,1,1) AS st, 
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'Bandeja: ',a.codctr,' ',a.pctr),'') AS averia, 
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, 
                        IF(a.caida<>'','Caida Masiva detectada','') AS caidan , 
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'Bandeja: ',rm.codctr)) AS averiarm ,
                        a.codigoreq AS codigo_req,a.tecnico AS  tecnicon,a.fecha_liquidacion AS fecha_liquidacionn,nameclient AS nombrepl 
                    FROM zz_new_system.cuarentenas_total a 
                    LEFT JOIN catalogos.jefaturas b 
                    ON a.nodo=b.nodo 
                    LEFT JOIN cms.`req_pend_macro_final` rm 
                    ON a.`IDCLIENTECRM`=rm.`codcli` 
                    WHERE 1=1 
                        $preguntaHoy AND 
                        CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) IN ('PUNTUAL1.-Niveles NO OK','Puntual1.-Niveles NO OK','1.-Niveles NO OK') 
                        $filtroJefatura $averiaReiteradaPendiente $codmotv  $tipoEstado $segunColor 
                    AND a.idGestionCuarentena = $idCuarentena
                    GROUP BY a.macaddress 

                    UNION 

                    SELECT 
                        b.jefatura,a.*,SUBSTR(a.status,1,1) AS st, 
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'Bandeja: ',a.codctr,' ',a.pctr),'') AS averia, 
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, 
                        IF(a.caida<>'','Caida Masiva detectada','') AS caidan , 
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'Bandeja: ',rm.codctr)) AS averiarm ,
                        a.codigoreq AS codigo_req,a.tecnico AS tecnicon,a.fecha_liquidacion AS fecha_liquidacionn,a.nameclient AS nombrepl 
                    FROM zz_new_system.cuarentenas_total a 
                    LEFT JOIN catalogos.jefaturas b ON a.nodo=b.nodo 
                    LEFT JOIN cms.`req_pend_macro_final` rm ON a.`IDCLIENTECRM`=rm.`codcli` 
                    WHERE 1=1 
                            $preguntaHoy AND 
                            CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) IN ('PUNTUAL2.- Offline - NO OK','Puntual2.- Offline - NO OK','2.- Offline - NO OK') 
                            $filtroJefatura $averiaReiteradaPendiente $codmotv $tipoEstado $segunColor 
                    AND a.idGestionCuarentena = $idCuarentena
                    GROUP BY a.macaddress 

                    UNION 

                    SELECT 
                        b.jefatura,a.*,SUBSTR(a.status,1,1) AS st, 
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'Bandeja: ',a.codctr,' ',a.pctr),'') AS averia, 
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, 
                        IF(a.caida<>'','Caida Masiva detectada','') AS caidan , 
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'Bandeja: ',rm.codctr)) AS averiarm ,
                        a.codigoreq AS codigo_req,a.tecnico AS tecnicon,a.fecha_liquidacion AS fecha_liquidacionn,a.nameclient AS nombrepl 
                    FROM zz_new_system.cuarentenas_total a 
                    LEFT JOIN catalogos.jefaturas b ON a.nodo=b.nodo 
                    LEFT JOIN cms.`req_pend_macro_final` rm ON a.`IDCLIENTECRM`=rm.`codcli` 
                    WHERE 1=1 
                        $preguntaHoy AND 
                        CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) 
                        NOT IN ('PUNTUAL1.-Niveles NO OK','Puntual1.-Niveles NO OK','1.-Niveles NO OK' ,'PUNTUAL2.- Offline - NO OK','Puntual2.- Offline - NO OK','2.- Offline - NO OK') 
                        $filtroJefatura  $averiaReiteradaPendiente $codmotv $tipoEstado $segunColor 
                        AND a.idGestionCuarentena = $idCuarentena
                    GROUP BY a.macaddress 
                    ) zx 
                    ORDER BY zx.status
                    " );

        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
             return "error";
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
            
        }catch(\Exception $e){
             //dd($e->getMessage());  
             return "error";
            // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
        }  

        return $lista;
    } 

    function procesarListaAveriasCuarentenas($lista)
    {

        $gestionF = new GestionFunctions;
        $parametrosRF = new Parametrosrf;
        $parametrosRFLista = $parametrosRF->getCuarentenasRF();
        $CuarentenaRF = $parametrosRF->getDecodeJsonNivelesRF($parametrosRFLista);
         
        
        $coloresConfigCuarentena =  ParametroColores::getCuarentenasParametros();
        $colores = $coloresConfigCuarentena->COLORES;
         
        $cantidad = count($lista);
        for ($i=0; $i < $cantidad; $i++) { 
            $lista[$i]->item = $i + 1;

            $st = $lista[$i]->st;
               
            if ($st < 2) {
                $background = $colores->segunNumeroEstado->colores[0]->background;
                $color = $colores->segunNumeroEstado->colores[0]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[0]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[0]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->fechaColorEstadoGestion;
 

            }
            if ($st == 2) {
                $background = $colores->segunNumeroEstado->colores[1]->background;
                $color = $colores->segunNumeroEstado->colores[1]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[1]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[1]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->fechaColorEstadoGestion;

            }
            if ($st == 3) {
                $background = $colores->segunNumeroEstado->colores[2]->background;
                $color = $colores->segunNumeroEstado->colores[2]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[2]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[2]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->fechaColorEstadoGestion;

                
            }
            if ($st > 3) {
                $background = $colores->segunNumeroEstado->colores[3]->background;
                $color = $colores->segunNumeroEstado->colores[3]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[3]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[3]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->fechaColorEstadoGestion;

            }

            if ($lista[$i]->STATUS == "8.-Servicio Suspendido") {
                $background = $colores->segunTextoEstado->colores[0]->background;
                $color = $colores->segunTextoEstado->colores[0]->color;

                $gestionRegistroIcon = $colores->segunTextoEstado->colores[0]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunTextoEstado->colores[0]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunTextoEstado->colores[0]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunTextoEstado->colores[0]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunTextoEstado->colores[0]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunTextoEstado->colores[0]->fechaColorEstadoGestion;

            }
 
            //$clienteGestionCuarentena = $gestionF->getGestionClienteCuarentenaByID($lista[$i]->IDCLIENTECRM);
             $clienteGestionCuarentena = $gestionF->getGestionClienteCuarentenaByID($lista[$i]->IDCLIENTECRM);
           
            if (isset($clienteGestionCuarentena[0])) {
 
                if(($clienteGestionCuarentena[0]->tipoaveria == 'APAGA MODEM' || $clienteGestionCuarentena[0]->tipoaveria == 'NO DESEA ATENCION') &&
                    $lista[$i]->STATUS <>'7.-OK'){
                        $background = $colores->segunTextoEstado->colores[1]->background;
                        $color = $colores->segunTextoEstado->colores[1]->color;

                        $gestionRegistroIcon = $colores->segunTextoEstado->colores[1]->gestionRegistroIcon;
                        $gestionDetalleIcon = $colores->segunTextoEstado->colores[1]->gestionDetalleIcon;
        
                        $tituloColorEstadoGestion = $colores->segunTextoEstado->colores[1]->tituloColorEstadoGestion;
                        $contenidoColorEstadoGestion = $colores->segunTextoEstado->colores[1]->contenidoColorEstadoGestion;
                        $usuarioColorEstadoGestion = $colores->segunTextoEstado->colores[1]->usuarioColorEstadoGestion;
                        $fechaColorEstadoGestion = $colores->segunTextoEstado->colores[1]->fechaColorEstadoGestion;

                    }
            } 
            

            $lista[$i]->clienteGestionCuarentena = $clienteGestionCuarentena;

            $lista[$i]->background = $background;
            $lista[$i]->colorText  = $color;

            $lista[$i]->gestionRegistroColor           = $gestionRegistroIcon;
            $lista[$i]->gestionDetalleColor            = $gestionDetalleIcon;

            $lista[$i]->tituloColorEstadoGestion       = $tituloColorEstadoGestion;
            $lista[$i]->contenidoColorEstadoGestion    = $contenidoColorEstadoGestion;
            $lista[$i]->usuarioColorEstadoGestion      = $usuarioColorEstadoGestion;
            $lista[$i]->fechaColorEstadoGestion        = $fechaColorEstadoGestion;


            if($lista[$i]->macstate=='online' || $lista[$i]->macstate=='w-online'){
                $pwrup=$lista[$i]->USPwr;
                $snrup=$lista[$i]->USMER_SNR;
                $pwrdn=$lista[$i]->DSPwr;
                $snrdn=$lista[$i]->DSMER_SNR;
                $rxpwr=$lista[$i]->RxPwrdBmv;
            }else{
                $pwrup='';
                $snrup='';
                $pwrdn='';
                $snrdn='';
                $rxpwr='';
            }

 
            
			if(( (double)$lista[$i]->USPwr < (double)$CuarentenaRF['up_pwr_min'] ||  (double)$lista[$i]->USPwr > (double)$CuarentenaRF['up_pwr_max'] ) &&  (double)$lista[$i]->USPwr > 0){
                $backgroundUSPwr    =   $CuarentenaRF['paramPOWER_UPColors'][0]->background;
                $colorUSPwr         =   $CuarentenaRF['paramPOWER_UPColors'][0]->color;
            }else{
                $backgroundUSPwr    =   $CuarentenaRF['paramPOWER_UPColors'][1]->background;
                $colorUSPwr         =   $CuarentenaRF['paramPOWER_UPColors'][1]->color;
            }
            
            if(((double)$lista[$i]->USMER_SNR < (double)$CuarentenaRF['up_snr_min'] ) && (double)$lista[$i]->USMER_SNR > (double)$CuarentenaRF['up_snr_max'] ){
                $backgroundUSMER_SNR    =   $CuarentenaRF['paramSNR_UPColors'][0]->background;
                $colorUSMER_SNR         =   $CuarentenaRF['paramSNR_UPColors'][0]->color;
            }else{
                $backgroundUSMER_SNR    =   $CuarentenaRF['paramSNR_UPColors'][1]->background;
                $colorUSMER_SNR         =   $CuarentenaRF['paramSNR_UPColors'][1]->color;
            }
			 
            if(((double)$lista[$i]->DSPwr < (double)$CuarentenaRF['down_pwr_min'] || (double)$lista[$i]->DSPwr > (double)$CuarentenaRF['down_pwr_max']) && (double)$lista[$i]->DSPwr<>''){
                $backgroundDSPwr    =   $CuarentenaRF['paramPOWER_DOWNColors'][0]->background;
                $colorDSPwr         =   $CuarentenaRF['paramPOWER_DOWNColors'][0]->color;
            }else{
                $backgroundDSPwr    =   $CuarentenaRF['paramPOWER_DOWNColors'][1]->background;
                $colorDSPwr         =   $CuarentenaRF['paramPOWER_DOWNColors'][1]->color;
            }
			 
            if((double)$lista[$i]->DSMER_SNR < (double)$CuarentenaRF['down_snr_min'] && (double)$lista[$i]->DSMER_SNR > (double)$CuarentenaRF['down_snr_max']){
                $backgroundsnrdn    =   $CuarentenaRF['paramSNR_DOWNColors'][0]->background;
                $colorsnrdn         =   $CuarentenaRF['paramSNR_DOWNColors'][0]->color;
            }else{
                $backgroundsnrdn    =   $CuarentenaRF['paramSNR_DOWNColors'][1]->background;
                $colorsnrdn         =   $CuarentenaRF['paramSNR_DOWNColors'][1]->color;
            }
           
            $lista[$i]->USPwr = $pwrup;
            $lista[$i]->USMER_SNR = $snrup;
            $lista[$i]->DSPwr = $pwrdn;
            $lista[$i]->DSMER_SNR = $snrdn;
            $lista[$i]->RxPwrdBmv = $rxpwr;

            $lista[$i]->backgroundUSPwr = $backgroundUSPwr;
            $lista[$i]->colorUSPwr = $colorUSPwr;
            $lista[$i]->backgroundUSMER_SNR = $backgroundUSMER_SNR;
            $lista[$i]->colorUSMER_SNR = $colorUSMER_SNR;
            $lista[$i]->backgroundDSPwr = $backgroundDSPwr;
            $lista[$i]->colorDSPwr = $colorDSPwr;
            $lista[$i]->backgroundsnrdn = $backgroundsnrdn;
            $lista[$i]->colorsnrdn = $colorsnrdn;
  
            // dd($lista[$i]);

           $lista[$i]->situacion = $lista[$i]->averia." ".$lista[$i]->nummasiva." ".$lista[$i]->Caida;

           $lista[$i]->codigoreq = (int) $lista[$i]->codigoreq > 0 ?  $lista[$i]->codigoreq : "";

        }

        return $lista;

    }

    function getlistaCriticosCuarentenas($idCuarentena,$averiaReiteradaPendiente,$filtroJefatura)
    {
        
        try {

            $lista = DB::select(" 
                SELECT 
                b.jefatura,SUBSTR(a.status,1,1) AS st,
                IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'</br>',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'</br>Bandeja: ',a.codctr,' ',a.pctr),'') AS averia,
                IF(a.Masiva IS NOT NULL,a.Masiva,'') AS nummasiva,
                IF(a.caida<>'','</br>Caida Masiva detectada','') AS caida,a.entidad,
                IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'Bandeja: ',rm.codctr)) AS averiarm ,
                a.* 
                FROM 
                zz_new_system.cuarentenas_total a  FORCE INDEX (IDCLIENTECRM,nodo)
                LEFT JOIN catalogos.jefaturas b FORCE INDEX (nodo)
                    ON a.NODO=b.nodo
                LEFT JOIN cms.`req_pend_macro_final` rm  FORCE INDEX (codcli)
                    ON a.`IDCLIENTECRM`=rm.`codcli` 
                WHERE 1=1 $filtroJefatura $averiaReiteradaPendiente 
                AND a.idGestionCuarentena = $idCuarentena
                ORDER BY st  
             " );

        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
             return "error";
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
            
        }catch(\Exception $e){
             //dd($e->getMessage());  
             return "error";
            // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
        }  

        return $lista;
    } 

    function procesarListaCriticosCuarentenas($lista)
    {

        $gestionF = new GestionFunctions;
        $parametrosRF = new Parametrosrf;
        $parametrosRFLista = $parametrosRF->getCuarentenasRF();
        $CuarentenaRF = $parametrosRF->getDecodeJsonNivelesRF($parametrosRFLista);

        $coloresConfigCuarentena =  ParametroColores::getCuarentenasParametros();
        $colores = $coloresConfigCuarentena->COLORES;

        $cantidad = count($lista);

        for ($i=0; $i < $cantidad ; $i++) { 
            
            $lista[$i]->item = $i + 1;

            $st = $lista[$i]->st;
               
            if ($st < 2) {
                $background = $colores->segunNumeroEstado->colores[0]->background;
                $color = $colores->segunNumeroEstado->colores[0]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[0]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[0]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[0]->fechaColorEstadoGestion;
 

            }
            if ($st == 2) {
                $background = $colores->segunNumeroEstado->colores[1]->background;
                $color = $colores->segunNumeroEstado->colores[1]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[1]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[1]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[1]->fechaColorEstadoGestion;

            }
            if ($st == 3) {
                $background = $colores->segunNumeroEstado->colores[2]->background;
                $color = $colores->segunNumeroEstado->colores[2]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[2]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[2]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[2]->fechaColorEstadoGestion;

                
            }
            if ($st > 3) {
                $background = $colores->segunNumeroEstado->colores[3]->background;
                $color = $colores->segunNumeroEstado->colores[3]->color;

                $gestionRegistroIcon = $colores->segunNumeroEstado->colores[3]->gestionRegistroIcon;
                $gestionDetalleIcon = $colores->segunNumeroEstado->colores[3]->gestionDetalleIcon;

                $tituloColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->tituloColorEstadoGestion;
                $contenidoColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->contenidoColorEstadoGestion;
                $usuarioColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->usuarioColorEstadoGestion;
                $fechaColorEstadoGestion = $colores->segunNumeroEstado->colores[3]->fechaColorEstadoGestion;

            }

            $clienteGestionCuarentena = $gestionF->getGestionClienteCuarentenaByID($lista[$i]->IDCLIENTECRM);
           
            if (isset($clienteGestionCuarentena[0])) {
 
                if($clienteGestionCuarentena[0]->tipoaveria == 'APAGA MODEM' || $clienteGestionCuarentena[0]->tipoaveria == 'NO DESEA ATENCION'){
                        $background = $colores->segunTextoEstado->colores[2]->background;
                        $color = $colores->segunTextoEstado->colores[2]->color;

                        $gestionRegistroIcon = $colores->segunTextoEstado->colores[2]->gestionRegistroIcon;
                        $gestionDetalleIcon = $colores->segunTextoEstado->colores[2]->gestionDetalleIcon;
        
                        $tituloColorEstadoGestion = $colores->segunTextoEstado->colores[2]->tituloColorEstadoGestion;
                        $contenidoColorEstadoGestion = $colores->segunTextoEstado->colores[2]->contenidoColorEstadoGestion;
                        $usuarioColorEstadoGestion = $colores->segunTextoEstado->colores[2]->usuarioColorEstadoGestion;
                        $fechaColorEstadoGestion = $colores->segunTextoEstado->colores[2]->fechaColorEstadoGestion;

                    }
            } 

            $lista[$i]->clienteGestionCuarentena = $clienteGestionCuarentena;

            $lista[$i]->background = $background;
            $lista[$i]->colorText  = $color;

            $lista[$i]->gestionRegistroColor           = $gestionRegistroIcon;
            $lista[$i]->gestionDetalleColor            = $gestionDetalleIcon;

            $lista[$i]->tituloColorEstadoGestion       = $tituloColorEstadoGestion;
            $lista[$i]->contenidoColorEstadoGestion    = $contenidoColorEstadoGestion;
            $lista[$i]->usuarioColorEstadoGestion      = $usuarioColorEstadoGestion;
            $lista[$i]->fechaColorEstadoGestion        = $fechaColorEstadoGestion;

            if($lista[$i]->macstate=='online' || $lista[$i]->macstate=='w-online'){
                $pwrup=$lista[$i]->USPwr;
                $snrup=$lista[$i]->USMER_SNR;
                $pwrdn=$lista[$i]->DSPwr;
                $snrdn=$lista[$i]->DSMER_SNR;
                $rxpwr=$lista[$i]->RxPwrdBmv;
            }else{
                $pwrup='';
                $snrup='';
                $pwrdn='';
                $snrdn='';
                $rxpwr='';
            }

            if(( (double)$lista[$i]->USPwr < (double)$CuarentenaRF['up_pwr_min'] ||  (double)$lista[$i]->USPwr > (double)$CuarentenaRF['up_pwr_max'] ) &&  (double)$lista[$i]->USPwr > 0){
                $backgroundUSPwr    =   $CuarentenaRF['paramPOWER_UPColors'][0]->background;
                $colorUSPwr         =   $CuarentenaRF['paramPOWER_UPColors'][0]->color;
            }else{
                $backgroundUSPwr    =   $CuarentenaRF['paramPOWER_UPColors'][1]->background;
                $colorUSPwr         =   $CuarentenaRF['paramPOWER_UPColors'][1]->color;
            }
            
            if(((double)$lista[$i]->USMER_SNR < (double)$CuarentenaRF['up_snr_min'] ) && (double)$lista[$i]->USMER_SNR > (double)$CuarentenaRF['up_snr_max'] ){
                $backgroundUSMER_SNR    =   $CuarentenaRF['paramSNR_UPColors'][0]->background;
                $colorUSMER_SNR         =   $CuarentenaRF['paramSNR_UPColors'][0]->color;
            }else{
                $backgroundUSMER_SNR    =   $CuarentenaRF['paramSNR_UPColors'][1]->background;
                $colorUSMER_SNR         =   $CuarentenaRF['paramSNR_UPColors'][1]->color;
            }
			 
            if(((double)$lista[$i]->DSPwr < (double)$CuarentenaRF['down_pwr_min'] || (double)$lista[$i]->DSPwr > (double)$CuarentenaRF['down_pwr_max']) && (double)$lista[$i]->DSPwr<>''){
                $backgroundDSPwr    =   $CuarentenaRF['paramPOWER_DOWNColors'][0]->background;
                $colorDSPwr         =   $CuarentenaRF['paramPOWER_DOWNColors'][0]->color;
            }else{
                $backgroundDSPwr    =   $CuarentenaRF['paramPOWER_DOWNColors'][1]->background;
                $colorDSPwr         =   $CuarentenaRF['paramPOWER_DOWNColors'][1]->color;
            }
			 
            if((double)$lista[$i]->DSMER_SNR < (double)$CuarentenaRF['down_snr_min'] && (double)$lista[$i]->DSMER_SNR > (double)$CuarentenaRF['down_snr_max']){
                $backgroundsnrdn    =   $CuarentenaRF['paramSNR_DOWNColors'][0]->background;
                $colorsnrdn         =   $CuarentenaRF['paramSNR_DOWNColors'][0]->color;
            }else{
                $backgroundsnrdn    =   $CuarentenaRF['paramSNR_DOWNColors'][1]->background;
                $colorsnrdn         =   $CuarentenaRF['paramSNR_DOWNColors'][1]->color;
            }

            $lista[$i]->USPwr = $pwrup;
            $lista[$i]->USMER_SNR = $snrup;
            $lista[$i]->DSPwr = $pwrdn;
            $lista[$i]->DSMER_SNR = $snrdn;
            $lista[$i]->RxPwrdBmv = $rxpwr;

            $lista[$i]->backgroundUSPwr = $backgroundUSPwr;
            $lista[$i]->colorUSPwr = $colorUSPwr;
            $lista[$i]->backgroundUSMER_SNR = $backgroundUSMER_SNR;
            $lista[$i]->colorUSMER_SNR = $colorUSMER_SNR;
            $lista[$i]->backgroundDSPwr = $backgroundDSPwr;
            $lista[$i]->colorDSPwr = $colorDSPwr;
            $lista[$i]->backgroundsnrdn = $backgroundsnrdn;
            $lista[$i]->colorsnrdn = $colorsnrdn;

            $lista[$i]->situacion = $lista[$i]->averia." ".$lista[$i]->nummasiva." ".$lista[$i]->Caida;
 
            $lista[$i]->codigoreq = (int) $lista[$i]->codigoreq > 0 ?  $lista[$i]->codigoreq : "";
        }

        return $lista;

    }
  
}