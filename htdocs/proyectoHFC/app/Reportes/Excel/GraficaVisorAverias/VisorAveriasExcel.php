<?php

namespace App\Reportes\Excel\GraficaVisorAverias;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class VisorAveriasExcel extends GeneralController implements FromCollection, WithHeadings {

    protected $nodo;
    protected $troba;

    function __construct($nodo,$troba) {
        $this->nodo = $nodo;
        $this->troba = $troba;
    }
    
    public function queryVisorAverias($nodo,$troba)
    {
        try {

            $query = DB::select("SELECT 
                                a.codofcadm AS CODOFCADM,a.codreq AS CODREQ,a.fecreg AS FECREG,a.estado AS ESTADO,a.codcli AS CODCLI,a.codctr AS CODCTR,a.desnomctr AS DESNOMCTR,a.nodocms AS NODO_CMS,a.trobacms AS TROBA_CMS,a.nodohfc AS NODO_HFC,a.trobahfc AS TROBA_HFC,a.amplificador AS AMPLIFICADOR,
                                a.tap AS TAP,a.desmotv AS DESMOTV,a.codigotiporeq AS TIPREQFIN,a.desc_motivo AS DESOBSORDTRAB,a.Direccion AS DIRECCION,a.tip_ing AS TIP_ING,a.cmts AS cmts,a.interface AS interface,a.scopesgroup AS scopesgroup,a.macstate AS macstate,a.RxPwrdBmv AS RxPwrdBmv,a.USPwr AS USPwr,a.USMER_SNR AS USMER_SNR,
                                a.DSPwr AS DSPwr,a.DSMER_SNR AS DSMER_SNR,a.estadomdm AS EstadoMDM,a.premium AS PREMIUM,IF(a.dias=0,'0',a.dias) AS DIAS,a.codmotv AS CODMOTV,a.masiva AS MASIVA,a.edoserv AS EDOSERV,a.zonal AS ZONAL,
                                a.codctr_final AS CODCTR_FINAL,a.ultimagestion AS 'ULTIMA_GESTION_TROBA',a.TipoRuido AS TIPORUIDO,a.observacionescms AS OBSERVACIONESCMS,a.motivotransferencia AS MOTIVOTRANSFERENCIA,a.telef1 AS TELEF1,a.telef2 AS TELEF2,a.telef3 AS TELEF3
                                FROM triaje.averias_revisadas a WHERE a.nodocms='".$nodo."' AND DATEDIFF(NOW(),a.fecreg)=0");
            
            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryVisorAverias($this->nodo,$this->troba));
    }


    public function headings(): array
    {

        $cabecera = array('CODOFCADM','CODREQ','FECREG','ESTADO','CODCLI','CODCTR','DESNOMCTR','NODO_CMS','TROBA_CMS','NODO_HFC','TROBA_HFC','AMPLIFICADOR','TAP','DESMOTV','TIPREQFIN','DESOBSORDTRAB','DIRECCION','TIP_ING','cmts','interface','scopesgroup','macstate','RxPwrdBmv','USPwr','USMER_SNR','DSPwr','DSMER_SNR','EstadoMDM','PREMIUM','DIAS','CODMOTV','MASIVA','EDOSERV','ZONAL','CODCTR_FINAL','TIPORUIDO','OBSERVACIONESCMS','MOTIVOTRANSFERENCIA','TELEF1','TELEF2','TELEF3');
        return $cabecera;

    }
 

}

?>