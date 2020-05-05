<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;

class ProblemaSenalFunctions {


    function getProblemaSenalNiveles($filtroJefatura,$filtroEstado)
    {
        //try{
        $listaNiveles = DB::select(
            "SELECT xx.* FROM
            (SELECT a.*,TIMEDIFF(NOW(),fecha_hora) AS tiempo,
            IF(a.tc='TC' AND caida='SI','red',IF(a.caida='SI' AND a.ncaidas>8,'yellow',
            IF(a.caida='SI','lightblue','lighgreen'))) AS fondo,
            IF(a.tc='TC' AND caida='SI','yellow',IF(a.caida='SI' AND a.ncaidas>8,'red',
            IF(a.caida='SI','red','black'))) AS letra,CONCAT('TOP : ',t.top) AS top, h.estado, 
            h.observaciones, h.usuario, h.fechahora, COUNT(c.nodo) AS ncrit, p.estado AS trabajo_estado
            FROM alertasx.niveles_new a
            LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba
            LEFT JOIN alertasx.gestion_alert h ON a.nodo=h.nodo AND a.troba=h.troba
            LEFT JOIN reportes.criticos c ON a.nodo=c.nodo AND a.troba=c.troba
            LEFT JOIN dbpext.trabajos_programados_noc p ON a.nodo=p.nodo AND a.troba=p.troba AND p.estado='ENPROCESO'
            WHERE $filtroJefatura a.troba<>'' AND a.nodo<>'' AND a.caida='SI' AND h.fechahora=(SELECT MAX(r.fechahora) FROM alertasx.gestion_alert r WHERE r.nodo=a.nodo AND r.troba=a.troba)
            GROUP BY a.nodo,a.troba
            UNION
            SELECT b.*,TIMEDIFF(NOW(),fecha_hora) AS tiempo,
            IF(b.tc='TC' AND caida='SI','red',IF(b.caida='SI' AND b.ncaidas>8,'yellow',
            IF(b.caida='SI','lightblue','lighgreen'))) AS fondo,
            IF(b.tc='TC' AND caida='SI','yellow',IF(b.caida='SI' AND b.ncaidas>8,'red',
            IF(b.caida='SI','red','black'))) AS letra,CONCAT('TOP : ',t.top) AS top, h.estado, 
            h.observaciones, h.usuario, h.fechahora, COUNT(c.nodo) AS ncrit, p.estado AS trabajo_estado
            FROM alertasx.niveles_new b
            LEFT JOIN catalogos.top100200 t ON b.nodo=t.nodo AND b.troba=t.troba
            LEFT JOIN alertasx.gestion_alert h ON b.nodo=h.nodo AND b.troba=h.troba
            LEFT JOIN reportes.criticos c ON b.nodo=c.nodo AND b.troba=c.troba
            LEFT JOIN dbpext.trabajos_programados_noc p ON b.nodo=p.nodo AND b.troba=p.troba AND p.estado='ENPROCESO'
            WHERE $filtroJefatura b.troba<>'' AND b.nodo<>'' AND b.caida='SI' AND h.estado IS NULL
            GROUP BY b.nodo,b.troba
            ORDER BY caida,jefatura,nodo,troba) xx $filtroEstado
            ");
            //LIMIT 25 
        //} catch(QueryException $ex){
          //  return "error";
        //}

        return $listaNiveles;

    }


    function procesoListaProblemasSenal($problemas)
    {
        $parametrosColores = new ParametroColores; 
        $coloresProblemaRF = $parametrosColores::getProblemaSenalRFParametros()->COLORES;

        $paramPwrup = DB::select("
            SELECT vmin AS pwr_up_min,vmax AS pwr_up_max 
            FROM catalogos.parametros_rf WHERE parametro='powerup_prom'");

        $paramSnrup = DB::select("
            SELECT vmin AS snr_up_min,vmax AS snr_up_max 
            FROM catalogos.parametros_rf WHERE parametro='snr_avg'");

        $paramPwrdn = DB::select("
            SELECT vmin AS pwr_dn_min,vmax AS pwr_dn_max 
            FROM catalogos.parametros_rf WHERE parametro='powerds_prom'");

        $paramSnrdn = DB::select("
            SELECT vmin AS snr_dn_min,vmax AS snr_dn_max 
            FROM catalogos.parametros_rf WHERE parametro='snr_down'");

        for ($i=0; $i < count($problemas); $i++) {

            $problemas[$i]->id = $i+1;

            $nodo = $problemas[$i]->nodo;
            $troba = $problemas[$i]->troba;
            $fecha_hora = $problemas[$i]->fecha_hora;

            $averiasQuery = DB::select("
                select COUNT(*) AS averiaM1 from ccm1.averias_m1_new 
                where codnod='$nodo' and nroplano='$troba' and fec_mov>='$fecha_hora'");

            $problemas[$i]->averiaM1 = isset($averiasQuery[0])? (int)$averiasQuery[0]->averiaM1 : 0;

            /*
            $averiasCatvQuery = DB::select("
                select COUNT(*) AS averiaCatv from ccm1.averias_catv_new 
                where codnod='$nodo' and nroplano='$troba' and fec_mov>='$fecha_hora' and codreq not in
                (select codreq AS aver from ccm1.averias_m1_new 
                where codnod='$nodo' and nroplano='$troba' and fec_mov>='$fecha_hora')");

            $problemas[$i]->averiaCatv = isset($averiasCatvQuery[0])? (int)$averiasCatvQuery[0]->averiaCatv : 0;
            */
            $problemas[$i]->averiaCatv = 0;

            //-----Estructura de Colores-----//
            if (($problemas[$i]->RxPwrdBmv >= 6 || $problemas[$i]->RxPwrdBmv <= 4) && $problemas[$i]->RxPwrdBmv > 0)
            {
                $backgroundRxPwrdBmv=$coloresProblemaRF->RxPwrdBmv->colores[0]->background;
                $colorRxPwrdBmv=$coloresProblemaRF->RxPwrdBmv->colores[0]->color;
            }else{
                $backgroundRxPwrdBmv=$coloresProblemaRF->RxPwrdBmv->colores[1]->background;
                $colorRxPwrdBmv=$coloresProblemaRF->RxPwrdBmv->colores[1]->color;
            }

            $problemas[$i]->backgroundRxPwrdBmv = $backgroundRxPwrdBmv;
            $problemas[$i]->colorRxPwrdBmv = $colorRxPwrdBmv;
            

            if ($problemas[$i]->pwr_up < $paramPwrup[0]->pwr_up_min || $problemas[$i]->pwr_up > $paramPwrup[0]->pwr_up_max) {
                $backgroundPwrUp=$coloresProblemaRF->PWR_UP->colores[1]->background;
                $colorPwrUp=$coloresProblemaRF->PWR_UP->colores[1]->color;
            }else {
                $backgroundPwrUp=$coloresProblemaRF->PWR_UP->colores[2]->background;
                $colorPwrUp=$coloresProblemaRF->PWR_UP->colores[2]->color;
            }

            $problemas[$i]->backgroundPwrUp = $backgroundPwrUp;
            $problemas[$i]->colorPwrUp = $colorPwrUp;


            if ($problemas[$i]->snr_up < $paramSnrup[0]->snr_up_min) {
                $backgroundSnrUp=$coloresProblemaRF->SNR_UP->colores[0]->background;
                $colorSnrUp=$coloresProblemaRF->SNR_UP->colores[0]->color;
            }else {
                $backgroundSnrUp=$coloresProblemaRF->SNR_UP->colores[1]->background;
                $colorSnrUp=$coloresProblemaRF->SNR_UP->colores[1]->color;
            }

            $problemas[$i]->backgroundSnrUp = $backgroundSnrUp;
            $problemas[$i]->colorSnrUp = $colorSnrUp;


            if ($problemas[$i]->pwr_dn < $paramPwrdn[0]->pwr_dn_min || $problemas[$i]->pwr_dn > $paramPwrdn[0]->pwr_dn_max) {
                $backgroundPwrDn=$coloresProblemaRF->PWR_DN->colores[0]->background;
                $colorPwrDn=$coloresProblemaRF->PWR_DN->colores[0]->color;
            }else {
                $backgroundPwrDn=$coloresProblemaRF->PWR_DN->colores[1]->background;
                $colorPwrDn=$coloresProblemaRF->PWR_DN->colores[1]->color;
            }

            $problemas[$i]->backgroundPwrDn = $backgroundPwrDn;
            $problemas[$i]->colorPwrDn = $colorPwrDn;


            if ($problemas[$i]->snr_dn < $paramSnrdn[0]->snr_dn_min) {
                $backgroundSnrDn=$coloresProblemaRF->SNR_DN->colores[0]->background;
                $colorSnrDn=$coloresProblemaRF->SNR_DN->colores[0]->color;
            }else {
                $backgroundSnrDn=$coloresProblemaRF->SNR_DN->colores[1]->background;
                $colorSnrDn=$coloresProblemaRF->SNR_DN->colores[1]->color;
            }

            $problemas[$i]->backgroundSnrDn = $backgroundSnrDn;
            $problemas[$i]->colorSnrDn = $colorSnrDn;

            
            if ($problemas[$i]->fechahora < $problemas[$i]->fecha_hora) {
                $backgroundEstado=$coloresProblemaRF->conEstado->colores[0]->background;
                $colorTextEstado=$coloresProblemaRF->conEstado->colores[0]->color;
                $colorUserEstado=$coloresProblemaRF->conEstado->colores[0]->usuarioColor;
                $colorObserv=$coloresProblemaRF->conEstado->colores[0]->observacionColor;
            }else {
                $backgroundEstado=$coloresProblemaRF->conEstado->colores[1]->background;
                $colorTextEstado=$coloresProblemaRF->conEstado->colores[1]->color;
                $colorUserEstado=$coloresProblemaRF->conEstado->colores[1]->usuarioColor;
                $colorObserv=$coloresProblemaRF->conEstado->colores[0]->observacionColor;
            }

            $problemas[$i]->backgroundEstado = $backgroundEstado;
            $problemas[$i]->colorTextEstado = $colorTextEstado;
            $problemas[$i]->colorUserEstado = $colorUserEstado;
            $problemas[$i]->colorObserv = $colorObserv;
            $problemas[$i]->backgroundSinEstado = $coloresProblemaRF->sinEstado->colores[0]->background;

        }


        return $problemas;

    }


    function getJefaturasNiveles()
    {
        $jefaturas =  DB::select("SELECT CONCAT(jefatura,'_',sede) jefatura FROM ccm1.zonales_nodos_eecc WHERE sede IS NOT NULL GROUP BY 1");

        return $jefaturas;
    }

    function listaClientesCriticos($nodo,$troba)
    {
        $lista = DB::select("select IDCLIENTECRM,idempresacrm,NAMECLIENT,NODO,TROBA,amplificador,tap,telf1,telf2,movil1,MACADDRESS,cmts,f_v,entidad 
                            from reportes.criticos where nodo='$nodo' and troba='$troba'");
        return $lista;
    }
 



}