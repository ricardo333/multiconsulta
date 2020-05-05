<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class EtiquetadoPuertosFunctions {

    function getListCmts()
    {
        try {
            $lista = DB::select("SELECT cmts FROM ccm1.cmts_ip ORDER BY cmts");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $lista;
    }

    function getListCmtsxFiltro($filtroCmts)
    {
        try {
            $estados = DB::select("SELECT a.cmts,a.interface,a.description AS trobas 
                                    FROM catalogos.etiqueta_puertos a 
                                    WHERE description<>'ELIMINAR' and cmts<>'' $filtroCmts
                                    group BY a.cmts,a.interface
                                    ORDER BY a.cmts,a.interface");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getProcesarEtiquetadoPuertos($etiquetadoPuertos, $cmtsfil)
    {

        try {
           #INICIO
                
                $parametrosColores = ParametroColores::getEtiquetadoPuertosParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES->etiquetado->colores;
                //dd($colores);
                
                $cantidadEtiquetadoPuertos = count($etiquetadoPuertos);
                $acumulandoRespuestaEtiquetadoPuertos = array();
                //$contadorId = 0;

                for ($i=0; $i < $cantidadEtiquetadoPuertos ; $i++) {
                    
                    $etiquetadoPuertos[$i]->cmtsfil = $cmtsfil;
                    $acumulandoRespuestaEtiquetadoPuertos[] = $this->procesoEtiquetadoPuertosGeneral($etiquetadoPuertos[$i],$colores);
                    //$contadorId++;
                }
                //dd($acumulandoRespuestaEtiquetadoPuertos);
                return $acumulandoRespuestaEtiquetadoPuertos;
           #END
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
 

    }

    private function procesoEtiquetadoPuertosGeneral($etiquetadoPuertos,$colores)
    { 
        $etiquetadoPuertos->fondo = $colores[1]->background;
        $etiquetadoPuertos->letra = $colores[1]->color;

        if ( $etiquetadoPuertos->trobas == 'VACIO' || $etiquetadoPuertos->trobas == 'vacio' || trim($etiquetadoPuertos->trobas) == '' || trim($etiquetadoPuertos->trobas) == 'VA CIO' || strlen(trim($etiquetadoPuertos->trobas))<7 ){
            $etiquetadoPuertos->fondoEtiquetadoPuertos = $colores[0]->background;
            $etiquetadoPuertos->letraEtiquetadoPuertos = $colores[0]->color;
        }

        return $etiquetadoPuertos;
                    
    }

    function actualizarEtiquetadoPuertos($request)
    {

        try {
            
            $update_exception = DB::update("update catalogos.etiqueta_puertos set description ='$request->n' where cmts='$request->r' and interface='$request->t'");

        } catch(QueryException $ex){ 
            return false;
        }

    }

}