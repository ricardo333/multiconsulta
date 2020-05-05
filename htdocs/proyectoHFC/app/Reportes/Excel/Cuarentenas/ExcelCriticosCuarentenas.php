<?php

namespace App\Reportes\Excel\Cuarentenas;

use DB;
use Excel;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExcelCriticosCuarentenas extends GeneralController implements FromCollection,WithHeadings {

     
    protected $identificadorCuerentena;
    protected $averiaReiteradaPendiente;
    protected $filtroJefatura;
    
 
    function __construct($identificadorCuerentena,$averiaReiteradaPendiente,$filtroJefatura) { 
        $this->identificadorCuerentena = $identificadorCuerentena;
        $this->averiaReiteradaPendiente = $averiaReiteradaPendiente;
        $this->filtroJefatura = $filtroJefatura; 
    }

    public function queryaveria($identificadorCuerentena,$averiaReiteradaPendiente,$filtroJefatura){

        try {
 
            $query = DB::select("                           
                                SELECT  b.jefatura,a.idempresacrm,a.IDCLIENTECRM,a.NAMECLIENT,a.cmts,a.interface,
                                a.macstate,a.RxPwrdBmv,a.USPwr,a.USMER_SNR,a.DSPwr,a.DSMER_SNR,a.direccion,a.NODO,
                                a.TROBA,a.amplificador,a.tap,a.telf1,a.telf2,a.movil1,a.MACADDRESS,a.SERVICEPACKAGE,
                                a.FECHAACTIVACION,a.estado,a.numcoo_x,a.numcoo_y,a.codreq,a.codmotv,a.desmotv,a.tipreqini,
                                a.desobsordtrab,a.codigotiporeq,a.codigomotivoreq,a.codctr,a.contrata,a.pctr,a.qctr,
                                a.fecharegistro,a.Caida,a.Masiva,a.edopend,a.STATUS,a.entidad,
                                CONCAT(pl.codigodelgruporeq,'-',pl.codigotiporeq) AS TipoAveria,
                                a.observaciones,a.usuario,
                                a.fechahora,
                                b.jefatura,SUBSTR(a.status,1,1) AS st,
                                IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'</br>',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'</br>Bandeja: ',a.codctr,' ',a.pctr),'') AS averia,
                                IF(a.Masiva IS NOT NULL,a.Masiva,'') AS nummasiva,
                                IF(a.caida<>'','</br>Caida Masiva detectada','') AS caida,
                                IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'Bandeja: ',rm.codctr)) AS averiarm,
                                pl.codigoreq,pl.tecnico,pl.fecha_liquidacion                               

                                FROM 
                                zz_new_system.cuarentenas_total a  FORCE INDEX (IDCLIENTECRM,nodo)
                                LEFT JOIN catalogos.jefaturas b FORCE INDEX (nodo)
                                    ON a.NODO=b.nodo
                                LEFT JOIN cms.`req_pend_macro_final` rm  FORCE INDEX (codcli)
                                    ON a.`IDCLIENTECRM`=rm.`codcli` 
                                LEFT JOIN cms.aver_liq_catv_pais pl  FORCE INDEX (NewIndex1)
                                    ON a.IDCLIENTECRM=pl.codigodelcliente
                                WHERE 1=1  $filtroJefatura $averiaReiteradaPendiente 
                                AND a.idGestionCuarentena = $identificadorCuerentena 
                                GROUP BY a.`MACADDRESS`  ORDER BY st
                        ");

            
           
            //return collect($newData);
            return $query;

        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }
        //return $query;
    }


    public function collection()
    {
        return collect($this->queryaveria($this->identificadorCuerentena,$this->averiaReiteradaPendiente,$this->filtroJefatura));
    }

    
    public function headings(): array
    {
        $cabecera = array(
            'JEFATURA',
            'IDEMPRESACRM',
            'IDCLIENTECRM',
            'NAMECLIENT',
            'CMTS',
            'INTERFACE',
            'MACSTATE',
            'RXPWRDBMV',
            'USPWR',
            'USMER_SNR',
            'DSPWR',
            'DSMER_SNR',
            'DIRECCION',
            'NODO',
            'TROBA',
            'AMPLIFICADOR',
            'TAP',
            'TELF1',
            'TELF2',
            'MOVIL1',
            'MACADDRESS',
            'SERVICEPACKAGE',
            'FECHAACTIVACION',
            'ESTADO',
            'NUMCOO_X',
            'NUMCOO_Y',
            'CODREQ',
            'CODMOTV',
            'DESMOTV',
            'TIPREQINI',
            'DESOBSORDTRAB',
            'CODIGOTIPOREQ',
            'CODIGOMOTIVOREQ',
            'CODCTR',
            'CONTRATA',
            'PCTR',
            'QCTR',
            'FECHAREGISTRO',
            'CAIDA',
            'MASIVA',
            'EDOPEND',
            'STATUS',
            'ENTIDAD',
            'TIPOAVERIA',
            'OBSERVACIONES',
            'USUARIO',
            'FECHAHORA',
            'ST',
            'AVERIA',
            'NUMMASIVA',
            'CAIDA',
            'AVERIARM',
            'CODREQ',
            'TECNICO',
            'FECHA_LQUIDACION'
        );

        return $cabecera;
    }
    

}


?>