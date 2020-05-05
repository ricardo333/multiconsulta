<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
  
class AgendaFunctions {

     
    function getListaAgenda($filtroEstado,$filtroCodCli)
    {
        try {
 
            $listaAgenda = DB::select("SELECT a.*,b.turno,c.tipoturno FROM preagenda.preagenda a
                                        LEFT JOIN preagenda.rangohorario b
                                        on a.rangohorario=b.id
                                        left join preagenda.tipoturno c
                                        on a.tipocliagenda=c.id	
                                        where 1=1 $filtroEstado $filtroCodCli order by fecharegistroagenda asc
                                    ");
        } catch(QueryException $ex){ 
              //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
              //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $listaAgenda;
    }

    function getEstadosAgenda($filtroSW)
    {
        $listaEstados = DB::select("select * from preagenda.estados $filtroSW order by id asc");

        return $listaEstados;
    }

    function getQuiebreAgenda()
    {
        $listaQuiebres = DB::select("select * from preagenda.quiebre");

        return $listaQuiebres;
    }

    function registroGestionAgenda($data,$usuario)
    {
        $estado = htmlspecialchars($data["estado"]);
        $quiebre = htmlspecialchars($data["quiebre"]);
        $comentario = htmlspecialchars($data["observacion"]);
        $id = htmlspecialchars($data["idAgenda"]);

        try {
            DB::update("update preagenda.preagenda set estado='$estado',quiebre='$quiebre',comentario='$comentario' WHERE id=$id");

            DB::insert("insert IGNORE preagenda.agendas_mov  set 
                        id=null,idagenda=$id,estado='$estado',quiebre='$quiebre',comentario='$comentario',fechamov=now(),usuario='$usuario'");

        } catch(QueryException $ex){ 
                //dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
            
        }catch(\Exception $e){
                //dd($e->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        }

       
    }

    function getGestionMovByCodCli($idAgenda)
    {
        try {
            
            $detalleAgendasMov = DB::select("select * from preagenda.agendas_mov where idagenda=$idAgenda");

             
        } catch(QueryException $ex){ 
                //dd($ex->getMessage());  
                    //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
                return "error";
            
        }catch(\Exception $e){
                //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        }

        return $detalleAgendasMov;

    }
 
  
}