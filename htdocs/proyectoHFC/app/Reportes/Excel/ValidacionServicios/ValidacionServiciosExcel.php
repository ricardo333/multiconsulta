<?php

namespace App\Reportes\Excel\ValidacionServicios;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
 
class ValidacionServiciosExcel extends GeneralController implements FromCollection, WithHeadings {
 

    protected $tipoBus;
    protected $idUsuario;

    function __construct($tipoBus,$idUsuario) {
        $this->tipoBus = $tipoBus;
        $this->idUsuario = $idUsuario;
    }

    private function queryServicioPorCodigoCliente($idUsuario){

        try {
            
            #INICIO
                    $query = DB::select("
                            SELECT ev.*,
                            ev.docsis,ev.marca,ev.Modelo,ev.hwversion,ev.Versioon,ev.tieneip
                            FROM 
                                (	SELECT b.idclientecrm,rp.codreq,rp.codmotv,rp.desmotv,REPLACE(b.NAMECLIENT,',',' ') AS nameclient,rp.fec_registro,REPLACE(b.direccion,',',' ') AS direccion,b.NODO,b.TROBA,b.amplificador,b.tap,b.telf1,b.telf2,b.movil1,    
                                b.macaddress,b.SERVICEPACKAGE,b.estado AS estado_modem,b.SCOPESGROUP,IF(d.macaddress IS NULL ,c.cmts,d.cmts) AS CMTS,IF(d.macaddress IS NULL ,c.interface,d.interface) AS INTERFACE,b.`numcoo_x`,b.`numcoo_y`,c.macstate,c.RxPwrdBmv,d.USPwr,d.USMER_SNR,d.DSPwr,d.DSMER_SNR,IF(mt.clientecms IS NOT NULL ,'MOVISTAR TOTAL','') AS movistar_total,
                                e.Caida AS CaidaA,f.Caida AS CaidaM,g.Caida AS CaidaS,h.codreqmnt,dt.docsis,dt.Fabricante AS marca,dt.Modelo,dt.hwversion,dt.Versioon,IF(c.numcpe<=1,'NO','SI') AS tieneip,IF(ps.tipopuerto IS NULL,'NO','SI') AS downsaturado
                                
                                FROM `zz_new_system`.`temporal_clientes_codigos` a INNER JOIN multiconsulta.nclientes b
                                ON a.codcli=b.idclientecrm
                                LEFT JOIN ccm1.scm_total c
                                ON b.mac2=c.MACAddress
                                LEFT JOIN ccm1.scm_phy_t d
                                ON b.mac2=d.MACAddress
                                LEFT JOIN alertasx.caidas_new_amplif e
                                ON b.nodo=e.nodo AND b.troba=e.troba AND b.amplificador=e.amplificador AND e.Caida='SI'
                                LEFT JOIN alertasx.caidas_new f
                                ON b.nodo=f.nodo AND b.troba=f.troba AND f.Caida='SI'
                                LEFT JOIN alertasx.niveles_new g
                                ON b.nodo=g.nodo AND b.troba=g.troba AND g.Caida='SI'
                                LEFT JOIN dbpext.masivas_tempx h
                                ON b.nodo=h.codnod AND b.troba=h.nroplano
                                LEFT JOIN cms.req_pend_macro_final rp
                                ON b.IDCLIENTECRM=rp.codcli
                                LEFT JOIN catalogos.movistar_total mt
                                ON b.idclientecrm=mt.clientecms
                                LEFT JOIN ccm1_data.marca_modelo_docsis_total_final dt
                                ON b.macaddress=dt.macaddress
                                LEFT JOIN reportes.clientes_en_puerto_saturado ps
                                ON a.codcli=ps.IDCLIENTECRM
                                WHERE a.idusuario=$idUsuario
                                )	ev
                                WHERE ev.idclientecrm<>969625
                            GROUP BY ev.macaddress
                    ");
                // dd($query);
                $newData = array();

                //Parametros RF 
                $parametrosRF = new Parametrosrf;  
                $paramDiagMasi_detalle = $parametrosRF->getValidacionServicioRF();
                $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);

                //dd($dataParametrosRF);

                foreach ($query as $q) {
                    
                    // dd($q);
                        $estado = Parametrosrf::getEstadoServiciosVSegunNivelesRF($q->CaidaA,$q->CaidaM,$q->CaidaS,$q->macstate,(double)$q->DSMER_SNR,(double)$q->DSPwr,
                                                                                (double)$q->USMER_SNR,(double)$q->USPwr,$dataParametrosRF);
                                        
                    //dd($q);
                    $newData[] =  
                    (object)array(
                            'IDCLIENTECRM'=> $q->idclientecrm,
                            'CODREQ' => $q->codreq,
                            'CODMOTV' => $q->codmotv,
                            'DESMOTV' => $q->desmotv,
                            'NAMECLIENT' => $q->nameclient,
                            'FREGISTRO' => $q->fec_registro,
                            'DIRECCION' => $q->direccion,
                            'NODO' => $q->NODO,
                            'TROBA' => $q->TROBA,
                            'AMPLIFICADOR'=> $q->amplificador,
                            'TAB' => $q->tap,
                            'TELF1' => $q->telf1,
                            'TELF2' => $q->telf2,
                            'MOVIL1' => $q->movil1,
                            'MACADDRESS' => $q->macaddress,
                            'SERVICEPACKAGE' => $q->SERVICEPACKAGE,
                            'SCOPESGROUP' => $q->SCOPESGROUP,
                            'RxPwrdBmv' => $q->RxPwrdBmv,
                            'USPwr' => $q->USPwr,
                            'USMER_SNR' => $q->USMER_SNR,
                            'DSPwr' => $q->DSPwr,
                            'DSMER_SNR' => $q->DSMER_SNR,
                            'STATUS' => $estado["mensaje"],
                            'INTERFACE' => $q->INTERFACE,
                            'CMTS' => $q->CMTS,
                            'X' => $q->numcoo_x,
                            'Y' => $q->numcoo_y,
                            'MACSTATE' => $q->macstate,
                            'MOVISTAR_TOTAL' => $q->movistar_total,
                            'DOCSIS' => $q->docsis,
                            'MARCA' => $q->marca,
                            'MODELO' => $q->marca,
                            'HWVERSION' => $q->Modelo,
                            'VERSIONFW' => $q->hwversion,
                            'TIENEIP?' => $q->tieneip,
                            'PTO_DOWN_SATURADO?' =>  $q->downsaturado
                    );
                    //dd("aquiiii");

                }



                return $newData;
            #END

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
       }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
       }  
   
    }

    private function queryServicioPorMacCliente($idUsuario){

        try {
            
            #INICIO
                    $query = DB::select("
                                    SELECT ev.*,
                                    ev.docsis,ev.marca,ev.Modelo,ev.hwversion,ev.Versioon
                                    FROM 
                                        (	SELECT b.idclientecrm,rp.codreq,rp.codmotv,rp.desmotv,REPLACE(b.NAMECLIENT,',',' ') AS nameclient,rp.fec_registro,REPLACE(b.direccion,',',' ') AS direccion,b.NODO,b.TROBA,b.amplificador,b.tap,b.telf1,b.telf2,b.movil1,    
                                        b.macaddress,b.SERVICEPACKAGE,b.ESTADO AS estado_modem,b.SCOPESGROUP,IF(d.macaddress IS NULL ,c.cmts,d.cmts) AS CMTS,IF(d.macaddress IS NULL ,c.interface,d.interface) AS INTERFACE,b.`numcoo_x`,b.`numcoo_y`,c.macstate,c.RxPwrdBmv,d.USPwr,d.USMER_SNR,d.DSPwr,d.DSMER_SNR,IF(mt.clientecms IS NOT NULL ,'MOVISTAR TOTAL','') AS movistar_total,
                                        e.Caida AS CaidaA,f.Caida AS CaidaM,g.Caida AS CaidaS,h.codreqmnt,dt.docsis,dt.Fabricante AS marca,dt.Modelo,dt.hwversion,dt.Versioon
                                        FROM `zz_new_system`.`temporal_clientes_mac` a INNER JOIN multiconsulta.nclientes b
                                        ON a.macaddress=b.mac3
                                        LEFT JOIN ccm1.scm_total c
                                        ON b.mac2=c.MACAddress
                                        LEFT JOIN ccm1.scm_phy_t d
                                        ON b.mac2=d.MACAddress
                                        LEFT JOIN alertasx.caidas_new_amplif e
                                        ON b.nodo=e.nodo AND b.troba=e.troba AND b.amplificador=e.amplificador AND e.Caida='SI'
                                        LEFT JOIN alertasx.caidas_new f
                                        ON b.nodo=f.nodo AND b.troba=f.troba AND f.Caida='SI'
                                        LEFT JOIN alertasx.niveles_new g
                                        ON b.nodo=g.nodo AND b.troba=g.troba AND g.Caida='SI'
                                        LEFT JOIN dbpext.masivas_tempx h
                                        ON b.nodo=h.codnod AND b.troba=h.nroplano
                                        LEFT JOIN cms.req_pend_macro_final rp
                                        ON b.IDCLIENTECRM=rp.codcli
                                        LEFT JOIN catalogos.movistar_total mt
                                        ON b.idclientecrm=mt.clientecms
                                        LEFT JOIN ccm1_data.marca_modelo_docsis_total_final dt
                                        ON b.macaddress=dt.macaddress
                                        where a.idusuario=$idUsuario
                                        )	ev
                                    GROUP BY ev.macaddress
                    ");

                     

                $newData = array();

                //Parametros RF 
                $parametrosRF = new Parametrosrf;  
                $paramDiagMasi_detalle = $parametrosRF->getValidacionServicioRF();
                $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);
               
                foreach ($query as $q) {
                    
                    // dd($q);IF(ev.estado_modem='I','Servicio Suspendido',
                    if ($q->estado_modem == "Inactivo") {
                        $estado = "Servicio Suspendido";
                    }else{
                        $estado = Parametrosrf::getEstadoServiciosVSegunNivelesRF($q->CaidaA,$q->CaidaM,$q->CaidaS,$q->macstate,(double)$q->DSMER_SNR,(double)$q->DSPwr,
                        (double)$q->USMER_SNR,(double)$q->USPwr,$dataParametrosRF);
                    }
                        
                   
                    $newData[] =  
                    (object)array(
                            'IDCLIENTECRM'=> $q->idclientecrm,
                            'CODREQ' => $q->codreq,
                            'CODMOTV' => $q->codmotv,
                            'DESMOTV' => $q->desmotv,
                            'NAMECLIENT' => $q->nameclient,
                            'FREGISTRO' => $q->fec_registro,
                            'DIRECCION' => $q->direccion,
                            'NODO' => $q->NODO,
                            'TROBA' => $q->TROBA,
                            'AMPLIFICADOR'=> $q->amplificador,
                            'TAB' => $q->tap,
                            'TELF1' => $q->telf1,
                            'TELF2' => $q->telf2,
                            'MOVIL1' => $q->movil1,
                            'MACADDRESS' => $q->macaddress,
                            'SERVICEPACKAGE' => $q->SERVICEPACKAGE,
                            'SCOPESGROUP' => $q->SCOPESGROUP,
                            'RxPwrdBmv' => $q->RxPwrdBmv,
                            'USPwr' => $q->USPwr,
                            'USMER_SNR' => $q->USMER_SNR,
                            'DSPwr' => $q->DSPwr,
                            'DSMER_SNR' => $q->DSMER_SNR,
                            'STATUS' => $estado["mensaje"],
                            'INTERFACE' => $q->INTERFACE,
                            'CMTS' => $q->CMTS,
                            'X' => $q->numcoo_x,
                            'Y' => $q->numcoo_y,
                            'MACSTATE' => $q->macstate,
                            'MOVISTAR_TOTAL' => $q->movistar_total,
                            'DOCSIS' => $q->docsis,
                            'MARCA' => $q->marca,
                            'MODELO' => $q->marca,
                            'HWVERSION' => $q->Modelo,
                            'VERSIONFW' => $q->hwversion,
                            'TIENEIP?' => "",
                            'PTO_DOWN_SATURADO?' =>  ""
                    );
            
                   // dd($newData);  
                }
 
                return $newData;
            #END

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
       }catch(\Exception $e){
            //  dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
       }  
   
    }


    public function collection()
    { 

        if ($this->tipoBus == 1) { //Codigo de Cliente
            return collect($this->queryServicioPorCodigoCliente($this->idUsuario));
        }else { //Mac del cliente
            return collect($this->queryServicioPorMacCliente($this->idUsuario));
        }
       

    }

    
    public function headings(): array
    {
        if ($this->tipoBus == 1) { //Codigo de Cliente
            $cabecera = array('IDCLIENTECRM','CODREQ','CODMOTV','DESMOTV','NAMECLIENT','FREGISTRO','DIRECCION','NODO','TROBA',
                        'AMPLIFICADOR','TAP','TELF1','TELF2','MOVIL1','MACADDRESS','SERVICEPACKAGE',
                        'SCOPESGROUP','RxPwrdBmv','USPwr','USMER_SNR','DSPwr','DSMER_SNR','STATUS',
                        'INTERFACE','CMTS','X','Y','MACSTATE','MOVISTAR_TOTAL','DOCSIS','MARCA',
                        'MODELO','HWVERSION','VERSIONFW','TIENEIP?','PTO_DOWN_SATURADO?');
        }else { //Mac del cliente
            $cabecera = array('IDCLIENTECRM','CODREQ','CODMOTV','DESMOTV','NAMECLIENT','FREGISTRO','DIRECCION','NODO','TROBA',
                        'AMPLIFICADOR','TAP','TELF1','TELF2','MOVIL1','MACADDRESS','SERVICEPACKAGE',
                        'SCOPESGROUP','RxPwrdBmv','USPwr','USMER_SNR','DSPwr','DSMER_SNR','STATUS',
                        'INTERFACE','CMTS','X','Y','MACSTATE','MOVISTAR_TOTAL','DOCSIS','MARCA',
                        'MODELO','HWVERSION','VERSIONFW');
        }

       
  

        return $cabecera;
    }

  

}