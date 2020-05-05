<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;
use App\Administrador\GestionCuarentena;
use Symfony\Component\HttpKernel\Exception\HttpException;
  
class GestionCuarentenaFunctions {

    function tipo()
    {
        $lista = DB::select("SELECT * FROM zz_new_system.tipo_cuarentenas WHERE estado='Activo'");
        return $lista;
    }

    function listaPrincipal($jefatura,$estado)
    {
        $lista = DB::select("select * FROM zz_new_system.`gestion_cuarentena` a  $jefatura $estado");
        return $lista;
    }

    function limpiaCodClientesTemporales($idClienteActivo)
    {
        try {
            DB::delete("DELETE FROM zz_new_system.`temporal_cuarentenas` WHERE idusuario=?",[$idClienteActivo]);
        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
            
        }catch(\Exception $e){
             //dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
        }  
    }

    function registroCodClientesTemporales($queryClientesInsert)
    {
        //  dd($queryClientesInsert);
         
        try {
               // $query = utf8_encode($queryClientesInsert);
                 
                DB::insert("insert into zz_new_system.`temporal_cuarentenas` VALUES $queryClientesInsert"); 
             
        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
           // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");

            return false;
            
        }catch(\Exception $e){
             // dd($e->getMessage());  
           // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           return false;
        } 

        return true;
 
    }

    function getClientesCuarentenasParaRegistro($idClienteActivo)
    {
        try {
            $clientesTem =  DB::select("  select 
                                    tc.nombre,tc.estado,tc.cuadroMando,tc.tipo,tc.entidad,tc.codcli,nc.NODO,nc.TROBA,jt.jefatura, nc.SERVICEPACKAGECRMID, 
                                    nc.SCOPESGROUP,tc.fechaInicio,tc.fechaFin
                                    FROM zz_new_system.temporal_cuarentenas tc
                                    LEFT JOIN multiconsulta.nclientes nc
                                    ON tc.codcli=nc.IDCLIENTECRM
                                    LEFT JOIN catalogos.jefaturas jt
                                    ON nc.NODO = jt.nodo
                                    WHERE tc.idusuario = ?
                                    ",[$idClienteActivo]
                                );

            return $clientesTem;
        }catch(QueryException $ex){ 
            //dd($ex->getMessage());  
          throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
       }catch(\Exception $e){
            // dd($e->getMessage());  
          throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
         
       } 
        
 
    }


}