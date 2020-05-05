<?php 

namespace App\Functions;
use DB; 
use DateTime;
use App\Administrador\ParametroColores;

class MonitoreoAveriasFunctions {


    function getMonitorAveriasHfc($filtroJefatura,$filtroEstado)
    {
        $listaAverias = DB::select(
            " SELECT xx.* FROM 
                (
                    SELECT 
                        a.jefatura,a.nodo,a.troba,a.cpend AS aver,a.consultas,a.ultreq,a.fec_registro,
                        TIMEDIFF(NOW(),a.fec_registro) AS tiempo,c.`codreqmnt`,c.`fecreg`, h.`fechahora`,h.`observaciones`,h.`usuario`,h.`estado`,
                        h.`remedy`,IF(TIMEDIFF(NOW(),a.fec_registro)<'02:00:00.0000','SG','') sg , TIMEDIFF(NOW(),h.fechahora) AS tiempog,
                        CONCAT(k.tipodetrabajo,' Supervisor:',k.supervisor,' Fecha:',k.`FINICIO`,' Hora:',k.`HINICIO`,' a ',k.`HTERMINO`,
                        ' Turno:',k.`HORARIO`,' - ',k.`CORTESN`,' Remedy:',k.`REMEDY` ,'
                        ',k.`OBSERVACIONES`,k.`ESTADO`,k.`usuario`,k.`fecha_registro`) AS trabprog, 
                        IF(p.troba IS NULL,'','PREMIUM') AS premium, rm.remedy AS remedy_hfc , ad.cant as calldmpe,ad.ultimallamada
                    FROM alertasx.monitor_averias a 
                    LEFT JOIN dbpext.`masivas_temp` c ON a.nodo=c.codnod  AND a.troba=c.nroplano 
                    LEFT JOIN 
                        (
                            SELECT f.* 
                            FROM alertasx.`gestion_alert` f 
                            INNER JOIN 
                                (
                                    SELECT 
                                        nodo,troba,MAX(fechahora) AS fechahora 
                                    FROM alertasx.`gestion_alert` WHERE DATEDIFF(NOW(),fechahora)=0 
                                    GROUP BY nodo,troba
                                ) g ON f.nodo=g.nodo AND f.troba=g.troba AND f.fechahora=g.fechahora
                        ) h ON a.nodo=h.nodo AND a.troba = h.troba 
                    LEFT JOIN 
                        (
                            SELECT 
                                i.ITEM,i.NODO,i.TROBA,i.AMP,IF(LENGTH(i.tipodetrabajo)<3,ct.tipodetrabajo,i.tipodetrabajo) AS tipodetrabajo, 
                                IF(LENGTH(i.SUPERVISORTDP)<3,cs.supervisor,i.SUPERVISORTDP) AS supervisor,i.FINICIO,i.HINICIO,i.HTERMINO,i.HORARIO, 
                                i.CORTESN,i.USUARIOREGISTRO AS OPERADOR,i.FECHAREGISTRO AS FECHA,i.HORAREGISTRO AS HORA,i.TIPODETRABAJO AS TRABAJO,
                                i.REMEDY,i.NOMBRETECNICOAPERTURA AS TECNICO,i.CELULARTECNICOAPERTURA AS RPM,i.CONTRATAAPERTURA AS CONTRATA,
                                i.HORACIERRE, i.OBSERVACIONAPERTURA AS OBSERVACIONES,i.ESTADO,i.FECHAREGISTRO AS fecha_registro,i.FECHAAPERTURA AS fecha_apertura,
                                i.FECHACIERRE AS fecha_cierre,i.FECHACANCELA AS fecha_cancela,i.USUARIOREGISTRO AS usuario,
                                i.USUARIOAPERTURANOC AS usuario_apertura, i.USUARIOCIERRE AS usuario_cierre,i.USUARIOCANCELA AS usuario_cancela 
                            FROM dbpext.trabajos_programados_noc i FORCE INDEX(ESTADO) 
                            LEFT JOIN catalogos.trabajos_programados ct ON i.tipodetrabajo=ct.id 
                            LEFT JOIN dbpext.supervisor cs ON i.SUPERVISORTDP=cs.id 
                            WHERE i.estado IN ('CERRADO','ENPROCESO') AND DATEDIFF(NOW(),i.finicio)<=2 
                            GROUP BY nodo,troba
                        ) k ON a.nodo = k.nodo AND a.troba = k.troba 
                    LEFT JOIN catalogos.premium_fases AS p ON CONCAT(a.nodo,a.troba)=p.troba 
                    LEFT JOIN alertasx.fuentes_view fu ON a.nodo=fu.nodo AND a.troba=fu.troba AND fu.resultadosnmp='SNMPOK' 
                                AND (fu.InputVoltagefinal+fu.OutputVoltagefinal+fu.OutputCurrentfinal+fu.TotalStringVoltagefinal)>0 
                    LEFT JOIN alertasx.`remedys_hfc` rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=1 
                    LEFT JOIN alertasx.alertas_dmpe_view ad on a.nodo=ad.nodo and a.troba=ad.troba
                    WHERE  a.jefatura NOT IN ('PROV_PUN','PROV_SUR','PROV_SMA','PROV_IQU','PROV_JUN') $filtroJefatura AND a.nodo<>'' AND a.troba<>'' GROUP BY a.nodo,a.troba 
                    ORDER BY a.cpend DESC
                ) xx $filtroEstado
                LIMIT 50"
        );

        return $listaAverias;
    }

    function getMonitorAveriasGpon($filtroJefatura,$filtroEstado)
    {
        $listaAverias = DB::select(
            " SELECT xx.* FROM
            (SELECT a.jefatura,a.nodo,a.troba,a.cpend AS aver,a.consultas,a.ultreq,a.fec_registro,TIMEDIFF(NOW(),a.fec_registro) AS tiempo,c.`codreqmnt`,c.`fecreg`,
            h.`fechahora`,h.`observaciones`,h.`usuario`,h.`estado`,h.`remedy`,IF(TIMEDIFF(NOW(),a.fec_registro)<'02:00:00.0000','SG','') sg ,
            TIMEDIFF(NOW(),h.fechahora) AS tiempog,CONCAT(k.tipodetrabajo,' Supervisor:',k.supervisor,' Fecha:',k.`FINICIO`,' Hora:',k.`HINICIO`,' a ',k.`HTERMINO`,
            ' Turno:',k.`HORARIO`,' - ',k.`CORTESN`,' Remedy:',k.`REMEDY` ,'</br> ',k.`OBSERVACIONES`,k.`ESTADO`,k.`usuario`,k.`fecha_registro`) AS trabprog,
            IF(p.troba IS NULL,'','PREMIUM') AS premium,
            rm.remedy AS remedy_hfc
            FROM alertasx.monitor_averias_gpon a
            LEFT JOIN dbpext.`masivas_temp` c ON a.nodo=c.codnod AND a.troba=c.nroplano
            LEFT JOIN  (SELECT f.* FROM alertasx.`gestion_alert` f INNER JOIN
            (SELECT nodo,troba,MAX(fechahora) AS fechahora FROM alertasx.`gestion_alert` WHERE DATEDIFF(NOW(),fechahora)=0 GROUP BY nodo,troba) g
            ON f.nodo=g.nodo AND f.troba=g.troba AND f.fechahora=g.fechahora) h ON a.nodo=h.nodo AND a.troba = h.troba
            LEFT JOIN (SELECT i.ITEM,i.NODO,i.TROBA,i.AMP,IF(LENGTH(i.tipodetrabajo)<3,ct.tipodetrabajo,i.tipodetrabajo) AS tipodetrabajo,
            IF(LENGTH(i.SUPERVISORTDP)<3,cs.supervisor,i.SUPERVISORTDP) AS supervisor,i.FINICIO,i.HINICIO,i.HTERMINO,i.HORARIO,
            i.CORTESN,i.USUARIOREGISTRO AS OPERADOR,i.FECHAREGISTRO AS FECHA,i.HORAREGISTRO AS HORA,i.TIPODETRABAJO AS TRABAJO,
            i.REMEDY,i.NOMBRETECNICOAPERTURA AS TECNICO,i.CELULARTECNICOAPERTURA AS RPM,i.CONTRATAAPERTURA AS CONTRATA,i.HORACIERRE,
            i.OBSERVACIONAPERTURA AS OBSERVACIONES,i.ESTADO,i.FECHAREGISTRO AS fecha_registro,i.FECHAAPERTURA AS fecha_apertura,
            i.FECHACIERRE AS fecha_cierre,i.FECHACANCELA AS fecha_cancela,i.USUARIOREGISTRO AS usuario,i.USUARIOAPERTURANOC AS usuario_apertura,
            i.USUARIOCIERRE AS usuario_cierre,i.USUARIOCANCELA AS usuario_cancela
            FROM dbpext.trabajos_programados_noc i FORCE INDEX(ESTADO)
            LEFT JOIN catalogos.trabajos_programados ct ON i.tipodetrabajo=ct.id
            LEFT JOIN dbpext.supervisor cs ON i.SUPERVISORTDP=cs.id
            WHERE i.estado IN ('CERRADO','ENPROCESO') AND DATEDIFF(NOW(),i.finicio)<=2
            GROUP BY nodo,troba) k ON a.nodo = k.nodo AND a.troba = k.troba
            LEFT JOIN catalogos.premium_fases AS p ON CONCAT(a.nodo,a.troba)=p.troba
            LEFT JOIN alertasx.fuentes_view fu ON a.nodo=fu.nodo AND a.troba=fu.troba AND fu.resultadosnmp='SNMPOK' AND
            
            (fu.InputVoltagefinal+fu.OutputVoltagefinal+fu.OutputCurrentfinal+fu.TotalStringVoltagefinal)>0
            LEFT JOIN alertasx.`remedys_hfc` rm ON a.nodo=rm.nodo AND a.troba=rm.troba AND DATEDIFF(NOW(),rm.fechahora)<=1
            
            where a.jefatura NOT IN ('PROV_PUN','PROV_SUR','PROV_SMA','PROV_IQU','PROV_JUN') $filtroJefatura AND a.nodo<>'' AND a.troba<>''
            GROUP BY a.nodo,a.troba ORDER BY a.cpend DESC) xx $filtroEstado"
        );
         //dd($listaAverias);
        return $listaAverias;
    }

    function procesoListaMonitorAveriasHfc($averias)
    {
        $parametrosColores = new ParametroColores; 
        $coloresMonitor = $parametrosColores::getMonitoreoAveriasParametros()->COLORES;
     
        for ($i=0; $i < count($averias); $i++) { 

            $averias[$i]->id = $i+1; 
            $tiempog =  strtotime($averias[$i]->tiempog);
            $tiempo =  strtotime($averias[$i]->tiempo);
            //$resta = abs($tiempog - $tiempo);
            $resta =  $tiempog - $tiempo;
            $transformDate = date ( 'H' , $resta );
            $signo = $resta > 0? "positivo" : "negativo";
            $resultadoRestaFinalConSigno =  $resta > 0 ? $transformDate : ($transformDate * -1);
            // dd($averias[$i]->tiempog."-".$averias[$i]->tiempo."=>".$resta."==>".$transformDate," DIGNO=>".$signo,
            //    " Multiplicado por su signo".$resultadoRestaFinalConSigno, "Es mayor que 2?=>".(int)$transformDate > 2,
            //    " Resta natural de php: ".$averias[$i]->tiempog);

            
            
            DB::insert("insert ignore alertasx.bitacora_torre values
                        (   '".$averias[$i]->jefatura."',
                            '".$averias[$i]->nodo."',
                            '".$averias[$i]->troba."',
                            ".(int)$averias[$i]->consultas.",
                            ".(int)$averias[$i]->aver.",
                            '".$averias[$i]->fec_registro."',
                            ".(int)$averias[$i]->codreqmnt.",
                            '".$averias[$i]->trabprog."',
                            '".$averias[$i]->estado."',
                            '".trim($averias[$i]->observaciones). "',
                            '".$averias[$i]->fechahora."',
                            '".$averias[$i]->usuario."',
                            now(),
                            null,
                            null,
                            '".$averias[$i]->remedy."'
                        )
                    ");

            #INICIO PROBLEMA COMERCIAL
                if ($averias[$i]->estado != "Problema Comercial") {
                    if ($averias[$i]->aver > 4 && $averias[$i]->aver < 8 )
                    {
                        $background            =   $coloresMonitor->cantidadAveriasHfc->colores[0]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasHfc->colores[0]->color;
                        $estadoBackgroundDef   =   $coloresMonitor->cantidadAveriasHfc->colores[0]->estadoDefaultBackground;
                        $mapaColor             =   $coloresMonitor->cantidadAveriasHfc->colores[0]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasHfc->colores[0]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasHfc->colores[0]->gestionDetalleIcon;
                    }
                    if ($averias[$i]->aver <= 4) {
                        $background            =   $coloresMonitor->cantidadAveriasHfc->colores[1]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasHfc->colores[1]->color;
                        $estadoBackgroundDef   =   $coloresMonitor->cantidadAveriasHfc->colores[1]->estadoDefaultBackground;
                        $mapaColor             =   $coloresMonitor->cantidadAveriasHfc->colores[1]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasHfc->colores[1]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasHfc->colores[1]->gestionDetalleIcon;

                    }
                    if ($averias[$i]->aver >= 8 )
                    {
                        $background            =   $coloresMonitor->cantidadAveriasHfc->colores[2]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasHfc->colores[2]->color;
                        $estadoBackgroundDef   =   $coloresMonitor->cantidadAveriasHfc->colores[2]->estadoDefaultBackground;
                        $mapaColor             =   $coloresMonitor->cantidadAveriasHfc->colores[2]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasHfc->colores[2]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasHfc->colores[2]->gestionDetalleIcon;
                    }

                    $averias[$i]->background            = $background;
                    $averias[$i]->color                 = $color;
                    $averias[$i]->mapaColor             = $mapaColor;
                    $averias[$i]->gestionRegistroColor  = $gestionRegistroColor;
                    $averias[$i]->gestionDetalleColor   = $gestionDetalleColor;

                    if($resultadoRestaFinalConSigno > 2 && $averias[$i]->sg=='SG'){
                        $backgroundEstado=$coloresMonitor->conEstado->colores[0]->background;
                        $colorTextEstado=$coloresMonitor->conEstado->colores[0]->color;
                        $colorUserEstado=$coloresMonitor->conEstado->colores[0]->usuarioColor;
                        $colorObserv=$coloresMonitor->conEstado->colores[0]->observacionColor;
                    }else {
                        //$backgroundEstado=$coloresMonitor->conEstado->colores[1]->background;
                        $backgroundEstado=$estadoBackgroundDef;
                        $colorTextEstado=$coloresMonitor->conEstado->colores[1]->color;
                        $colorUserEstado=$coloresMonitor->conEstado->colores[1]->usuarioColor;
                        $colorObserv=$coloresMonitor->conEstado->colores[0]->observacionColor;
                    }

                    $averias[$i]->backgroundEstado = $backgroundEstado;
                    $averias[$i]->colorTextEstado = $colorTextEstado;
                    $averias[$i]->colorUserEstado = $colorUserEstado;
                    $averias[$i]->colorObserv = $colorObserv;
                    $averias[$i]->backgroundSinEstado = $coloresMonitor->sinEstado->colores[0]->background;
 
                }
  
            #END PROBLEMA COMERCIAL  

        }
         
        return $averias;
    }

    function procesoListaMonitorAveriasGpon($averias)
    {
        $parametrosColores = new ParametroColores; 
        $coloresMonitor = $parametrosColores::getMonitoreoAveriasParametros()->COLORES;
     
        for ($i=0; $i < count($averias); $i++) { 

            $averias[$i]->id = $i+1;
            $averias[$i]->hoy = date("Y-m-d");

            $tiempog =  strtotime($averias[$i]->tiempog);
            $tiempo =  strtotime($averias[$i]->tiempo);
            //$resta = abs($tiempog - $tiempo); 
            $resta =  $tiempog - $tiempo;
            $transformDate = date ( 'H' , $resta );
            $signo = $resta > 0? "positivo" : "negativo";
            $resultadoRestaFinalConSigno =  $resta > 0 ? $transformDate : ($transformDate * -1);

             
            DB::insert("insert ignore alertasx.bitacora_torre values
                        (   '".$averias[$i]->jefatura."',
                            '".$averias[$i]->nodo."',
                            '".$averias[$i]->troba."',
                            ".(int)$averias[$i]->consultas.",
                            ".(int)$averias[$i]->aver.",
                            '".$averias[$i]->fec_registro."',
                            ".(int)$averias[$i]->codreqmnt.",
                            '".$averias[$i]->trabprog."',
                            '".$averias[$i]->estado."',
                            '".trim($averias[$i]->observaciones). "',
                            '".$averias[$i]->fechahora."',
                            '".$averias[$i]->usuario."',
                            now(),
                            null,
                            null,
                            '".$averias[$i]->remedy."'
                        )
                    ");

            #INICIO PROBLEMA COMERCIAL
                if ($averias[$i]->estado != "Problema Comercial") {
                   // dd($averias[$i]->aver);
                    if ((int)$averias[$i]->aver == 2)
                    {
                        $background            =   $coloresMonitor->cantidadAveriasGpon->colores[0]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasGpon->colores[0]->color;
                        $mapaColor             =   $coloresMonitor->cantidadAveriasGpon->colores[0]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasGpon->colores[0]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasGpon->colores[0]->gestionDetalleIcon;
                    }
                    if ((int)$averias[$i]->aver < 2) {
                        $background            =   $coloresMonitor->cantidadAveriasGpon->colores[1]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasGpon->colores[1]->color; 
                        $mapaColor             =   $coloresMonitor->cantidadAveriasGpon->colores[1]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasGpon->colores[1]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasGpon->colores[1]->gestionDetalleIcon;
                    }
                    if ((int)$averias[$i]->aver > 2 )
                    {
                        $background            =   $coloresMonitor->cantidadAveriasGpon->colores[2]->background;
                        $color                 =   $coloresMonitor->cantidadAveriasGpon->colores[2]->color;
                        $mapaColor             =   $coloresMonitor->cantidadAveriasGpon->colores[2]->mapaIcon;
                        $gestionRegistroColor  =   $coloresMonitor->cantidadAveriasGpon->colores[2]->gestionRegistroIcon;
                        $gestionDetalleColor   =   $coloresMonitor->cantidadAveriasGpon->colores[2]->gestionDetalleIcon;
                    }
                    
                    $averias[$i]->background            = $background;
                    $averias[$i]->color                 = $color;
                    $averias[$i]->mapaColor             = $mapaColor;
                    $averias[$i]->gestionRegistroColor  = $gestionRegistroColor;
                    $averias[$i]->gestionDetalleColor   = $gestionDetalleColor;

                    if($resultadoRestaFinalConSigno > 2 && $averias[$i]->sg=='SG'){
                        $backgroundEstado=$coloresMonitor->conEstado->colores[0]->background;
                        $colorTextEstado=$coloresMonitor->conEstado->colores[0]->color;
                        $colorUserEstado=$coloresMonitor->conEstado->colores[0]->usuarioColor;
                        $colorObserv=$coloresMonitor->conEstado->colores[0]->observacionColor;
                    }else {
                        $backgroundEstado=$coloresMonitor->conEstado->colores[1]->background;
                        $colorTextEstado=$coloresMonitor->conEstado->colores[1]->color;
                        $colorUserEstado=$coloresMonitor->conEstado->colores[1]->usuarioColor;
                        $colorObserv=$coloresMonitor->conEstado->colores[0]->observacionColor;
                    }

                    $averias[$i]->backgroundEstado = $backgroundEstado;
                    $averias[$i]->colorTextEstado = $colorTextEstado;
                    $averias[$i]->colorUserEstado = $colorUserEstado;
                    $averias[$i]->colorObserv = $colorObserv;
                    $averias[$i]->backgroundSinEstado = $coloresMonitor->sinEstado->colores[0]->background;
 
                }
  
            #END PROBLEMA COMERCIAL  

        }
         
        return $averias;
    }

    function maxRegistroMonAverias()
    {
        return DB::select("select MAX(fec_registro) as act from cms.req_pend_macro_final");
    }

    function getJefaturasAverias()
    {
        $jefaturas =  DB::select("SELECT jefatura FROM catalogos.jefaturas  GROUP BY jefatura");

        return $jefaturas;
    }
 
}