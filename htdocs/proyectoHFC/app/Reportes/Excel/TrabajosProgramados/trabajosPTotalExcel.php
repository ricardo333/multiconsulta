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
 
class trabajosPTotalExcel extends GeneralController implements FromCollection, WithHeadings {
 
  
    private function queryTrabajosPTotales(){
 
         
        try { 
 
            $listaTrabaosProgTotal = DB::select("
                                        SELECT a.ITEM,b.jefatura,a.NODO,a.TROBA,a.DESNODO,a.DEPARTAMENTO,c.usuario AS USUARIOAPERTURANOC,a.CONTRATAAPERTURA,a.NOMBRETECNICOAPERTURA,
                                        a.CARNETTECNICOAPERTURA,a.CELULARTECNICOAPERTURA,a.SUPERVISORCONTRATA,a.CELULARSUPERVISORCONTRATA,a.FECHAAPERTURA,a.HORAAPERTURA,
                                        LEFT(REPLACE(REPLACE(REPLACE(REPLACE(a.OBSERVACIONAPERTURA,',',' '),'
                                        ',' '),';',' '),'       ',' '),LENGTH(a.OBSERVACIONAPERTURA)-5) AS  OBSERVACIONAPERTURA,
                                        a.SUPERVISORTDP,a.CELULARSUPERVISORTDP,
                                        REPLACE(REPLACE(REPLACE(a.AMP,',',' '),';',' '),'       ',' ')  AS AMP,
                                        a.TIPODETRABAJO,a.AFECTACION,a.FINICIO,a.HINICIO,a.HTERMINO,a.HORARIO,a.CORTESN,
                                        REPLACE(REPLACE(REPLACE(REPLACE(a.REMEDY,',',' '),'
                                        ',' '),';',' '),'       ',' ') as REMEDY,
                                        a.FECHACANCELA,d.usuario AS USUARIOCANCELA,
                                        LEFT(REPLACE(REPLACE(REPLACE(REPLACE(a.OBSERVACIONCANCELA,',',' '),'
                                        ',' '),';',' '),'       ',' '),LENGTH(a.OBSERVACIONCANCELA)-5) AS  OBSERVACIONCANCELA,
                                        a.ESTADO,
                                        LEFT(REPLACE(REPLACE(REPLACE(REPLACE(a.OBSERVACIONREGISTRO,',',' '),'
                                        ',' '),';',' '),'       ',' '),LENGTH(a.OBSERVACIONREGISTRO)-5) AS  OBSERVACIONREGISTRO,
                                        a.HORAREGISTRO,a.USUARIOREGISTRO,a.FECHAREGISTRO,a.REQCMS,e.usuario AS USUARIOCIERRE,a.CONTRATACIERRE,a.CARNETTECNICOCIERRE,a.NOMBRETECNICOCIERRE,
                                        REPLACE(REPLACE(REPLACE(REPLACE(a.CELULARTECNICOCIERRE,',',' '),'
                                        ',' '),';',' '),'       ',' ') As CELULARTECNICOCIERRE ,
                                        a.FECHACIERRE,a.HORACIERRE,a.ENVIOCIERRE,a.ELEMENTOTRABAJADO,
                                        LEFT(REPLACE(REPLACE(REPLACE(REPLACE(a.OBSERVACIONCIERRE,',',' '),'
                                        ',' '),';',' '),'       ',' '),LENGTH(a.OBSERVACIONCIERRE)-5) AS  OBSERVACIONCIERRE,
                                        a.TROBASHIJAS,
                                        a.ESTADOCMS,
                                        a.INCIDENCIASTTP,
                                        dl.`codreqmnt` AS NUMREQ,
                                        dl.codliq AS CODIGODELIQUIDACION,
                                        dl.detliq AS DETALLEDELIQUIDACION,
                                        dl.fechahora AS FECHALIQUIDA,
                                        dl.observacion AS OBSERVALIQUIDA,
                                        dl.contrata AS CONTRATALIQUIDA,
                                        dl.nombretecnico AS NOMBRETECNICOLIQUIDA
                                        FROM dbpext.trabajos_programados_noc a
                                        LEFT JOIN catalogos.jefaturas b
                                        ON a.nodo=b.nodo
                                        LEFT JOIN ccm1.usuarios c
                                        ON a.USUARIOAPERTURANOC=c.idusuario
                                        LEFT JOIN ccm1.usuarios d
                                        ON a.USUARIOCANCELA=d.idusuario
                                        LEFT JOIN ccm1.usuarios e
                                        ON a.USUARIOCIERRE=e.idusuario
                                        LEFT JOIN dbpext.liquidacionTTPP lq
                                        ON a.ITEM=lq.idttpp
                                        LEFT JOIN alertasx.`datliq_masiva` dl
                                        ON lq.numreq=dl.`codreqmnt`
                                        WHERE DATEDIFF(NOW(),FECHAREGISTRO)<=30
                                        GROUP BY a.item
                                        ");

            //dd($listaTrabaosProgTotal);

            $dataResult = array();

            foreach ($listaTrabaosProgTotal as $data) {
 

               $dataResult[] = array(
                                    "ITEM" => $data->ITEM,
                                    "JEFATURA" => $data->jefatura,
                                    "NODO" => $data->NODO,
                                    "TROBA" => $data->TROBA,
                                    "DESNODO" => $data->DESNODO,
                                    "DEPARTAMENTO" => $data->DEPARTAMENTO,
                                    "USUARIOAPERTURANOC" => $data->USUARIOAPERTURANOC,
                                    "CONTRATAAPERTURA" => $data->CONTRATAAPERTURA,
                                    "NOMBRETECNICOAPERTURA" => $data->NOMBRETECNICOAPERTURA,
                                    "CARNETTECNICOAPERTURA" => $data->CARNETTECNICOAPERTURA,
                                    "CELULARTECNICOAPERTURA" => $data->CELULARTECNICOAPERTURA,
                                    "SUPERVISORCONTRATA" => $data->SUPERVISORCONTRATA,
                                    "CELULARSUPERVISORCONTRATA" => $data->CELULARSUPERVISORCONTRATA,
                                    "FECHAAPERTURA" => $data->FECHAAPERTURA,
                                    "HORAAPERTURA" => $data->HORAAPERTURA,
                                    "OBSERVACIONAPERTURA" => $data->OBSERVACIONAPERTURA,
                                    "SUPERVISORTDP" => $data->SUPERVISORTDP,
                                    "CELULARSUPERVISORTDP" => $data->CELULARSUPERVISORTDP,
                                    "AMP" => $data->AMP,
                                    "TIPODETRABAJO" => $data->TIPODETRABAJO,
                                    "AFECTACION" => $data->AFECTACION,
                                    "FINICIO" => $data->FINICIO,
                                    "HINICIO" => $data->HINICIO,
                                    "HTERMINO" => $data->HTERMINO,
                                    "HORARIO" => $data->HORARIO,
                                    "CORTESN" => $data->CORTESN,
                                    "REMEDY" => $data->REMEDY,
                                    "FECHACANCELA" => $data->FECHACANCELA,
                                    "USUARIOCANCELA" => $data->USUARIOCANCELA,
                                    "OBSERVACIONCANCELA" => $data->OBSERVACIONCANCELA,
                                    "ESTADO" => $data->ESTADO,
                                    "OBSERVACIONREGISTRO" => $data->OBSERVACIONREGISTRO,
                                    "HORAREGISTRO" => $data->HORAREGISTRO,
                                    "USUARIOREGISTRO" => $data->USUARIOREGISTRO,
                                    "FECHAREGISTRO" => $data->FECHAREGISTRO,
                                    "REQCMS" => $data->REQCMS,
                                    "USUARIOCIERRE" => $data->USUARIOCIERRE,
                                    "CONTRATACIERRE" => $data->CONTRATACIERRE,
                                    "CARNETTECNICOCIERRE" => $data->CARNETTECNICOCIERRE,
                                    "NOMBRETECNICOCIERRE" => $data->NOMBRETECNICOCIERRE,
                                    "CELULARTECNICOCIERRE" => $data->CELULARTECNICOCIERRE,
                                    "FECHACIERRE" => $data->FECHACIERRE,
                                    "HORACIERRE" => $data->HORACIERRE,
                                    "ENVIOCIERRE" => $data->ENVIOCIERRE,
                                    "ELEMENTOTRABAJADO" => $data->ELEMENTOTRABAJADO,
                                    "OBSERVACIONCIERRE" => $data->OBSERVACIONCIERRE,
                                    "TROBASHIJAS" => $data->TROBASHIJAS,
                                    "ESTADOCMS" => $data->ESTADOCMS,
                                    "INCIDENCIASTTP" => $data->INCIDENCIASTTP,
                                    "CODIGODELIQUIDACION" => $data->CODIGODELIQUIDACION,
                                    "DETALLEDELIQUIDACION" => $data->DETALLEDELIQUIDACION,
                                    "FECHALIQUIDA" => $data->FECHALIQUIDA,
                                    "OBSERVALIQUIDA" => $data->OBSERVALIQUIDA,
                                    "CONTRATALIQUIDA" => $data->CONTRATALIQUIDA,
                                    "NOMBRETECNICOLIQUIDA" => $data->NOMBRETECNICOLIQUIDA,
                                    "NUMREQ" => $data->NUMREQ
               );
            }
            //dd($dataResult); 
            
            return $dataResult;
           

        } catch(QueryException $ex){ 
           //dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
       }catch(\Exception $e){
             //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
       }  
   
    }

     
    public function collection()
    { 
        //dd($this->item."---".$this->nodo."---".$this->troba);
        return collect($this->queryTrabajosPTotales());
        
        
    }

    
    public function headings(): array
    {
        
        $cabecera = array(
                            'ITEM',
                            'JEFATURA',
                            'NODO',
                            'TROBA',
                            'DESNODO',
                            'DEPARTAMENTO',
                            'USUARIOAPERTURANOC',
                            'CONTRATAAPERTURA',
                            'NOMBRETECNICOAPERTURA',
                            'CARNETTECNICOAPERTURA',
                            'CELULARTECNICOAPERTURA',
                            'SUPERVISORCONTRATA',
                            'CELULARSUPERVISORCONTRATA',
                            'FECHAAPERTURA',
                            'HORAAPERTURA',
                            'OBSERVACIONAPERTURA',
                            'SUPERVISORTDP',
                            'CELULARSUPERVISORTDP',
                            'AMP',
                            'TIPODETRABAJO',
                            'AFECTACION',
                            'FINICIO',
                            'HINICIO',
                            'HTERMINO',
                            'HORARIO',
                            'CORTESN',
                            'REMEDY',
                            'FECHACANCELA',
                            'USUARIOCANCELA',
                            'OBSERVACIONCANCELA',
                            'ESTADO',
                            'OBSERVACIONREGISTRO',
                            'HORAREGISTRO',
                            'USUARIOREGISTRO',
                            'FECHAREGISTRO',
                            'REQCMS',
                            'USUARIOCIERRE',
                            'CONTRATACIERRE',
                            'CARNETTECNICOCIERRE',
                            'NOMBRETECNICOCIERRE',
                            'CELULARTECNICOCIERRE',
                            'FECHACIERRE',
                            'HORACIERRE',
                            'ENVIOCIERRE',
                            'ELEMENTOTRABAJADO',
                            'OBSERVACIONCIERRE',
                            'TROBASHIJAS',
                            'ESTADOCMS',
                            'INCIDENCIASTTP',
                            'CODIGODELIQUIDACION',
                            'DETALLEDELIQUIDACION',
                            'FECHALIQUIDA',
                            'OBSERVALIQUIDA',
                            'CONTRATALIQUIDA',
                            'NOMBRETECNICOLIQUIDA',
                            'NUMREQ'
                        );
        

       
  

        return $cabecera;
    }

  

}