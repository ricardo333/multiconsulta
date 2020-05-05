<?php 

namespace App\Functions;

use DB;  
use Illuminate\Database\QueryException;
 

class ValidacionServiciosFunctions {

    function limpiaCodClientesTemporalesByIdUser($idClienteActivo)
    {
        try {
            DB::delete("DELETE FROM zz_new_system.`temporal_clientes_codigos` WHERE idusuario=?",[$idClienteActivo]);
        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
            
        }catch(\Exception $e){
             // dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
        }  
    }

    function registroCodClientesTemporalesByIdUser($dataClientes,$idClienteActivo)
    {
        $cantidadClientes = count($dataClientes);
         
        try {
            for ($i=0; $i < $cantidadClientes ; $i++) {  
                DB::insert("insert into zz_new_system.`temporal_clientes_codigos` VALUES(?,?)",[$dataClientes[$i],$idClienteActivo]);
            } 
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
 
    function getRegistrosCodClientesTemporalesByIdUser($idClienteActivo)
    {
      $clientesTem =  DB::select("select * from zz_new_system.`temporal_clientes_codigos` where idusuario=?",[$idClienteActivo]);
      return $clientesTem;
    }

    function limpiaMacClientesTemporalesByIdUser($idClienteActivo)
    {
        try {
            DB::delete("DELETE FROM zz_new_system.`temporal_clientes_mac` WHERE idusuario=?",[$idClienteActivo]);
        } catch(QueryException $ex){ 
             // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
 
            
        }catch(\Exception $e){
             // dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
           
        }  
    }

    function registroMacClientesTemporalesByIdUser($dataMacaddress,$idClienteActivo)
    {
        $cantidadClientes = count($dataMacaddress);
        //replace(replace(macaddress,'.',''),':','') as 'macaddress',idusuario
         
        try {
            for ($i=0; $i < $cantidadClientes ; $i++) {  
                $MacAddress =  str_replace(":","",str_replace(".","",$dataMacaddress[$i]));
                DB::insert("insert into zz_new_system.`temporal_clientes_mac` VALUES(?,?)",[$MacAddress,$idClienteActivo]);
            } 
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
    function getRegistrosMacClientesTemporalesByIdUser($idClienteActivo)
    {
      $clientesTem =  DB::select("select * from zz_new_system.`temporal_clientes_mac` where idusuario=?",[$idClienteActivo]);
      return $clientesTem;
    }


}