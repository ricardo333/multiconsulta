<?php

namespace App\Reportes\Excel\Cuarentenas;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelAveriasCuarentenas extends GeneralController implements FromCollection,WithHeadings {

    protected $preguntaHoy;
    protected $identificadorCuerentena;
    protected $averiaReiteradaPendiente;
    protected $filtroJefatura;
    protected $codmotv;
    protected $tipoEstado;
    protected $segunColor;
 
    function __construct($identificadorCuerentena,$preguntaHoy,$averiaReiteradaPendiente,
                        $filtroJefatura,$codmotv,$tipoEstado,$segunColor) {

        $this->preguntaHoy = $preguntaHoy;
        $this->identificadorCuerentena = $identificadorCuerentena;
        $this->averiaReiteradaPendiente = $averiaReiteradaPendiente;
        $this->filtroJefatura = $filtroJefatura;
        $this->codmotv = $codmotv;
        $this->tipoEstado = $tipoEstado;
        $this->segunColor = $segunColor;

    }

    public function queryaveria($identificadorCuerentena,$preguntaHoy,$averiaReiteradaPendiente,
                                $filtroJefatura,$codmotv,$tipoEstado,$segunColor){

        try {
            $query = DB::select("
                        SELECT b.jefatura,a.*,SUBSTR(a.status,1,1) AS st,
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'</br>',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'</br>Bandeja: ',a.codctr,' ',a.pctr),'') AS averia,
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, IF(a.caida<>'','</br>Caida Masiva detectada','') AS caida ,
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'</br>',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'</br>Bandeja: ',rm.codctr)) AS averiarm
                        ,pl.codigoreq AS codigo_req,CONCAT(pl.codigodelgruporeq,'-',pl.codigotiporeq) AS TipoAveria,pl.tecnico,pl.fecha_liquidacion,
                        CONCAT(TRIM(pl.apellidopaterno),' ',TRIM(pl.apellidomaterno),' ',TRIM(pl.nombres)) AS nombrepl
                        FROM zz_new_system.cuarentenas_total a FORCE INDEX (IDCLIENTECRM,idGestionCuarentena)
                        INNER JOIN zz_new_system.`gestion_cuarentena` gc FORCE INDEX (identificador,trobas,servicePackageCrmid,scopesGroup)
                            ON a.`idGestionCuarentena` = gc.id AND gc.estado = 'Activo'  
                        LEFT JOIN catalogos.jefaturas b ON a.nodo=b.nodo
                        LEFT JOIN cms.`req_pend_macro` rm ON a.`IDCLIENTECRM`=rm.`codcli`
                        LEFT JOIN cms.aver_liq_catv_pais pl ON a.IDCLIENTECRM=pl.codigodelcliente
                        WHERE 1=1 $preguntaHoy AND CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) IN ('PUNTUAL1.-Niveles NO OK','Puntual1.-Niveles NO OK','1.-Niveles NO OK')
                        $averiaReiteradaPendiente $filtroJefatura $codmotv $tipoEstado $segunColor    
                        AND a.idGestionCuarentena = $identificadorCuerentena 
                        GROUP BY  a.macaddress
                        UNION
                        SELECT b.jefatura,a.*,SUBSTR(a.status,1,1) AS st,
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'</br>',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'</br>Bandeja: ',a.codctr,' ',a.pctr),'') AS averia,
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, IF(a.caida<>'','</br>Caida Masiva detectada','') AS caida ,
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'</br>',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'</br>Bandeja: ',rm.codctr)) AS averiarm
                        ,pl.codigoreq AS codigo_req,CONCAT(pl.codigodelgruporeq,'-',pl.codigotiporeq) AS TipoAveria,pl.tecnico,pl.fecha_liquidacion,
                        CONCAT(TRIM(pl.apellidopaterno),' ',TRIM(pl.apellidomaterno),' ',TRIM(pl.nombres)) AS nombrepl
                        FROM zz_new_system.cuarentenas_total a FORCE INDEX (IDCLIENTECRM,idGestionCuarentena)
                        INNER JOIN zz_new_system.`gestion_cuarentena` gc FORCE INDEX (identificador,trobas,servicePackageCrmid,scopesGroup)
                            ON a.`idGestionCuarentena` = gc.id AND gc.estado = 'Activo'  
                        LEFT JOIN catalogos.jefaturas b ON a.nodo=b.nodo
                        LEFT JOIN cms.`req_pend_macro` rm ON a.`IDCLIENTECRM`=rm.`codcli`
                        LEFT JOIN cms.aver_liq_catv_pais pl ON a.IDCLIENTECRM=pl.codigodelcliente
                        WHERE 1=1 $preguntaHoy AND CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) IN ('PUNTUAL2.- Offline - NO OK','Puntual2.- Offline - NO OK','2.- Offline - NO OK')
                        $averiaReiteradaPendiente $filtroJefatura $codmotv $tipoEstado $segunColor 
                        AND a.idGestionCuarentena = $identificadorCuerentena
                        GROUP BY  a.macaddress
                        UNION
                        SELECT b.jefatura,a.*,SUBSTR(a.status,1,1) AS st,
                        IF(a.edopend ='P',CONCAT('Averia: ',a.codreq,'</br>',a.tipreqini,' ',a.codmotv,' ',a.desmotv,'</br>Bandeja: ',a.codctr,' ',a.pctr),'') AS averia,
                        IF(a.Masiva IS NOT NULL,CONCAT(' Averia Masiva: ',a.Masiva,' '),'') AS nummasiva, IF(a.caida<>'','</br>Caida Masiva detectada','') AS caida ,
                        IF(rm.codcli IS NULL ,'',CONCAT('Averia: ',rm.codreq,'</br>',rm.tipreqini,' ',rm.codmotv,' ',rm.desmotv,'</br>Bandeja: ',rm.codctr)) AS averiarm
                        ,pl.codigoreq AS codigo_req,CONCAT(pl.codigodelgruporeq,'-',pl.codigotiporeq) AS TipoAveria,pl.tecnico,pl.fecha_liquidacion,
                        CONCAT(TRIM(pl.apellidopaterno),' ',TRIM(pl.apellidomaterno),' ',TRIM(pl.nombres)) AS nombrepl

                        FROM zz_new_system.cuarentenas_total a FORCE INDEX (IDCLIENTECRM,idGestionCuarentena)
                        INNER JOIN zz_new_system.`gestion_cuarentena` gc FORCE INDEX (identificador,trobas,servicePackageCrmid,scopesGroup)
                            ON a.`idGestionCuarentena` = gc.id AND gc.estado = 'Activo'  
                        LEFT JOIN catalogos.jefaturas b ON a.nodo=b.nodo
                        LEFT JOIN cms.`req_pend_macro` rm ON a.`IDCLIENTECRM`=rm.`codcli`
                        LEFT JOIN cms.aver_liq_catv_pais pl ON a.IDCLIENTECRM=pl.codigodelcliente
                        WHERE 1=1 $preguntaHoy AND CONCAT(TRIM(a.tipoaveria),TRIM(a.status)) NOT IN ('PUNTUAL1.-Niveles NO OK','Puntual1.-Niveles NO OK','1.-Niveles NO OK'
                        ,'PUNTUAL2.- Offline - NO OK','Puntual2.- Offline - NO OK','2.- Offline - NO OK') 
                        $averiaReiteradaPendiente $filtroJefatura $codmotv $tipoEstado $segunColor 
                        AND a.idGestionCuarentena = $identificadorCuerentena
                        GROUP BY  a.macaddress
                        ");

            $newData = array();
 
           // dd($query);
            foreach ($query as $q) {
 
            $newData[] =  
                (object)array(
                    "JEFATURA" => $q->jefatura,
                    "IDEMPRESACRM" => $q->idempresacrm,
                    "IDCLIENTECRM" => $q->IDCLIENTECRM,
                    "NAMECLIENT" => $q->NAMECLIENT,
                    "CMTS" => $q->cmts,
                    "INTERFACE" => $q->interface,
                    "MACSTATE" => $q->macstate,
                    "RXPWRDBMV" => $q->RxPwrdBmv,
                    "USPWR" => $q->USPwr,
                    "USMER_SNR" => $q->USMER_SNR,
                    "DSPWR" => $q->DSPwr,
                    "DSMER_SNR" => $q->DSMER_SNR,
                    "DIRECCION" => $q->direccion,
                    "NODO" => $q->NODO,
                    "TROBA" => $q->TROBA,
                    "AMPLIFICADOR" => $q->amplificador,
                    "TAP" => $q->tap,
                    "TELF1" => $q->telf1,
                    "TELF2" => $q->telf2,
                    "MOVIL1" => $q->movil1,
                    "MACADDRESS" => $q->MACADDRESS,
                    "SERVICEPACKAGE" => $q->SERVICEPACKAGE,
                    "FECHAACTIVACION" => $q->FECHAACTIVACION,
                    "ESTADO" => $q->estado,
                    "NUMCOO_X" => $q->numcoo_x,
                    "NUMCOO_Y" => $q->numcoo_y,
                    "CODREQ" => $q->codreq,
                    "CODMOTV" => $q->codmotv,
                    "DESMOTV" => $q->desmotv,
                    "TIPREQINI" => $q->tipreqini,
                    "DESOBSORDTRAB" => $q->desobsordtrab,
                    "CODIGOTIPOREQ" => $q->codigotiporeq,
                    "CODIGOMOTIVOREQ" => $q->codigomotivoreq,
                    "CODCTR" => $q->codctr,
                    "CONTRATA" => $q->contrata,
                    "PCTR" => $q->pctr,
                    "QCTR" => $q->qctr,
                    "FECHAREGISTRO" => $q->fecharegistro,
                    "CAIDA" => $q->Caida,
                    "MASIVA" => $q->Masiva,
                    "EDOPEND" => $q->edopend,
                    "STATUS" => $q->STATUS,
                    "TIPOAVERIA" => $q->TipoAveria,
                    "OBSERVACIONES" => $q->observaciones,
                    "USUARIO" => $q->usuario,
                    "FECHAHORA" => $q->fechahora,
                    "ST" => $q->st,
                    "AVERIA" => $q->averia,
                    "NUMMASIVA" => $q->nummasiva,
                    "CAIDA" => $q->caida,
                    "AVERIARM" => $q->averiarm,
                    "CODREQ" => $q->codigo_req,
                    "TECNICO" => $q->tecnico,
                    "FECHA_LQUIDACION" => $q->fecha_liquidacion
                );

            }

            //return collect($newData);
            return $newData;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }
        //return $query;
    }


    public function collection()
    {
        return collect($this->queryaveria($this->identificadorCuerentena,$this->preguntaHoy,$this->averiaReiteradaPendiente,
                                            $this->filtroJefatura,$this->codmotv,$this->tipoEstado,$this->segunColor));
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