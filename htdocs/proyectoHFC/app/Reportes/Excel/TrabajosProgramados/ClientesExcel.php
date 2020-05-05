<?php

namespace App\Reportes\Excel\TrabajosProgramados;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Symfony\Component\HttpKernel\Exception\HttpException;
 
class ClientesExcel extends GeneralController implements FromCollection, WithHeadings {
 

    protected $item;
    protected $nodo;
    protected $troba;

    function __construct($item,$nodo,$troba) {
        $this->item = $item;
        $this->nodo = $nodo;
        $this->troba = $troba;
    }

    private function queryClientesEnTPByItemNodoTroba($item,$nodo,$troba){
 
 
        try {

             //Parametros RF 
             $parametrosRF = new Parametrosrf;  
             $paramTP_detalle = $parametrosRF->getTrabajosProgramadosRF();
             $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramTP_detalle);
             $mensajes = $dataParametrosRF["mensajes"];
            // dd($mensajes);
 
            $listaClientesTp = DB::select("
                                    SELECT * FROM 
                                    ((SELECT nc.nodo, nc.troba, nc.amplificador, nc.tap, nc.direccion, 
                                    IF(c.macaddress IS NOT NULL,c.cmts,IF(b.MACState IS NOT NULL,b.cmts,'')) AS cmts, 
                                    IF(b.MACState ='offline',b.interface,c.interface) AS interface, nc.scopesgroup, 
                                    IF(c.macaddress IS NOT NULL,'online',
                                    IF(b.MACState IS NOT NULL,b.MACState,'')) AS macstate, 
                                    IF(b.MACState <>'offline',b.RxPwrdBmv,' ') AS RxPwrdBmv, 
                                    IF(b.MACState <>'offline',c.USPwr,' ') AS USPwr, 
                                    IF(b.MACState <>'offline',c.USMER_SNR,' ') AS USMER_SNR, 
                                    IF(b.MACState <>'offline',c.DSPwr,' ') AS DSPwr, 
                                    IF(b.MACState <>'offline',c.DSMER_SNR,' ') AS DSMER_SNR, 

                                    IF(m.codreqmnt>0 , 
                                        '".$mensajes->mensaje_uno[0]->mensaje."', 
                                    IF(e.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' 
                                    OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' OR b.macstate = 'init(o)' 
                                    OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)' OR b.macstate = 'init(t)' 
                                    OR b.macstate = 'bpi(wait)'),
                                        '".$mensajes->mensaje_dos[0]->mensaje."', 
                                    IF(f.Caida='SI' AND (b.macstate='offline' OR b.macstate = 'init(d)' 
                                    OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' 
                                    OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' 
                                    OR b.macstate = 'init(r1)' OR b.macstate = 'init(t)' 
                                    OR b.macstate = 'bpi(wait)'),
                                        '".$mensajes->mensaje_tres[0]->mensaje."', 
                                    IF(g.Caida='SI',
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(b.macstate='offline',
                                        '".$mensajes->mensaje_doce[0]->mensaje."', 
                                    IF(c.USMER_SNR < ".$dataParametrosRF["up_snr_min"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USMER_SNR < ".$dataParametrosRF["up_snr_min"]." AND c.USPwr < ".$dataParametrosRF["up_pwr_min"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USPwr  < ".$dataParametrosRF["up_pwr_min"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USPwr  > ".$dataParametrosRF["up_pwr_max"]." AND c.DSPwr > ".$dataParametrosRF["down_pwr_min"]." AND c.DSPwr < ".$dataParametrosRF["down_pwr_max"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.DSPwr  > ".$dataParametrosRF["down_pwr_max"]." AND c.USPwr  < ".$dataParametrosRF["up_pwr_min"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USPwr < ".$dataParametrosRF["up_pwr_min"]." AND c.USPwr > 0 ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USPwr < ".$dataParametrosRF["up_pwr_min"]." AND c.DSPwr > ".$dataParametrosRF["down_pwr_max"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.DSPwr > ".$dataParametrosRF["down_pwr_max"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USPwr  > ".$dataParametrosRF["up_pwr_max"]." AND c.DSPwr > ".$dataParametrosRF["down_pwr_max"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(c.USMER_SNR  < ".$dataParametrosRF["up_snr_max"]." AND c.DSPwr  > ".$dataParametrosRF["down_pwr_max"]." ,
                                        '".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(b.macstate = 'init(d)' OR b.macstate = 'init(i)' OR b.macstate = 'init(io)' 
                                    OR b.macstate = 'init(o)' OR b.macstate = 'init(r)' OR b.macstate = 'init(r1)' 
                                    OR b.macstate = 'init(t)' OR b.macstate = 'bpi(wait)',
                                        '".$mensajes->mensaje_cinco[0]->mensaje."', 
                                    IF(c.DSPwr < ".$dataParametrosRF["down_pwr_min"]." AND c.USPwr > ".$dataParametrosRF["up_pwr_max"].",
                                        '".$mensajes->mensaje_seis[0]->mensaje."', 
                                    IF(c.DSPwr < ".$dataParametrosRF["down_pwr_min"]." OR c.DSPwr > ".$dataParametrosRF["down_pwr_max"].",
                                        '".$mensajes->mensaje_seis[0]->mensaje."', 
                                    IF(c.DSPwr < ".$dataParametrosRF["down_pwr_min"]." AND c.DSMER_SNR < ".$dataParametrosRF["down_snr_min"]." ,
                                        '".$mensajes->mensaje_seis[0]->mensaje."', 
                                    IF(c.USPwr > ".$dataParametrosRF["up_pwr_min"]." AND c.USPwr  <= ".$dataParametrosRF["up_pwr_max"]." AND c.USMER_SNR  >= ".$dataParametrosRF["up_snr_max"]." AND c.DSPwr > ".$dataParametrosRF["down_pwr_min"]." 
                                    AND c.DSPwr <  ".$dataParametrosRF["down_pwr_max"].",
                                        '".$mensajes->mensaje_seis[0]->mensaje."', 
                                    IF(c.DSPwr='' AND c.DSMER_SNR='' AND b.macstate = 'online',
                                        '".$mensajes->mensaje_siete[0]->mensaje."', 
                                    IF(c.DSPwr='' AND c.DSMER_SNR='' AND b.macstate = '',
                                        '".$mensajes->mensaje_ocho[0]->mensaje."', 
                                    IF(b.MACState IN ('init','init(t)','init(r2)','init(r1)'),
                                        '".$mensajes->mensaje_nueve[0]->mensaje."', 
                                    IF(b.MACState IN ('init(d)','DHCP','init(o)'),
                                        '".$mensajes->mensaje_diez[0]->mensaje."' , 
                                    IF(c.DSPwr IS NULL AND b.macstate IS NULL, 
                                        '".$mensajes->mensaje_siete[0]->mensaje."', 
                                        '".$mensajes->mensaje_once[0]->mensaje."'))))))))))))))))))))))))) AS estadomdm, 

                                    IF(pr.troba IS NOT NULL,'PREMIUM','MASIVO') AS premium, 
                                    IF(mt.clientecms IS NULL,'','MOVISTAR TOTAL') AS movistar_total, 
                                    IF(m.codnod IS NULL,'Individual','Masiva') AS masiva, 
                                    IF(nc.estado='Activo','Servicio Activo', 
                                    IF(nc.estado='Inactivo','Servicio Suspendido','')) AS edoserv , 
                                    zo.jefatura AS zonal, nc.macaddress ,nc.telf1,nc.telf2,nc.movil1 as movil1_s,nc.idclientecrm as IDCLIENTE,nc.codserv,
                                    tp.SUPERVISORTDP,tp.FINICIO,tp.HINICIO,tp.HTERMINO,tp.HORARIO,tp.CORTESN,tp.TIPODETRABAJO,
                                    tp.FECHAREGISTRO AS fecha_registro,tp.FECHAAPERTURA AS fecha_apertura,tp.FECHACIERRE AS fecha_cierre,
                                    tp.USUARIOAPERTURANOC AS usuario_apertura,tp.USUARIOCIERRE AS usuario_cierre,tp.ESTADO,tp.USUARIOREGISTRO AS usuario,tp.OBSERVACIONREGISTRO AS OBSERVACIONES,
                                    IF(ta.OPERADOR_MOVIL1 IS NULL,tcms.Operador,ta.OPERADOR_MOVIL1) AS OPERADOR_MOVIL1,
                                    IF(ta.movil1 IS NULL,tcms.Telefono,ta.movil1) AS movil1,
                                    IF(ta.OPERADOR_MOVIL2 IS NULL,tcms.operador_2,ta.OPERADOR_MOVIL2) AS OPERADOR_MOVIL2,
                                    IF(ta.movil2 IS NULL,tcms.telfono2,ta.movil2) AS movil2,
                                    IF(ta.OPERADOR_MOVIL3 IS NULL,tcms.operador_3,ta.OPERADOR_MOVIL3) AS OPERADOR_MOVIL3,
                                    IF(ta.movil3 IS NULL,tcms.telfono3,ta.movil3) AS movil3,
                                    IF(ta.OPERADOR_MOVIL4 IS NULL,tcms.operador_4,ta.OPERADOR_MOVIL4) AS OPERADOR_MOVIL4,
                                    IF(ta.movil4 IS NULL,tcms.telfono4,ta.movil4) AS movil4,
                                    ta.OPERADOR_MOVIL5,
                                    ta.movil5
                                    FROM dbpext.detalle_ttpp_afectacion a 
                                    INNER JOIN multiconsulta.nclientes nc ON a.idclientecrm=nc.idclientecrm 
                                    LEFT JOIN ccm1.scm_total b ON nc.mac2=b.MACAddress 
                                    LEFT JOIN ccm1.scm_phy_t c ON nc.mac2=c.MACAddress 
                                    LEFT JOIN catalogos.jefaturas zo ON nc.nodo=zo.nodo 
                                    LEFT JOIN catalogos.premium pr ON CONCAT(nc.nodo,nc.troba)=pr.troba 
                                    LEFT JOIN catalogos.movistar_total mt ON a.idclientecrm=mt.clientecms 
                                    LEFT JOIN dbpext.masivas_temp m ON nc.nodo = m.codnod AND nc.troba=m.nroplano 
                                    LEFT JOIN alertasx.caidas_new_amplif e ON nc.nodo=e.nodo AND nc.troba=e.troba 
                                    AND nc.amplificador=e.amplificador AND e.Caida='SI' 
                                    LEFT JOIN alertasx.caidas_new f ON nc.nodo=f.nodo AND nc.troba=f.troba AND f.Caida='SI' 
                                    LEFT JOIN alertasx.niveles_new g ON nc.nodo=g.nodo AND nc.troba=g.troba AND g.Caida='SI' 
                                    LEFT JOIN dbpext.trabajos_programados_noc tp
                                    ON a.idttpp=tp.item
                                    LEFT JOIN catalogos.telefonos_atis ta ON a.idclientecrm=ta.CABLE_CLIENTE_CMS
                                    LEFT JOIN catalogos.telefonos_cms tcms ON a.idclientecrm=tcms.Cliente
                                    WHERE nc.nodo='$nodo'  AND nc.troba='$troba'  AND a.idttpp=$item
                                    GROUP BY nc.macaddress
                                    ) 
                                    UNION
                                    (SELECT nc.nodo, nc.plano AS troba, nc.codlex AS amplificador, nc.codtap AS tap , nc.direc_inst AS direccion, 
                                    '' AS cmts,
                                    '' AS interface,
                                    '' AS SCOPESGROUP,'' AS MACSTATE,'' AS RXPWRDBMV,'' AS USPWR,'' AS USMER_SNR,'' AS DSPWR,'' AS DSMER_SNR,
                                    IF(m.codreqmnt>0 , '".$mensajes->mensaje_uno[0]->mensaje."', 
                                    IF(e.Caida='SI','".$mensajes->mensaje_dos[0]->mensaje."', 
                                    IF(g.Caida='SI','".$mensajes->mensaje_cuatro[0]->mensaje."', 
                                    IF(f.Caida='SI' ,'".$mensajes->mensaje_tres[0]->mensaje."','".$mensajes->mensaje_once[0]->mensaje."')))) AS estadomdm, 
                                    IF(pr.troba IS NOT NULL,'PREMIUM','MASIVO') AS premium, 
                                    IF(mt.clientecms IS NULL,'','MOVISTAR TOTAL') AS movistar_total, 
                                    IF(m.codnod IS NULL,'Individual','Masiva') AS masiva, 
                                    '' AS EDOSERV,ofi_cli AS ZONAL,'' AS MACADDRESS,
                                    nc.TELEFCL1 AS TELF1,
                                    nc.TELEFCL2 AS TELF2,
                                    nc.TELEFCL3 AS MOVIL1,
                                    nc.cliente AS IDCLIENTE,nc.servicio AS IDSERVICIO,
                                    tp.SUPERVISORTDP,tp.FINICIO,tp.HINICIO,tp.HTERMINO,tp.HORARIO,tp.CORTESN,tp.TIPODETRABAJO,
                                    tp.FECHAREGISTRO AS fecha_registro,tp.FECHAAPERTURA AS fecha_apertura,tp.FECHACIERRE AS fecha_cierre,
                                    tp.USUARIOAPERTURANOC AS usuario_apertura,tp.USUARIOCIERRE AS usuario_cierre,tp.ESTADO,tp.USUARIOREGISTRO AS usuario,tp.OBSERVACIONREGISTRO AS OBSERVACIONES,
                                    IF(ta.OPERADOR_MOVIL1 IS NULL,tcms.Operador,ta.OPERADOR_MOVIL1) AS OPERADOR_MOVIL1,
                                    IF(ta.movil1 IS NULL,tcms.Telefono,ta.movil1) AS movil1,
                                    IF(ta.OPERADOR_MOVIL2 IS NULL,tcms.operador_2,ta.OPERADOR_MOVIL2) AS OPERADOR_MOVIL2,
                                    IF(ta.movil2 IS NULL,tcms.telfono2,ta.movil2) AS movil2,
                                    IF(ta.OPERADOR_MOVIL3 IS NULL,tcms.operador_3,ta.OPERADOR_MOVIL3) AS OPERADOR_MOVIL3,
                                    IF(ta.movil3 IS NULL,tcms.telfono3,ta.movil3) AS movil3,
                                    IF(ta.OPERADOR_MOVIL4 IS NULL,tcms.operador_4,ta.OPERADOR_MOVIL4) AS OPERADOR_MOVIL4,
                                    IF(ta.movil4 IS NULL,tcms.telfono4,ta.movil4) AS movil4,
                                    ta.OPERADOR_MOVIL5,
                                    ta.movil5
                                    FROM cms.planta_clarita nc 
                                    LEFT JOIN dbpext.trabajos_programados_noc tp ON nc.nodo=tp.nodo AND nc.plano=tp.TROBA
                                    LEFT JOIN catalogos.jefaturas zo ON nc.nodo=zo.nodo 
                                    LEFT JOIN catalogos.premium pr ON CONCAT(nc.nodo,nc.plano)=pr.troba 
                                    LEFT JOIN catalogos.movistar_total mt ON nc.cliente=mt.clientecms 
                                    LEFT JOIN dbpext.masivas_temp m ON nc.nodo = m.codnod AND nc.plano=m.nroplano 
                                    LEFT JOIN alertasx.caidas_new_amplif e ON nc.nodo=e.nodo AND nc.plano=e.troba AND nc.codlex=e.amplificador AND e.Caida='SI' 
                                    LEFT JOIN alertasx.caidas_new f ON nc.nodo=f.nodo AND nc.plano=f.troba AND f.Caida='SI' 
                                    LEFT JOIN alertasx.niveles_new g ON nc.nodo=g.nodo AND nc.plano=g.troba AND g.Caida='SI'  
                                    LEFT JOIN catalogos.telefonos_atis ta ON nc.cliente=ta.CABLE_CLIENTE_CMS
                                    LEFT JOIN catalogos.telefonos_cms tcms ON nc.cliente=tcms.Cliente
                                    WHERE nc.nodo='$nodo'  AND nc.plano='$troba'  AND tp.item=$item
                                    GROUP BY nc.`SERVICIO`
                                    )) xx
                                    group by xx.IDCLIENTE
                                    ");

            //dd($listaClientesTp);

            $dataResult = array();

            foreach ($listaClientesTp as $data) {

                $movil1Detalle = str_replace('-','',$data->movil1);
                if(substr($movil1Detalle,1,1)== 9  && substr($movil1Detalle,0,1)==1) $movil1Detalle=substr($movil1Detalle,1,10); 

                $movil2Detalle = str_replace('-','',$data->movil2);
                if(substr($movil2Detalle,1,1)==9   && substr($movil2Detalle,0,1)==1) $movil2Detalle=substr($movil2Detalle,1,10);
                
                $movil3Detalle = str_replace('-','',$data->movil3);
                if(substr($movil3Detalle,1,1)==9  && substr($movil3Detalle,0,1)==1) $movil3Detalle=substr($movil3Detalle,1,10);

                $movil4Detalle = str_replace('-','',$data->movil4);
                if(substr($movil4Detalle,1,1)==9  && substr($movil4Detalle,0,1)==1) $movil4Detalle=substr($movil4Detalle,1,10);

                $movil5Detalle = str_replace('-','',$data->movil5);
                if(substr($movil5Detalle,1,1)==9   and substr($movil5Detalle,0,1)==1) $movil5Detalle=substr($movil5Detalle,1,10);

               $dataResult[] = array(
                                    "NODO" => $data->nodo,
                                    "TROBA" => $data->troba,
                                    "AMPLIFICADOR" => $data->amplificador,
                                    "TAP" => $data->tap,
                                    "DIRECCION" => $data->direccion,
                                    "CMTS" => $data->cmts,
                                    "INTERFACE" => $data->interface,
                                    "SCOPESGROUP" => $data->scopesgroup,
                                    "MACSTATE" => $data->macstate,
                                    "RXPWRDBMV" => $data->RxPwrdBmv,
                                    "USPWR" => $data->USPwr,
                                    "USMER_SNR" => $data->USMER_SNR,
                                    "DSPWR" => $data->DSPwr,
                                    "DSMER_SNR" => $data->DSMER_SNR,
                                    "ESTADOMDM" => $data->estadomdm,
                                    "PREMIUM" => $data->premium,
                                    "MOVISTAR_TOTAL" => $data->movistar_total,
                                    "MASIVA" => $data->masiva,
                                    "EDOSERV" => $data->edoserv,
                                    "ZONAL" => $data->zonal,
                                    "MACADDRESS" => $data->macaddress,
                                    "TELF1" => $data->telf1,
                                    "TELF2" => $data->telf2,
                                    "MOVIL1" => $data->movil1_s,
                                    "IDCLIENTE" => $data->IDCLIENTE,
                                    "IDSERVICIO" => $data->codserv,
                                    "SUPERVISOR" => $data->SUPERVISORTDP,
                                    "FINICIO" => $data->FINICIO,
                                    "HINICIO" => $data->HINICIO,
                                    "HTERMINO" => $data->HTERMINO,
                                    "HORARIO" => $data->HORARIO,
                                    "CORTESN" => $data->CORTESN,
                                    "TIPODETRABAJO" => $data->TIPODETRABAJO,
                                    "FECHA_REGISTRO" => $data->fecha_registro,
                                    "FECHA_APERTURA" => $data->fecha_apertura,
                                    "FECHA_CIERRE" => $data->fecha_cierre,
                                    "ESTADO" => $data->ESTADO,
                                    "USUARIO" => $data->usuario,
                                    "OBSERVACIONES" => $data->OBSERVACIONES,
                                    "OPERADOR_MOVIL1" => $data->OPERADOR_MOVIL1,
                                    "movil1" => $movil1Detalle,
                                    "OPERADOR_MOVIL2" => $data->OPERADOR_MOVIL2,
                                    "movil2" => $movil2Detalle,
                                    "OPERADOR_MOVIL3" => $data->OPERADOR_MOVIL3,
                                    "movil3" => $movil3Detalle,
                                    "OPERADOR_MOVIL4" => $data->OPERADOR_MOVIL4,
                                    "movil4" => $movil4Detalle,
                                    "OPERADOR_MOVIL5" => $data->OPERADOR_MOVIL5,
                                    "movil5" => $movil5Detalle
               );
            }
            //dd($dataResult);
            return $dataResult;
           

        } catch(QueryException $ex){ 
           //dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
       }catch(\Exception $e){
            // dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
       }  
   
    }

    

    public function collection()
    { 
        //dd($this->item."---".$this->nodo."---".$this->troba);
        return collect($this->queryClientesEnTPByItemNodoTroba($this->item,$this->nodo,$this->troba));
        
        
    }

    
    public function headings(): array
    {
        
        $cabecera = array(
                            'NODO',
                            'TROBA',
                            'AMPLIFICADOR',
                            'TAP',
                            'DIRECCION',
                            'CMTS',
                            'INTERFACE',
                            'SCOPESGROUP',
                            'MACSTATE',
                            'RXPWRDBMV',
                            'USPWR',
                            'USMER_SNR',
                            'DSPWR',
                            'DSMER_SNR',
                            'ESTADOMDM',
                            'PREMIUM',
                            'MOVISTAR_TOTAL',
                            'MASIVA',
                            'EDOSERV',
                            'ZONAL',
                            'MACADDRESS',
                            'TELF1',
                            'TELF2',
                            'MOVIL1',
                            'IDCLIENTE',
                            'IDSERVICIO',
                            'SUPERVISOR',
                            'FINICIO',
                            'HINICIO',
                            'HTERMINO',
                            'HORARIO',
                            'CORTESN',
                            'TIPODETRABAJO',
                            'FECHA_REGISTRO',
                            'FECHA_APERTURA',
                            'FECHA_CIERRE',
                            'ESTADO',
                            'USUARIO',
                            'OBSERVACIONES',
                            'OPERADOR_MOVIL1',
                            'movil1',
                            'OPERADOR_MOVIL2',
                            'movil2',
                            'OPERADOR_MOVIL3',
                            'movil3',
                            'OPERADOR_MOVIL4',
                            'movil4',
                            'OPERADOR_MOVIL5',
                            'movil5'
        );
        

       
  

        return $cabecera;
    }

  

}