<?php

namespace App\Functions\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceMulticonsultaFunctions
{

    public function validaUsuario($user)
    {
        $queryUser = DB::select("SELECT user FROM zz_new_system.api_services WHERE user='$user'");

        return $queryUser;
    }

    public function validaPassword($pass)
    {
        $queryUser = DB::select("SELECT password FROM zz_new_system.api_services WHERE password=MD5('$pass')");

        return $queryUser;
    }

    public function validaUserToken($user,$token)
    {
        $queryUser = DB::select("SELECT user FROM zz_new_system.api_services WHERE user='$user' AND token='$token'");

        return $queryUser;
    }


    public function generarToken($user)
    {
        $token = Str::random(60);
        $time = Carbon::now();

        $queryToken = DB::update("UPDATE zz_new_system.api_services SET token='$token', time='$time' WHERE user='$user'");

        $getToken = DB::select("SELECT token FROM zz_new_system.api_services WHERE user='$user'");

        return $getToken;

    }

    public function tiempoiniToken($token)
    {
        $tiempoini = DB::select("SELECT time FROM zz_new_system.api_services WHERE token='$token'");

        return $tiempoini;

    }




    //--------------------------------------------------------------------------------//
    public function IfExistCliente($idcliente)
    {
        $queryCMTS = DB::select("SELECT cmts FROM multiconsulta.nclientes WHERE IDCLIENTECRM=$idcliente");

        $cantidad = count($queryCMTS);
        
        return $cantidad;
    }

    public function getCMTSCliente($idcliente)
    {
        $queryCMTS = DB::select("SELECT DISTINCT cmts FROM ccm1_data.marca_modelo_docsis_total_final WHERE NroCliente=$idcliente");
        
        $cantidadResult = count($queryCMTS);
        $CMTSList = array();
        if($cantidadResult < 0){
            return [];
        }

        return $queryCMTS;
    }


    public function getDisponibilidadIPS($CMTSCliente)
    {
        
        $cantidadCMTS = count($CMTSCliente);
        $resultadoFinalIPS = array();
        for ($i=0; $i < $cantidadCMTS ; $i++) { 

            $cmts = $CMTSCliente[$i]->cmts;
            $queryIPS = DB::select("SELECT * FROM
                            (
                                SELECT scopesgroup,SUM(total) tot,SUM(used) usado,SUM(available) disp, cmts 
                                FROM catalogos.`redesip_n` 
                                WHERE cmts like '%$cmts%'
                                    AND scopesgroup NOT IN('CPE-IP-FIJA','GRUPOS','Principal')                
                                GROUP BY 1
                            ) xx
                        WHERE xx.disp<250 AND (xx.usado/xx.tot)*100>=90");
           
            $cantidadResult = count($queryIPS);
            
            if($cantidadResult > 0){ // si hay mas a 0 resultados es porque no está disponible y debemos especificar detalles del CMTS
                $resultadoFinalIPS[] =$queryIPS;
            }
            
        }

        return $resultadoFinalIPS;
    }


    function getNodoAndTrobaClient($idcliente)
    {
        
        $queryNodoAndTroba = DB::select("SELECT DISTINCT CONCAT(NODO,'-',TROBA) AS NODO_TROBA FROM multiconsulta.`nclientes` WHERE IDCLIENTECRM=$idcliente");
        
        $cantidadResult = count($queryNodoAndTroba);

        //$NODO_TROBA_LIST = array();
        if($cantidadResult == 0){
            return [];
        }

        //$NODO_TROBA_LIST[] = $queryNodoAndTroba;
        //dd($queryNodoAndTroba);

        //return $NODO_TROBA_LIST;
        return $queryNodoAndTroba;

    }


    public function getTrabajosByNodoAndTrobaList($nodoyTrobaClientes)
    {

        $cantidadNODO_TROBAS = count($nodoyTrobaClientes);
        $resultadoFinalTRAB_PROG = array();

        for ($i=0; $i<$cantidadNODO_TROBAS; $i++) { 

            $nodo_troba = $nodoyTrobaClientes[$i]->NODO_TROBA;
            //$nodo_troba = $nodoyTrobaClientes[$i]["NODO_TROBA"];
            
	        $queryTrabProg = DB::select("SELECT NODO,TROBA,TIPODETRABAJO,FINICIO,HINICIO,HTERMINO,fechacierre,fechacancela,estado,MAX(fecharegistro) AS fechaRegistro
                            FROM dbpext.`trabajos_programados_noc` 
                            WHERE CONCAT(NODO,'-',TROBA) = '$nodo_troba'
                            AND DATEDIFF(NOW(),fecharegistro) < 5	 
                            GROUP BY NODO,TROBA");
           
            $cantidadResult = count($queryTrabProg);
            
            if($cantidadResult > 0){ // si hay mas a 0 se guarda el nodo y troba de trabajo programado
                $resultadoFinalTRAB_PROG[] = $queryTrabProg;
            }

        }

        return $resultadoFinalTRAB_PROG;

    }


    public function getNodoAndTrobaMasivaCMByClient($idcliente)
    {       
        $queryNT_CM = DB::select("SELECT a.nodo,a.troba FROM multiconsulta.nclientes a
                        INNER JOIN dbpext.`masivas_tempx` b ON a.nodo=b.codnod AND a.troba=b.`nroplano`
                        WHERE a.idclientecrm=$idcliente GROUP BY a.nodo,a.troba");

        $cantidadResult = count($queryNT_CM);

        if($cantidadResult == 0){
            return [];
        }

        $resultMasivasCM = array();
        $resultMasivasCM[]= $queryNT_CM;

        return $resultMasivasCM;

    }


    public function getNodoAndTrobaAlertadoCMByClient($idcliente)
    {
          
        $queryAlertados = DB::select("SELECT a.nodo,a.troba FROM multiconsulta.nclientes a
                        INNER JOIN alertasx.`clientes_alertados` b ON a.nodo=b.nodo AND a.troba=b.troba
                        WHERE a.idclientecrm=$idcliente GROUP BY a.nodo,a.troba");

        $cantidadResult = count($queryAlertados);

        if($cantidadResult == 0){
            return [];
        }

        $resultAlertadosCLient = array();
        $resultAlertadosCLient[]= $queryAlertados;

        return $resultAlertadosCLient;

    }


    public function getDetallesMasivasByNodoTroba($masivas,$idcliente)
    {
        //Recorrer detalles de masivas por  Nodo - troba y cliente
        $cantidadM = count($masivas);
        $ResultMasivaByNodos = array();
        //dd($masivas);

        for ($i=0; $i < $cantidadM; $i++) { 

            //$nodo = $masivas[$i]["nodo"];
            //$troba = $masivas[$i]["troba"];
            $nodo = $masivas[$i][0]->nodo;
            $troba = $masivas[$i][0]->troba;

            $queryMasiva = DB::select("SELECT 'SI' AS Esmasiva, a.macaddress,a.nodo,a.troba,c.MACState FROM 
                            multiconsulta.nclientes a
                            INNER JOIN ccm1.scm_total c
                            ON a.mac2=c.`MACAddress`
                            WHERE a.idclientecrm=$idcliente AND a.nodo='$nodo' AND a.troba='$troba'");

            $masivasNT = array();
            $masivasNT[]= $queryMasiva;
            
            $ResultMasivaByNodos[]= $masivasNT;

        }

        return $ResultMasivaByNodos;

    }


    public function getDetallesAlertasCMByClient($masivas,$idcliente)
    {
        //Recorrer en busca de ver si está alertado la masiva del cliente por Nodo - troba y cliente
        $cantidadM = count($masivas);
        $ResultAlertadosByNodos = array();
        //dd($masivas);

        for ($i=0; $i < $cantidadM; $i++) { 

            //$nodo = $masivas[$i]["nodo"];
            //$troba = $masivas[$i]["troba"];
            $nodo = $masivas[$i][0]->nodo;
            $troba = $masivas[$i][0]->troba;

            //dd($nodo);
            //$troba = $masivas[$i]["troba"];

            $queryAlertas = DB::select("SELECT * FROM alertasx.`clientes_alertados` 
                            WHERE idclientecrm=$idcliente AND nodo='$nodo' AND troba='$troba'");

            $cantidadAlertados = count($queryAlertas);

            if($cantidadAlertados > 0){
                $alertasNT = array();

                for ($i=0; $i < $cantidadAlertados; $i++) { 
                    $elemento = ""; 
                    if ($queryAlertas[$i]->tipo == "CAIDA MASIVA" || $queryAlertas[$i]->tipo == "CAIDA SENAL") {
                        $elemento = "TROBA";
                    }
                    if ($queryAlertas[$i]->tipo == "CAIDA AMPLIF") {
                        $elemento = "AMPLIFICADOR";
                    }

                    $alertasNT[]= array(
                        "alertado"=>"SI",
                        "NODO"=>$queryAlertas[$i]->nodo,
                        "TROBA"=>$queryAlertas[$i]->troba,
                        "MACAddress"=>$queryAlertas[$i]->MACADDRESS,
                        "MacState"=>$queryAlertas[$i]->mactate,
                        "tipoAlerta"=>$queryAlertas[$i]->tipo,
                        "elementoAfectado"=>$elemento
                    );
                    
                }

                $ResultAlertadosByNodos[]= $alertasNT;

            }
            
        }

        return $ResultAlertadosByNodos;

    }



    public function getIPS($idcliente)
    { 
        $existeCMTSCliente = $this->IfExistCliente($idcliente);
 
        if($existeCMTSCliente == 0){
            return json_encode(["error"=>true,"mensaje"=>"ERROR:201906. CODIGO DE CLIENTE NO EXISTE.","code"=>403]);
            die();
        }

        $CMTSCliente = $this->getCMTSCliente($idcliente);
        if(count($CMTSCliente) == 0){
            return json_encode(["error"=>false,"IPS"=>null]);
            die();
        }
        $disponibilidadIPS = $this->getDisponibilidadIPS($CMTSCliente);
 
        return json_encode(["error"=>false,"IPS"=>$disponibilidadIPS]);
    }


    function getTrabajosProgramados($idcliente)
    {

        $nodoyTrobaClientes = $this->getNodoAndTrobaClient($idcliente);
        //dd($nodoyTrobaClientes);

        if(count($nodoyTrobaClientes) == 0){
            return json_encode(["error"=>false,"TRABAJOS_PROGRAMADOS"=>null]);
        }

        $trabajosProgramadosList = $this->getTrabajosByNodoAndTrobaList($nodoyTrobaClientes);
 
        return json_encode(["error"=>false,"TRABAJOS_PROGRAMADOS"=>$trabajosProgramadosList]);

    }


    public function getMasivaCMByClient($idcliente)
    {
        $getNodoAndTrobaMasivaCMByClient = $this->getNodoAndTrobaMasivaCMByClient($idcliente);
        $getNodoAndTrobaAlertadoCMByClient = $this->getNodoAndTrobaAlertadoCMByClient($idcliente);
        //dd($getNodoAndTrobaAlertadoCMByClient);

        $ARRAY_GENERAL_MASIVAS = array();

        if(count($getNodoAndTrobaMasivaCMByClient) < 1){
            $ARRAY_GENERAL_MASIVAS["MASIVAS"] = [];
        }else{
            //Paso porque Si tienen masivas
            $resultDetallesMasivas = $this->getDetallesMasivasByNodoTroba($getNodoAndTrobaMasivaCMByClient,$idcliente);
            $ARRAY_GENERAL_MASIVAS["MASIVAS"] = $resultDetallesMasivas;
        }

        if(count($getNodoAndTrobaAlertadoCMByClient) < 1){
            $ARRAY_GENERAL_MASIVAS["ALERTADOS"] = [];
        }else{
           //Buscamos si está alertado
            $resultAlertadosMasivasCM = $this->getDetallesAlertasCMByClient($getNodoAndTrobaAlertadoCMByClient,$idcliente);
            $ARRAY_GENERAL_MASIVAS["ALERTADOS"] = $resultAlertadosMasivasCM;
        }
         
        return json_encode(["error"=>false,"resultDetallesCM"=>$ARRAY_GENERAL_MASIVAS]);

    }









    //--------------------------------------------------------------------------------//



    function getClienteHFC($idcliente)
    { 
        $consultaCliente = DB::select("SELECT st.cmts,a.nodo AS nodo,a.troba AS troba,n.velocidad_final AS SERVICEPACKAGE,
        nv.USMER_SNR,nv.DSMER_SNR,st.IPAddress,a.MACADDRESS,f.Fabricante, f.Modelo,f.Versioon AS Version_firmware,
        IF(a.scopesgroup='CPE-CGNAT','CGNAT','CPE') AS scopesgroup,a.estado,nv.USPwr,nv.DSPwr,
        IF(g.codreqmnt >0,g.codreqmnt,0) AS num_masiva, IF(ll.tipo='CAIDA MASIVA','Caida',IF(ll.tipo='CAIDA AMPLIF','Caida Amplif',
        IF(ll.tipo='CAIDA SENAL','Señal_RF',IF(ll.tipo='CAIDA SENAL AMPLIF','Señal RF Amplif','')))) AS cliente_alerta,
        IF(st.MACState LIKE '%nline%','online',IF(st.macstate IS NULL,IF(nv.`MACAddress` IS NULL,'','online'),st.macstate)) AS MACState,st.`NumCPE`
        FROM multiconsulta.nclientes a
        LEFT JOIN ccm1_data.marca_modelo_docsis_total f  ON a.MACADDRESS=f.MACAddress
        LEFT JOIN catalogos.velocidades_cambios n ON a.SERVICEPACKAGE=n.SERVICEPACKAGE
        LEFT JOIN dbpext.masivas_temp g ON a.nodo=g.codnod AND a.troba=g.nroplano
        LEFT JOIN alertasx.clientes_alertados ll ON a.MACADDRESS = ll.macaddress
        LEFT JOIN reportes.criticos cc ON a.idclientecrm=cc.idclientecrm
        LEFT JOIN ccm1.scm_phy_t nv ON a.mac2=nv.macaddress
        LEFT JOIN ccm1.scm_total st ON a.mac2=st.macaddress
        WHERE a.idclientecrm <> 969625 AND a.`IDCLIENTECRM`='$idcliente'");

        return $consultaCliente;
    }


    function obtenerSnmp($fabricante,$ipaddress)
    {
        $fabricante_substr = substr($fabricante,0,5);
        $oidx='iso.3.6.1.2.1.4.34.1.10.1.4';

        if($fabricante_substr=="Arris"){
            $oidx='iso.3.6.1.2.1.4.20.1.1';
        }

        if($fabricante_substr=="Hitro"){
            $oidx='iso.3.6.1.2.1.4.22.1.1.1'; 
        }

        $cpe=array();
         
        if ($ipaddress<>'0.0.0.0'){
            $ippu="snmpwalk  -c MODEM8K_PILOTO -v2c ".$ipaddress." ".$oidx;
            
            exec($ippu,$cpe);
            $regy=array();
            $reg ='';
            $cantidad_cpe = count($cpe);

            for ($i=0;$i<$cantidad_cpe;$i++){
                $regy = $cpe[$i];
                $cad='';

                if($fabricante_substr=="Arris"){ 
                    $cad=substr($regy,20,3);
                }else {
                    if($fabricante_substr=="Hitro"){
                        $cad=substr($regy,30,3); 
                    }else{ 
                        $cad=substr($regy,33,3);
                    }
                }
                
                if ($cad<>"10." && $cad<>'127' && $cad<>'192' && trim($cad)<>''){
                    $reg=trim(str_replace("= INTEGER: 1","",$cpe[$i]));
                }
            } 
            
            $oid ='';
            $publica='';
            if($fabricante_substr=="Arris"){
                $oid="iso.3.6.1.2.1.2.2.1.6.10";
                $publica=substr($reg,20,15); 
            } else {
                if($fabricante_substr=="Hitro"){
                    $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,30,14);
                    $publica=substr($reg,30,15);  
                } elseif(strlen($reg)>10){ 
                    $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,33,15); 
                    $oid = str_replace("\"","",$oid); 
                    $publica=substr($reg,33,15); 
                    $publica=str_replace("\"","",$publica);
                }        
            }
             
            // Aqui obtenemos la MAC CPE
            $maccpe=array();
            $snmp='snmpget -c MODEM8K_PILOTO -v2c '.$ipaddress.' '.$oid;
            exec($snmp,$maccpe);
        
            $macaddress='';
            $cpex=array();
            $regx=array();
            $reg1x=array();
            $reg2x=array();
            $regx = empty($maccpe[0])? '' : $maccpe[0];
            $macx='';
            
            if($regx != ''){
                $regxx = explode(": ", $regx);
                
                foreach ($regxx as $fil2x) {
                    if ($fil2x!=''){
                        $reg2x[] = $fil2x;
                    }  
                }
                
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
            
            if(trim(substr($publica,1,2))==''){
                $macx='';
                $publica='';
            }
            

            return array(
                "Publica" => $publica,
                "MacCpe" => $macx
            );

        }

    }



    function validaNiveles($downPx,$upSnr,$downSnr,$upPx,$cliente_alerta,$nodo,$troba,$macstate,$num_masiva,$numcpe,$publica,$macx)
    {

        $tipoprob='';
        $niveles='ok';
        
        if (($cliente_alerta == 'Caida' or $cliente_alerta == 'Señal RF' or $cliente_alerta == 'Caida Amplif' or 
            $cliente_alerta == 'Señal RF Amplif') and $niveles==''){

            if ($downPx < -5 or $downPx > 10) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upSnr * 1 < 27) {
                //$tipoprob = 'Generar averia R417 </br>Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upSnr * 1 < 27 and $upPx * 1 < 36) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downSnr * 1 < 29) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downPx * 1 <= -5 or $downPx * 1 > 12)  {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upPx * 1 <= 35 or $upPx * 1 > 55)  {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downPx * 1 > 10 and $upPx * 1 <= 36) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downPx * 1 > 8 and $downSnr * 1 < 30) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upPx * 1 < 35 and $upPx * 1 > 0) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downPx * 1 > 15) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upSnr * 1 < 27 and $downSnr * 1 > 30 and $downPx * 1 >= -10 and $downPx * 1 <= 12 and $upPx * 1 >= 37 and $upPx * 1 <= 55) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($upSnr * 1 > 27 and $downSnr * 1 < 30 and $downPx * 1 >= -10 and $downPx * 1 <= 12 and $upPx * 1 >= 37 and $upPx * 1 <= 55 ) {
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
                $niveles = 'Malos';
            }
            if ($downPx * 1 < -15 or $downPx * 1 > 15) {
                $niveles = 'Malos';
                //$tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
                $tipoprob = 'Probable problema de Pext';
            }
        }
	
        if (($cliente_alerta <> 'Caida' and $cliente_alerta <> 'Señal RF' and $cliente_alerta <> 'Caida Amplif' and 
            $cliente_alerta <> 'Señal RF Amplif' ) and $niveles<>'Malos'){

            if (($downPx < -5 and $upPx > 55)) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if (($downPx < -5 or $downPx > 10)) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($downPx < -5 and $downSnr < 30) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($upSnr  < 27) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($downSnr  < 29) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if (($downPx  <= -5 or $downPx  > 12)) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if (($upPx  <= 35 or $upPx  > 55)) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($downPx  < -10 and $upPx  > 55) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'malos';
            }
            if ($downPx  > 8 and $downSnr  < 30) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($downPx  > 15) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($upSnr < 27 and $downSnr > 30 and $downPx >= -10 and $downPx <= 12 and $upPx >= 37 and $upPx <= 55) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($upSnr > 27 and $downSnr < 30 and $downPx >= -10 and $downPx <= 12 and $upPx >= 37 and $upPx <= 55) {
                $tipoprob = 'Probable averia en:Red Cliente';
                $niveles = 'Malos';
            }
            if ($downPx < -15 or $downPx > 15) {
                $niveles = 'Malos';
                $tipoprob = 'Probable averia en:Red Cliente';
            }
        }

        if($downPx=='' and $downSnr=='' and $macstate == 'online'){$tipoprob = '';}

        //Validacion de estado Init para mensaje de generacion de averias y derivacion a badeja 415
        if (($macstate == "init(d)" or $macstate == "init(i)" or $macstate == "init(io)" or $macstate == "init(o)" or $macstate == "init(r)" or 
            $macstate == "init(r1)"  or $macstate == "init(t)" or $macstate == "bpi(wait)") and $num_masiva * 1==0){
            $tipoprob = 'Probable averia en:Red Cliente';
        }

        //Mensaje de IP Publica
        if ($publica <>'no' and (strlen(trim($publica))<10 and $numcpe==0) and ($macstate == "online") and trim($macx)=='' and $num_masiva==0)
		{
			if($mensaje==''){
				$mensaje='Cable Modem sin IP Publica';
            }
            
			$tipoprob = $mensaje;
        
        }
        
        return $tipoprob;

    }





}