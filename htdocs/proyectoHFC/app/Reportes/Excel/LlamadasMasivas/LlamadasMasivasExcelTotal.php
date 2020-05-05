<?php

namespace App\Reportes\Excel\LlamadasMasivas;

use DB;
use Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Http\Controllers\GeneralController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
 
class LlamadasMasivasExcelTotal extends GeneralController implements FromCollection, WithHeadings {

    protected $jefatura;
    protected $top;
    protected $nodo;

    function __construct($jefatura,$top,$nodo) {
        $this->jefatura = $jefatura;
        $this->top = $top;
        $this->nodo = $nodo;
    }

    public function queryLlamadasTroba($filtroJefatura,$filtroTop,$filtroNodo)
    {
        try {
            
            $query = DB::select("
                                SELECT zo.jefatura,a.nodo,a.troba,a.cant,a.ultimallamada,b.codreqmnt,
                                (SELECT SUM(aver) AS aver FROM catalogos.`averias_resum` WHERE nodo=a.nodo AND troba=a.troba AND DATEDIFF(NOW(),dia)>=0) AS aver 
                                FROM alertasx.alertas_dmpe_view a 
                                LEFT JOIN dbpext.masivas_temp b ON a.nodo=b.codnod AND a.troba=b.nroplano 
                                LEFT JOIN catalogos.jefaturas zo ON a.nodo=zo.nodo 
                                LEFT JOIN catalogos.top100200 t ON a.nodo=t.nodo AND a.troba=t.troba 
                                WHERE a.nodo<>'Nodo' AND SUBSTR(a.troba,1,1)<>'D' AND a.nodo<>'' AND zo.jefatura <>'' $filtroJefatura $filtroTop $filtroNodo AND zo.jefatura NOT IN ('PROV_PUN','PROV_SUR','PROV_SMA','PROV_IQU','PROV_JUN') 
                                ORDER BY a.cant DESC");

            return collect($query);
            //dd($query);

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }

    }

    public function collection()
    {
        return collect($this->queryLlamadasTroba($this->jefatura, $this->top, $this->nodo));
    }


    public function headings(): array
    {
        
        $cabecera = array('jefatura','nodo','troba','cant','fecha_fin','codmasiva','aver');
        return $cabecera;

    }
 

}

?>