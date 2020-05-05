<?php 

namespace App\Functions;

use DB; 
use App\Administrador\Parametrosrf;
use App\Functions\IntrawayFunctions;
use Illuminate\Support\Facades\Auth;
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
use App\Functions\CablemodemStatusFunctions;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MulticonsultaFunctions {
 
    function validarSearch($bus,$tipoBus){
        //compruebo que el tamaño del string sea válido.
        
        $mensaje = "";
        $error = false;

        if($tipoBus == "seleccionar" || ($tipoBus != 1 && $tipoBus != 2 && $tipoBus != 3 && $tipoBus != 4 && $tipoBus != 5 && $tipoBus != 6)){  
            $mensaje = 'El tipo de busqueda no es válido';  
            //$this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
            throw ValidationException::withMessages([ "tipo" => "Seleccione un tipo de busqueda válida" ]);
        } 

        switch ($tipoBus) {
            case 1://Cod Cliente
                    if (preg_match("/^[0-9\.]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "El codigo del cliente no tiene un formato válido";
                    }
                    if (substr($bus,0,1) == 0) {
                        $error = true;
                        $mensaje = "El codigo del cliente no tiene un formato válido";
                    }
                break; 
            case 2://Mac
                    if (preg_match("/^[a-zA-Z0-9\:.]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "La Mac Address no tiene un formato válido";
                    }
                    if (strlen(trim($bus)) < 12 || strlen(trim($bus)) > 17) {
                        $error = true;
                        $mensaje = "La longitud de la Mac Address no es correcta.";
                    }
                break; 
            case 3://Telefono o movil
                    if (preg_match("/^[0-9]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "El telefono TFA/CEL no tiene un formato válido.";
                    }
                    if (strlen(trim($bus)) > 9 || strlen(trim($bus)) < 7) {
                        $error = true;
                        $mensaje = "La longitud del telefono no es correcto.";
                    }
                break; 
            case 4://HFC
                    if (preg_match("/^[0-9]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "El telefono HFC no tiene un formato válido.";
                    }
                    if (strlen(trim($bus)) > 9 || strlen(trim($bus)) < 7) {
                        $error = true;
                        $mensaje = "La longitud del HFC no es correcto.";
                    }
                break; 
            case 5://DNI
                    if (preg_match("/^[0-9]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "El DNI no tiene un formato válido.";
                    }
                    if (strlen(trim($bus)) > 8 || strlen(trim($bus)) < 8) {
                        $error = true;
                        $mensaje = "La longitud del DNI no es correcto.";
                    }
                break; 
            case 6://RUC
                    if (preg_match("/^[0-9]+$/", $bus) != 1) {
                        $error = true;
                        $mensaje = "La RUC no tiene un formato válido.";
                    }
                    if (strlen(trim($bus)) > 11 || strlen(trim($bus)) < 11) {
                        $error = true;
                        $mensaje = "La longitud de la RUC no es correcta.";
                    }
                break; 
                default: 
                    throw new HttpException(422,"El tipo de busqueda no existe en el sistema."); 
                    break; 
        }

        if ($error) {
            throw new HttpException(422,$mensaje); 
        }
 
    }
 

    function getCantUltRegisMulti($codCliente,$usuario,$ultimosMinutos)
    {
        //Registros del usuario auth en la ultima hora
        $ultimoRegistro = DB::select("
                                    SELECT COUNT(*) as cantidad FROM multiconsulta.multi_consultas 
                                    WHERE fechahora >= DATE_SUB(NOW(), INTERVAL $ultimosMinutos MINUTE) 
                                    AND dato=? AND usuario=?",[$codCliente,$usuario]);
        
        $cantidad=$ultimoRegistro[0]->cantidad;

        return $cantidad; 
    }

    function getDataClientByMac($macAddress)
    { 
        try { 
            $resultado = DB::select("
                             SELECT * FROM multiconsulta.`nclientes` 
                             WHERE macaddress= ?",[$macAddress]); 
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        } 
        return $resultado; 
    }

    function getCodClientByDNI($dni)
    { 
        try { 
            $resultado = DB::select(" select CLIENTE from cms.`planta_clarita` WHERE numerodoc='$dni' "); 
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        } 
        return $resultado; 
    }
    function getCodClientByRUC($ruc)
    { 
        try { 
            $resultado = DB::select(" SELECT CLIENTE FROM cms.`planta_clarita` WHERE NUMERORUC=$ruc "); 
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        } 
        return $resultado; 
    }

    function storeConsulta($bus,$tipoBus,$fech_hor,$usuario)
    {   
        DB::insert("insert ignore multiconsulta.multi_consultas 
        (item,tipobusqueda,dato,fechahora,usuario)
        values(null,?,?,?,?)",
                [$tipoBus,$bus,$fech_hor,$usuario]);  
         
    }

    function updateConsulta($bus, $mensaje, $fech_hor,$rol,$usuario) { 
         
        DB::update("update multiconsulta.multi_consultas 
                            SET mensaje=? 
                            WHERE dato=? AND fechahora=? AND usuario=?",
                        [$mensaje,$bus,$fech_hor,$usuario]);  
        
    }

    function codsCliConMuchosServicios()
    {
        $lista = DB::select("select idclientecrm FROM multiconsulta.nclientes GROUP BY 1
                            HAVING (COUNT(*)>20)");
        return $lista;
    }
  
    function ArmandoQuery($tipoBus,$bus){
        $queryresult = array();   
        switch ($tipoBus) {
          case 1 :
              $queryresult["filtroWhere"] = " AND a.IDCLIENTECRM=$bus ";
              $queryresult["TipBus"] = "IDCLIENTECRM";
              $queryresult["limit"] = " ";
              break;
          case 2 :
              $queryresult["filtroWhere"] = " AND a.`mac3`='" . str_replace('-', '', str_replace(':', '', str_replace('.', '', $bus))) . "' ";
              $queryresult["TipBus"] = "MACADDRESS";
              $queryresult["limit"] = " LIMIT 1 "; 
              break;
          case 3 :
              $queryresult["filtroWhere"] = " AND (a.`telf1`='$bus' or a.`telf2`='$bus' or a.`movil1`='$bus' )";
              $queryresult["TipBus"] = "TELEFONO TBA/CEL";
              $queryresult["limit"] = "  ";
              break;
          case 4 :
              $queryresult["filtroWhere"] = " and h.`telefonohfc`='$bus'";
              $queryresult["TipBus"] = "TELEFONO HFC";
              $queryresult["limit"] = "  "; 
              break; 
        default: 
              throw new HttpException(422,"El tipo de busqueda no existe en el sistema.");
              break; 
       }
       return $queryresult;

    }

    function queryPrincipal($filtroWhere,$limit){

        
        try { 
            $qprinc = DB::select("
            SELECT a.codserv,a.IDCLIENTECRM,a.estado AS estadoserv ,a.idservicio, a.idproducto, a.idventa,a.amplificador,a.idproductomta,
            a.nameclient AS Nombre, tcm.telf1 as telf1, tcm.telf2 as telf2, tcm.telf3 as movil1, a.MACADDRESS,a.IPCM,
            f.Fabricante, f.Modelo,
            f.Versioon AS Version_firmware,st.NumCPE, if(st.cmts is null,if(nv.macaddress is null,st.cmts,nv.cmts),st.cmts) as cmts,a.mtamac,IF(a.mtamac<>'N/D','Cliente Tiene VOIP','Cliente No Tiene VOIP') AS voip,
            IF(g.codreqmnt >0,g.codreqmnt,0) AS num_masiva,h.telefonohfc, a.mac2, n.veloc_comercial,'' AS fecha_corte,
            IF(a.estado='Inactivo','Cortado','Nada') AS corte, a.scopesgroup ,
            IF(px.nodo IS NOT NULL,'TRABAJO PROGRAMADO','') AS trab,px.TIPODETRABAJO,
            a.nodo AS NODO,a.troba AS TROBA, n.velocidad_final AS SERVICEPACKAGE,n.SERVICEPACKAGECRMID AS SERVICEPACKAGECRMID,
            n.velocidad_final ,
            IF(st.macstate LIKE '%nline%' ,CONCAT(IF(k.tipopuerto IS NOT NULL,k.tipopuerto,''),IF(so.Puerto IS NOT NULL AND so.semana36='Sat_>90','PUERTO_SATURADO</br>',''),''),'') AS saturado,
            IF(ll.tipo='CAIDA MASIVA','Caida',IF(ll.tipo='CAIDA AMPLIF','Caida Amplif',
            IF(ll.tipo='CAIDA SENAL','Señal_RF',IF(ll.tipo='CAIDA SENAL AMPLIF','Señal RF Amplif','')))) AS cliente_alerta
            , TRIM(a.IPCM) AS IPAddress,
            IF(a.cmts='HIGUERETA3' AND SUBSTR(a.f_v,1,6) IN ('C5/0/0','C5/0/1','C5/0/2'), ss.description,CONCAT(a.nodo,' : ',a.troba)) AS Nodo_Troba,
            TIMEDIFF(NOW(),CONCAT(SUBSTR(g.fecreg,7,4),'-',SUBSTR(g.fecreg,4,2),'-',SUBSTR(g.fecreg,1,2),' ',SUBSTR(g.fecreg,12,8))) AS tiempo_masiva,
            IF(cc.entidad IS NOT NULL ,'CLIENTE INFLUYENTE','') AS tipocli,
            IF(nv.Interface IS NOT NULL,TRIM(nv.Interface),TRIM(st.Interface)) AS interface,
            nv.USPwr,nv.USMER_SNR,nv.DSPwr,nv.DSMER_SNR,st.RxPwrdBmv,st.IPAddress,
            IF(st.MACState LIKE '%nline%','online',IF(st.macstate IS NULL,IF(nv.`MACAddress` IS NULL,'','online'),st.macstate)) AS MACState,f.docsis,a.naked,'' AS velocidad_actual,
            IF(cv.codigo IS NOT NULL,'CONVERGENTE','') AS convergente,tm.telef1 as tmtelef1,tm.telef2 as tmtelef2,tm.telef3 as tmtelef3
            FROM multiconsulta.nclientes a
                    LEFT JOIN ccm1_data.marca_modelo_docsis_total f  ON a.MACADDRESS=f.MACAddress
                    LEFT JOIN catalogos.velocidades_cambios n ON a.SERVICEPACKAGE=n.SERVICEPACKAGE
                    LEFT JOIN dbpext.masivas_temp g ON a.nodo=g.codnod AND a.troba=g.nroplano
                    LEFT JOIN catalogos.telefonoshfc h ON a.MACADDRESS=h.macaddress
                    LEFT JOIN dbpext.trabajos_pendientes_view px  ON a.nodo=px.nodo AND a.troba=px.troba
                    LEFT JOIN reportes.clientes_en_puerto_saturado k  ON a.MACADDRESS=k.macaddress
                    LEFT JOIN alertasx.clientes_alertados ll ON a.MACADDRESS = ll.macaddress
                    LEFT JOIN catalogos.etiqueta_puertos ss ON a.cmts=ss.cmts AND a.f_v=ss.interface
                    LEFT JOIN reportes.criticos cc ON a.idclientecrm=cc.idclientecrm
                    LEFT JOIN ccm1.scm_phy_t nv ON a.mac2=nv.macaddress
                    LEFT JOIN ccm1.scm_total st ON a.mac2=st.macaddress
                    LEFT JOIN catalogos.convergente cv ON a.idclientecrm=cv.codigo
                    LEFT JOIN catalogos.saturaciones_olt so on concat(a.nodo,a.troba)=so.Puerto
                    left join catalogos.telef_multi tm on  a.idclientecrm=tm.codcli
                    left join catalogos.planta_telef_cms_new tcm on a.idclientecrm=tcm.cliente
                WHERE 1=1 $filtroWhere 
                GROUP BY a.`IDCLIENTECRM`,5,6  $limit");

          } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            // Note any method of class PDOException can be called on $ex.
          }
 
                 
          return $qprinc;
    }

    function getInfoRequerimientoCATV($codcli){


        try {
            $result = DB::select("SELECT a.codigo_del_cliente,a.codigo_req  AS codreq,a.codigo_tipo_req,a.codigo_motivo_req,b.des_motivo,a.fecha_liquidacion
                            FROM cms.prov_liq_catv_pais a
                            INNER JOIN cms.cms_tiporeq_motivo b
                            ON a.codigo_tipo_req=b.tipo_req
                            AND a.codigo_motivo_req=b.motivo
                            WHERE a.codigo_del_cliente = '$codcli' AND DATEDIFF(NOW(),a.fecha_liquidacion)<=7 
                            ORDER BY a.fecha_liquidacion DESC LIMIT 1"); 
        }catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            // Note any method of class PDOException can be called on $ex.
        }
      

        return $result;
    }

    function buscarClientePlantaClarita($codcli)
    {
        try {
            $queryTv = DB::select("SELECT a.ofi_cli,a.cliente,a.CODLEX, CONCAT(TRIM(a.nombre),' ',TRIM(a.ape_pat),' ',TRIM(a.ape_mat)) AS nomcli,a.servicio, a.NODO,a.desnodo,a.plano,a.desdpt,a.desdtt, 
                        IF(DATEDIFF(NOW(),d.FINICIO)<=15 AND tipodetrabajo='DIGITALIZACION' ,'ZONA DIG NUEVA (No generar averia)', IF(DATEDIFF(NOW(),d.FINICIO)>15 , 'ZONA DIG ANTIGUA','NO DIGITALIZADA')) AS dato, 
                        IF(d.fecha_apertura IS NULL,'',d.fecha_apertura) AS fechan, 
                        IF(c.nodo IS NOT NULL ,'Averia en M1',IF( DATEDIFF(NOW(),d.FINICIO)<=1 ,'TRABAJO PROGRAMADO','')) AS masiva, 
                        IF(e.codnod IS NOT NULL,e.codreqmnt,'') AS num_masiva,
                        IF(f.cant >=10 , 'CLIENTE DENTRO DE MASIVA CATV ','NO' ) AS masivacatv , 
                        IF(e.fecreg IS NOT NULL, TIMEDIFF(NOW(),CONCAT(STR_TO_DATE(e.fecreg, '%d/%m/%Y'),' ',TRIM((SUBSTR(e.fecreg,11,6))))),'') AS tiempo_masiva,
                        tiptec ,if(mt.clientecms is null,'','Movistar Total') as mtot
                        FROM cms.planta_clarita a LEFT JOIN alertasx.caidas_t c ON a.NODO=c.nodo AND a.plano=c.troba AND c.Caida='SI' 
                        LEFT JOIN dbpext.trabajos_pendientes_view d ON a.nodo=d.nodo AND a.plano=d.troba AND d.estado='ENPROCESO'
                        LEFT JOIN dbpext.masivas_temp e ON a.NODO=e.codnod AND a.plano=e.nroplano AND e.codedo='S' 
                        LEFT JOIN alertasx.masivas_catv f ON a.NODO=f.nodo AND a.plano=f.troba 
                        left join catalogos.movistar_total mt on a.cliente=mt.clientecms
                        WHERE a.cliente ='$codcli' GROUP BY a.cliente");
        } catch (QueryException $ex) {
            return "error";
        }

        return $queryTv;
    }

    function buscarClienteAdsl($codcli)
    {
        try {
            $queryAdsl = DB::select("SELECT * FROM adsl.`PlantaAdsl` a WHERE a.FEACTS=? GROUP BY a.feacts",[$codcli]);
        }  catch (QueryException $ex) {
            return "error";
        }

        return $queryAdsl;
    }

    function resBusVarios($bus){

        try {
            $cad =  DB::select(
                "SELECT 
                a.IDCLIENTECRM,
                f.Nombre,
                a.telf1,
                a.telf2,
                a.MACADDRESS,
                a.SERVICEPACKAGE,
                a.cmts AS cmts1,
                a.f_v AS interface,
                c.IPAddress,
                c.MACState,
                f.Fabricante,
                f.Modelo,
                f.Versioon AS Version_firmware,
                f.cmts,
                a.direccion
                FROM multiconsulta.nclientes a 
                LEFT JOIN ccm1.scm_total c 
                ON a.mac2=c.macaddress
                LEFT JOIN ccm1_data.marca_modelo_docsis_total_final f
                ON a.MACADDRESS=f.MACAddress
                WHERE 1=1 AND a.idclientecrm=?", [$bus]);
        } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            // Note any method of class PDOException can be called on $ex.
          }
        
        
             return $cad;
    }

    function validaDigitalizacion($nodo,$troba){
    
        $msjDigi='';
        $resTrabDigi = DB::select("
        SELECT concat(MENSAJE,' : REALIZADA EL : ',substr(fecha_registro,1,10)) as MENSAJE 
        FROM dbpext.digitalizacion_view WHERE nodo=?
           AND troba=? LIMIT 1",[$nodo, $troba]);
           
        if(!empty($resTrabDigi)){
            $msjDigi = trim($resTrabDigi[0]->MENSAJE);
        }
         
        return $msjDigi;
    }

    function validaTrabProg($nodo,$troba,$bus,$mensaje,$fech_hor,$rol,$corte,$trab){
        $rowTrabProg='';
        $msjDigi='';
        $esTrabProg =0;
 
        $resTrabProg= DB::select("
        SELECT 'SI' as tp  FROM dbpext.trabajos_programados_noc WHERE nodo=?
        AND troba=? and estado='ENPROCESO' LIMIT 1", [$nodo,$troba]);
  
        if(!empty($resTrabProg)){
          //$rowTrabProg = $resTrabProg[0]->tp."</br>".$msjDigi;
          $rowTrabProg = $resTrabProg[0]->tp;
        }
       
        if ($rowTrabProg == 'SI') {
            $esTrabProg = 1;
        } 
        elseif ($trab == 'TRABAJO PROGRAMADO') {
              // Es masiva 
                  $esTrabProg = 1;
                  //$mensaje='';
                  
                  if ($corte <> 'Cortado') {
                    $esTrabProg = 1;
                    //$mensaje = 'Troba dentro de Trabajos Programados';
                  }
                  
              //Guarda consulta
               // $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
                
                
                
        }
       
        return $esTrabProg;
    }

    function validarServicio($nodo,$troba,$area,$corte,$trab,$ttrab,$num_masiva,$tiempo_masiva,$cliente_alerta){
        $mensaje='';
        $sw=0;
          if($corte=='Cortado' && $sw==0)
            {$sw=1;$mensaje='Cliente con corte de servicio ejecutado en Intraway M1';}
          if($corte<>'Cortado' && $trab<>'' && $sw==0)
            {$sw=1;$mensaje='Troba dentro de Trabajos Programados :</br>'.$ttrab."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta<>'' && $num_masiva*1==0)
            {if($cliente_alerta=='Caida' || $cliente_alerta=='Caida Amplif' ){$msjx=$cliente_alerta;} else {$msjx=$cliente_alerta;}
            $sw=1;$mensaje='Alerta de '.$msjx.' Generar averia R417 </br> Problemas en el servicio detectado.'."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta<>'' && $num_masiva*1>0)
            {$sw=1;$mensaje="Generar averia R417 </br>Problema con su Servicio :<br/>Averia Nro:" . $num_masiva . "</br> Tiempo de incidencia: ".substr($tiempo_masiva,0,8)."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta=='' && $num_masiva>0)
            {$sw=1;$mensaje="Generar averia R417 </br>Problemas en el Servicio de TV <br/>Averia Nro:" . $num_masiva . "</br> Tiempo : ".substr($tiempo_masiva,0,8)."</br>Nodo:".$nodo." Troba:".$troba;}
          return $mensaje;
    }

    function validaMsjPer($cmts,$cmtspuerto,$nodo,$troba,$hoy){
      $ms = DB::select("
            select msj from multiconsulta.tbmsj
            where ( (cmts=? and cmts<>'')  or  
                    (ptocmts=? and ptocmts<>'') or 
                    (nodo=? and troba=? and nodo<>'' and troba<>'') or 
                    (nodo=? and nodo<>''  and troba='')
                  ) and fechahorafin>=?",[$cmts,$cmtspuerto,$nodo,$troba,$nodo,$hoy]); 
      
      $msj=empty($ms)? '' : $ms[0]->msj;
      return $msj;
    }

    function validaMta($mtamac,$telefonohfc){
      $tipoprob='';
      if($mtamac=='N/D' && $telefonohfc>0){
        $tipoprob='Mta No Provisionado en Intraway </br> Agendar a Back Office</br>';
      }
      return $tipoprob;
    }

    function validaNiveles($downPx,$upSnr,$downSnr,$upPx,$cliente_alerta,$nodo,$troba,$macstate,$num_masiva){
      $tipoprob='';
      $niveles='ok';
     
          if (($cliente_alerta == 'Caida' || $cliente_alerta == 'Señal RF' || $cliente_alerta == 'Caida Amplif' || $cliente_alerta == 'Señal RF Amplif' ) && $niveles==''){
             //echo "Entra";
          if ($downPx < -5 || $downPx > 10) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr  < 27 ) {
              $tipoprob = 'Generar averia R417 </br>Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr  < 27 and $upPx <36 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
            //echo "entra";
          }
          if ($downSnr  < 29 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx  <= - 5 || $downPx  > 12)  {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
         
          if ($upPx  <= 35 || $upPx  > 55)  {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx  > 10 && $upPx  <= 36  ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx  > 8 && $downSnr  < 30 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upPx  < 35 && $upPx  > 0 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx  > 15 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
         //
          if ($upSnr  < 27 && $downSnr  > 30 && $downPx  >= - 10 && $downPx  <= 12 && $upPx  >= 37 && $upPx  <= 55) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr  > 27 && $downSnr  < 30 && $downPx  >= - 10 && $downPx  <= 12 && $upPx  >= 37 && $upPx  <= 55 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx  < - 15 || $downPx  > 15) {
              $niveles = 'Malos';
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
          }
        }
       
        // Hasta aqui - Problemas de Planta
        ///************************** */
        if (($cliente_alerta <> 'Caida' && $cliente_alerta <> 'Señal RF' && $cliente_alerta <> 'Caida Amplif' && $cliente_alerta <> 'Señal RF Amplif' ) && $niveles<>'Malos'){
          if (($downPx < - 5 && $upPx > 55)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if (($downPx < - 5 || $downPx > 10)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx < - 5 && $downSnr < 30 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          // vacio
          
          if ($upSnr  < 27 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          
          if ($downSnr  < 29) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if (($downPx  <= - 5 || $downPx  > 12)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          } 
        
          if (($upPx  <= 35 || $upPx  > 55)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
         //
        
          if ($downPx  < - 10 && $upPx  > 55 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'malos';
          }
        
          if ($downPx  > 8 && $downSnr  < 30 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
         //vacio
          if ($downPx  > 15 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          //
         
          if ($upSnr  < 27 && $downSnr  > 30 && $downPx  >= - 10 && $downPx  <= 12 && $upPx  >= 37 && $upPx  <= 55) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($upSnr  > 27 && $downSnr  < 30 && $downPx  >= - 10 && $downPx  <= 12 && $upPx  >= 37 && $upPx  <= 55 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx  < - 15 || $downPx  > 15) {
              $niveles = 'Malos';
              $tipoprob = 'Probable averia en:Red Cliente';
            
          }
        }
        if($downPx=='' and $downSnr==''  && $macstate == 'online'){$tipoprob ='';}
       
        //Validacion de estado Init para mensaje de generacion de averias y derivacion a badeja 415
        if ( ($macstate == "init(d)" || $macstate == "init(i)"   || $macstate == "init(io)"  || $macstate == "init(o)"     || 
            $macstate == "init(r)"  || $macstate == "init(r1)"  || $macstate == "init(t)"   || $macstate == "bpi(wait)")   
            && $num_masiva ==0 ){
          $tipoprob = 'Probable averia en:Red Cliente';
        }
        // Fin de validacion de Init
        
        ///
        $amasiv = DB::select(
          "select 'SI' as averia 
          FROM alertasx.caidas_t a 
          WHERE a.nodo=? AND a.troba=? AND Caida='SI' 
          limit 1",[$nodo,$troba]);
        $masi = empty($amasiv) ? "" : $amasiv[0]->averia;
        
        ///
        $amasiv_amp = DB::select(
          "select 'SI' as averia 
          FROM alertasx.caidas_new_amplif a 
          WHERE a.nodo=? AND a.troba=? AND estado='CAIDO'  
          limit 1",[$nodo,$troba]);
        $masi_amp = empty($amasiv_amp) ? "" : $amasiv_amp[0]->averia;
  
        

        if($masi_amp == 'SI' && ($macstate=='offline' || $macstate == "init(d)" || $macstate == "init(i)" || $macstate == "init(io)" || $macstate == "init(o)" || $macstate == "init(r)" || $macstate == "init(r1)"  || $macstate == "init(t)" || $macstate == "bpi(wait)")){
           $tipoprob = 'Probable problema de Pext - Amplif';
        }
  
        if($masi == 'SI'   && ($macstate=='offline' || $macstate == "init(d)" || $macstate == "init(i)" || $macstate == "init(io)" || $macstate == "init(o)" || $macstate == "init(r)" || $macstate == "init(r1)"  || $macstate == "init(t)" || $macstate == "bpi(wait)")){
            $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
        }
  
        if(($cliente_alerta == 'Caida Amplif' || $cliente_alerta == 'Señal RF Amplif') &&  $niveles<>'ok'){
            $tipoprob = 'Probable problema de Pext - Amplif';
        }
  
        if(($cliente_alerta == 'Caida' || $cliente_alerta == 'Señal RF' )  &&  $niveles<>'ok'){
            $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
        }
  
        if($niveles=='ok'){ $tipoprob='';}
  
          return $tipoprob;
    }

    function verMacIpPe($fabricante,$ipaddress,$docsis)
    {

        
        $verMacIpPeR = array();

        $fabricante_substr = substr($fabricante,0,5);
        $oidx='iso.3.6.1.2.1.4.34.1.10.1.4';

        if($fabricante_substr=="Arris"){
            $oidx='iso.3.6.1.2.1.4.20.1.1';
            
        }
        if($fabricante_substr=="Hitro"){
            $oidx='iso.3.6.1.2.1.4.22.1.1.1'; 
        }

        // Proceso para obtener la ip publica o cgnat
        $verMacIpPeR["publica"]="";
        $verMacIpPeR["macx"]="";
        $verMacIpPeR["macmta"]="";
        $verMacIpPeR["ipmta"]="";

        $cpe=array();
         
        if ($ipaddress<>'0.0.0.0'){//inicio if
            $ippu="snmpwalk  -c MODEM8K_PILOTO -v2c ".$ipaddress." ".$oidx;
            //echo $ippu;
            // dd($ippu);
            //$reg=array();
            exec($ippu,$cpe);
            //\Log::error(["resultado Public"=>$cpe,"comando"=>$ippu]);
             
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."10.77.25.62" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."10.79.8.246" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."100.105.154.126" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."127.0.0.1" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."192.168.1.1" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."192.168.22.1" = INTEGER: active(1)';
               // $cpe[] = 'IP-MIB::ipAddressRowStatus.ipv4."192.168.100.1" = INTEGER: active(1)';
             //dd($cpe);
            $regy=array();
            
            $reg ='';
            //echo $ippu;4
            $cantidad_cpe = count($cpe);
            for ($i=0;$i<$cantidad_cpe;$i++){
                $regy = $cpe[$i];
                $cad='';
                if($fabricante_substr=="Arris"){ 
                    $cad=substr($regy,20,3);
                    
                    //echo $cad."</br>";
                    //echo $regy;
                }else {
                    if($fabricante_substr=="Hitro"){
                        $cad=substr($regy,30,3); 
                    }else{ 
                        $cad=substr($regy,33,3);
                    }
                }
                
                if ($cad<>"10." && $cad<>'127' && $cad<>'192' && trim($cad)<>''){
                    $reg=trim(str_replace("= INTEGER: 1","",$cpe[$i]));
                    //echo $reg."</br>";
                }
                
            } 
            //echo "el reg es: ".$reg;
            //dd($reg);
            $oid ='';
            $publica='';
            if($fabricante_substr=="Arris"){
                $oid="iso.3.6.1.2.1.2.2.1.6.10";
                $publica=substr($reg,20,15);//si se utiliza  
            }
            else {
                if($fabricante_substr=="Hitro"){
                        $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,30,14);
                        $publica=substr($reg,30,15);  
                    } elseif(strlen($reg)>10){ 
                        //dd("aquii");
                        $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,33,15); 
                        $oid = str_replace("\"","",$oid); 
                        $publica=substr($reg,33,15); 
                        $publica=str_replace("\"","",$publica);
                    }
                        
            }
             
            // Aqui obtenemos la MAC CPE
            $maccpe=array();
            $snmp='snmpget -c MODEM8K_PILOTO -v2c '.$ipaddress.' '.$oid;
            // dd($snmp);
            exec($snmp,$maccpe);
            //\Log::error(["resultado macx"=>$maccpe,"comando"=>$snmp]);
            // $maccpe[] = "snmpget -c MODEM8K_PILOTO -v2c 10.77.25.62 iso.3.6.1.2.1.4.22.1.2.1.100.105.154.12";
            //echo "\nComando snmp --->".$snmp;
            $macaddress='';
            $cpex=array();
            $regx=array();
            $reg1x=array();
            $reg2x=array();
            $regx = empty($maccpe[0])? '' : $maccpe[0];
            $macx='';
             //dd($regx);
            if($regx != ''){
                $regxx = explode(": ", $regx);
                //dd($regxx);
                //print_r($regxx);
                foreach ($regxx as $fil2x) {
                    if ($fil2x!=''){
                            $reg2x[] = $fil2x;
                    }  
                }
                 //dd($reg2x);
                if (isset($reg2x[1])) {
                    $arregloMacStr = explode(":", $reg2x[1]);
                    for ($i=0; $i < count($arregloMacStr); $i++) {  
                        $macx .= (strlen($arregloMacStr[$i]) < 2 ) ? "0".$arregloMacStr[$i] : $arregloMacStr[$i]; 
                        if ($i+1 < count($arregloMacStr)) $macx .= ":"; 
                    }
                } 
            }else{
                $reg2x[] = "";
            }
            //dd($publica);
            if(trim(substr($publica,1,2))==''){
                $macx='';
                $publica='';
            }
            //dd($publica);
            $mta1=array();
            $macmta="snmpwalk -c MODEM8K_PILOTO -v2c ".$ipaddress." iso.3.6.1.2.1.4.22.1.2.16"; // si se utiliza
            exec($macmta,$macmtax);
            //\Log::error(["resultado macmta"=>$macmtax,"comando"=>$macmta]);
            //$macmtax[]="IP-MIB::ipNetToMediaPhysAddress.16.0.0.0.0 = STRING: 90:d:cb:e0:52:3";

            if(isset($macmtax[0])){
                $mta1=$macmtax[0];
                $mta2 = explode("=", $mta1);
                $ipmta=substr($mta2[0],35,16); //si se utiliza
                //$macmta=str_replace(" ",":",substr($mta2[1],9,17));
                $extraeMac = substr($mta2[1],9,17);
                $arregloMacMta = explode(":",$extraeMac);
                $armandoMacMta = "";
                for ($i=0; $i < count($arregloMacMta); $i++) {  
                    $armandoMacMta .= (strlen($arregloMacMta[$i]) < 2 ) ? "0".$arregloMacMta[$i] : $arregloMacMta[$i]; 
                    if ($i+1 < count($arregloMacMta)) $armandoMacMta .= ":"; 
                }
                $macmta = $armandoMacMta; 
                 
            }else{
                $ipmta='';
                $macmta='';
            } 
            if($docsis=='DOCSIS2'){
                $ipmta='';
                $macmta='';
            }
                $verMacIpPeR["publica"]=$publica;
                $verMacIpPeR["macx"]=$macx;
                $verMacIpPeR["macmta"]=$macmta;
                $verMacIpPeR["ipmta"]=$ipmta;

                return $verMacIpPeR;

        }//fin if
    }

    function ultimoRequerimiento($bus){
        $msj = '';
           
            try { 
              $x = DB::select(
                "select a.codigo_req  as codreq,a.codigo_tipo_req,a.codigo_motivo_req,b.des_motivo,a.fecha_liquidacion
                FROM cms.prov_liq_catv_pais a
                INNER JOIN cms.cms_tiporeq_motivo b
                ON a.codigo_tipo_req=b.tipo_req
                AND a.codigo_motivo_req=b.motivo
                WHERE a.codigo_del_cliente = ? and datediff(now(),fecha_liquidacion)<=7 
                order by a.fecha_liquidacion", [$bus]);

            } catch(QueryException $ex){ 
              //dd($ex->getMessage()); 
              throw new HttpException(422,"Problemas con la red, intente nuevamente.");
              // Note any method of class PDOException can be called on $ex.
            }
         
        $x2 = empty($x[0]->codreq)? 0 : $x[0]->codreq;
        if ($x2 > 0) {
            $msj = "ULT.REQ:" . $x[0]->codreq . " EL DIA :" . $x[0]->fecha_liquidacion . "</br>TPO_REQ:" . $x[0]->codigo_tipo_req . " " . $x[0]->codigo_motivo_req . " : " . $x[0]->des_motivo . "</br>" . "<font color=yellow size=1>Si el cliente reclama por Inst. de deco o Ctrl Rmto - Generar Rutina</font>";
        }
      
        return $msj; 
    }

    function validaObsoleto($marca,$model){

        try { 
            $obso = DB::select(
              "select COUNT(*) AS obsoleto 
                FROM ccm1_data.cm_obsoletos_tabla a 	
                WHERE  REPLACE(a.fabricante,' ','')=REPLACE(?,' ','') 
                AND REPLACE(modelo,' ','')=REPLACE(?,' ','')", [$marca,$model]);

          } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            throw new HttpException(422,"Problemas con la red, intente nuevamente.");
            // Note any method of class PDOException can be called on $ex.
          }

       // $obso="SELECT COUNT(*) AS obsoleto FROM ccm1_data.cm_obsoletos_tabla a 	WHERE  REPLACE(a.fabricante,' ','')=REPLACE('$marca',' ','') AND REPLACE(modelo,' ','')=REPLACE('$model',' ','')";
      //echo $obso;
       
      $obso3 = empty($obso[0]->obsoleto)? 0 : $obso[0]->obsoleto;
 
      $obsoleto =  $obso3>=1  ? 'SI' : 'NO';
       
      return $obsoleto;
    }
    function consultasClienteDia($idclientecrm){
        $cc = DB::select("
                SELECT COUNT(*) as consultas FROM multiconsulta.multi_consultas 
                WHERE DATEDIFF(NOW(),fechahora)=0 AND dato=?", [$idclientecrm]);
        $cc2=empty($cc)? 0 : $cc[0]->consultas;
        return $cc2;
    }

    function validaNegocio($idclientecrm){
        $identifica = DB::select("
                        SELECT a.identifica from cms.planta_clarita a
                        WHERE a.cliente =? and identifica='ZONA14' 
                        GROUP BY a.cliente", [$idclientecrm]);

        $negocio=empty($identifica)? "" : $identifica[0]->identifica;
       
        return $negocio;
    }

    function msjMovistarTotal($idclientecrm){
        $resultMT =  DB::select(" 
                    select 'CLIENTE MOVISTAR TOTAL. Realizar soporte completo. Si no funciona generar averia R427.' AS msjtot  
                    FROM catalogos.movistar_total WHERE clientecms=?", [$idclientecrm]);
        $msj=empty($resultMT)? "" : $resultMT[0]->msjtot;

        return $msj;
        
    }

    function msjOperador($idclientecrm){
        $resultMsj =  DB::select("select mensaje FROM catalogos.analgesico WHERE ClienteCms=?", [$idclientecrm]);
        $msj=empty($resultMsj)? "" : $resultMsj[0]->mensaje;

        return $msj;
        
    }

    function validaSegmento($nodo,$troba){
        $resultSegmento = DB::select("
                            select segmento 
                            FROM catalogos.segmentos_view 
                            WHERE nodo=? AND troba=?", [$nodo,$troba]);
        
        $segmento=empty($resultSegmento)? "" : $resultSegmento[0]->segmento;
        
        return trim($segmento); 
    }

    function validaExperto($nodo,$troba){

        $result = "";

        if($nodo<>'' && $troba<>''){

            $nt=$nodo.$troba;
            $resultEx = DB::select("
                                        select count(*) as cant 
                                        from catalogos.expertowifi where  
                                        nodotroba=?", [$nt]);
        
            $resultCant=empty($resultEx)? 0 : $resultEx[0]->cant;
    
            $result= $resultCant>=1 ? 'SI' : 'NO';
       
        }

        return $result;
    }

    function validaEnergia($nodo,$troba,$niveles,$macstate)
    {
        $resultEnergia = "";
        $fec2 = DB::select("
                            select fecha_hora FROM alertasx.`caidas_new` 
                            WHERE estado='CONTINUA' AND nodo=? AND 
                            troba=? limit 1", [$nodo,$troba]);
        
        $fecha=empty($fec2)? "" : $fec2[0]->fecha_hora;

        
	
	    if($fecha<>''){
            $en1= DB::select("
                            select 
                            IF(estado='Asignado: Energia','Energia','Luz Domiciliaria') as  energia
                            FROM alertasx.gestion_alert WHERE 
                            nodo=? AND troba=? AND fechahora>?  
                            AND estado IN ('Asignado: Energia','Cayo Luz Domiciliaria') 
                            LIMIT 1", [$nodo,$troba,$fecha]);

            $energia=empty($en1)? "" : $en1[0]->energia;

            if ($energia='Energia'){
                $resultEnergia="Troba con problemas de Energia - Generar Averia R417";
            }
            if($energia='Luz Domiciliaria'){
                $resultEnergia="Caida de Luz Domiciliaria en la zona -No generar Averia";
            }
             
        } 
        
        if($niveles=='ok' && $macstate<>'offline') $resultEnergia=''; 
        
	    return $resultEnergia;

    }

    function validaPlazoAtencion($nodo,$troba){
        $ms=DB::select("
                        select mensaje from 
                        catalogos.pendientes_nodo_troba where 
                        nodo=? and troba=?", [$nodo,$troba]);
        
        $plazoatencion=empty($ms)? "" : $ms[0]->mensaje;
       
        return $plazoatencion;
    }

    function validaPartial($mac2){
        $valpar = DB::select("
                        select bonding from 
                        ccm1.partial_service 
                        where macaddress=? ",[$mac2]);
        //echo $valpar;
        $bonding=empty($valpar)? "" : $valpar[0]->bonding;
         
        return $bonding;
    }

    function validaIntraway(){
        $valintra = DB::select("
                        select count(*) as init from ccm1.scm_total where macstate 
                        in ('init(d)','init(i)','init(o)','init(io)','init(t)','init(dr)')");
        
        $cantIntra=empty($valintra)? "" : $valintra[0]->init;
         
        return $cantIntra;

    }

    function procesarClientPlantaClarita($detallePlantaCl)
    {
       
        $detallePlantaCl[0]->titulo = "";
        $detallePlantaCl[0]->mensajeMasiva = "";
        $detallePlantaCl[0]->mensajeNumeroMasiva = "";
        $detallePlantaCl[0]->imgArbol = "img_masivo";
        $detallePlantaCl[0]->msjSegmento = "";

        if ($detallePlantaCl[0]->ofi_cli != "") {
            if($detallePlantaCl[0]->mtot =="Movistar Total"){
                if ($detallePlantaCl[0]->tiptec  == "GPON") $detallePlantaCl[0]->titulo = "GPON - Movistar Total";
            }else{
                if ($detallePlantaCl[0]->tiptec  == "GPON") $detallePlantaCl[0]->titulo = "GPON";
            }
        }

        $validacionDigital = $this->validaDigitalizacion($detallePlantaCl[0]->NODO,$detallePlantaCl[0]->plano);//Valida digitalización
        $detallePlantaCl[0]->mensajeDigital = $validacionDigital;

        $detallePlantaCl[0]->backgroundDigi = 'white';
        $detallePlantaCl[0]->colorDigi = 'black';

        if (substr($detallePlantaCl[0]->mensajeDigital,0,22) == 'DIGITALIZACION ANTIGUA') {
            $detallePlantaCl[0]->backgroundDigi = 'white';
           $detallePlantaCl[0]->colorDigi = 'orange';
        }
        if (substr($detallePlantaCl[0]->mensajeDigital,0,20) == 'DIGITALIZACION NUEVA') {
            $detallePlantaCl[0]->backgroundDigi = 'white';
           $detallePlantaCl[0]->colorDigi = 'red';
            $detallePlantaCl[0]->mensajeDigital=$detallePlantaCl[0]->mensajeDigital." - (No Generar Averia)";
        }
        if ($detallePlantaCl[0]->mensajeDigital == '') {
            $detallePlantaCl[0]->backgroundDigi = 'white';
            $detallePlantaCl[0]->colorDigi = 'green';
        }

  
        if ($detallePlantaCl[0]->masiva == 'Masiva M1' or $detallePlantaCl[0]->masiva == 'TRABAJO PROGRAMADO') { 
            $detallePlantaCl[0]->mensajeMasiva = "Problemas con el servicio de TV </br> Averia #: " . $detallePlantaCl[0]->masiva;
             
        }else 	{
            if ($detallePlantaCl[0]->masiva != '') { 
                $detallePlantaCl[0]->mensajeMasiva = "Problemas en el Servicio de TV </br>Averia #: " . $detallePlantaCl[0]->masiva;
                }
        }

        if ($detallePlantaCl[0]->num_masiva > 0) { 
            $detallePlantaCl[0]->mensajeNumeroMasiva = "Problemas en el servicio de TV Averia #: " . $detallePlantaCl[0]->num_masiva; 
        }
        else {
            if ($detallePlantaCl[0]->masivacatv != 'NO') {
                if ($detallePlantaCl[0]->masiva <> '') {
                    $detallePlantaCl[0]->mensajeNumeroMasiva = $detallePlantaCl[0]->masivacatv. "Tiempo de Incidencia:". $detallePlantaCl[0]->tiempo_masiva; 
                }
            }
            
        }

         
	    $segmento = $this->validaSegmento($detallePlantaCl[0]->NODO,$detallePlantaCl[0]->plano);

        if($segmento == "PREMIUM Fase_1" || $segmento=="PREMIUM Fase_2") $detallePlantaCl[0]->imgArbol='img_total';
        
         
        $detallePlantaCl[0]->msjSegmento = $segmento;
 
        return $detallePlantaCl;

    }
 
 

    function procesarMulticonsulta($recordP,$fech_hor,$ParametrosColoresGeneral){
  
        
        //PRIMERAS DECLARACIONES
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $horario = date("h:j", strtotime($fech_hor));
 
       
        $recordP[0]->rol = $rolNombre; 
        $recordP[0]->bondingCli=""; 
        $recordP[0]->cambiarModem=""; 
        $recordP[0]->msjNegocio = ""; 
        $recordP[0]->msjSegmento = "";  
        $recordP[0]->msjPlazoAtencion = ""; 
        $recordP[0]->imgArbol = "img_masivo"; 
        $recordP[0]->playa = ""; 
        $recordP[0]->corte = trim($recordP[0]->corte); 
        $recordP[0]->esMasiva = 0;  
        $recordP[0]->resultadoAlerta="";  
        $recordP[0]->mensajeGeneral = '';  
        $recordP[0]->mensajeMasiva = '';  
        $recordP[0]->esTrabProg = 0;  
        $recordP[0]->cantidadConsultas = 0;
        $recordP[0]->verAgenda = 0;

        $recordP[0]->ipcm = $recordP[0]->IPAddress;
        $obsoleto=$this->validaObsoleto($recordP[0]->Fabricante,$recordP[0]->Modelo);
        $recordP[0]->obsoleto = $obsoleto;
        
        if($recordP[0]->ipcm=='') $recordP[0]->ipcm = $recordP[0]->IPCM;
        if($recordP[0]->IPAddress=='') $recordP[0]->IPAddress = $recordP[0]->ipcm;
        $suspendido = "";
          
        
        if ($recordP[0]->corte == 'Cortado') {
          $recordP[0]->esMasiva = 1; 
        }

        $recordP[0]->bondingCli = $this->validaPartial($recordP[0]->mac2);

        #VALIDA MENSAJE MOVISTAR TOTAL
        $recordP[0]->msjMovistarTotal = $this->msjMovistarTotal($recordP[0]->IDCLIENTECRM);

        #VALIDA MENSAJE OPERADOR
        $recordP[0]->msjOperador = $this->msjOperador($recordP[0]->IDCLIENTECRM);
        

        if ($recordP[0]->msjMovistarTotal <>'') $recordP[0]->imgArbol='img_total'; $recordP[0]->verAgenda = 1;

        #VALIDA NEGOCIO - PLANTA CLARITA
        $negocio=$this->validaNegocio($recordP[0]->IDCLIENTECRM);
       
        #VALIDA SEGMENTO - CATALOGOS VIEW SEGMENTOS
        $segmento = $this->validaSegmento($recordP[0]->NODO,$recordP[0]->TROBA);

        if($segmento == "PREMIUM Fase_1" || $segmento=="PREMIUM Fase_2") $recordP[0]->imgArbol='img_total';
      
        
        if($negocio=="ZONA14"){
            $recordP[0]->imgArbol ="img_negocios";
            $recordP[0]->msjNegocio = "NEGOCIO";
        } 
        $recordP[0]->msjSegmento = ($negocio<>'') ? "" : $segmento;
         
        #DOCSIS
        
            if($recordP[0]->docsis=='DOCSIS2'){
                $recordP[0]->cambiarModem='</br><font size=2>Se recomienda Cambio de Modem a Docsis3</font>';
            }
            
        #END DOCSIS

        #COLORES VOIP
            $recordP[0]->voipBackground = $ParametrosColoresGeneral->Voip->colores[0]->background;
            $recordP[0]->voipColor = $ParametrosColoresGeneral->Voip->colores[0]->color;
        #END COLORES
       
        #COLORES MENSAJE OPERADOR
            $recordP[0]->msjOperadorBackground = $ParametrosColoresGeneral->mensajeOperador->colores[0]->background;
            $recordP[0]->msjOperadorColor = $ParametrosColoresGeneral->mensajeOperador->colores[0]->color;
        #END COLORES
         

        #COLORES AVERIAS
            $recordP[0]->averiasBackground =  $ParametrosColoresGeneral->Averias_ploblemas->colores[0]->background;
            $recordP[0]->averiasColor = $ParametrosColoresGeneral->Averias_ploblemas->colores[0]->color;
        #END COLORES AVERIAS
        
    
 
         //Valida Servicio si esta en corte, caida o averia  
         $resultado = $this->validarServicio($recordP[0]->NODO,$recordP[0]->TROBA,$rolNombre,$recordP[0]->corte,
                                            $recordP[0]->trab,$recordP[0]->TIPODETRABAJO,$recordP[0]->num_masiva,
                                            $recordP[0]->tiempo_masiva,$recordP[0]->cliente_alerta);
         
         
       if($resultado<>''){
          $recordP[0]->esMasiva = 1; 
          $recordP[0]->mensajeGeneral=$resultado;  //mensaje obtiene el resultado de servicio 
          if($resultado=='Cliente con corte de servicio ejecutado en Intraway M1') $suspendido = $resultado;
        }
        
         
        if($resultado=='' && $suspendido==''){  
          $hoy=date("Y-m-d H:i:s");
          $cmts_puerto=$recordP[0]->cmts.'-'.$recordP[0]->interface;
          
          $resultado=$this->validaMsjPer($recordP[0]->cmts,$cmts_puerto,$recordP[0]->NODO,$recordP[0]->TROBA,$hoy);
         
          if($resultado<>''){
            $recordP[0]->esMasiva = 1; 
            $recordP[0]->mensajeGeneral=$resultado;   
          }
        }

        // Cuenta las consultas del dia para el cliemte
        $recordP[0]->cantidadConsultas = $this->consultasClienteDia($recordP[0]->IDCLIENTECRM);
         
        

        $validacionDigital = $this->validaDigitalizacion($recordP[0]->NODO,$recordP[0]->TROBA);//Valida digitalización
        $recordP[0]->mensajeDigital = $validacionDigital; //verificar si se usa

        //Valida Trabajo Programado
        $esTrabProg=$this->validaTrabProg($recordP[0]->NODO,$recordP[0]->TROBA,$recordP[0]->IDCLIENTECRM,
                                        $recordP[0]->mensajeGeneral,$fech_hor,$rolNombre,$recordP[0]->corte,$recordP[0]->trab);


        if ($validacionDigital<>''){
            $recordP[0]->mensajeGeneral = ""; 
            if ($esTrabProg==0) {
                $recordP[0]->esTrabProg = 1; 
            }
        }  
 
        if((int)$recordP[0]->num_masiva > 0) $recordP[0]->mensajeMasiva = "Averia Num:".$recordP[0]->num_masiva;
 
        //Para obtener phy desde el cmts;
        //SEGUNDAS  DECLARACIONES
        $npwr_up =  (double)$recordP[0]->USPwr;
        $nsnr_up =  (double)$recordP[0]->USMER_SNR;
        $npwr_dn =  (double)$recordP[0]->DSPwr;
        $nsnr_dn =  (double)$recordP[0]->DSMER_SNR;
        $tipoprob = '';
        $niveles = 'ok';
 
        if ($recordP[0]->MACState == 'offline' || $recordP[0]->MACState == '') {
          $npwr_dn = 0;
          $nsnr_dn = 0;
        }

        #TRABAJANDO CON DOWNSTREAM y UPSTREAM 

            //CABLE MODEM -- Lectura de niveles de señal//
            //Si el Down Power y SNR estan vacios
 
            if ($npwr_dn + $nsnr_dn == 0 && $recordP[0]->MACState == 'online' && $recordP[0]->estadoserv=='Activo') {

                //Valido Espacios vacios
                if ($recordP[0]->IDCLIENTECRM <> "" && $recordP[0]->IPAddress <>"" && $recordP[0]->Fabricante <> "" && $recordP[0]->Modelo <> "") {
                    $statusCablemodem = new CablemodemStatusFunctions;  
                    $arrMedicionesStatus = $statusCablemodem->statusPrincipal($recordP[0]->IDCLIENTECRM,$recordP[0]->IPAddress,$recordP[0]->Fabricante,$recordP[0]->Modelo);
                    //dd((double)$arrMedicionesStatus["Downstream"][0]["Power"]);
                   // dd($arrMedicionesStatus["Downstream"][0]["Power"]);
                   if ($arrMedicionesStatus != "Error" && $arrMedicionesStatus != "Error Codigo") {
                       if(count($arrMedicionesStatus["Downstream"]) > 0)  $npwr_dn = (double)$arrMedicionesStatus["Downstream"][0]["Power"];
                       if(count($arrMedicionesStatus["Downstream"]) > 0) $nsnr_dn = (double)$arrMedicionesStatus["Downstream"][0]["SNR"];
                       if(count($arrMedicionesStatus["Upstream"]) > 0)  $npwr_up = (double)$arrMedicionesStatus["Upstream"][0]["Power"]; 
                   }
                }
  
            }
        #END MEJORAS
          
        $downPx = (double)$npwr_dn;
        $upPx = (double)$npwr_up;
        $downSnr = (double)$nsnr_dn;
        $upSnr = (double)$nsnr_up;

        $recordP[0]->nivelesRuido = array(
            "downPx"=>$downPx,
            "upPx"=>$upPx,
            "downSnr"=>$downSnr,
            "upSnr"=>$upSnr
        );

        #Implementar un administrador de colores según los niveles de ruido (MEJORAS)

            $parametrosRF = new Parametrosrf;  
 
            $paramMulti_detalle = $parametrosRF->getMulticonsultaNivelesRF();
            $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramMulti_detalle);
              
            $recordP[0]->coloresNivelesRuido = Parametrosrf::getColoresNivelesRF((double)$downSnr,(double)$downPx,
            (double)$upSnr,(double)$upPx,$dataParametrosRF);

        #End implementación (MEJORAS)

        $errorMta=$this->validaMTA($recordP[0]->mtamac,$recordP[0]->telefonohfc);
        
        $tipoprob=$this->validaNiveles($downPx,$upSnr,$downSnr,$upPx,$recordP[0]->cliente_alerta,
                                        $recordP[0]->NODO,$recordP[0]->TROBA,$recordP[0]->MACState,$recordP[0]->num_masiva);
       
        if($suspendido<>'') $tipoprob=$suspendido;

        if($resultado<>'' && $suspendido=='' && $recordP[0]->MACState<>'') {
          $tipoprob='';
        }else{
          $resultado=$tipoprob;
        }
         
       
        if($tipoprob<>''){
	
          $niveles='Malos';
          $recordP[0]->mensajeGeneral = $tipoprob;
       
        }
 
        $publica="";
        $macx="";
        $macmta="";
        $ipmta="";
        $msj='';
        $mostrar = '.';
        $playa = '';
  
        if ($recordP[0]->MACState == 'online' && strlen(trim($resultado))== 0 && strlen(trim($tipoprob))==0) {
            $fabricante=$recordP[0]->Fabricante; 
            
            $mac_ip_cpe = $this->verMacIpPe($fabricante, $recordP[0]->IPAddress,$recordP[0]->docsis);
            $publica= $mac_ip_cpe["publica"];
            $macx= $mac_ip_cpe["macx"];
            $macmta= $mac_ip_cpe["macmta"];
            $ipmta= $mac_ip_cpe["ipmta"]; 
        } else {
            if($recordP[0]->MACState <> 'online'){
                $publica='no'; 
            } else {
              $fabricante=$recordP[0]->Fabricante;  
                $mac_ip_cpe = $this->verMacIpPe($fabricante, $recordP[0]->IPAddress,$recordP[0]->docsis);
                $publica= $mac_ip_cpe["publica"];
                $macx= $mac_ip_cpe["macx"];
                $macmta= $mac_ip_cpe["macmta"];
                $ipmta= $mac_ip_cpe["ipmta"];
            }
        }
         //dd($publica);
        #COLORES MACSTATE  
            $arrayMacStateColors = ParametroColores::getColorMacstate($recordP[0]->MACState,$ParametrosColoresGeneral->MacState); 
            $recordP[0]->MacStateBackground = $arrayMacStateColors["background"]; 
            $recordP[0]->MacStateColor= $arrayMacStateColors["color"]; 
 
        #END COLORES MACSTATE 

        #COLORES SCOPEGROUOP
            $recordP[0]->scopesgroupBackground = $ParametrosColoresGeneral->ScopesGroup->colores[0]->background; 
            $recordP[0]->scopesgroupColor = $ParametrosColoresGeneral->ScopesGroup->colores[0]->color;  
        #ENDCOLORES SCOPEGROUP
  
        // Fin de ips

        if ($resultado<>'' && $tipoprob<>'' && 
            (($downSnr == '-' || $downSnr == '-----') || ($upSnr + $downSnr + $downPx + $downPx + $upPx + $upPx * 1 == 0)) && 
            trim($recordP[0]->interface) <> '' && $tipoprob == '') {
            $tipoprob = "Sop||te / Back Office";
        }

        if ($resultado<>'' && $tipoprob<>'' && 
            (($downSnr == '-' || $downSnr == '-----') || ($downSnr + $downPx + $upPx == 0)) &&
            trim($recordP[0]->interface) == '' && $tipoprob == '') {
            $tipoprob = "Soporte / Back Office"; 
            $niveles = 'Malos';
        }

        if ($niveles == "ok" && $tipoprob <> '') {
            $tipoprob == '';
        }
          
        ##Obtiene el ultimo requerimiento atendido al cliente
           
        if ($niveles == "ok" && $tipoprob == '') { 
            $msj=$this->ultimoRequerimiento($recordP[0]->IDCLIENTECRM);
              
        } 
 
        //Guardamos Resultado como alerta
        $recordP[0]->resultadoAlerta=$resultado;
         

        //INTERFACES
                
        if ($recordP[0]->cmts == 'HIGUERETA3' && (substr($recordP[0]->interface, 0, 6) == 'C5/0/0' || substr($recordP[0]->interface, 0, 6) == 'C5/0/1' || substr($recordP[0]->interface, 0, 6) == 'C5/0/2')) {
            $recordP[0]->playa = ' PLAYAS';
        }

        #MENSAJE PROBLEMAS SOPORTE CLIENTE TABLA MULTICONSULTA 
            $mensajeProblemas = "";
            $recordP[0]->otrasAverias = "";
            $saturado = '';
            //Temporal = 
          
            if ( $horario >= '08:00' && $horario <= '23:59') {
                $saturado = $recordP[0]->saturado;
            } 

            
            
            if ($publica <>'no' &&  (strlen(trim($publica))< 10 && (int)$recordP[0]->NumCPE == 0 ) && ($recordP[0]->MACState == "online") &&
                trim($macx)=='' && (int)$recordP[0]->num_masiva== 0   && $tipoprob <> 'Probable problema de Pext / Back Office')
            { 
                if($recordP[0]->mensajeGeneral == "") $recordP[0]->mensajeGeneral = "Cable Modem sin IP Publica";
                 
                $tipoprob = $recordP[0]->mensajeGeneral; 
            }
            
             
            $expertowifi=$this->validaExperto($recordP[0]->NODO,$recordP[0]->TROBA);
             
            
           
            if($recordP[0]->msjMovistarTotal<>'' ||  $expertowifi=='SI'){
                if($expertowifi=='SI' && $recordP[0]->msjMovistarTotal<>''){
                $tipoprob=$recordP[0]->msjMovistarTotal;
                } 
                if($expertowifi=='NO'  && $recordP[0]->msjMovistarTotal<>''){
                $tipoprob=$recordP[0]->msjMovistarTotal;
                }
                if($expertowifi=='SI' && $recordP[0]->msjMovistarTotal==''){
                $tipoprob="<font size=2>".$tipoprob.'</br>ZONA EXPERTO WIFI - TRANSFERIR A DIGITEX</font>';
                } 
            }
           
            
            if ((int)$recordP[0]->num_masiva < 100 && $tipoprob <> '') { 
                    $mensajeProblemas = $tipoprob; 
            }
            else { 
                $mensajeProblemas = $tipoprob.' '.$errorMta;
            }

           
             
            if($recordP[0]->cliente_alerta=="Caida Amplif" || $recordP[0]->cliente_alerta=='Caida'){
                $esenergia=$this->validaEnergia($recordP[0]->NODO,$recordP[0]->TROBA,$niveles,$recordP[0]->MACState);
                if($esenergia<>''){
                    $mensajeProblemas=$esenergia;
                }
            }

           
            
            if ($mensajeProblemas <> "" && $recordP[0]->corte <> 'Cortado') {
               
                if ((int)$recordP[0]->num_masiva > 0 && ($recordP[0]->cliente_alerta == 'Caida' || $recordP[0]->cliente_alerta == 'Señal RF' || $recordP[0]->cliente_alerta == 'Caida Amplif' || $recordP[0]->cliente_alerta== 'Señal RF Amplif') && $recordP[0]->corte <> 'Cortado') {
                    $recordP[0]->otrasAverias = $saturado . $mensajeProblemas; 
                }
                else{ 
                    if ($recordP[0]->corte <> 'Cortado') {
                        $recordP[0]->otrasAverias = $saturado . $mensajeProblemas; 
                    }
                } 
            }
            else {
                if ($recordP[0]->corte <> 'Cortado') {
                    $recordP[0]->otrasAverias = $saturado . $mensajeProblemas;  
                }
            }

           
           

            if($saturado<>''){
                $recordP[0]->mensajeGeneral=$recordP[0]->mensajeGeneral."</br>".$saturado." ".$mensajeProblemas;
            }

        #END MENSAJE SOPORTE PROBLEMAS CLIENTE

        
        #PLAZO ATENCION
        
            $recordP[0]->msjPlazoAtencion = $this->validaPlazoAtencion($recordP[0]->NODO,$recordP[0]->TROBA);
            #COLORES PLAZO ATENCIÓN 
            $recordP[0]->plazoAtencionBackground = $ParametrosColoresGeneral->PlazoAtencion->colores[0]->background;
            $recordP[0]->plazoAtencionColor = $ParametrosColoresGeneral->PlazoAtencion->colores[0]->color;
            
        #END PLAZO ATENCION

        #TIPO DE GRAFICO
        $recordP[0]->tipoGrafico = "";

        if($recordP[0]->cliente_alerta == "Caida" or strlen($recordP[0]->cliente_alerta)==9){
            $recordP[0]->tipoGrafico = "Troba";
        }

        if($recordP[0]->cliente_alerta == "Caida Amplif" or strlen($recordP[0]->cliente_alerta)==16){
            $recordP[0]->tipoGrafico = "Amplificador";
        }

        if($recordP[0]->mensajeGeneral == "Probable averia en:Red Cliente"){
            $recordP[0]->tipoGrafico = "Cliente";
        }elseif ($recordP[0]->mensajeGeneral == "Probable problema de Pext / Back Office") {
            $recordP[0]->tipoGrafico = "Troba";
        }elseif ($recordP[0]->mensajeGeneral == "Probable problema de Pext - Amplif") {
            $recordP[0]->tipoGrafico = "Amplificador";
        }

        if ($recordP[0]->tipoGrafico == "") {
            $recordP[0]->tipoGrafico = "OK";
        }

        
        $valorIntraway = $this->validaIntraway();

        if($valorIntraway > 300 && ($recordP[0]->MACState == 'init(d)' || 
            $recordP[0]->MACState == 'init(i)' || $recordP[0]->MACState == 'init(o)' || 
            $recordP[0]->MACState == 'init(io)' || $recordP[0]->MACState == 'init(t)' || 
            $recordP[0]->MACState == 'init(dr)')){
                $recordP[0]->tipoGrafico = "Intraway";
            }

        if ($recordP[0]->tipoGrafico == "" && ($recordP[0]->MACState == 'online' || $recordP[0]->MACState == 'w-online' || 
            $recordP[0]->MACState == 'w-online(pt)' || $recordP[0]->MACState == 'p-online' || 
            $recordP[0]->MACState == 'online(pt)' || $recordP[0]->MACState == 'online(d)' || 
            $recordP[0]->MACState == 'wonline')) {
                $recordP[0]->tipoGrafico = "OK";
            
        }
        #END TIPO DE GRAFICO

        $recordP[0]->publica=$publica;
        $recordP[0]->macmta=$macmta;
        $recordP[0]->ipmta=$ipmta;
        $recordP[0]->macx=$macx;
        

         //dd($recordP[0]);

        
        

        return $recordP;
 

    }

    function CableModemsDecosForResetOne($codCliente)
    {
        try {
            $decos = DB::select(
                "select CODOFICADM,CODSRV,CASID,SERIE,SERIETARJ,EDOCOMPXSR,SECUENCIA,IDPRODUCTO,
                codmat,serie as numser,'A' as tipo 
                ,if(tipoadqui='A','Alquiler',if(tipoadqui='V','Venta','Comodato')) as tipoadqui
                FROM catalogos.cablemodem_glciexp054_decos
                WHERE CODELEMSRV='DED' 
                AND  CODCLIENTE = ?
                AND EDOCOMPXSR='A' 
                GROUP BY SERIE
                ORDER BY SECUENCIA",[$codCliente]);

        } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            // Note any method of class PDOException can be called on $ex.
        }
       
 
        return $decos;
         
    }

    function historicoRefresh($numser)
    {
        $historico = DB::select(
            "select count(*) as es 
            from ccm1.historico_refresh 
            where numser=? and 
            timediff(now(),fecha_hora)<='00:04:59'",[$numser]);
        return $historico;
    }

    function insertHistoricoRefresh($idcliente,$codsrv,$numser,$codmat,$usuario)
    {
        $fech_hor = date("Y-m-d H:i:s");
       // dd($idcliente."-".$codsrv."-".$numser."-".$codmat."-".$usuario."-".$fech_hor);
          
        DB::insert(
                "insert into ccm1.historico_refresh (codcli,codsrv,numser,codmat,usuario,fecha_hora)
                 values (?,?,?,?,?,?)", [$idcliente,$codsrv,$numser,$codmat,$usuario,$fech_hor]);
        
 
 
    }

    function CableModemsDecosForResetAll($codsrv,$idcliente)
    {
        $varios = DB::select(
            "select a.serie AS numser,codmat,'A'
            FROM catalogos.`cablemodem_glciexp054_decos` a 
            WHERE a.codsrv=? AND a.codcliente=? 
            AND a.codelemsrv='DED' AND a.desccondcl='ACTIVO' 
            AND a.descconsrv='ACTIVO' AND a.edocompxsr='A'",[$codsrv,$idcliente]);
        return $varios;
         
    }

    function showVelocidadesDisponibles()
    {
        $velocidades= DB::select(
            "select velocidad_final AS vf  
            FROM catalogos.velocidades_m  
            ORDER BY velocidad_final*1");
        
        return $velocidades;
    }

    function replaceNuevaVelocidadBD($fini,$dias,$nvel,$mac,$idusuario)
    {
        //Nvelocidad
        DB::statement(
            "replace  
            catalogos.`excepciones` 
            select *,'$fini' AS fecha_inic,
            ADDDATE('$fini', INTERVAL $dias DAY) AS fecha_fin,'N' as devuelto,
            (
                select servicepackagecrmid 
                from catalogos.velocidades_m 
                where velocidad_final='$nvel'
            ) as nvel,'' as subido ,'$idusuario',now()
            FROM multiconsulta.nclientes 
            WHERE macaddress='$mac'"
        );
  	
    }

    function getUltimoRegistroLogByMac($mac){

        $dataLogCliente = DB::select("select * FROM zz_auditoria.`log_cm_velocidades`
                                        WHERE macAddress=?
                                        ORDER BY fechaAccion DESC
                                        LIMIT 1",[$mac]);
        return $dataLogCliente;
    }

    function getDataClientForActivate($mac)
    {
  
        try {
            $data_client = DB::select(
                "select a.idclientecrm, IF(a.idservicio=1,a.idventa,a.idproducto) AS idproducto, a.idservicio, 
                a.SERVICEPACKAGECRMID as velocidad,
                a.SCOPESGROUP, IF(a.idserviciomta<>'','MTA',' ') AS idserviciomta, a.fecha_upload  as fecha_upload  FROM multiconsulta.nclientes a 
                WHERE a.macaddress=? GROUP BY a.macaddress",[$mac]);
           
        } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            throw new HttpException(409,"No se encontrarón datos del cliente. Se están generando los datos en estos momentos. intente dentro de otro momento.");
           // Note any method of class PDOException can be called on $ex.
        }

        if (count($data_client) == 0) {
            throw new HttpException(409,"No se encontrarón datos del cliente. Se están generando los datos en estos momentos. intente dentro de otro momento.");
        }
         
       return $data_client[0];
    }


    function updateNuevaVelocidadBD($mac,$nvel)
    {
       
        try {

             $update_exception = DB::update(
                "update 
                catalogos.`excepciones` 
                set subido='S' 
                WHERE macaddress=?",[$mac]);

            if($update_exception){
                $data_client = $this->getDataClientForActivate($mac);

                $velocidades = DB::select(
                                        "select servicepackagecrmid,servicepackage
                                        from catalogos.velocidades_m 
                                        where velocidad_final=?",[$nvel]
                                    );
       
                  
           }

        } catch(QueryException $ex){ 
             //dd($ex->getMessage()); 
            throw new HttpException(409,"No se encontrarón datos del cliente. Se están generando los datos en estos momentos. intente dentro de otro momento.");
            // Note any method of class PDOException can be called on $ex.
        }

        if (count($velocidades) == 0) {
            throw new HttpException(409,"No se encontrarón datos del cliente, para el cambio de velocidad.");
        }

        return array(
            "dataCliente"=>$data_client,
            "velocidades"=>$velocidades[0]
        );
         
        
    }

    function UpdateNclienteVelocidad($servicepackagecrmid,$servicepackage,$mac)
    {
       $update_cliente = DB::update(
                        "update multiconsulta.nclientes 
                        set 
                        servicepackagecrmid=?,
                        servicepackage=? 
                        where macaddress=? ",[$servicepackagecrmid,$servicepackage,$mac]);

    }

    function insertHistoricoVelocidad($mac,$servicepackagecrmid,$servicepackage,$idusuario)
    {
        $update_historico= DB::insert(
            "insert into ccm1.historico_ccm1  
              values (?,?,?,?,now())",
               [$mac,$servicepackagecrmid,$servicepackage,$idusuario]);  
    }
  
    function velocidadCMRetorno()
    {
 
        $getClientVelocidad = DB::select(
                            "select  a.idclientecrm,a.idproducto AS idProdVenta, b.idservicio, b.servicepackagecrmid,lg.`velocidad` AS velocidadInicial, lg.`fechaAccion`,
                            b.scopesgroup,IF(b.idserviciomta<>'','MTA',' ') AS mta
                            FROM catalogos.`excepciones` a  INNER JOIN  multiconsulta.`nclientes` b ON a.`idproducto`=b.`idproducto` 
                            INNER JOIN  (
                                select macAddress,velocidad,fechaAccion FROM `zz_auditoria`.`log_cm_velocidades`
                                ORDER BY fechaAccion ASC LIMIT 1
                            ) AS lg ON b.`MACADDRESS` = lg.`macAddress`
                            WHERE a.fecha_fin<NOW() AND a.nvel=b.`SERVICEPACKAGECRMID` AND a.idproducto>0  AND a.devuelto='N' 
                            UNION
                            select a.idclientecrm,a.idventa AS idProdVenta, b.idservicio,b.servicepackagecrmid,lg.`velocidad` AS velocidadInicial, lg.`fechaAccion`,
                            b.scopesgroup,IF(b.idserviciomta<>'','MTA',' ') AS mta 
                            FROM catalogos.`excepciones` a  INNER JOIN  multiconsulta.`nclientes` b ON a.`idventa`=b.`idventa` AND devuelto='N'
                            INNER JOIN  (
                                select macAddress,velocidad,fechaAccion FROM `zz_auditoria`.`log_cm_velocidades`
                                ORDER BY fechaAccion ASC LIMIT 1
                            ) AS lg ON b.`MACADDRESS` = lg.`macAddress`
                            WHERE fecha_fin<NOW() AND a.nvel=b.`SERVICEPACKAGECRMID` AND a.idventa>0 AND devuelto='N'"
        );

        return $getClientVelocidad;

    }
 

    function UpdateNclienteStatus($estado,$mac)
    {
        DB::update(
            "update multiconsulta.nclientes set fecha_upload=now(), estado=? where macaddress=?",[$estado,$mac]
        );
    }

    function UpdateNclienteScopeGroup($mac)
    {
        DB::update(
            "update multiconsulta.nclientes 
            set fecha_upload=now(),
            scopesgroup=IF(scopesgroup='CPE','CPE-CGNAT','CPE') 
            where macaddress=?",[$mac]
        );
    }

    function registerClienteCgnatToCpe($idclientecrm,$idusuario,$motivo)
    {
        DB::insert("insert IGNORE multiconsulta.cgnat_a_cpe 
                    VALUES (?,now(),?,?)",[$idclientecrm,$idusuario,$motivo]
                );
    }
 
    function getDataHistoricoNivelesTrobas($puertoCmts)
    {
        try {
            $queryHis = DB::select("SELECT 
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
                        FROM ccm1.scm_phy_hist a
                        WHERE CONCAT(a.cmts,a.interface)=TRIM('$puertoCmts') order by a.fecha_hora desc");
                        
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }
    function getDataHistoricoCaidasTrobas($puertoCmts)
    {
        try {
            $queryHis = DB::select("SELECT 
                        cmts, interface, description, `cm_tot`, `cm_offline`,cm_tot-cm_offline as oper, (cm_offline/cm_tot)*100 AS cmporc, 
                        fecha_hora, fecha_hora_f, TIMEDIFF(fecha_hora_f,fecha_hora) AS tiempo_durac, 
                        IF(TIMEDIFF(NOW(),fecha_hora_f)<='00:03','SI','NO') AS vigencia FROM ccm1.`scm_sum_alerta` 
                        WHERE CONCAT(cmts,interface)='$puertoCmts' and datediff(now(),fecha_hora)<=15 ORDER BY fecha_hora_f DESC LIMIT 30");
                       //  
                       //WHERE CONCAT(cmts,interface)='$puertoCmts' ORDER BY fecha_hora_f DESC LIMIT 30");
                        
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        } 

        return $queryHis; 
         
    }

    function getDetalleTelefonosCatalodoByCliente($codcli){
        $datosTelfMulti =  DB::select("select * from catalogos.telef_multi where codcli=".$codcli);

        return $datosTelfMulti;
    }


    function registroCatalogosTelefonos($data){

       // dd($data);
        try {
            
            $codcli=$data["idCliente"];
            /*$datosTelfMulti =  DB::select("select * from catalogos.telef_multi where codcli=".$codcli);

            if (count($datosTelfMulti) > 0) {
                if(isset($data["telefono1"]) && strlen($data["telefono1"]) > 6 ){
                    $telef1 = $data["telefono1"]; 
                }else{
                  $telef1 = $datosTelfMulti[0]->telef1;
                  $mensajeInsert .= " Telefono1 ";
                }
               if(isset($data["telefono2"]) && strlen($data["telefono2"]) > 6 ){
                   $telef2 = $data["telefono2"]; 
               }else{
                  $telef2 = $datosTelfMulti[0]->telef2;
                  $mensajeInsert .= " Telefono2 ";
               }
               if($data["telefono3"] && strlen($data["telefono3"]) > 6 ){
                   $telef3 = $data["telefono3"]; 
               }else{
                  $telef3 = $datosTelfMulti[0]->telef3;
                  $mensajeInsert .= " Telefono3 ";
               }
            }*/
            $telef1=(int)$data["telefono1"];
            $telef2=(int)$data["telefono2"];
            $telef3=(int)$data["telefono3"];
 
            DB::statement("replace 
                            catalogos.telef_multi set 
                            codcli=".$codcli.",telef1=".$telef1.",
                            telef2=".$telef2.",telef3=".$telef3);
         
            DB::insert("insert ignore catalogos.planta_telef_cms_new 
                            (
                                select idclientecrm,codserv,nodo,troba,amplificador,'','',$telef1,$telef2,$telef3,0,0,0,0,0,0,0,0,0,0,'HFC' 
                                from 
                                multiconsulta.nclientes 
                                where idclientecrm=".$codcli."
                            )
                        ");
            DB::update("update catalogos.planta_telef_cms_new set telf6=".$telef1.",telf7=".$telef2.",telf8=".$telef3." where cliente=".$codcli);
           
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }


       // return $mensajeInsert;
        //dd("se registro");
 
        
    }

    function getDetailsAgenda($idCliente)
    {
        try {
            
            /*$detalle = DB::select("SELECT 
                                a.cliente AS codcli,a.servicio,IF(a.nodo<>b.nodo AND b.nodo IS NOT NULL,b.nodo,a.nodo) AS nodo,
                                a.DIREC_INST, CONCAT(trim(a.NOMBRE),' ',trim(a.APE_PAT),' ',trim(a.APE_MAT)) as nameclient 
                                FROM cms.planta_clarita a 
                                LEFT JOIN multiconsulta.nclientes b ON a.servicio=b.codserv 
                                WHERE a.cliente=?",[$idCliente]);*/
            $detalle = DB::select("SELECT 
                                a.cliente AS codcli,a.servicio,IF(a.nodo<>b.nodo AND b.nodo IS NOT NULL,b.nodo,a.nodo) AS nodo,
                                a.DIREC_INST, CONCAT(trim(a.NOMBRE),' ',trim(a.APE_PAT),' ',trim(a.APE_MAT)) as nameclient 
                                FROM cms.planta_clarita a 
                                LEFT JOIN multiconsulta.nclientes b ON a.CLIENTE=b.IDCLIENTECRM
                                WHERE a.cliente=?",[$idCliente]);

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }

        return $detalle;
       
    }

    function getAgendasActuales($idCliente)
    {
        try {

            $detalle = DB::select("SELECT 
                                    a.codcli,a.fecha,a.codreq,b.`turno`,a.fecharegistroagenda,a.id,a.tipocliagenda
                                    FROM preagenda.preagenda a 
                                    INNER JOIN preagenda.`rangohorario` b ON a.rangohorario=b.id
                                    WHERE codcli=? AND estado 
                                    IN ('AGENDA PENDIENTE','REAGENDA PENDIENTE','SE AGENDA EN PSI','SE RE-AGENDA EN PSI') 
                                    limit 1",[$idCliente]);

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        }

        return $detalle;
       
    }

    function getTipoDeAgenda()
    {
        $lista = DB::select("select * from preagenda.tipoturno WHERE tipoagenda='AGENDA'");
        return $lista;
    }
    function getDiaDeAgenda()
    {
        $lista = DB::select("select * from preagenda.dia where datediff(fecha,now())<=6 and datediff(fecha,now())>=0");
        return $lista;
    }
    function getDiaDeAgendaByFecha($fecha)
    {
        $lista = DB::select("select * from preagenda.dia where fecha=?",[$fecha]);
        return $lista;
    }
    function getTurnoByTipoAgenda($tipoTurno)
    {
        $lista = DB::select("select tipoturno,id,idrangohorario from preagenda.tipoturno where id=?",[$tipoTurno]);
        return $lista;
    }
 
    function getRangohorarioByTurno($filtroTurnoHora,$idRangoHorarios)
    {
        $lista = DB::select("select * from preagenda.rangohorario where  $filtroTurnoHora idturno=?",[$idRangoHorarios]);
        return $lista;
    }

    function getHorarioById($id)
    {
        $lista = DB::select("select * from preagenda.rangohorario where id=?",[$id]);
        return $lista;
    }

    function cantidadCuposDisponiblesEnNodo($nodo,$dia,$tipoTurno){
       
        $cantidadCuposACrear = DB::select("select 
                            IF(COUNT(cupo)=0,1,cupo+1) as cupo from preagenda.nodocupos 
                            where  nodo=? and fecha=? and turno=?",[$nodo,$dia,$tipoTurno]);
    
        $cupos = $cantidadCuposACrear[0]->cupo;

        $MaximoCuposPorNodo = DB::select("SELECT cupoxturno FROM preagenda.cuposxnodo limit 1");

        $cuposMaximos =  $MaximoCuposPorNodo[0]->cupoxturno;

        return array(
            "cuposActualizar"=>$cupos,
            "cuposMaximos"=>$cuposMaximos,
        );
    }

    function CreandoCupoAgenda($cuposActualizar,$cuposMaximos,$nodo,$dia,$tipoTurno)
    {
        try {

            if ($cuposActualizar == 1) {
                DB::insert("insert ignore preagenda.nodocupos (nodo,fecha,turno,cupo)
                            values(?,?,?,?)",[$nodo,$dia,$tipoTurno,1]);
            }else{
               /* if ((int)$cuposActualizar > (int)$cuposMaximos) {
                    throw new HttpException(500,"No hay mas cupos para el nodo $nodo en este turno elija otro por favor."); 
                }*/ 
                DB::update("update preagenda.nodocupos set 
                            cupo=? where nodo=? and fecha=? and turno=?",[$cuposActualizar,$nodo,$dia,$tipoTurno]);
            }
 
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(500,"Ocurrio un problema en la separación de su cupo, intente nuevamente.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(500,"Ocurrio un problema en la separación de su cupo, intente nuevamente.");
        }

    }

    function quitarCupoTemporalReservado($cuposActualizar,$cuposMaximos,$nodo,$dia,$tipoTurno)
    {
       // dd($cuposActualizar);
        try {

            if ((int)$cuposActualizar - 2 == 0) {
                DB::insert("delete from preagenda.nodocupos where nodo = ? and fecha = ?  and turno = ? ",[$nodo,$dia,$tipoTurno]);
            }else{
               /* if ((int)$cuposActualizar > (int)$cuposMaximos) {
                    throw new HttpException(500,"No hay mas cupos para el nodo $nodo en este turno elija otro por favor."); 
                }*/ 
                DB::update("update preagenda.nodocupos set 
                            cupo=? where nodo=? and fecha=? and turno=?",[(int)$cuposActualizar - 2,$nodo,$dia,$tipoTurno]);
            }
 
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(500,"Ocurrio un problema en la separación de su cupo, intente nuevamente.");
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(500,"Ocurrio un problema en la separación de su cupo, intente nuevamente.");
        }

    }

    function registrarPreAgendaMulti($data)
    {
        //dd($data);

        try {
   
            $codcli = $data["idCliente"];
            $servicio = $data["servicioCliente"];
            $nodo = $data["nodo"];
            $telefono1 = $data["telefonoFijo"];
            $telefono2 = $data["telefonoMovil"];
            $nombre = $data["nombreCliente"];
            $codreq = $data["codigoRequerimiento"];
            $comentarios = $data["observaciones"];
            $fdia = $data["fechaDia"];
            $fturno = $data["turnoHorario"];
            $idr = $data["idRangoHorario"];
            $tz1 = $data["tipoAgendaCliente"];
           
            DB::insert("insert ignore 
            preagenda.preagenda values 
            (
                null,
                ".$codcli.",
                ".$servicio.",
                '".$nodo."',
                '".$telefono1."',
                '".$telefono2."',
                '".$nombre."',
                '".$codreq."',
                '".$comentarios."',
                '".$fdia."',
                '".$fturno."',
                '".$idr."',
                'AGENDA PENDIENTE',
                '',
                now(),
                ".$tz1.")
            "); 

            if ($data["EstadoAgendaProcesar"] == "preagendar") {
                    $tipoTurno = htmlspecialchars($data["idRangoHorario"]);
                    $dia = htmlspecialchars($data["fechaDia"]);
                    $nodo = htmlspecialchars($data["nodo"]);
                    $idAgendaActiva = htmlspecialchars($data["idAgendaActiva"]);
         
                    //dd($idAgendaActiva);
                        $detalle = DB::select("SELECT 
                                    a.codcli,a.fecha,a.codreq,b.`turno`,a.fecharegistroagenda,a.id,a.tipocliagenda,a.nodo,a.rangohorario
                                    FROM preagenda.preagenda a 
                                    INNER JOIN preagenda.`rangohorario` b ON a.rangohorario=b.id
                                    WHERE a.id=? AND a.estado 
                                    IN ('AGENDA PENDIENTE','REAGENDA PENDIENTE','SE AGENDA EN PSI','SE RE-AGENDA EN PSI') 
                                    limit 1",[$idAgendaActiva]);
                   // dd($detalle);
                    $detallesTipoTurno = $this->cantidadCuposDisponiblesEnNodo($detalle[0]->nodo,$detalle[0]->fecha,$detalle[0]->rangohorario);
                        
                    $this->quitarCupoTemporalReservado((int)$detallesTipoTurno["cuposActualizar"] ,(int)$detallesTipoTurno["cuposMaximos"],$detalle[0]->nodo,$detalle[0]->fecha,$detalle[0]->rangohorario);

                    DB::update("update preagenda.preagenda set estado='CANCELADA' WHERE id=?",[$idAgendaActiva]);
                
            }

         } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(500,"OCurrio un problema al registrar la agenda, intente nuevamente.");
            
        }catch(\Exception $e){
             //dd($e->getMessage());  
            throw new HttpException(500,"OCurrio un problema al registrar la agenda, intente nuevamente.");
        } 
           
    }

   



}