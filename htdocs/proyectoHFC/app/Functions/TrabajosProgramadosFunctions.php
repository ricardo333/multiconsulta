<?php 

namespace App\Functions;
use DB;  
use Illuminate\Support\Facades\Auth;
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TrabajosProgramadosFunctions {

    function detailsByNodoTroba($nodo,$troba)
    {
        $detalle = DB::select("select NODO,TROBA,TIPODETRABAJO,SUPERVISORTDP AS SUPERVISOR,FINICIO,HINICIO,HTERMINO,HORARIO,CORTESN,ESTADO  FROM dbpext.`trabajos_programados_noc`
                                WHERE NODO='$nodo' AND TROBA='$troba'
                                AND DATEDIFF(NOW(),finicio)>=0 and DATEDIFF(NOW(),finicio)<=2 order by finicio desc limit 1");
        return  $detalle;
    }

    function detailsPendientesByItem($item)
    {

        try {
            $detalle = DB::select("select ITEM,NODO,TROBA,AMP,TIPODETRABAJO,SUPERVISORTDP as SUPERVISOR,
                                    FINICIO,HINICIO,HTERMINO,HORARIO,CORTESN,USUARIOREGISTRO AS OPERADOR,OBSERVACIONREGISTRO AS OBSERVACION 
                                    from dbpext.trabajos_programados_noc 
                                    where item= $item and estado='PENDIENTE'");
            return $detalle;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
       } 
       
    }

    function detailsEnProcesoByItem($item)
    {

        try {
            $detalle = DB::select("select ITEM,NODO,TROBA,AMP,TIPODETRABAJO,SUPERVISORTDP as SUPERVISOR,
                                    FINICIO,HINICIO,HTERMINO,HORARIO,CORTESN,USUARIOREGISTRO AS OPERADOR,OBSERVACIONREGISTRO AS OBSERVACION 
                                    from dbpext.trabajos_programados_noc 
                                    where item= $item and estado='ENPROCESO'");
            return $detalle;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
       } 
       
    }

    function getTrabajosProgramadosList($jefatura,$estado,$joinJefatura)
    {
        try {
            $lista = DB::select("
                        select tp.*,sum(dmpe.cant) AS calls,rv.cpend,concat(tp.FINICIO,' ',tp.HINICIO) AS fechattpp   
                        from (
                            select 
                        a.ITEM, a.NODO, a.TROBA, 
                        IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal))  AS jefatura,
                        a.AMP, a.SUPERVISORTDP, a.USUARIOREGISTRO, a.FINICIO, a.HINICIO, a.HTERMINO, a.HORARIO, a.CORTESN, a.ESTADO,
                         a.FECHAREGISTRO, a.HORAREGISTRO, a.TIPODETRABAJO, a.REMEDY, a.NOMBRETECNICOAPERTURA, a.`CELULARSUPERVISORCONTRATA`, 
                        IF(ESTADO='ENPROCESO',a.CONTRATAAPERTURA,IF(ESTADO='CERRADO',a.CONTRATACIERRE,'')) AS CONTRATAAPERTURA, 
                        IF(ESTADO='PENDIENTE',a.OBSERVACIONREGISTRO,IF(ESTADO='ENPROCESO',a.OBSERVACIONAPERTURA,IF(ESTADO='CERRADO',a.OBSERVACIONCIERRE,''))) AS OBSERVACIONREGISTRO,
                         a.FECHAAPERTURA, a.HORAAPERTURA,mas.codreqmnt,if(a.estado='ENPROCESO',0,1) AS idest,a.IMAGENAPERTURA,a.IMAGENCIERRE
                        from dbpext.trabajos_programados_noc a  
                        LEFT JOIN dbpext.masivas_temp mas ON a.NODO=mas.codnod AND a.TROBA=mas.nroplano
                        LEFT JOIN ccm1.zonales_nodos_eecc zn1 ON a.NODO = zn1.NODO
                        where a.estado in ('ENPROCESO','PENDIENTE') and a.estado<>'' 
                        union
                        SELECT 
                        a.ITEM, a.NODO, a.TROBA, 
                        IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal))  AS jefatura,
                        a.AMP, a.SUPERVISORTDP, a.USUARIOREGISTRO, a.FINICIO, a.HINICIO, a.HTERMINO, a.HORARIO, a.CORTESN, 
                        a.ESTADO, a.FECHAREGISTRO, a.HORAREGISTRO, a.TIPODETRABAJO, a.REMEDY, a.NOMBRETECNICOAPERTURA, a.`CELULARSUPERVISORCONTRATA`, 
                        a.CONTRATAAPERTURA, a.OBSERVACIONREGISTRO, a.FECHAAPERTURA, a.HORAAPERTURA,mas.codreqmnt,2 as idest,
                        a.IMAGENAPERTURA,a.IMAGENCIERRE
                        from dbpext.trabajos_programados_noc a  
                        LEFT JOIN dbpext.masivas_temp mas ON a.NODO=mas.codnod AND a.TROBA=mas.nroplano
                        LEFT JOIN ccm1.zonales_nodos_eecc zn1 ON a.NODO = zn1.NODO
                        where a.estado in ('CERRADO') and datediff(now(),fechacierre)<=7 and a.estado<>'') tp
                        LEFT JOIN 
                        (  SELECT  nodo,troba,SUBSTR(fechahora,12,2) AS hora,COUNT(*) AS cant,fechahora 
                            FROM alertasx.`alertas_dmpe` GROUP BY 1,2,3) dmpe
                        ON tp.nodo=dmpe.nodo AND tp.troba=dmpe.troba AND dmpe.hora>=SUBSTR(hinicio,1,2) AND DATEDIFF(dmpe.`fechahora`,tp.finicio)=0 
                        left JOIN cms.req_pend_view rv on tp.nodo=rv.nodo and tp.troba=rv.troba 
                        $joinJefatura 
                        $jefatura $estado   
                        GROUP BY tp.nodo,tp.troba
                        ORDER BY tp.idest ,sum(dmpe.cant) DESC
                        ");
                        /*
                         

                            SELECT * FROM (SELECT 
                            a.ITEM, a.NODO, a.TROBA, 
                            IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal))  AS jefatura,
                            a.AMP, a.SUPERVISORTDP, a.USUARIOREGISTRO, a.FINICIO, a.HINICIO, a.HTERMINO, a.HORARIO, a.CORTESN, a.ESTADO,
                            a.FECHAREGISTRO, a.HORAREGISTRO, a.TIPODETRABAJO, a.REMEDY, a.NOMBRETECNICOAPERTURA, a.`CELULARSUPERVISORCONTRATA`, 
                            IF(ESTADO='ENPROCESO',a.CONTRATAAPERTURA,IF(ESTADO='CERRADO',a.CONTRATACIERRE,'')) AS CONTRATAAPERTURA, 
                            IF(ESTADO='PENDIENTE',a.OBSERVACIONREGISTRO,IF(ESTADO='ENPROCESO',a.OBSERVACIONAPERTURA,IF(ESTADO='CERRADO',a.OBSERVACIONCIERRE,''))) AS OBSERVACIONREGISTRO,
                            a.FECHAAPERTURA, a.HORAAPERTURA,mas.codreqmnt
                            FROM dbpext.trabajos_programados_noc a 
                            LEFT JOIN dbpext.masivas_temp mas ON a.NODO=mas.codnod AND a.TROBA=mas.nroplano
                            LEFT JOIN ccm1.zonales_nodos_eecc zn1 ON a.NODO = zn1.NODO
                            WHERE a.estado IN ('ENPROCESO','PENDIENTE') AND a.estado<>'' 
                            UNION
                            SELECT 
                            a.ITEM, a.NODO, a.TROBA, 
                            IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal))  AS jefatura,
                            a.AMP, a.SUPERVISORTDP, a.USUARIOREGISTRO, a.FINICIO, a.HINICIO, a.HTERMINO, a.HORARIO, a.CORTESN, a.ESTADO, a.FECHAREGISTRO, a.HORAREGISTRO,
                            a.TIPODETRABAJO, a.REMEDY, a.NOMBRETECNICOAPERTURA, a.`CELULARSUPERVISORCONTRATA`, a.CONTRATAAPERTURA, a.OBSERVACIONREGISTRO, a.FECHAAPERTURA, a.HORAAPERTURA,mas.codreqmnt
                            FROM dbpext.trabajos_programados_noc a  
                            LEFT JOIN dbpext.masivas_temp mas ON a.NODO=mas.codnod AND a.TROBA=mas.nroplano
                            LEFT JOIN ccm1.zonales_nodos_eecc zn1 ON a.NODO = zn1.NODO
                            WHERE a.estado IN ('CERRADO') AND DATEDIFF(NOW(),fechacierre)<=7 AND a.estado<>'' ) tp
                             $jefatura $estado 
                            ORDER BY tp.estado DESC,tp.finicio DESC,tp.nodo,tp.troba
                             */
                        
                        
            return $lista;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       }catch(\Exception $e){
            //dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
       } 
        
    }

    function getEstadosTP()
    {
        $estados = DB::select("SELECT a.ESTADO FROM dbpext.trabajos_programados_noc a GROUP BY a.ESTADO");
        return $estados;
    }

    function getMicrozonas()
    {
        $microzonas = DB::select("select microzona,SUBSTR(microzona,6,3)*1 as item FROM catalogos.microzonas 
                                    GROUP BY 1
                                    ORDER BY 2");

        return $microzonas;
    }

    function procesarTrabajoProg($trabajoProg)
    {
            $coloresTrabProg = ParametroColores::getTrabajosProgramadosParametros();
            $colores = $coloresTrabProg->COLORES->segunEstado->colores;
            //dd($colores);

            for ($i=0; $i < count($trabajoProg); $i++) { 

                    if ($trabajoProg[$i]->ESTADO == "ENPROCESO") {
                         $background = $colores[0]->background;
                         $color = $colores[0]->color;
                         $colorIcon = $colores[0]->colorIcons;
                         $gestionRegistroColor = $colores[0]->colorIconGestion;
                    }

                    if ($trabajoProg[$i]->ESTADO == "CERRADO") {
                         $background = $colores[1]->background;
                         $color = $colores[1]->color;
                         $colorIcon = $colores[1]->colorIcons;
                         $gestionRegistroColor = $colores[1]->colorIconGestion;
                    }
                    
                    if ($trabajoProg[$i]->ESTADO != "ENPROCESO" && $trabajoProg[$i]->ESTADO != "CERRADO") {
                        $background = $colores[2]->background;
                        $color = $colores[2]->color;
                        $colorIcon = $colores[2]->colorIcons;
                        $gestionRegistroColor = $colores[2]->colorIconGestion;
                    }

                    $trabajoProg[$i]->id = $i +1;
                    $trabajoProg[$i]->background = $background;
                    $trabajoProg[$i]->color = $color;
                    $trabajoProg[$i]->colorIcon = $colorIcon;
                    $trabajoProg[$i]->gestionRegistroColor = $gestionRegistroColor;

                    //
                    $iconoCalls = "";
                    if((int)$trabajoProg[$i]->calls > 6 && (int)$trabajoProg[$i]->calls <= 10) $iconoCalls = "ttpp_ambar_1.png";
                    if((int)$trabajoProg[$i]->calls > 10) $iconoCalls='ttpp_rojo.png'; 
                    if((int)$trabajoProg[$i]->calls <= 6) $iconoCalls='ttpp_verde.png'; 

                    $trabajoProg[$i]->iconoCalls = $iconoCalls;

            }

            return $trabajoProg;
            
    }

    function getNodoTrobas()
    {
        $lista = DB::select("select nodo,plano from cms.nodo_troba group by nodo,plano");
        return $lista;
    }

    function getTipoTrabajoProgramado()
    {
        $lista = DB::select("select * FROM catalogos.trabajos_programados ORDER BY 1");
        return $lista;
    }

    function getTipoTrabajoGeneral()
    {
        $lista = DB::select("select TRABAJO from catalogos.trabajo WHERE TRABAJO != ''");
        return $lista;
    }

    function getSupervisorGeneral()
    {
        $lista = DB::select("select * FROM dbpext.supervisor");
        return $lista;
    }

    function getSupervisorTDPByTipoTrabajoId($id_trabajo)
    {
        $lista = DB::select("select * FROM dbpext.supervisor s
                                RIGHT JOIN   (
                                                SELECT ts.idsupervisor,ts.idtrabajos,t.tipodetrabajo,t.tipodetrabajo1
                                                FROM dbpext.asign_trabajos_supervidor ts
                                                LEFT JOIN catalogos.trabajos_programados t ON ts.idtrabajos = t.id
                                                WHERE ts.idtrabajos = $id_trabajo
                                            ) AS ku
                                ON s.id = ku.idsupervisor
                                ORDER BY 1");
        return $lista;
    }

    function getFechaSegunTipoTrabajo($id_trabajo)
    {
        $fecha_actual = date("Y-m-d"); 
        $fecha = date_create($fecha_actual);
        date_add($fecha, date_interval_create_from_date_string('1 days'));
        $fecha_new = date_format($fecha, 'Y-m-d');
        
        #PARAMETRIZAR MAS ADELANTE // EN LA BD REGISTRAR LOS TIPOS DE TRABAJOS CON FECHA ACTUAL
        if($id_trabajo == 22 || $id_trabajo == 23){  //"REPARACION EDIFICIO" - "TRABAJOS DE EMERGENCIA"
            $fecha_enviar = $fecha_actual;
        } else{
            $fecha_enviar = $fecha_new;
        } 

        return $fecha_enviar;
    } 

    function SetRegisterTrabProg($data)
    {
        $fregistro=date("Y-m-d H:i:s");
        $cantidad_nodos_planos = count($data["nodoPlano"]);
         //dd($cantidad_nodos_planos);
        for ($i=0;$i< $cantidad_nodos_planos;$i++)    
        {     
            
            $nodo_plano= $data["nodoPlano"][$i];    
            $nodo=substr($nodo_plano,0,(STRPOS($nodo_plano,'-'))+1);
            $nodo=str_replace('-','',$nodo);
            $plano=substr($nodo_plano,STRPOS($nodo_plano,'-')+1,4);

            $hora=date("H:i:s");
            $nod=DB::select("SELECT nombre,dpto FROM catalogos.jefaturas WHERE nodo='$nodo' GROUP BY 1");
            
            $desnodo=isset($nod[0]) ? $nod[0]->nombre : "";
            $dpto=isset($nod[0]) ? $nod[0]->dpto : "";
             
            
            $insert = DB::insert("insert into dbpext.trabajos_programados_noc values 
                                (NULL,'$nodo','$plano','$desnodo','$dpto','','','','','','','','','','','".$data["supervisorText"]."',
                                '".$data["celularSupervisorTDP"]."','".$data["amplificador"]."','".$data["tipoTrabajoText"]."','".$data["afectacion"]."',
                                '".$data["fechaInicio"]."','".$data["HoraInicio"]."','".$data["HoraTermino"]."','".$data["horario"]."','".$data["corteServicio"]."',
                                '".$data["remedy"]."','','','','PENDIENTE',
                                '".$data["observacion"]."','$hora','".$data["usuario"]."',NOW(),'','','','','','','','','','','','','','','','','','','','')");
 
        } 
    }

    function cancelarTPPendienteByItem($item,$observaciones)
    {
        
        try {
            
            $usuario = Auth::user()->nombre;
            $FECHACANCELA=date("Y-m-d H:i:s");

            $detalle = DB::select("update dbpext.trabajos_programados_noc set 
                                    OBSERVACIONCANCELA='$observaciones',
                                    ESTADO='CANCELADO',
                                    FECHACANCELA='$FECHACANCELA',
                                    USUARIOCANCELA='$usuario'
                                    WHERE item='$item' and estado in ('PENDIENTE')");
            return $detalle;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al cancelar el TP, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al cancelar el TP, intente dentro de un minuto por favor.");
           
       } 
    }

    function getTecnicos()
    {
        $tecnicos = DB::select("select TECNICO from catalogos.tecnico WHERE TECNICO != '' ");
        return $tecnicos;
    }

    function getContratas()
    {
        $contratas = DB::select("select contrata from catalogos.contrata WHERE contrata != '' ");
        return $contratas;
    }

    function updateTrabajoProgramadoApertura($data)
    {

        try {

            $item = $data["item"];
            $observaciones = "APERTURA T.P.=>( ".$data["observaciones"]." ) ";
            DB::update(
                "update dbpext.trabajos_programados_noc set 
                FECHAAPERTURA='".$data["fechaDeApertura"]."',
                HORAAPERTURA='".$data["hora"]."',
                NOMBRETECNICOAPERTURA='".$data["tecnico"]."',
                CELULARTECNICOAPERTURA='".$data["telefono"]."',
                CONTRATAAPERTURA='".$data["contrata"]."',
                OBSERVACIONAPERTURA='".$observaciones."',
                ESTADO='ENPROCESO',
                CARNETTECNICOAPERTURA='".$data["carnetTecnico"]."',
                CELULARSUPERVISORCONTRATA='".$data["celSupContrata"]."',
                SUPERVISORCONTRATA='".$data["supervisorContrata"]."',
                USUARIOAPERTURANOC='".$data["usuario"]."',
                IMAGENAPERTURA='".$data["nombreImagen"]."'
                WHERE item=$item and estado='PENDIENTE' ");
    
            /*DB::insert("insert ignore dbpext.detalle_ttpp_afectacion SELECT 
                a.idclientecrm,
                b.item AS idttpp 
                FROM multiconsulta.nclientes a 
                INNER JOIN dbpext.trabajos_programados_noc b 
                ON a.nodo=b.nodo AND a.troba=b.troba
                WHERE b.estado IN ('ENPROCESO','CERRADO','PENDIENTE')  AND b.item=$item
                GROUP BY 1");*/

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al abrir el TP, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al abrir el TP, intente dentro de un minuto por favor.");
           
       } 
        
          
    }


    function updateTrabajoProgramadoCierre($data)
    {
         //throw new HttpException(409,"Se generó un conflicto con los datos al abrir el TP, intente dentro de un minuto por favor.");
         //dd($data);
        try {

            $item = $data["item"];
            $usuario = $data["usuario"];
            
            $update_trabajos = DB::update(
                                "update dbpext.trabajos_programados_noc set 
                                TROBASHIJAS='".$data["trobasHijas"]."',
                                FECHACIERRE='".$data["fcierre"]."',
                                HORACIERRE='".$data["horaDeCierre"]."',
                                ELEMENTOTRABAJADO='".$data["trabajo"]."',
                                NOMBRETECNICOCIERRE='".$data["tecnico"]."',
                                CELULARTECNICOCIERRE='".$data["telefonoTecnico"]."',
                                CONTRATACIERRE='".$data["contrata"]."',
                                OBSERVACIONCIERRE='".$data["observaciones"]."',
                                ESTADO='CERRADO',
                                NOMBRETECNICOCIERRE='".$data["tecnico"]."',
                                CARNETTECNICOCIERRE='".$data["carnetTecnico"]."',
                                USUARIOCIERRE='".$data["usuario"]."',
                                IMAGENCIERRE='".$data["nombreImagen"]."'
                                
                                WHERE item=$item and estado='ENPROCESO'");
            
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al abrir el TP, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos al abrir el TP, intente dentro de un minuto por favor.");
           
       } 
      
    }

    function insertNodoTroba($nodo,$troba)
    {
        try {
            
             DB::insert("insert into cms.nodo_troba VALUES ('$nodo','$troba')");

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor.");
           
           
       }catch(\Exception $e){
           //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor.");
           
       } 
         
    }

    function insertTipoTrabajo($trabajo2,$trabajo)
    {
        try {
            
            DB::insert("insert into catalogos.trabajos_programados VALUES (null,'$trabajo2','$trabajo')");

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor."); 
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor.");
            
        } 
   
    }

    function insertSupervisor($super2,$super)
    {
        try {
            
            DB::insert("insert into dbpext.supervisor VALUES (NULL,'$super2','$super')");

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor."); 
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al guardar los datos, intente dentro de un minuto por favor.");
            
        }  
       
    }

    function tipoTrabajoNoAsignadoSupervisorById($id_supervisor)
    {

        $lista = DB::select("select * FROM catalogos.trabajos_programados tprog LEFT JOIN   
                                    (   SELECT ts.idsupervisor,ts.idtrabajos 
                                        FROM dbpext.asign_trabajos_supervidor ts 
                                            LEFT JOIN 
                                            dbpext.supervisor s ON ts.idsupervisor = s.id
                                        WHERE ts.idsupervisor=$id_supervisor
                                    ) AS ku
                                ON tprog.id = ku.idtrabajos
                            WHERE ku.idtrabajos IS NULL");

       

        return $lista;
    }

    function tipoTrabajoAsignadoSupervisorById($id_supervisor)
    {
       
        $lista = DB::select("select ts.id,ts.idsupervisor,ts.idtrabajos,s.supervisor,
                                s.supervisor1,t.tipodetrabajo,t.tipodetrabajo1
                                                FROM dbpext.asign_trabajos_supervidor ts 
                                                LEFT JOIN dbpext.supervisor s ON ts.idsupervisor = s.id
                                                LEFT JOIN catalogos.trabajos_programados t ON ts.idtrabajos = t.id
                                WHERE ts.idsupervisor=$id_supervisor");
        
        return $lista;
    }

    function insertTrabajoSupervisor($supervisor,$trabajos)
    { 
       
        try {

            //Eliminamos Los trabajos del supervisor
            DB::delete("delete FROM dbpext.asign_trabajos_supervidor  WHERE  idsupervisor = $supervisor");

            if (isset($trabajos)) {
                $longitud_trabajos = count($trabajos);
       
                if ($longitud_trabajos > 0) {
                    for ($i=0;$i<$longitud_trabajos ;$i++) 
                    {  
                        $identificador = $supervisor.$trabajos[$i]["idtrabajos"];
                        $idTrabajos = $trabajos[$i]["idtrabajos"];

                        DB::insert("insert into dbpext.asign_trabajos_supervidor VALUES ($identificador,$supervisor,$idTrabajos)"); 
                    } 
                }
            }
            
           

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al actualizar los datos, intente dentro de un minuto por favor."); 
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto al actualizar los datos, intente dentro de un minuto por favor.");
            
        } 
           
    }

    function cantidadmaximaLlamada($nodo,$troba)
    {
        try {
            $cantidad = DB::select("select sum(Cant) as total,MAX(a.ultimallamada) AS hora 
                                            FROM alertasx.alertas_dmpe_view a 
                                            where nodo='$nodo' and troba='$troba'"
                                );
            return $cantidad;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto en el servicio, intente dentro de un minuto por favor."); 
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto en el servicio, intente dentro de un minuto por favor.");
            
        } 
      
    }

    function getDataGraficoLlamadas($nodo,$troba)
    {
        try {
            $detalle = DB::select("
                                select a.nodo,a.troba,a.hora,a.desdia,a.prom,b.hoy FROM 
                                (SELECT nodo,troba,hora,desdia,ROUND(AVG(cant),0) AS prom FROM alertasx.`llamadasdmpexdia_troba` 
                                WHERE DAYOFWEEK(fecha)=DAYOFWEEK(NOW()) AND DATEDIFF(NOW(),fecha)<=30 and nodo='$nodo' and troba='$troba'
                                GROUP BY nodo,troba,hora) a
                                LEFT JOIN 
                                (SELECT nodo,troba,hora,desdia,SUM(cant) AS hoy FROM alertasx.`llamadasdmpexdia_troba` 
                                WHERE DATEDIFF(NOW(),fecha)=0 and nodo='$nodo'  and troba='$troba'
                                GROUP BY nodo,troba,hora) b
                                ON a.nodo=b.nodo and a.troba=b.troba  AND a.hora=b.hora");
            return $detalle;
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto en el servicio, intente dentro de un minuto por favor."); 
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto en el servicio, intente dentro de un minuto por favor.");
            
        } 
       
    }


 

}