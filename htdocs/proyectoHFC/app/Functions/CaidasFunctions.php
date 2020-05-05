<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class CaidasFunctions {

    function getNodoTrobas()
    {
        $lista = DB::select("SELECT CONCAT(TRIM(nodo),TRIM(troba)) AS troba FROM alertasx.`caidas_new_amplif` GROUP BY 1");
        return $lista;
    }

    function getListaCaidaMasiva($filtroJefatura,$nodo)
    {
        try {

            $parametrosColores = ParametroColores::getCaidasParametros();
            $colores = $parametrosColores->COLORES->segunEstado->colores;
           // dd($colores);
            $estados = DB::select("SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt as codmasiva,a.umbral,a.Caida,a.fecha_hora,a.digi,
                                    IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,

                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->background."',
                                        IF(a.estado='CONTINUA'  AND a.ncaidas>8 OR a.offline>80 ,'".$colores[1]->background."',
                                            IF(a.estado='CONTINUA','".$colores[2]->background."','".$colores[4]->background."')
                                        )
                                    ) AS fondo ,
                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->color."',
                                        IF(a.estado='CONTINUA' and  a.ncaidas>8 OR a.offline>80,'".$colores[1]->color."',
                                            IF(a.estado='CONTINUA','".$colores[2]->color."','".$colores[4]->color."')
                                        )
                                    ) AS letra,
                                    
                                    IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,fp.respaldo, 
                                    if(pr.troba is null ,'','PREMIUM') as premium,rm.remedy, CONCAT('TOP : ',t.top) AS top ,ad.cant AS calldmpe,ad.ultimallamada 
                                    FROM alertasx.caidas_new a
                                    LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                                    left join catalogos.premium pr on concat(a.nodo,a.troba)=pr.troba
                                    left join dbpext.masivas_tempx mt
                                    on a.nodo=mt.codnod and a.troba=mt.nroplano
                                    left join alertasx.remedys_hfc  rm
                                    on a.nodo=rm.nodo and a.troba=rm.troba and datediff(now(),rm.fechahora)<=2
                                    left join catalogos.top100200 t on a.nodo=t.nodo and a.troba=t.troba
                                    LEFT JOIN alertasx.alertas_dmpe_view ad ON a.nodo=ad.nodo AND a.troba=ad.troba 
                                    where datediff(now(),fecha_hora)=0 $filtroJefatura $nodo AND a.nodo<>'' AND a.troba<>''
                                    group by a.estado,a.jefatura,a.nodo,a.troba
                                    order by a.estado,a.jefatura,a.nodo,a.troba 
                                    ");
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getListaCaidaNoc($filtroJefatura,$nodo)
    {
        try {

            $parametrosColores = ParametroColores::getCaidasParametros();
            $colores = $parametrosColores->COLORES->segunEstado->colores;
           // dd($colores);
            $estados = DB::select("SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt as codmasiva,a.umbral,a.Caida,a.fecha_hora,a.digi,
                                    IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,
                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->background."',
                                        IF(a.estado='CONTINUA'  AND a.ncaidas>8 OR a.offline>80 ,'".$colores[1]->background."',
                                            IF(a.estado='CONTINUA','".$colores[2]->background."','".$colores[4]->background."')
                                        )
                                    ) AS fondo ,
                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->color."',
                                        IF(a.estado='CONTINUA' and  a.ncaidas>8 OR a.offline>80,'".$colores[1]->color."',
                                            IF(a.estado='CONTINUA','".$colores[2]->color."','".$colores[4]->color."')
                                        )
                                    ) AS letra,
                                    IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,fp.respaldo, 
                                    if(pr.troba is null ,'','PREMIUM') as premium,rm.remedy,(a.offline/a.cancli) as porc_caida
                                    FROM alertasx.caidas_new a
                                    LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                                    left join catalogos.premium pr on concat(a.nodo,a.troba)=pr.troba
                                    left join dbpext.masivas_tempx mt
                                    on a.nodo=mt.codnod and a.troba=mt.nroplano
                                    left join alertasx.remedys_hfc  rm
                                    on a.nodo=rm.nodo and a.troba=rm.troba and datediff(now(),rm.fechahora)<=2
                                    where datediff(now(),fecha_hora)=0 $filtroJefatura $nodo AND a.nodo<>'' AND a.troba<>'' 
                                    group by a.estado,a.jefatura,a.nodo,a.troba
                                    order by a.estado,a.jefatura,a.nodo,a.troba");
                                    
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getListaCaidaTorreHfc($filtroJefatura,$nodo)
    {
        try {

            $parametrosColores = ParametroColores::getCaidasParametros();
            $colores = $parametrosColores->COLORES->segunEstado->colores;
           // dd($colores);
            $estados = DB::select("SELECT a.jefatura,a.nodo,a.troba,a.cancli,a.offline,mt.codreqmnt as codmasiva,a.umbral,a.Caida,a.fecha_hora,a.digi,
                                    IF(a.estado='LEVANTO',a.tiempo,TIMEDIFF(NOW(),fecha_hora)) AS tiempo,a.ncaidas,a.numbor,a.fecha_fin,a.estado,a.tc,
                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->background."',
                                        IF(a.estado='CONTINUA'  AND a.ncaidas>8 OR a.offline>80 ,'".$colores[1]->background."',
                                            IF(a.estado='CONTINUA','".$colores[0]->color."','".$colores[4]->background."')
                                        )
                                    ) AS fondo ,
                                    IF(a.tc='TC' AND estado='CONTINUA','".$colores[0]->color."',
                                        IF(a.estado='CONTINUA' and  a.ncaidas>8 OR a.offline>80,'".$colores[1]->color."',
                                            IF(a.estado='CONTINUA','".$colores[2]->color."','".$colores[4]->color."')
                                        )
                                    ) AS letra,
                                    IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,fp.respaldo, 
                                    if(pr.troba is null ,'','PREMIUM') as premium,rm.remedy,(a.offline/a.cancli) as porc_caida
                                    FROM alertasx.caidas_new a
                                    LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                                    left join catalogos.premium pr on concat(a.nodo,a.troba)=pr.troba
                                    left join dbpext.masivas_tempx mt
                                    on a.nodo=mt.codnod and a.troba=mt.nroplano
                                    left join alertasx.remedys_hfc  rm
                                    on a.nodo=rm.nodo and a.troba=rm.troba and datediff(now(),rm.fechahora)<=2
                                    where a.offline<500 and (a.offline/a.cancli)<=0.75 
                                    and datediff(now(),fecha_hora)=0 $filtroJefatura $nodo  AND a.nodo<>'' AND a.troba<>''
                                    group by a.estado,a.jefatura,a.nodo,a.troba
                                    order by a.estado,a.jefatura,a.nodo,a.troba");
                                    
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getListaCaidaAmplificador($filtroJefatura,$filtroTroba)
    {
        try {

            $parametrosColores = ParametroColores::getCaidasParametros();
            $colores = $parametrosColores->COLORES->segunEstado->colores;
           // dd($colores);
            $lista = DB::select("select  IF(zn1.sede='LIMA',CONCAT(zn1.sede,'-',zn1.jefatura),CONCAT('PROV-',zn1.zonal))  AS jefatura,
                                a.nodo,a.troba,a.cancli AS cancli,
                                a.offline,a.amplificador,b.codreqmnt AS  codmasiva,a.fecha_hora,a.estado,rm.remedy, 
                                IF(a.estado='CAIDO',TIMEDIFF(NOW(),a.fecha_hora),a.tiempo) AS tiempo,
                                c.cant AS cant1,d.tipo,a.fecha_fin,
                                IF(e.caidas >3,'CRITICA','') AS tcaidas,e.caidas AS ncaidas,d.numbor,
                                dg.fecha AS fecha_digi,IF(dg.nodo IS NOT NULL ,'Digitalizado','') AS digi,
                                CONCAT('TOP : ',t.top) AS top,
                                IF(fp.nodo IS NULL,'NO','SI') AS fuente,fp.mac4,fp.respaldo
                                FROM  alertasx.`caidas_new_amplif` a
                                LEFT JOIN catalogos.db_fuentes fp ON a.nodo=fp.nodo AND a.troba=fp.troba
                                LEFT JOIN dbpext.masivas_temp b
                                ON  a.nodo=b.codnod AND a.troba=b.nroplano
                                LEFT JOIN ccm1_temporal.consultasr c
                                ON a.nodo=c.nodo AND a.troba=c.troban
                                LEFT JOIN catalogos.bornesxtroba d
                                ON a.nodo=d.nodo AND a.troba=d.troba
                                LEFT JOIN ccm1_temporal.cant_caidas e
                                ON a.nodo=e.nodo AND a.troba=e.troba
                                LEFT JOIN catalogos.trobas_digi_view dg
                                ON a.nodo=dg.nodo AND a.troba=dg.troba
                                INNER JOIN ccm1.`zonales_nodos_eecc` zn1
                                ON zn1.`NODO`=a.`nodo`
                                LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba
                                LEFT JOIN alertasx.remedys_hfc  rm
                                on a.nodo=rm.nodo and a.troba=rm.troba and datediff(now(),rm.fechahora)<=2
                                $filtroTroba AND a.nodo<>'' AND a.troba<>''
                                GROUP BY a.estado,a.nodo,a.troba,a.amplificador
                                $filtroJefatura
                                ORDER BY a.estado,a.nodo,a.troba,a.amplificador
                               ");
        } catch(QueryException $ex){ 
              //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
              //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $lista;
    }

    function getProcesarCaidasMasivas($caidas,$filtroEstado)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getCaidasParametros();
                $colores = $parametrosColores->COLORES->segunEstado->colores;
        
                $cantidadCaidas = count($caidas);

                $acumulandoRespuestaCaidas = array();
                $contadorId = 0;
        
                for ($i=0; $i < $cantidadCaidas ; $i++) { 


                    $nodo = $caidas[$i]->nodo;
                    $troba = $caidas[$i]->troba;

                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='$nodo' and troba='$troba' and datediff(now(),fechahora)=0 order by fechahora desc limit 1");

                    $txtestadomasiva = "";
                    if (isset($alertasGestionQuery[0])) { 
                        $txtestadomasiva =  $alertasGestionQuery[0]->estado. " " . $alertasGestionQuery[0]->fechahora;
                    }
                    //print_r($alertasGestionQuery);
                    //echo "<br/><br/><br/>";
                    $caidas[$i]->alertasGestion = $alertasGestionQuery;

                    $averiasQuery = DB::select("select COUNT(*) AS aver from cms.req_pend_macro_final  where codnod='$nodo' and nroplano='$troba' 
                                                    and datediff(now(),fec_registro)=0");

                    $caidas[$i]->averiasc = ((int)$averiasQuery[0]->aver == 0)? 0 : $averiasQuery[0]->aver; 

                    $top='';
                    if($caidas[$i]->top == 'TOP : 200' || $caidas[$i]->top =='TOP : 100'){
                        $top= $caidas[$i]->top;
                    }
                    $caidas[$i]->top = $top;
         
                    if ($filtroEstado != "") {
                        
                       if (isset($alertasGestionQuery[0])) {
                         
                            //dd($alertasGestionQuery[0]->estado);
                            if (trim($alertasGestionQuery[0]->estado) == $filtroEstado) {
                                //Aqui se deberia crear una function private que procese el dato
                                $caidas[$i]->id = $contadorId + 1;
  
                                $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                                $contadorId++;
                            }
                       }
                    }else{
                          //Aqui se deberia reutilizar la function private que procesa el dato
                          $caidas[$i]->id = $contadorId + 1;
                          $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                          $contadorId++;
                    }

                   
                        
                }
        
                DB::insert("insert IGNORE ccm1_temporal.alarmas_caidas_historico
                            SELECT a.nodo,a.troba,b.aver,c.llamadas,a.cancli as cant,a.umbral,a.offline as off,a.fecha_hora,
                                TIMEDIFF(a.fecha_fin,a.fecha_hora) AS tiempo,a.fecha_fin,
                                concat(f.codreqmnt,' [ Est: ' ,f.edofrecave,']') AS codmasiva
                                FROM alertasx.caidas_new a 
                                LEFT JOIN (SELECT codnod AS nodo,nroplano AS troba,COUNT(*) AS aver FROM ccm1.averias_m1 GROUP BY 1,2) b
                                ON a.nodo=b.nodo AND a.troba=b.troba
                                LEFT JOIN catalogos.llamadasxtroba c
                                ON a.nodo=c.nodo AND a.troba=c.troban
                                LEFT JOIN dbpext.masivas_temp f
                                ON a.nodo=f.codnod AND a.troba=f.nroplano
                                WHERE a.fecha_fin<>'' AND DATEDIFF(NOW(),a.fecha_fin)=0 and a.cancli>50
                                GROUP BY a.nodo,a.troba
                                ORDER BY a.nodo,troba,a.fecha_fin DESC");
            
                return $acumulandoRespuestaCaidas;
           #END
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
      
    function getProcesarCaidasNoc($caidas,$filtroEstado)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getCaidasParametros();
                $colores = $parametrosColores->COLORES->segunEstado->colores;
        
                $cantidadCaidas = count($caidas);

                $acumulandoRespuestaCaidas = array();
                $contadorId = 0;
        
                for ($i=0; $i < $cantidadCaidas ; $i++) { 


                    $nodo = $caidas[$i]->nodo;
                    $troba = $caidas[$i]->troba;

                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='$nodo' and troba='$troba' and datediff(now(),fechahora)=0 order by fechahora desc limit 1");

                    $txtestadomasiva = "";
                    if (isset($alertasGestionQuery[0])) { 
                        $txtestadomasiva =  $alertasGestionQuery[0]->estado. " " . $alertasGestionQuery[0]->fechahora;
                    }
                    //print_r($alertasGestionQuery);
                    //echo "<br/><br/><br/>";
                    $caidas[$i]->alertasGestion = $alertasGestionQuery;

                    $averiasQuery = DB::select("select COUNT(*) AS aver from cms.req_pend_macro_final  where codnod='$nodo' and nroplano='$troba' 
                                                and datediff(now(),fec_registro)=0");

                    $caidas[$i]->averiasc = ((int)$averiasQuery[0]->aver == 0)? 0 : $averiasQuery[0]->aver; 
        
                    //dd($filtroEstado);
                    if ($filtroEstado != "") {
                        
                       if (isset($alertasGestionQuery[0])) {
                         
                            //dd($alertasGestionQuery[0]->estado);
                            if (trim($alertasGestionQuery[0]->estado) == $filtroEstado) {
                                //Aqui se deberia crear una function private que procese el dato
                                if((int)$caidas[$i]->offline > 500 || (double)$caidas[$i]->porc_caida >= 0.75 || (int)$caidas[$i]->averiasc > 50){
                                    $caidas[$i]->id = $contadorId + 1;
                                    $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                                    $contadorId++;
                                }
                                
                            }
                       }
                    }else{
                          //Aqui se deberia reutilizar la function private que procesa el dato
                         
                          if((int)$caidas[$i]->offline > 500 || (double)$caidas[$i]->porc_caida >= 0.75 || (int)$caidas[$i]->averiasc > 50){
                            $caidas[$i]->id = $contadorId + 1; 
                            $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                            $contadorId++;
                          }
                          
                    }

                   
                        
                }
        
                DB::insert("insert IGNORE ccm1_temporal.alarmas_caidas_historico
                            SELECT a.nodo,a.troba,b.aver,c.llamadas,a.cancli as cant,a.umbral,a.offline as off,a.fecha_hora,
                                TIMEDIFF(a.fecha_fin,a.fecha_hora) AS tiempo,a.fecha_fin,
                                concat(f.codreqmnt,' [ Est: ' ,f.edofrecave,']') AS codmasiva
                                FROM alertasx.caidas_new a 
                                LEFT JOIN (SELECT codnod AS nodo,nroplano AS troba,COUNT(*) AS aver FROM ccm1.averias_m1 GROUP BY 1,2) b
                                ON a.nodo=b.nodo AND a.troba=b.troba
                                LEFT JOIN catalogos.llamadasxtroba c
                                ON a.nodo=c.nodo AND a.troba=c.troban
                                LEFT JOIN dbpext.masivas_temp f
                                ON a.nodo=f.codnod AND a.troba=f.nroplano
                                WHERE a.fecha_fin<>'' AND DATEDIFF(NOW(),a.fecha_fin)=0 and a.cancli>50
                                GROUP BY a.nodo,a.troba
                                ORDER BY a.nodo,troba,a.fecha_fin DESC");
            
                return $acumulandoRespuestaCaidas;
           #END
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

    function getProcesoTorreHfc($caidas,$filtroEstado)
    {

        try {

            #INICIO
                $parametrosColores = ParametroColores::getCaidasParametros();
                $colores = $parametrosColores->COLORES->segunEstado->colores;
        
                $cantidadCaidas = count($caidas);

                $acumulandoRespuestaCaidas = array();
                $contadorId = 0;

                for ($i=0; $i < $cantidadCaidas ; $i++) { 
                    $nodo = $caidas[$i]->nodo;
                    $troba = $caidas[$i]->troba;

                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert where nodo='$nodo' and troba='$troba' and datediff(now(),fechahora)=0 order by fechahora desc limit 1");

                    $txtestadomasiva = "";
                    if (isset($alertasGestionQuery[0])) { 
                        $txtestadomasiva =  $alertasGestionQuery[0]->estado. " " . $alertasGestionQuery[0]->fechahora;
                    }
                    //print_r($alertasGestionQuery);
                    //echo "<br/><br/><br/>";
                    $caidas[$i]->alertasGestion = $alertasGestionQuery;

                    $averiasQuery = DB::select("select COUNT(*) AS aver from cms.req_pend_macro_final  where codnod='$nodo' and nroplano='$troba' 
                                                and datediff(now(),fec_registro)=0");

                    $caidas[$i]->averiasc = ((int)$averiasQuery[0]->aver == 0)? 0 : $averiasQuery[0]->aver; 

                    if ($filtroEstado != "") {
                        
                        if (isset($alertasGestionQuery[0])) {
                          
                             //dd($alertasGestionQuery[0]->estado);
                             if (trim($alertasGestionQuery[0]->estado) == $filtroEstado) {
                                 //Aqui se deberia crear una function private que procese el dato
                                 if((int)$caidas[$i]->averiasc < 50){
                                     $caidas[$i]->id = $contadorId + 1;
                                     $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                                     $contadorId++;
                                 }
                                 
                             }
                        }
                    }else{
                           //Aqui se deberia reutilizar la function private que procesa el dato
                          
                           if((int)$caidas[$i]->averiasc < 50){
                             $caidas[$i]->id = $contadorId + 1; 
                             $acumulandoRespuestaCaidas[] = $this->procesoCaidaGeneral($caidas[$i],$txtestadomasiva,$colores);
                             $contadorId++;
                           }
                           
                    }
 
                    
                }

                DB::insert("insert IGNORE ccm1_temporal.alarmas_caidas_historico
                            SELECT a.nodo,a.troba,b.aver,c.llamadas,a.cancli as cant,a.umbral,a.offline as off,a.fecha_hora,
                                TIMEDIFF(a.fecha_fin,a.fecha_hora) AS tiempo,a.fecha_fin,
                                concat(f.codreqmnt,' [ Est: ' ,f.edofrecave,']') AS codmasiva
                                FROM alertasx.caidas_new a 
                                LEFT JOIN (SELECT codnod AS nodo,nroplano AS troba,COUNT(*) AS aver FROM ccm1.averias_m1 GROUP BY 1,2) b
                                ON a.nodo=b.nodo AND a.troba=b.troba
                                LEFT JOIN catalogos.llamadasxtroba c
                                ON a.nodo=c.nodo AND a.troba=c.troban
                                LEFT JOIN dbpext.masivas_temp f
                                ON a.nodo=f.codnod AND a.troba=f.nroplano
                                WHERE a.fecha_fin<>'' AND DATEDIFF(NOW(),a.fecha_fin)=0 and a.cancli>50
                                GROUP BY a.nodo,a.troba
                                ORDER BY a.nodo,troba,a.fecha_fin DESC");

                return $acumulandoRespuestaCaidas;
            #END
             
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

    function getProcesoAmplificador($caidas,$filtroEstado)
    {
        
        try {
            #INICIO
                //dd($caidas);
                $parametrosColores = ParametroColores::getCaidasParametros();
                $colores = $parametrosColores->COLORES->Amplificador->colores;
                $cantidadCaidas = count($caidas);
 
                $contadorId = 0;
        
                for ($i=0; $i < $cantidadCaidas ; $i++) { 

                    $caidas[$i]->id = $contadorId + 1;

                    $nodo =$caidas[$i]->nodo;
                    $troba =$caidas[$i]->troba;
                    $caidas[$i]->tiempoCol = substr($caidas[$i]->tiempo,0,5);

                    $alertasGestionQuery = DB::select("select * from alertasx.gestion_alert 
                                            where nodo='$nodo' and troba='$troba' 
                                            order by fechahora desc limit 1");

                    $caidas[$i]->colorDifFechasGestionAlert = $colores[3]->background;
                    $caidas[$i]->gestionAlertas = [];
                    $caidas[$i]->estadoGestion = "";
                    $caidas[$i]->fechaHoraGestion = "";
                    $caidas[$i]->diferenciaFechaHora = false;
                    //dd($alertasGestionQuery);
                    if (isset($alertasGestionQuery[0])) { 
                        $caidas[$i]->gestionAlertas = $alertasGestionQuery[0];
                        $caidas[$i]->estadoGestion = $alertasGestionQuery[0]->estado;
                        $caidas[$i]->observacionesGestion = $alertasGestionQuery[0]->observaciones;
                        $caidas[$i]->fechaHoraGestion = $alertasGestionQuery[0]->fechahora;
                        $caidas[$i]->usuarioGestion = $alertasGestionQuery[0]->usuario;
                        $caidas[$i]->diferenciaFechaHora = $alertasGestionQuery[0]->fechahora < $caidas[$i]->fecha_hora;
                    }

                   
                    

                    $trabajoProgQuery = DB::select("SELECT ESTADO AS estado
                                                    FROM dbpext.`trabajos_programados_noc` 
                                                    WHERE NODO='$nodo' AND TROBA='$troba'
                                                    AND DATEDIFF(NOW(),FECHAREGISTRO)<=2 
                                                    order by FECHAREGISTRO desc limit 1");
                   // dd("aqusssiii");

                    $caidas[$i]->estadoTrabajoProgramado = isset($trabajoProgQuery[0]) ? $trabajoProgQuery[0]->estado : "";

                    $critica = DB::select("select count(*) as ncrit from reportes.criticos where nodo='$nodo' and troba='$troba'");

                    $caidas[$i]->ncrit = isset($critica[0])? (int)$critica[0]->ncrit : 0;
 
                    if ($caidas[$i]->estado != "LEVANTO") {
 

                        $caidas[$i]->background=$colores[0]->background;
                        $caidas[$i]->color=$colores[0]->color;
                        $caidas[$i]->mapaColor=$colores[0]->mapaIcon;
                        $caidas[$i]->gestionRegistroColor=$colores[0]->gestionRegistroIcon;
                        $caidas[$i]->gestionDetalleColor=$colores[0]->gestionDetalleIcon;

                        $caidas[$i]->tituloColorEstadoGestion       = $colores[0]->tituloColorEstadoGestion;
                        $caidas[$i]->contenidoColorEstadoGestion    = $colores[0]->contenidoColorEstadoGestion;
                        $caidas[$i]->usuarioColorEstadoGestion      = $colores[0]->usuarioColorEstadoGestion;
                        $caidas[$i]->fechaColorEstadoGestion        = $colores[0]->fechaColorEstadoGestion;

                        $caidas[$i]->crit = '';

                        if (($caidas[$i]->tipo == 'CRITICA' &&  $caidas[$i]->offline>80) ||  $caidas[$i]->offline>80 || 
                             $caidas[$i]->tcaidas=='CRITICA' || $caidas[$i]->cant1>19) {
                                
                            $caidas[$i]->background=$colores[2]->background;
                           
                            $caidas[$i]->mapaColor=$colores[2]->mapaIcon;
                            $caidas[$i]->gestionRegistroColor=$colores[2]->gestionRegistroIcon;
                            $caidas[$i]->gestionDetalleColor=$colores[2]->gestionDetalleIcon;

                            $caidas[$i]->tituloColorEstadoGestion       = $colores[2]->tituloColorEstadoGestion;
                            $caidas[$i]->contenidoColorEstadoGestion    = $colores[2]->contenidoColorEstadoGestion;
                            $caidas[$i]->usuarioColorEstadoGestion      = $colores[2]->usuarioColorEstadoGestion;
                            $caidas[$i]->fechaColorEstadoGestion        = $colores[2]->fechaColorEstadoGestion;

                            $caidas[$i]->crit = 'CRITICA'; 
                            DB::insert("insert IGNORE ccm1_temporal.trobasconcaidas_sms (nodo, troba, fecha_hora, sms_enviado)
                                        VALUES ('" . $nodo . "','" . $troba . "', '" . $caidas[$i]->fecha_hora . "', 0) ");
                             
                        } 

                        
                        $averiaSuma = DB::select("select sum(aver) as aver from ccm1_temporal.averxtrob where
                                            nodo='".$nodo."' and troba='".$troba."' and datediff(fec_mov,'".$caidas[$i]->fecha_hora."')=0");

                        $caidas[$i]->averia = (int)$averiaSuma[0]->aver;

                        $cantidadConsultAmplif = DB::select("select sum(cant) as cons from ccm1_temporal.consultasr_amplif where
                                                            nodo='".$nodo."' and troban='".$troba."' and amplificador='".$caidas[$i]->amplificador."'  and 
                                                            fechahora>='".$caidas[$i]->fecha_hora."'");

                        $caidas[$i]->cantConsultAmplif = (int)$cantidadConsultAmplif[0]->cons;
                   

                    }else{
                        $caidas[$i]->background=$colores[1]->background;
                        $caidas[$i]->color=$colores[1]->color;
                        $caidas[$i]->mapaColor=$colores[1]->mapaIcon;
                        $caidas[$i]->gestionRegistroColor=$colores[1]->gestionRegistroIcon;
                        $caidas[$i]->gestionDetalleColor=$colores[1]->gestionDetalleIcon;

                        $caidas[$i]->tituloColorEstadoGestion       = $colores[1]->tituloColorEstadoGestion;
                        $caidas[$i]->contenidoColorEstadoGestion    = $colores[1]->contenidoColorEstadoGestion;
                        $caidas[$i]->usuarioColorEstadoGestion      = $colores[1]->usuarioColorEstadoGestion;
                        $caidas[$i]->fechaColorEstadoGestion        = $colores[1]->fechaColorEstadoGestion;

                        $caidas[$i]->averia = 0;

                        $cantidadConsultAmplif = DB::select("select count(*) as cons from ccm1_temporal.consultasr_amplif where 
                                                            nodo='".$nodo."' and troban='".$troba."' and amplificador='".$caidas[$i]->amplificador."'   
                                                            and fechahora>='".$caidas[$i]->fecha_hora."'");
 

                        $caidas[$i]->cantConsultAmplif = (int)$cantidadConsultAmplif[0]->cons;
  
                    }

                    $fuentesQuery = DB::select("select IF(macstate IN ('online','online(d)','online(pt)','p-online','w-online','w-online(pt)',
                                                    'ol-d','ol-pt'),'ON', IF(macstate ='offline','OF','PR')) AS macstate,macaddress
                                            from ccm1.scm_total WHERE macaddress = '".$caidas[$i]->mac4."'");

                    $caidas[$i]->fuenteEstado = isset($fuentesQuery[0]) ? $fuentesQuery[0]->macstate : "";

                      
                    $contadorId++;

                }

                return $caidas;

            #END
        } catch(QueryException $ex){ 
              //dd($ex->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
           
       }catch(\Exception $e){
             // dd($e->getMessage());  
           //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return "error";
       }
    }

    private function procesoCaidaGeneral($caidas,$txtestadomasiva,$colores)
    { 

            #INICIO

                $hoy=date("Y-m-d");
                //print_r("paso...");
                //  dd($caidas);
                $caidas->hoy = $hoy;
                 
                $nodo = $caidas->nodo;
                $troba = $caidas->troba;
                $caidas->divisionOffline = (double)((int)$caidas->offline / (int)$caidas->cancli);
                $caidas->tiempoCol = substr($caidas->tiempo, 0, 5);
                $fecha_hora = $caidas->fecha_hora;

                $consultasMQuery = DB::select("select sum(cant) as cons from ccm1_temporal.consultasr_n where
                                                    nodo='$nodo' and troban='$troba' and fechahora>='" . $caidas->fecha_hora . "'");
                
                $caidas->consultasM = (int)$consultasMQuery[0]->cons == 0 ?  0 : $consultasMQuery[0]->cons;
                
                $caidas->crit = '';
 
                #PROC
                    if ($caidas->estado != "LEVANTO") {

                        #COLORES ICONOS

                            if ($caidas->tc == "TC" && $caidas->estado == "CONTINUA") {
                                $mapaColor             =   $colores[0]->mapaIcon;
                                $gestionRegistroColor  =   $colores[0]->gestionRegistroIcon;
                                $gestionDetalleColor   =   $colores[0]->gestionDetalleIcon;
                                $otrosIconsColor       =   $colores[0]->otrosIcons;

                                $tituloColorEstadoGestion       =   $colores[0]->tituloColorEstadoGestion;
                                $contenidoColorEstadoGestion       =   $colores[0]->contenidoColorEstadoGestion;
                                $usuarioColorEstadoGestion       =   $colores[0]->usuarioColorEstadoGestion;
                                $fechaColorEstadoGestion       =   $colores[0]->fechaColorEstadoGestion;
                                
                            }elseif ($caidas->estado == "CONTINUA" && (int)$caidas->ncaidas > 8 || (int)$caidas->offline > 80 ) {
                                $mapaColor             =   $colores[1]->mapaIcon;
                                $gestionRegistroColor  =   $colores[1]->gestionRegistroIcon;
                                $gestionDetalleColor   =   $colores[1]->gestionDetalleIcon;
                                $otrosIconsColor       =   $colores[1]->otrosIcons;

                                $tituloColorEstadoGestion       =   $colores[1]->tituloColorEstadoGestion;
                                $contenidoColorEstadoGestion       =   $colores[1]->contenidoColorEstadoGestion;
                                $usuarioColorEstadoGestion       =   $colores[1]->usuarioColorEstadoGestion;
                                $fechaColorEstadoGestion       =   $colores[1]->fechaColorEstadoGestion;

                            }elseif ($caidas->estado == "CONTINUA") {
                                $mapaColor             =   $colores[2]->mapaIcon;
                                $gestionRegistroColor  =   $colores[2]->gestionRegistroIcon;
                                $gestionDetalleColor   =   $colores[2]->gestionDetalleIcon;
                                $otrosIconsColor       =   $colores[2]->otrosIcons;

                                $tituloColorEstadoGestion       =   $colores[2]->tituloColorEstadoGestion;
                                $contenidoColorEstadoGestion       =   $colores[2]->contenidoColorEstadoGestion;
                                $usuarioColorEstadoGestion       =   $colores[2]->usuarioColorEstadoGestion;
                                $fechaColorEstadoGestion       =   $colores[2]->fechaColorEstadoGestion;

                            }else{
                                $mapaColor             =   $colores[4]->mapaIcon;
                                $gestionRegistroColor  =   $colores[4]->gestionRegistroIcon;
                                $gestionDetalleColor   =   $colores[4]->gestionDetalleIcon;
                                $otrosIconsColor       =   $colores[4]->otrosIcons;

                                $tituloColorEstadoGestion       =   $colores[4]->tituloColorEstadoGestion;
                                $contenidoColorEstadoGestion       =   $colores[4]->contenidoColorEstadoGestion;
                                $usuarioColorEstadoGestion       =   $colores[4]->usuarioColorEstadoGestion;
                                $fechaColorEstadoGestion       =   $colores[4]->fechaColorEstadoGestion;

                            }
                            
                            $caidas->mapaColor             = $mapaColor;
                            $caidas->gestionRegistroColor  = $gestionRegistroColor;
                            $caidas->gestionDetalleColor   = $gestionDetalleColor;
                            $caidas->otrosIconsColor       = $otrosIconsColor;

                            $caidas->tituloColorEstadoGestion    = $tituloColorEstadoGestion;
                            $caidas->contenidoColorEstadoGestion = $contenidoColorEstadoGestion;
                            $caidas->usuarioColorEstadoGestion   = $usuarioColorEstadoGestion;
                            $caidas->fechaColorEstadoGestion     = $fechaColorEstadoGestion;
 
                        #END

                
                        if ($caidas->tc == 'TC' || (int)$caidas->offline > 80) {
                            $caidas->crit = 'CRITICA';
                            DB::insert("insert IGNORE ccm1_temporal.trobasconcaidas_sms (nodo, troba, fecha_hora, sms_enviado)
                                                    VALUES ('" . $nodo . "','" . $troba . "', '" . $caidas->fecha_hora . "', 0) "); 
                        } 

                        $trobasCms = DB::select("select nodo_cms, troba_cms from catalogos.migraciones where nodo_hfc='$nodo' and troba_hfc='$troba'");
                        
                        $migalt = "";

                        if (count($trobasCms) > 0) {
                            $migalt="(";
                            for ($i=0; $i < count($trobasCms) ; $i++) { 
                                if($trobasCms[$i]->nodo_cms != ''){
                                    $migalt=$migalt.$trobasCms[$i]->nodo_cms." ".$trobasCms[$i]->troba_cms;
                                } 
                                if( $i+1 < count($trobasCms) ){ 
                                    $migalt=$migalt.", "; 
                                }
                            } 
                            $migalt=$migalt.")";  
                        } 

                        $caidas->migalt = $migalt;

                        
                        $critica = DB::select("select count(*) as ncrit from reportes.criticos where nodo='$nodo' and troba='$troba'");

                        $caidas->ncrit = isset($critica[0])? (int)$critica[0]->ncrit : 0;

                        $trabajoProgQuery = DB::select("SELECT ESTADO AS estado
                                                            FROM dbpext.`trabajos_programados_noc`
                                                            WHERE NODO='$nodo' AND TROBA='$troba' and estado='ENPROCESO'
                                                            order by finicio desc limit 1");

                        //$caidas->trabajosProgramados = $trabajoProgQuery;
                        $caidas->estadoTrabajoProgramado = "";
                        $txttrabajoprogramado = '';
                        if (isset($trabajoProgQuery[0])) {
                            $caidas->estadoTrabajoProgramado = $trabajoProgQuery[0]->estado; 
                            $txttrabajoprogramado = $trabajoProgQuery[0]->estado; 
                        }
                         
                        $caidas->txttrabajoprogramado = $txttrabajoprogramado;

                        /*$averiasQuery = DB::select("select COUNT(*) AS aver from cms.req_pend_macro_final  where codnod='$nodo' and nroplano='$troba' 
                                                and datediff(now(),fec_registro)=0");

                        $caidas->averiasc = ((int)$averiasQuery[0]->aver == 0)? 0 : $averiasQuery[0]->aver;*/ 
                        

                        $txtfecha_fin = '';
                        $txtalertaestado = 'PENDIENTE';
                             
                    }else{ 
                        
                            $caidas->fondo = $colores[3]->background;
                            $caidas->letra = $colores[3]->color;

                            $caidas->mapaColor             =   $colores[3]->mapaIcon;
                            $caidas->gestionRegistroColor  =   $colores[3]->gestionRegistroIcon;
                            $caidas->gestionDetalleColor   =   $colores[3]->gestionDetalleIcon;
                            $caidas->otrosIconsColor       =   $colores[3]->otrosIcons;

                            $caidas->tituloColorEstadoGestion       =   $colores[3]->tituloColorEstadoGestion;
                            $caidas->contenidoColorEstadoGestion    =   $colores[3]->contenidoColorEstadoGestion;
                            $caidas->usuarioColorEstadoGestion      =   $colores[3]->usuarioColorEstadoGestion;
                            $caidas->fechaColorEstadoGestion        =   $colores[3]->fechaColorEstadoGestion;


                            $trabajoProgQuery = DB::select("select ESTADO AS  estado
                                                                FROM dbpext.`trabajos_programados_noc`
                                                                WHERE NODO='$nodo' AND TROBA='$troba'  and estado='ENPROCESO'
                                                                order by FECHAREGISTRO desc limit 1");
                            //dd($trabajoProgQuery);
                            //$caidas->trabajosProgramados = $trabajoProgQuery;
                            $caidas->estadoTrabajoProgramado = "";
                            $txttrabajoprogramado = '';
                            if (isset($trabajoProgQuery[0])) {
                                $caidas->estadoTrabajoProgramado = $trabajoProgQuery[0]->estado;
                                if ($trabajoProgQuery[0]->estado == 'CERRADO') { 
                                    $txttrabajoprogramado = $trabajoProgQuery[0]->estado; 
                                }
                                else {
                                    $txttrabajoprogramado = $trabajoProgQuery[0]->estado;  
                                }
                            }
                             
                            $caidas->txttrabajoprogramado = $txttrabajoprogramado;
 
                            $averiasQuery = DB::select("select COUNT(*) AS aver from cms.req_pend_macro_final  where codnod='$nodo' and nroplano='$troba' 
                                                        and fec_registro<='".$caidas->fecha_fin."'");

                            $caidas->averiasc = ((int)$averiasQuery[0]->aver == 0)? 0 : $averiasQuery[0]->aver; 

                            $txtfecha_fin = $caidas->fecha_fin;
                            $txtalertaestado = 'CERRADA';
                                    
                    }
                #END

                $fuentesQuery = DB::select("select IF(macstate IN ('online','online(d)','online(pt)','p-online','w-online','w-online(pt)',
                                                    'ol-d','ol-pt'),'ON', IF(macstate ='offline','OF','PR')) AS macstate,macaddress
                                            from ccm1.scm_total WHERE macaddress = '".$caidas->mac4."'");

                $caidas->fuenteEstado = isset($fuentesQuery[0]) ? $fuentesQuery[0]->macstate : "";
                
                $detalleGestionAlertQuery = DB::select("select fechahora,observaciones,usuario,tecnico 
                                                    FROM alertasx.gestion_alert 
                                                    WHERE nodo='$nodo' AND troba='$troba' AND fechahora>'$fecha_hora'
                                                    ORDER BY fechahora DESC LIMIT 1");


                $txtfgest = "";
                $txtobs = "";
                $txtuser = "";
                $txttecn = "";
                
                if (isset($detalleGestionAlertQuery[0])) {
                    $txtfgest = $detalleGestionAlertQuery[0]->fechahora;
                    $txtobs = $detalleGestionAlertQuery[0]->observaciones;
                    $txtuser = $detalleGestionAlertQuery[0]->usuario;
                    $txttecn = $detalleGestionAlertQuery[0]->tecnico;
                }

                if ($txtalertaestado != "PENDIENTE") {
                    DB::statement("replace alertasx.alertas_reporte 
                                    values ('".$caidas->jefatura."','$nodo','$troba','".$caidas->txttrabajoprogramado."',
                                            ".(int)$caidas->averiasc.",".(int)$caidas->cancli.",".(int)$caidas->umbral.",".(int)$caidas->offline.",
                                            ".(int)$caidas->codmasiva.",".(int)$caidas->consultasM.",
                                            '".$fecha_hora."','$txtfecha_fin','".$caidas->crit."','".$caidas->tiempo."',
                                            ".(int)$caidas->ncaidas.",".(int)$caidas->numbor.",'$txtestadomasiva','$txtalertaestado',
                                            '$txtfgest','$txtobs','$txtuser','$txttecn')");
                } 

                
            #END
   
        return $caidas;
                    
    }

    function listaClientesCriticos($nodo,$troba,$amplificador)
    {
        $lista = DB::select("select IDCLIENTECRM,idempresacrm,NAMECLIENT,NODO,TROBA,amplificador,tap,telf1,telf2,movil1,MACADDRESS,cmts,f_v,entidad 
                            from reportes.criticos where nodo='$nodo' and troba='$troba' $amplificador ");
        return $lista;
    }
 

}