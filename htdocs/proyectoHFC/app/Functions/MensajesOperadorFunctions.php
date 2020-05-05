<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;

class MensajesOperadorFunctions {

    function registroMensajesOperador($dataClientes)
    {
        $cantidadClientes = count($dataClientes);
        try {
            for ($i=0; $i < $cantidadClientes ; $i++) {  
                DB::insert("insert ignore into catalogos.analgesico VALUES(?,?)",[$dataClientes[$i][0],$dataClientes[$i][1]]);
                //DB::insert("insert ignore into catalogos.analgesico_test_b VALUES(?,?)",[$dataClientes[$i][0],$dataClientes[$i][1]]);
            }
        }catch(QueryException $ex){ 
            return false;
        }catch(\Exception $e){
            return false;
        }

        return true;
 
    }

    function eliminarMensajesOperador($clientesHaEliminar)
    {
        $cantidadClientes = count($clientesHaEliminar);
        try {
            for ($i=0; $i < $cantidadClientes ; $i++) {
                $eliminar = DB::delete("delete FROM catalogos.analgesico WHERE ClienteCms='$clientesHaEliminar[$i]'");
                //$eliminar = DB::delete("delete FROM catalogos.analgesico_test_b WHERE ClienteCms='$clientesHaEliminar[$i]'");
            }
        }catch(QueryException $ex){ 
            return false;
        }catch(\Exception $e){
            return false;
        }

        return true;
 
    }

    function actualizarMensajesOperador()
    {
        try {
             
            //$eliminaMensajesOperadorTemp = DB::delete("delete from catalogos.analgesico_c where mensaje='ELIMINAR' or ClienteCms=0");
            //$renombraHist = DB::statement("rename table catalogos.analgesico to catalogos.analgesico_d,catalogos.analgesico_c to catalogos.analgesico,catalogos.analgesico_d to catalogos.analgesico_c");
            
            //$eliminaMensajesOperadorTemp = DB::delete("delete from catalogos.analgesico_test_b where mensaje='ELIMINAR' or ClienteCms=0");
            //$renombraHist = DB::statement("rename table catalogos.analgesico_test_a to catalogos.analgesico_test_a_temp,catalogos.analgesico_test_b to catalogos.analgesico_test_a,catalogos.analgesico_test_a_temp to catalogos.analgesico_test_b");

        } catch(QueryException $ex){ 
            return false;
        }

    }

}