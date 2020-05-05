<?php 

namespace App\Functions;
use DB; 
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
  
class GestionFunctions {

    function getEstadoAlertas($filtro="")
    {
        try {
            $estados = DB::select("SELECT estado FROM alertasx.geoalertas_estados WHERE activo=1 $filtro GROUP BY 1");
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
        } 
        
        return $estados;
    }

    function getTecnicosGestion()
    {
        try {
            $tecnicos = DB::select("select nombre1 from alertasx.tecnicos order by nombre1 asc");
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
        } 
        
        return $tecnicos; 
       
    }

    function getCausaGestion()
    {
        try {
            $causas = DB::select("select * from catalogos.causaalert order by causa");
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
        } 
        
        return $causas; 
    }
    function getAreasResponsablesGestion()
    {
        try {
            $areasResponsables = DB::select("select * from catalogos.area_responsable");
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return [];
        } 
        
        return $areasResponsables; 
    }

    function registroGestionIndividual($parametros)
    {
          //dd(isset($parametros["codtecliq"]));
            //dd($parametros["modulo"]);
            
            
          try {
             #INICIO
                    DB::insert("insert into alertasx.gestion_alert value (
                                        ?,?,now(),?,?,?,?,?,?,?,?,?,?)",[
                                        isset($parametros["nodo"])? $parametros["nodo"] : "", 
                                        isset($parametros["troba"])? $parametros["troba"] : "", 
                                        isset($parametros["observaciones"])? $parametros["observaciones"] : "",
                                        isset($parametros["usuario"])? $parametros["usuario"] : "",
                                        isset($parametros["tecnico"])? $parametros["tecnico"] : "",
                                        isset($parametros["estado"])? $parametros["estado"] : "",
                                        isset($parametros["caidaAlcance"])? $parametros["caidaAlcance"] : "",
                                        isset($parametros["servicioAfectado"])? $parametros["servicioAfectado"] : "",
                                        isset($parametros["numRequerimiento"])? $parametros["numRequerimiento"] : 0,
                                        isset($parametros["remedy"])? $parametros["remedy"] : "",
                                        isset($parametros["causa"])? $parametros["causa"] : 0,
                                        isset($parametros["areaResponsable"])? $parametros["areaResponsable"] : 0
                                        ]);
                    if (isset($parametros["modulo"])) {  

                        

                         DB::insert("insert into dbpext.liquidacionTTPP 
                                        value (null,?,?,?,now(),?,?,?,?,?,?,?,?,?,?)
                                    ",
                                    [
                                        $parametros["idttpp"],
                                        isset($parametros["nodo"])? $parametros["nodo"] : "",
                                        isset($parametros["troba"])? $parametros["troba"] : "",
                                        isset($parametros["observaciones"])? $parametros["observaciones"] : "",
                                        isset($parametros["usuario"])? $parametros["usuario"] : "",
                                        isset($parametros["tecnico"])? $parametros["tecnico"] : "",
                                        isset($parametros["estado"])? $parametros["estado"] : "",
                                        isset($parametros["caidaAlcance"])? $parametros["caidaAlcance"] : "",
                                        isset($parametros["servicioAfectado"])? $parametros["servicioAfectado"] : "",
                                        isset($parametros["numRequerimiento"])? $parametros["numRequerimiento"] : 0,
                                        isset($parametros["remedy"])? $parametros["remedy"] : "",
                                        isset($parametros["causa"])? $parametros["causa"] : 0,
                                        isset($parametros["areaResponsable"])? $parametros["areaResponsable"] : 0
                                    ]);
                    }

                    if (isset($parametros["numRequerimiento"])) {
                        if ($parametros["numRequerimiento"] != 0) {
                            if(trim($parametros["estado"]) == "Enviada:ATENTO para liquidar" || trim($parametros["estado"]) == "Enviada:COT para liquidar"){
                                    DB::insert("insert into alertasx.datliq_masiva 
                                                    value (?,?,?,?,?,?,?,?,now())",
                                                [
                                                    $parametros["numRequerimiento"],
                                                    $parametros["codtecliq"],
                                                    $parametros["codliq"],
                                                    $parametros["detliq"],
                                                    $parametros["observaciones"],
                                                    $parametros["afectacion"],
                                                    $parametros["contrata"],
                                                    $parametros["nombretecnico"]
                                                ]);
                                
                            }
                        }
                    }
             #END
          } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
            throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
             
            
        }catch(\Exception $e){
            // dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 
         
       
    }
    function registroGestionMasiva($parametros)
    {
           
          try {
             #INICIO
                    
                    for ($i=0; $i < count($parametros["trobas"]) ; $i++) { 
                            $nodom=substr($parametros["trobas"][$i],0,2);
                            $trobam=substr($parametros["trobas"][$i],2,4); 
                            $requQuery = DB::select("select codreqmnt from dbpext.masivas_temp where codnod='$nodom' and nroplano='$trobam'");
                            $numreq = isset($requQuery[0]) ?  $requQuery[0]->codreqmnt : 0 ;
                           //dd($numreq);

                            DB::insert("insert into alertasx.gestion_alert value (
                                ?,?,now(),?,?,?,?,?,?,?,?,?,?)",[
                                                                    $nodom, 
                                                                    $trobam, 
                                                                    isset($parametros["observaciones"])? $parametros["observaciones"] : "",
                                                                    isset($parametros["usuario"])? $parametros["usuario"] : "",
                                                                    isset($parametros["tecnico"])? $parametros["tecnico"] : "",
                                                                    isset($parametros["estado"])? $parametros["estado"] : "",
                                                                    isset($parametros["caidaAlcance"])? $parametros["caidaAlcance"] : "",
                                                                    isset($parametros["servicioAfectado"])? $parametros["servicioAfectado"] : "",
                                                                    $numreq,
                                                                    isset($parametros["remedy"])? $parametros["remedy"] : "",
                                                                    isset($parametros["causa"])? $parametros["causa"] : 0,
                                                                    isset($parametros["areaResponsable"])? $parametros["areaResponsable"] : 0
                                                                ]);

                             
                            if ($numreq > 0) {
                                if(trim($parametros["estado"]) == "Enviada:ATENTO para liquidar" || trim($parametros["estado"]) == "Enviada:COT para liquidar"){
                                        DB::insert("insert into alertasx.datliq_masiva 
                                                        value (?,?,?,?,?,?,?,?,now())",
                                                    [
                                                        $numreq,
                                                        $parametros["codtecliq"],
                                                        $parametros["codliq"],
                                                        $parametros["detliq"],
                                                        $parametros["observaciones"],
                                                        $parametros["afectacion"],
                                                        $parametros["contrata"],
                                                        $parametros["nombretecnico"]
                                                    ]);
                                    
                                }
                            }
                            

                    }
                   
                    
             #END
          } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
             
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 
         
       
    }

    function gestListaRegistros($whereQuery)
    {
        try {

            $lista = DB::select("select * from alertasx.gestion_alert $whereQuery order by fechahora desc");
            return $lista;
            
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
             
            
        }catch(\Exception $e){
             // dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 
     
    }

    function getNodoTrobas()
    {
        $lista = DB::select("select concat(trim(codnod),trim(nroplano)) as troba from cms.req_pend_macro_final group by 1");
        return $lista;
    }

    function detalleMasiva($codigoRequerimiento)
    {
        $masiva = DB::select("select * from alertasx.`datliq_masiva` WHERE codreqmnt=$codigoRequerimiento");
        return $masiva;
    }

    function getGestionClienteCuarentenaByID($IDCLIENTECRM)
    {
        $clienteCuarentena = DB::select("select * from alertasx.gestion_cuarentena where idcliente=$IDCLIENTECRM order by idbitacora desc limit 1");
        return $clienteCuarentena;
    }

    function registroGestionCuarentenaIndividual($parametros)
    {
         
        try {

             DB::insert("insert into alertasx.gestion_cuarentena value (null,?,?,?,?,now())",
                    [
                        $parametros["idClienteCRM"],
                        $parametros["tipoDeAveria"],
                        $parametros["observaciones"],
                        $parametros["usuario"]
                    ]); 
               
        } catch(QueryException $ex){ 
            // dd($ex->getMessage());  
           throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
             
            
        }catch(\Exception $e){
             // dd($e->getMessage());  
             throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            
        } 

    }

    function getGestionCuarentenasByIdClienteCrm($idClienteCrm)
    {
        $lista = DB::select("select * from alertasx.gestion_cuarentena where idcliente=? order by fechahora desc",[$idClienteCrm]);

        return $lista;
    }
   

}