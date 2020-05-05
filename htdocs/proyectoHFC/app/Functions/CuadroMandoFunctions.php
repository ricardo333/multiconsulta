<?php 

namespace App\Functions;
use DB;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CuadroMandoFunctions {

    function getCuadroMandoList($filtroCategoria)
    {
        try {
            /*
            $lista = DB::select("select * from alertasx.dashboard 
                                WHERE SUBSTR(tipo,1,14) NOT IN ('MOVISTAR TOTAL','CLIENTES CRITI','TOTAL CLIENTES','ALTAS NUEVAS: ') 
                                AND tipo NOT LIKE 'AVERIAS%'");
                                */
            $lista = DB::select("select * from zz_new_system.dashboard $filtroCategoria");

            return $lista;
        } catch(QueryException $ex){ 
           return "error";
        }catch(\Exception $e){
            return "error";
        } 
         
    }


    
    function procesoListaCuadroMando($listaMando)
    {

        //$parametrosColores = new ParametroColores; 
        //$coloresMasivaCms = $parametrosColores::getMasivaCmsParametros()->COLORES;

        for ($i=0; $i < count($listaMando); $i++) {

            $listaMando[$i]->id = $i+1;

            //$nodo = $masiva[$i]->nodo;

            $cant = $listaMando[$i]->cant;
            $clientes = $listaMando[$i]->clientes;

            if($cant == null){
                $listaMando[$i]->cant = 0;
            }else{
                $listaMando[$i]->cant = $cant;
            }

            if($clientes == null){
                $listaMando[$i]->clientes = 0;
            }else{
                $listaMando[$i]->clientes = $clientes;
            }


            /////////
            if(trim(substr($listaMando[$i]->tipo,0,30))=='MEGA MASIVA TROBAS CAIDAS Nodo'){
                $motivo="cuadroMando";
                $url="/administrador/caidas";
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,13)=="CAIDAS TROBAS"){
                $nodo="caidas_masivas";
                $motivo="cuadroMando";
                $url="/administrador/caidas";
                $listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,19)=="CAIDAS AMPLIFICADOR"){
                $nodo="caidas_amplificador";
                $motivo="cuadroMando";
                $url="/administrador/caidas";
                $listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,13)=="TOTAL AVERIAS"){
                $nodo="Total_Averias";
                $motivo="cuadroMando";
                $url="/administrador/ingreso-averias";
                $listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,17)=="CMTS PROBLEMAS IP"){
                $nodo="Monitor_Ips";
                $motivo="cuadroMando";
                $url="/administrador/monitor-ips";
                $listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,17)=="PUERTOS SATURADOS"){

                if (substr($listaMando[$i]->tipo, -6)=="(CASA)") {
                    $nodo="CASA";
                } else {
                    $nodo="CISCO";
                }
                
                $motivo="cuadroMando";
                $url="/administrador/saturacion-down";
                $listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }

            
            if(substr($listaMando[$i]->tipo,0,24)=="MOVISTAR TOTAL (Averias)"){
                $codmotv=substr($listaMando[$i]->tipo,strlen($listaMando[$i]->tipo)-4);
                $listaMando[$i]->nodo = $codmotv;
            }

            if(substr($listaMando[$i]->tipo,0,16)=="MOVISTAR TOTAL :"){
                $tipox=trim(substr($listaMando[$i]->tipo,16,100));
                $listaMando[$i]->nodo = $tipox;
            }

            if(substr($listaMando[$i]->tipo,0,18)=="MASIVAS PENDIENTES"){

                if (substr($listaMando[$i]->tipo, -5)=="TOTAL") {
                    $nodo="Total";
                    $listaMando[$i]->nodo = $nodo;
                } 
                /*
                else {
                    $nodo=substr($listaMando[$i]->tipo, -2);
                }
                */
                
                $motivo="cuadroMando";
                $url="/administrador/masiva-cms";
                //$listaMando[$i]->nodo = $nodo;
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }


            if(substr($listaMando[$i]->tipo,0,11)=="CUARENTENAS"){
                $motivo="cuadroMando";
                $url="/administrador/cuarentenas";
                $listaMando[$i]->motivo = $motivo;
                $listaMando[$i]->url = $url;
            }




        }

        return $listaMando;

    }


    function getCategorias()
    {
        $categorias =  DB::select("SELECT categoria FROM zz_new_system.dashboard WHERE categoria IS NOT NULL GROUP BY 1");
        
        return $categorias;
    }

                            



}