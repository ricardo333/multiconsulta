<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;
use App\Administrador\ParametroColores;

class MasivaCmsFunctions {


    function getMasivaCms($filtroJefatura,$filtroEstado)
    {
        $listaCms = DB::select(
            "SELECT * FROM (
                SELECT zn1. jefatura,h.estado AS estadog,
                b.codnod AS nodo,b.nroplano AS troba ,coff.cancli,coff.umbral,coff.offline, p.estado AS trabajo_estado,
                DATEDIFF(NOW(),CONCAT(SUBSTR(b.fecreg,7,4),'-',SUBSTR(b.fecreg,4,2),'-',SUBSTR(b.fecreg,1,2),' ',SUBSTR(b.fecreg,12,8))) AS dias,
                b.codreqmnt AS codmasiva,CONCAT(STR_TO_DATE(fecreg, '%d/%m/%Y'),' ',TRIM((SUBSTR(fecreg,11,6))))  AS fecha_hora,coff.Caida AS estado,
                TIMEDIFF(NOW(),CONCAT(STR_TO_DATE(fecreg, '%d/%m/%Y'),' ',TRIM((SUBSTR(fecreg,11,6))))) AS tiempo,c.cant AS cant1, 
                CONCAT(c.eventid,' ',c.usuario,'-',c.fecha_inicio) AS eventid, COUNT(cr.nodo) AS ncrit,c.cant AS cantl,
                d.tipo,'' AS fecha_fin, IF(e.caidas >3 OR d.numbor>1000,'CRITICA','') AS tcaidas,e.caidas AS ncaidas,d.numbor, dg.fecha AS fecha_digi,
                IF(dg.nodo IS NOT NULL ,'Digitalizado','') AS digi,IF(tr.critica=1,'TC','') AS tc,IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,rpv.cpend,rm.remedy
                FROM  dbpext.masivas_temp b
                LEFT JOIN alertasx.alertas_dmpe_view c ON b.codnod=c.nodo AND b.nroplano=c.troba
                LEFT JOIN
                (SELECT f.* FROM alertasx.`gestion_alert` f INNER JOIN
                (SELECT nodo,troba,MAX(fechahora) AS fechahora FROM alertasx.`gestion_alert` WHERE DATEDIFF(NOW(),fechahora)=0 GROUP BY nodo,troba) g
                ON f.nodo=g.nodo AND f.troba=g.troba AND f.fechahora=g.fechahora) h ON b.codnod=h.nodo AND b.nroplano = h.troba
                LEFT JOIN catalogos.bornesxtroba d ON b.codnod=d.nodo AND b.nroplano=d.troba
                LEFT JOIN ccm1_temporal.cant_caidas e ON b.codnod=e.nodo AND b.nroplano=e.troba
                LEFT JOIN catalogos.trobas_digi_view dg ON b.codnod=dg.nodo AND b.nroplano=dg.troba
                LEFT JOIN catalogos.jefaturas zn1 ON zn1.`NODO`=b.`codnod`
                LEFT JOIN alertasx.offline_total coff ON b.codnod=coff.nodo AND b.nroplano=coff.troba
                LEFT JOIN catalogos.trobas_criticas_n tr ON b.codnod=tr.nodo AND b.nroplano=tr.troba
                LEFT JOIN catalogos.microzonas mz ON b.codnod=mz.nodo AND b.nroplano=mz.troba
                LEFT JOIN catalogos.db_fuentes fp ON b.codnod=fp.nodo AND b.nroplano=fp.troba
                LEFT JOIN cms.req_pend_view rpv ON b.codnod=rpv.nodo AND b.nroplano=rpv.troba
                LEFT JOIN alertasx.remedys_hfc  rm ON b.codnod=rm.nodo AND b.nroplano=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=2
                LEFT JOIN reportes.criticos cr ON b.codnod=cr.nodo AND b.nroplano=cr.troba
                LEFT JOIN dbpext.trabajos_programados_noc p ON b.codnod=p.nodo AND b.nroplano=p.troba AND p.estado='ENPROCESO'
                WHERE b.codnod<>'' AND b.nroplano<>'' AND b.codreqmnt>0 $filtroJefatura GROUP BY 1,2,3,4 ) am $filtroEstado
                ORDER BY cpend DESC
            ");

            //LEFT JOIN ccm1_temporal.consultasr c ON b.codnod=c.nodo AND b.nroplano=c.troban

        return $listaCms;

    }


    function procesoListaMasivaCms($masiva)
    {

        $parametrosColores = new ParametroColores; 
        $coloresMasivaCms = $parametrosColores::getMasivaCmsParametros()->COLORES;

        for ($i=0; $i < count($masiva); $i++) {

            $masiva[$i]->id = $i+1;

            $nodo = $masiva[$i]->nodo;
            $troba = $masiva[$i]->troba;

            $cpend = $masiva[$i]->cpend;
            $cantl = $masiva[$i]->cantl;
            $eventid = $masiva[$i]->eventid;

            if($cpend == null){
                $masiva[$i]->aver = 0;
            }else{
                $masiva[$i]->aver = $cpend;
            }

            if($cantl == null){
                $masiva[$i]->call = 0;
            }else{
                $masiva[$i]->call = $cantl;
            }

            if($eventid == null){
                $masiva[$i]->dmpe = "";
            }else{
                $masiva[$i]->dmpe = $eventid;
            }
    

            $estadoAveriasQuery = DB::select("
                SELECT * FROM alertasx.gestion_alert 
                WHERE nodo='$nodo' and troba='$troba' and datediff(now(),fechahora)=0 
                ORDER BY fechahora desc limit 1");

            if (isset($estadoAveriasQuery[0])) {
                $masiva[$i]->tp_estado = $estadoAveriasQuery[0]->estado;
                $masiva[$i]->tp_observaciones = $estadoAveriasQuery[0]->observaciones;
                $masiva[$i]->tp_usuario = $estadoAveriasQuery[0]->usuario;
                $masiva[$i]->tp_fechahora = $estadoAveriasQuery[0]->fechahora;
            }

            $mac4 = $masiva[$i]->mac4;

            if ($mac4 == null) {

                $masiva[$i]->estadoFuente = "";
                
            }else {

                $estadoFuenteQuery = DB::select("
                SELECT IF(macstate IN ('online','online(d)','online(pt)','p-online','w-online',
                'w-online(pt)','ol-d','ol-pt'),'ON', IF(macstate ='offline','ON','PR')) AS estadoFuente
                FROM ccm1.scm_total WHERE macaddress = '$mac4'");
                
                $masiva[$i]->estadoFuente = isset($estadoFuenteQuery[0])? $estadoFuenteQuery[0]->estadoFuente : 0;
                
            }


            //-----Estructura de Colores-----//
            if (($masiva[$i]->tipo == 'CRITICA' && $masiva[$i]->offline > 80) || $masiva[$i]->offline > 80 || $masiva[$i]->tcaidas == 'CRITICA' || $masiva[$i]->cant1 > 19)
            {
                $background=$coloresMasivaCms->Critica->colores[0]->background;
                $color=$coloresMasivaCms->Critica->colores[0]->color;
            }else {
                $background=$coloresMasivaCms->Critica->colores[2]->background;
                $color=$coloresMasivaCms->Critica->colores[2]->color;
            }

            if ($masiva[$i]->tc == 'TC') {
                $background=$coloresMasivaCms->Critica->colores[1]->background;
                $color=$coloresMasivaCms->Critica->colores[1]->color;
            }

            $masiva[$i]->background = $background;
            $masiva[$i]->color = $color;

            if ($masiva[$i]->cancli > 0) {
                if (($masiva[$i]->offline / $masiva[$i]->cancli) > .3 || $masiva[$i]->offline > 80) {
                    $backgroundOff=$coloresMasivaCms->Offline->colores[0]->background;
                    $colorOff=$coloresMasivaCms->Offline->colores[0]->color;
                }else {
                    $backgroundOff=$coloresMasivaCms->Offline->colores[1]->background;
                    $colorOff=$coloresMasivaCms->Offline->colores[1]->color;
                }
            }else {
                //$backgroundOff=$coloresMasivaCms->Offline->colores[1]->background;
                //$colorOff=$coloresMasivaCms->Offline->colores[1]->color;
                $backgroundOff=$background;
                $colorOff=$color;

            }
            

            $masiva[$i]->backgroundOff = $backgroundOff;
            $masiva[$i]->colorOff = $colorOff;

            
            if(isset($masiva[$i]->tp_fechahora[0])){
                if ($masiva[$i]->tp_fechahora < $masiva[$i]->fecha_hora) {
                    //$backgroundEstado=$coloresMasivaCms->conEstado->colores[0]->background;
                    //$colorTextEstado=$coloresMasivaCms->conEstado->colores[0]->color;
                    $colorUserEstado=$coloresMasivaCms->conEstado->colores[0]->usuarioColor;
                    $colorObserv=$coloresMasivaCms->conEstado->colores[0]->observacionColor;
                }else {
                    //$backgroundEstado=$coloresMasivaCms->conEstado->colores[1]->background;
                    //$colorTextEstado=$coloresMasivaCms->conEstado->colores[1]->color;
                    $colorUserEstado=$coloresMasivaCms->conEstado->colores[1]->usuarioColor;
                    $colorObserv=$coloresMasivaCms->conEstado->colores[1]->observacionColor;
                }
            }
            


            //$masiva[$i]->backgroundEstado = $backgroundEstado;
            $masiva[$i]->colorTextEstado = $color;
            $masiva[$i]->colorUserEstado = $colorUserEstado;
            $masiva[$i]->colorObserv = $colorObserv;
            //$masiva[$i]->backgroundSinEstado = $coloresMasivaCms->sinEstado->colores[0]->background;

            

        }


        return $masiva;

    }


    function getJefaturasCms()
    {
        $jefaturas =  DB::select("SELECT jefatura FROM catalogos.jefaturas WHERE sede IS NOT NULL GROUP BY 1");
        
        return $jefaturas;
    }


    function listaClientesCriticos($nodo,$troba)
    {
        $lista = DB::select("select IDCLIENTECRM,idempresacrm,NAMECLIENT,NODO,TROBA,amplificador,tap,telf1,telf2,movil1,MACADDRESS,cmts,f_v,entidad 
                            from reportes.criticos where nodo='$nodo' and troba='$troba'");
        return $lista;
    }


    function eliminarMasivaxNodoTroba($nodo,$troba)
    {
        $eliminar = DB::delete("delete FROM dbpext.masivas_temp 
                                WHERE codnod='$nodo' AND nroplano='$troba'");

        //return $lista;
    }


    //------Proceso de Subida de Masivas------//

    function registraHistorico()
    {
        try {
            
            $registHist = DB::insert("insert ignore into dbpext.masivas_historicox 
                                        select * from dbpext.masivas_tempx");
            
            $eliminaMasivaTemp = DB::delete("delete from dbpext.masivas_tempx_c");
            
        } catch(QueryException $ex){ 
            return false;
        }

    }


    function registroMasivas($dataClientes)
    {
        $cantidadClientes = count($dataClientes);
         
        try {
            for ($i=0; $i < $cantidadClientes ; $i++) {  
                DB::insert("insert ignore into dbpext.masivas_tempx_c VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$dataClientes[$i][0],
                            $dataClientes[$i][1],$dataClientes[$i][2],$dataClientes[$i][3],$dataClientes[$i][4],$dataClientes[$i][5],
                            $dataClientes[$i][6],$dataClientes[$i][7],$dataClientes[$i][8],$dataClientes[$i][9],$dataClientes[$i][10],
                            $dataClientes[$i][11],$dataClientes[$i][12],$dataClientes[$i][13],$dataClientes[$i][14],$dataClientes[$i][15],
                            $dataClientes[$i][16],$dataClientes[$i][17],$dataClientes[$i][18],$dataClientes[$i][19],$dataClientes[$i][20],
                            $dataClientes[$i][21],$dataClientes[$i][22],$dataClientes[$i][23],$dataClientes[$i][24],$dataClientes[$i][25],
                            $dataClientes[$i][26],$dataClientes[$i][27],$dataClientes[$i][28],$dataClientes[$i][29],$dataClientes[$i][30],
                            $dataClientes[$i][31],$dataClientes[$i][32],$dataClientes[$i][33],$dataClientes[$i][34],$dataClientes[$i][35],
                            $dataClientes[$i][36],$dataClientes[$i][37],$dataClientes[$i][38],$dataClientes[$i][39],$dataClientes[$i][40],
                            $dataClientes[$i][41],$dataClientes[$i][42],$dataClientes[$i][43],$dataClientes[$i][44],$dataClientes[$i][45],
                            $dataClientes[$i][46],$dataClientes[$i][47],$dataClientes[$i][48],$dataClientes[$i][49],$dataClientes[$i][50],
                            $dataClientes[$i][51],$dataClientes[$i][52],$dataClientes[$i][53],$dataClientes[$i][54],$dataClientes[$i][55],
                            $dataClientes[$i][56],$dataClientes[$i][57],$dataClientes[$i][58],$dataClientes[$i][59],$dataClientes[$i][60],
                            $dataClientes[$i][61],$dataClientes[$i][62],$dataClientes[$i][63]]);
            }
        }catch(QueryException $ex){ 
            return false;
        }catch(\Exception $e){
            return false;
        }

        return true;
 
    }


    function procesarMasiva()
    {
        try {

            $update_exception = DB::update("update dbpext.masivas_tempx_c set fecha_upload=now()");
            
            $eliminaMasivaTempc = DB::delete("delete from dbpext.masivas_tempx 
                                            where Oficina in (select Oficina 
                                            from dbpext.masivas_tempx_c group by Oficina)
                                            or 'LIM' not in (select Oficina 
                                            from dbpext.masivas_tempx_c group by Oficina)");

            $insMasivaTemp = DB::insert("insert ignore dbpext.masivas_tempx (select * from dbpext.masivas_tempx_c where codreqmnt>0)");

            $eliminaMasivaTempx = DB::delete("delete from dbpext.masivas_tempx where LENGTH(Oficina)>3 OR LENGTH(nroplano)=1");
            

        } catch(QueryException $ex){ 
            return false;
        }

    }


    function actualizarMasiva()
    {
        try {

            $limpiarTabla = DB::statement("truncate table dbpext.masivas_temp");
            
            $registMasiva = DB::insert("insert ignore dbpext.masivas_temp SELECT 0 AS checka,
                            Numero_Fercuencia_Averia AS numfrecave,Tipo_Frecuencia_Averia AS tipfrecave,
                            fecreg AS fecreg,Oficina AS codofcadm,'' AS codcmts,codnod,nroplano,
                            '' AS codtrtrn,'' AS edofrecave,Cantidad_Requerida AS cantreq,
                            Nro_Cantidad AS nrocant,Fecha_Ultima_Transferencia AS fecultact,
                            Departamento AS coddpt,Provincia AS codpvc,codreqmnt AS codreqmnt,
                            Codigo_Contrata AS codctr,EstadoActuacionCMS AS codedo,fecliq,
                            '' AS indorigreq,'' AS cantreqliq,codareahbl,IndicadorActuacion AS indactuacion,
                            '' AS indseginc,'' AS indinc,'' AS codinc,fecha_upload
                            FROM dbpext.masivas_tempx WHERE codreqmnt>0");

            $elimMasivaTemp = DB::delete("delete from dbpext.masivas_temp 
                                        where LENGTH(codofcadm)>3 OR LENGTH(nroplano)=1");

        } catch(QueryException $ex){ 
            return false;
        }

    }



    








}