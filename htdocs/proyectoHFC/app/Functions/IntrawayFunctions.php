<?php
namespace App\Functions;

use DB; 
use SoapClient;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IntrawayFunctions
{

	protected function primer_intraway(){
        $conexiones = config('intraway.one_intraway'); 
            
        return $conexiones;
	}
	
     // getMediciones está  sin uso, ya que se estan evaluando segun CURl de MODEMS - es mas rapido
    function getMediciones($codCliente, $idServicio, $idProducto, $idventaw) {

        
        //echo "<br/>".$codCliente." ".$idServicio." ".$idProducto;
        $fila = $this->PeticionIntraway($idServicio, $idProducto, $idventaw );
         
		if($fila == "error"){
			return "error";
		}
	
        $arr = array();
        $arr["powerDown"] = ($fila["dspl"] == "N/A") 	? '' : $fila["dspl"]/1;
        $arr["powerUp"] =  	($fila["uspl"]== "N/A") 	? '' : $fila["uspl"]/1;
        $arr["snrDown"] =   ($fila["dssnr"]== "N/A") 	? '' : $fila["dssnr"]/1;
        $arr["snrUp"] =  	($fila["ussnr"]== "N/A") 	? '' : $fila["ussnr"]/1;
		//print_r($arr);
		
        return $arr;
    }
    
    function PeticionIntraway($idServicio,$idProducto,$idVenta,$statusUno="")
	{
		$intrawayConexion = $this->primer_intraway();
 
		try {
				$autkey = $intrawayConexion["key"];
				$wsdl = $intrawayConexion["wsdl"];

				$client = new SoapClient( $wsdl );

				if ($idServicio=='2') {
						//$idVentaPadre = '0';
						$idProducto = $idProducto;
						$idVenta = '0';
				}
				else if ($idServicio=='1') {   // Intraway creados manuales
						//$idVentaPadre = '0';
						$idVenta = $idVenta;
						$idProducto = '0';
				}
				else if ($statusUno=="PCI" && $idServicio=='1') {   // Intraway creados manuales
					//$idVentaPadre = '0';
					$idVenta = $idProducto;
					$idProducto = '0';
				}

				/*
				if ($statusUno=="PCI") {
					$status = '<status>TRUE|FALSE|NULL</status>';
				} else {
					$status = '<status>TRUE|FALSE|NULL</status>';
				}
				*/
				
				
				$arrXml = array (
						'authKey'=>$autkey,
						'idEmpresaCRM'=>'127',
						'idServicio'=>$idServicio,
						'idVenta'=>$idVenta,
						'idProducto'=>$idProducto,
						'xmlEncoding'=>'
						<DocsisStatusParameters>
						<getBasicData>
							<status>TRUE</status>
						</getBasicData>
						<getCMLeases>
							<status>TRUE</status>
							<order>DESC</order>
							<cantRecords>2</cantRecords>
						</getCMLeases>
						<getMTALeases>
							<status>FALSE</status>
							<order>DESC</order>
							<cantRecords>10</cantRecords>
						</getMTALeases>
						<getCPELeases>
							<status>TRUE</status>
							<order>DESC</order>
							<cantRecords>5</cantRecords>
						</getCPELeases>
						<getSPDescription>
							<status>FALSE</status>
						</getSPDescription>
						<getPoolingData>
							<status>FALSE</status>
							<order>ASC</order>
							<cantRecords>ALL</cantRecords>
							<inicio>1</inicio>
							<final>1</final>
						</getPoolingData>
						<getBasicMTAData>
							<status>TRUE</status>
						</getBasicMTAData>
						<trafficControlInfo>
							<status>TRUE|FALSE|NULL</status>
							<showDescriptiveInfo>TRUE|FALSE|NULL</showDescriptiveInfo>
							<showLastAction>TRUE|FALSE|NULL</showLastAction>      
							<showPeriodTrafficInfo>TRUE|FALSE|NULL</showPeriodTrafficInfo>
						</trafficControlInfo>
						</DocsisStatusParameters>'
					);

			$res1 = $client->__soapCall("GetDocsisStatus", $arrXml);

		} catch (\Throwable $th) {
			//dd("hubo un error intraway...");
			return "error"; // de generarse un error retorna defrente el error para no detener el procesos general
		}
 
		
		//print_r($arrXml);
          
		 
		$res11 = json_decode(json_encode($res1),true);
		// print_r($res11);
         
		 
        $msg1 = $res11["errorStr"];// mensaje Operation Success o failed
        
		$errorResultInt = ($res11["idError"]=='0') ? 0 : 1 ;
		 
		 
		//echo "Y = ".$y. " IDError = ".$res11["idError"];
		
		if ($errorResultInt!=0) {
			//echo "El cliente no existe en Intraway.";
			//echo "Ocurrio un problema con la conexion a Intraway. Intentelo nuevamente.";
			// die();
			return "error";
		}
			
		$resFinal = $res11["DocsisStatusObjOutput"];
		
		// print_r($resFinal);

		return $resFinal;	
	}

	function intraway_cliente($codCliente)
	{
 
		$intrawayConexion = $this->primer_intraway();
  

		try {
			#INICIO PETICION
				$autkey = $intrawayConexion["key"];
				$wsdl = $intrawayConexion["wsdl"];
				$client = new SoapClient( $wsdl );

				// Codigo de cliente
				 $codigoCliente = (int) $codCliente - 1;
				 
				//$codigoClienteCMS = $codCliente;

				$arrXml1 = array (
					'authKey'=>$autkey,
					'idEmpresaCRM'=>'127',
					'idClienteCRM'=>"$codigoCliente",
					'cantRecords'=>'1',
					'showProducts' => array(
						'showDocsis' => 'TRUE',
						'showPacketCable' => 'TRUE',
						'showVoipLine' => 'TRUE'
						
					)
				);
				$res1 = $client->__soapCall("GetReport", $arrXml1);
			#END
		} catch (\Throwable $th) {
			//dd("hubo un error intraway...");
			return "error"; // de generarse un error retorna defrente el error para no detener el procesos genera
		}
		
		$res11 = json_decode(json_encode($res1),true);
		
		if ($res11["idError"]!="0") {
			return "error";
		}

		if ($res11["report"][0]["idClienteCRM"] != $codCliente) {
			return "error";                       
		}
		
		 
		return $res11;
		  
	}


	function procesarClienteIntraway($clienteReporte)
	{

		$transformCliente = array_map(function($el){ 
          
			for ($i=0; $i < count($el["Docsis"]) ; $i++) { 
				 $velocidadReal = $this->servicePackageVelocidad($el["Docsis"][$i]["ServicePackage"]);
				 $el["Docsis"][$i]["velocidadVigenteMB"] =$velocidadReal;  

				 if ($el["Docsis"][$i]["Activo"] =='SI') {
					  $el["Docsis"][$i]["msgActivo"] ="Cabemodem Activo";  
				 }else{
					  $el["Docsis"][$i]["msgActivo"] ="Cabemodem Desactivado";  
				 }

				 if ($el["Docsis"][$i]["idServicio"]=="2" ) {
					  $el["Docsis"][$i]["idDocsis"] = $el["Docsis"][$i]["idProducto"]; 
				 }
				 else {
					  if ($el["Docsis"][$i]["idVenta"]=='0') {
						   $el["Docsis"][$i]["idDocsis"] = $el["Docsis"][$i]["idProducto"];
					  }
					  else {
						   $el["Docsis"][$i]["idDocsis"] = $el["Docsis"][$i]["idVenta"];
					  }
					  
				 }

			}

			return $el;

	   },$clienteReporte); 

	   return $transformCliente;

	}

	function servicePackageVelocidad($sp) { 
        $cad = DB::select(
            "select veloc_comercial 
            FROM catalogos.velocidades_cambios 
            WHERE SERVICEPACKAGECRMID=? ",[$sp]);
        $velocVigenteMB = 0;
         
        if(empty($cad[0]->veloc_comercial)){
           
            return $velocVigenteMB;
        }else{
            $velocVigenteMB = ((int)$cad[0]->veloc_comercial / 1000)." Mb";
            return $velocVigenteMB;
           
        }  
	}
	
	function resetOnCM($codcliente,$idservicio,$idproductocm,$idVenta)
	{
		if ($idservicio=="1") {
			$idproducto = "0";
			$idVenta = $idVenta;
		}
		else {
			$idVenta = "0";
			$idproducto = $idproductocm;
		}

		$intrawayConexion = $this->primer_intraway();
		 

		try {

			$autkey = $intrawayConexion["key"];
			$wsdl = $intrawayConexion["wsdl"];
 
			$client = new SoapClient($wsdl );
	
			$arrXml1 = array (
				'authKey'=>$autkey
				,'ArrayOfInterfaceObjInput' => array(
					'item' => array(
						'idEntradaCaller'=>'0'
						,'idEmpresaCRM'=>'127'
						,'idVenta'=>$idVenta
						,'idServicio'=>$idservicio
						,'idProducto'=>$idproducto
						,'xmlEncoding'=>'
							<maintenance>
								<command><name>BOOT_CM</name></command>
							</maintenance>
						'
					)
				)
			);
	
			$res1 = $client->__soapCall("Maintenance", $arrXml1);

		} catch (\Throwable $th) {
			 //dd("hubo un error intraway...",$th);
			return "error"; // de generarse un error retorna defrente el error para no detener el procesos genera
		}
		
		$res11 = json_decode(json_encode($res1),true);
		//dd($res11);
		
		$msg1 = $res11[0]["errorStr"];
		  
		if (trim($res11[0]["idError"])=='0') {
			$y = "CM Reiniciado correctamente\n".$msg1."\n Si no reinicia por favor pedir al cliente que desconecte el modem y vuelva a conectar";
		}
		else{
			$y = "Ocurrio un error..."."\n Si no reinicia por favor pedir al cliente que desconecte el modem y lo vuelva a conectar";
		}

		return $y;

	}

	function ActiveOrChangeCM($iwAction,$codCliente,$idProducto,$idServicio,$serviceP,$idISPCRM,$ispMtaCrmId)
	{
 
		$activationCode = '';
		$fechaActivationCodeExpirationDate = '';

		if ($idServicio == '1') {  // IW
			$idVenta = $idProducto;
			$idProducto = "0";	
		}
		else if ($idServicio == '2') {  // CMS
			$idVenta = "0";
		}

		$intrawayConexion = $this->primer_intraway();
		$autkey = $intrawayConexion["key"];
		$wsdl = $intrawayConexion["wsdl"];

		try {
 
			$client = new SoapClient($wsdl);

			if ($iwAction=="activar") {
				//echo "ACTIVAR -- idCliente = $idCliente, idProducto = $idProducto, idServicio = $idServicio";
				$arrXml1 = array (
					'authKey'=>$autkey
					,'ArrayOfInterfaceObjInput' => array(
						'item' => array(
							'idEntradaCaller'=>'0'
							,'idInterface'=>'620'
							,'idEstado'=>'2'
							,'asyncronic'=>'0'
							,'fechaDiferido'=>''
							,'idCliente'=>$codCliente
							,'idEmpresa'=>'127'
							,'idVenta'=>$idVenta
							,'idVentaPadre'=>'0'
							,'Status'=>'NewTask'
							,'idServicio'=>$idServicio
							,'idProducto'=>$idProducto
							,'idServicioPadre'=>'0'
							,'idProductoPadre'=>'0'
							,'idPromotor'=>'0'
							,'xmlEncoding'=>'
							<handleCM>
							<ServicePackageCRMID>'.$serviceP.'</ServicePackageCRMID>
							<Hub></Hub >
							<Nodo></Nodo >
							<idISPCRM>'.$idISPCRM.'</idISPCRM>
							<BuscarTagCM>CM</BuscarTagCM>
							<ProductName></ProductName>
							<IspMtaCrmId>'.$ispMtaCrmId.'</IspMtaCrmId>
							<BandPackageCrmId></BandPackageCrmId>
							<PrepaidPolicyCrmId></PrepaidPolicyCrmId>
							<CantCPE>2</CantCPE>
							<FixedIpMax>0</FixedIpMax>
							<ActivationCode>'.$activationCode.'</ActivationCode>
							<USChannelId></USChannelId>
							<DSFreq></DSFreq>
							<PeriodicalBaseBalance>0</PeriodicalBaseBalance>
							<StartingBalance>0</StartingBalance>
							<NoBoot>FALSE</NoBoot>
							<DPIPackageCRMId></DPIPackageCRMId>
							<WPAKey></WPAKey>
							<WEPKey></WEPKey>
							<ActivationCodeExpirationDate>'.$fechaActivationCodeExpirationDate.'</ActivationCodeExpirationDate>
							</handleCM>
							'
						)
					)
				);
			}
			else if ($iwAction=="desactivar") {
				//echo "DESACTIVAR";
			
				$arrXml1 = array (
					'authKey'=>'Nestorsecreto'
					,'ArrayOfInterfaceObjInput' => array(
						'item' => array(
							'idEntradaCaller'=>'0'
							,'idInterface'=>'620'
							,'idEstado'=>'4'
							,'asyncronic'=>'0'
							,'fechaDiferido'=>''
							,'idCliente'=>$codCliente
							,'idEmpresa'=>'127'
							,'idVenta'=>'0'
							,'idVentaPadre'=>'0'
							,'Status'=>'NewTask'
							,'idServicio'=>$idServicio
							,'idProducto'=>$idProducto
							,'idServicioPadre'=>'0'
							,'idProductoPadre'=>'0'
							,'idPromotor'=>'0'
							,'xmlEncoding'=>'
							<handleCM>
							<ServicePackageCRMID>'.$serviceP.'</ServicePackageCRMID>
							<Hub></Hub >
							<Nodo></Nodo >
							<idISPCRM>'.$idISPCRM.'</idISPCRM>
							<BuscarTagCM>CM</BuscarTagCM>
							<ProductName></ProductName>
							<IspMtaCrmId>'.$ispMtaCrmId.'</IspMtaCrmId>
							<BandPackageCrmId></BandPackageCrmId>
							<PrepaidPolicyCrmId></PrepaidPolicyCrmId>
							<CantCPE>2</CantCPE>
							<FixedIpMax>0</FixedIpMax>
							<ActivationCode>'.$activationCode.'</ActivationCode>
							<USChannelId></USChannelId>
							<DSFreq></DSFreq>
							<PeriodicalBaseBalance>0</PeriodicalBaseBalance>
							<StartingBalance>0</StartingBalance>
							<NoBoot>FALSE</NoBoot>
							<DPIPackageCRMId></DPIPackageCRMId>
							<WPAKey></WPAKey>
							<WEPKey></WEPKey>
							<ActivationCodeExpirationDate>'.$fechaActivationCodeExpirationDate.'</ActivationCodeExpirationDate>
							</handleCM>
							'
						)
					)
				);
			}

			$res1 = $client->__soapCall("Put", $arrXml1);
		} catch (\Throwable $th) {
			 return "error";
			 //No se retorna el throw porque la funcion se utiliza en retorno de velocidad y es necesario
			 //obtener el LOG de ello detallado..
			// throw new HttpException(409,"Se detectó un problema de conectividad con Intraway, intente dentro de otro momento.");
		}

		
  		//print_r($res1); 

		$res11 = json_decode(json_encode($res1),true);
		//dd($res11);

		if ($res11[0]["idError"]!="0") {
			return "error";
		}

		//$msg1 = $res11[0]["errorStr"];

		return $res11;

	}

	function cambiarIPScopesGroup($idclientecrm,$idservicio,$idproductocm,$idSP,$cmbIspCrm)
	{

		if ($idservicio=="1") {
			$idproducto = "0";
			$idVenta = $idproductocm;
		}
		else {
			$idproducto = $idproductocm;
			$idVenta = "0";
		}

		$intrawayConexion = $this->primer_intraway();
		$autkey = $intrawayConexion["key"];
		$wsdl = $intrawayConexion["wsdl"];

		try {
			 
			#inicio cambio por intraway
			 
				$client = new SoapClient($wsdl);

				$arrXml1 = array (
					'authKey'=>$autkey
					,'ArrayOfInterfaceObjInput' => array(
						'item' => array(
							'idEntradaCaller'=>'0'
							,'idInterface'=>'620'
							,'idEstado'=>'2'
							,'asyncronic'=>'0'
							,'fechaDiferido'=>''
							,'idCliente'=>$idclientecrm
							,'idEmpresa'=>'127'
							,'idVenta'=>$idVenta
							,'idVentaPadre'=>'0'
							,'Status'=>'NewTask'
							,'idServicio'=>$idservicio
							,'idProducto'=>$idproducto
							,'idServicioPadre'=>'0'
							,'idProductoPadre'=>'0'
							,'idPromotor'=>'0'
							,'xmlEncoding'=>'
							<handleCM>
							<ServicePackageCRMID>'.$idSP.'</ServicePackageCRMID>
							<Hub></Hub >
							<Nodo></Nodo >
							<idISPCRM>'.$cmbIspCrm.'</idISPCRM>
							<BuscarTagCM>CM</BuscarTagCM>
							<ProductName></ProductName>
							<IspMtaCrmId></IspMtaCrmId>
							<BandPackageCrmId></BandPackageCrmId>
							<PrepaidPolicyCrmId></PrepaidPolicyCrmId>
							<CantCPE>2</CantCPE>
							<FixedIpMax>0</FixedIpMax>
							<ActivationCode></ActivationCode>
							<USChannelId></USChannelId>
							<DSFreq></DSFreq>
							<PeriodicalBaseBalance>0</PeriodicalBaseBalance>
							<StartingBalance>0</StartingBalance>
							<NoBoot>FALSE</NoBoot>
							<DPIPackageCRMId></DPIPackageCRMId>
							<WPAKey></WPAKey>
							<WEPKey></WEPKey>
							<ActivationCodeExpirationDate></ActivationCodeExpirationDate>
							</handleCM>'
						)
					)
				);

				
				$res1 = $client->__soapCall("Put", $arrXml1);
			#END

		} catch (\Throwable $th) { 
		  throw new HttpException(409,"Se detectó un problema de conectividad con Intraway, intente dentro de otro momento.");
	   }

	   //print_r($res1); 

		$res11 = json_decode(json_encode($res1),true);
		//dd($res11);

		if ($res11[0]["idError"]!="0") {
			return "error";
		}

		//$msg1 = $res11[0]["errorStr"];

		return $res11;
 
	
	}
 
	 
}