<?php

namespace App\Reportes\Excel\MonitorAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use App\Administrador\Parametrosrf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MonitorAveriasExcelReverificar extends GeneralController implements FromCollection,WithHeadings {

    public function collection()
    {
        try {
            $query = DB::select("
                                SELECT 
                                rv.codofcadm,
                                rv.codreq,
                                rv.fecreg,
                                rv.estado,
                                rv.codcli,
                                rv.codctr,
                                rv.desnomctr,
                                rv.nodocms,
                                rv.trobacms,
                                rv.nodohfc,
                                rv.trobahfc,
                                rv.amplificador,
                                rv.tap,
                                rv.desmotv,
                                rv.codigotiporeq,
                                rv.desc_motivo,
                                rv.direccion,
                                rv.tip_ing,
                                rv.cmts,
                                rv.interface,
                                rv.scopesgroup,
                                rv.macstate,
                                rv.RxPwrdBmv,
                                rv.USPwr,
                                rv.USMER_SNR,
                                rv.DSPwr,
                                rv.DSMER_SNR,
                                rv.estadomdm,
                                rv.premium,
                                rv.dias,
                                '' as convergente,
                                rv.codmotv,
                                rv.desmotv,
                                rv.masiva,
                                rv.edoserv,
                                rv.zonal
                                FROM triaje.`averias_revisadas` rv
                                WHERE rv.estadomdm IN ('OK') AND rv.edoserv='Servicio Activo'
                                AND rv.tip_ing IN ('MALA SENAL/SIN SENAL','MALA TRANSFERENCIA','MALOS PARAMETROS','No Navega','TRABAJOS PROGRAMADOS','LENTITUD')
                                AND masiva='Individual'");

           /* #RETIRADO
                    $newData = array();

                    //Parametros RF 
                    $parametrosRF = new Parametrosrf;  
                    $paramDiagMasi_detalle = $parametrosRF->getMonitoreoAveriaRF();
                    $dataParametrosRF = $parametrosRF->getDecodeJsonNivelesRF($paramDiagMasi_detalle);

                foreach ($query as $q) {

                    if ($q->codreqmnt > 0) {
                        $estado["mensaje"] = "Averia Masiva - Problema PEXT";
                    }else{
                        $estado = Parametrosrf::getMonitoreoAveriasVSegunNivelesRF($q->codreqmnt,$q->Caida1,$q->Caida2,$q->Caida3,$q->macstate,
                                                (double)$q->USMER_SNR,(double)$q->USPwr,(double)$q->DSPwr,(double)$q->DSMER_SNR,$dataParametrosRF);
                    }
                                        
                    
                    if($estado["mensaje"]=="OK"){

                    $newData[] =  
                    (object)array(
                        'CODOFCADM'=> $q->codofcadm,
                        'CODREQ'=> $q->codreq,
                        'FECREG'=> $q->fecreg,
                        'ESTADO'=> $q->estado,
                        'CODCLI'=> $q->codcli,
                        'CODCTR'=> $q->codctr,
                        'DESNOMCTR'=> $q->desnomctr,
                        'NODO_CMS'=> $q->codnod,
                        'TROBA_CMS'=> $q->nroplano,
                        'NODO_HFC'=> $q->nodo,
                        'TROBA_HFC'=> $q->troba,
                        'AMPLIFICADOR'=> $q->codlex,
                        'TAP'=> $q->codtap,
                        'CODMOTV'=> $q->desmotv,
                        'TIPREQFIN'=> $q->codigotiporeq,
                        'DESOBSORDTRAB'=> $q->desc_motivo,
                        'TIPODEVIA'=> $q->tipodevia,
                        'NOMBREDELAVIA'=> $q->nombredelavia,
                        'NUMERO'=> $q->numero,
                        'PISO'=> $q->piso,
                        'INTERIOR'=> $q->interior,
                        'MANZANA'=> $q->manzana,
                        'LOTE'=> $q->lote,
                        'TIP_ING'=> $q->tip_ing,
                        'cmts'=> $q->cmts,
                        'interface'=> $q->interface,
                        'scopesgroup'=> $q->scopesgroup,
                        'macstate'=> $q->macstate,
                        'RxPwrdBmv'=> $q->RxPwrdBmv,
                        'USPwr'=> $q->USPwr,
                        'USMER_SNR'=> $q->USMER_SNR,
                        'DSPwr'=> $q->DSPwr,
                        'DSMER_SNR'=> $q->DSMER_SNR,
                        'EstadoMDM'=> $estado["mensaje"],
                        'PREMIUM'=> $q->premium,
                        'DIAS'=> $q->dias,
                        'CONVERGENTE'=> $q->convergente,
                        'CODMOTV'=> $q->codmotv,
                        'DESMOTV'=> $q->motivo,
                        'MASIVA'=> $q->masiva,
                        'EDOSERV'=> $q->edoserv,
                        'ZONAL'=> $q->zonal

                        );

                    }

                }
            #END
           */

            return collect($query);

        } catch(QueryException $ex){ 
            //dd($ex);
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    
    public function headings(): array
    {
        $cabecera = array(
            'CODOFCADM',
            'CODREQ',
            'FECREG',
            'ESTADO',
            'CODCLI',
            'CODCTR',
            'DESNOMCTR',
            'NODO_CMS',
            'TROBA_CMS',
            'NODO_HFC',
            'TROBA_HFC',
            'AMPLIFICADOR',
            'TAP',
            'CODMOTV',
            'TIPREQFIN',
            'DESOBSORDTRAB',
            'DIRECCION',
            'TIP_ING',
            'cmts',
            'interface',
            'scopesgroup',
            'macstate',
            'RxPwrdBmv',
            'USPwr',
            'USMER_SNR',
            'DSPwr',
            'DSMER_SNR',
            'EstadoMDM',
            'PREMIUM',
            'DIAS',
            'CONVERGENTE',
            'CODMOTV',
            'DESMOTV',
            'MASIVA',
            'EDOSERV',
            'ZONAL'
        );

        return $cabecera;
    }


}



?>