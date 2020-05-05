<?php

namespace App\Reportes\Excel\Agendas;

use DB;
use Excel;
use App\Http\Controllers\GeneralController;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgendaUltimaSemanaExcel extends GeneralController implements FromCollection,WithHeadings {

    

    public function queryAgendaUltimaSemana(){

        try {
            $query = DB::select("
                                    SELECT 
                                        a.id,a.codcli,a.`codserv`,a.`nodo`,a.`telefono1`,a.`telefono2`,a.`nameclient`,a.`codreq`,a.`comentario`,
                                        a.`fecha`,b.turno,c.tipoturno,a.`estado`,a.`quiebre`,a.`fecharegistroagenda`
                                    FROM preagenda.preagenda a
                                    LEFT JOIN preagenda.rangohorario b
                                        ON a.rangohorario=b.id
                                    LEFT JOIN preagenda.tipoturno c
                                        ON a.tipocliagenda=c.id
                                    WHERE DATEDIFF(NOW(),fecharegistroagenda)<=7"
                                );
 
            return $query;

        } catch(QueryException $ex){ 
            throw new HttpException(409,"Hubo un error en los datos, intente en un minuto por favor.");
        }
        //return $query;
    }


    public function collection()
    {
        return collect($this->queryAgendaUltimaSemana());
    }

    
    public function headings(): array
    {
        $cabecera = array(
            'ID',
            'CODCLI',
            'CODSER',
            'NODO',
            'TELEFONO1',
            'TELEFONO2',
            'NOMBRE',
            'CODREQ',
            'COMENTARIO',
            'FECHAAGENDA',
            'TURNO',
            'TIPOTURNO',
            'ESTADO',
            'QUIEBRE',
            'FECHAREGISTROAGENDA'
        );

        return $cabecera;
    }
    

}


?>