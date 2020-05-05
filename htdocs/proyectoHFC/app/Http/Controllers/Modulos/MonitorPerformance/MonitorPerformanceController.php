<?php

namespace App\Http\Controllers\Modulos\MonitorPerformance;

use Illuminate\Http\Request;
use App\Administrador\ParametroColores;
use App\Functions\MonitorPerformanceFunctions;
use App\Http\Controllers\GeneralController;

class MonitorPerformanceController extends GeneralController
{

   public function view()
   {

        return view('administrador.modulos.monitorPerformance.index');

   }


    public function lista(Request $request)
    {
        if($request->ajax()){

            #INICIO
            $funcionMonitor = new MonitorPerformanceFunctions;

            $tipoServer = $request->tipoServer;
            //dd($tipoServer);

            $retornoMonitor =  $funcionMonitor->getListaMonitorSQLWeb($tipoServer);
            //dd($retornoMonitor);
                    
            if ($retornoMonitor == "error") {
                return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
            } 

            $performancesqlResult = $funcionMonitor->getProcesarMonitoreoSQL($retornoMonitor);
    
            return datatables($performancesqlResult)->toJson();
                
            #END
        }
        return abort(404); 
   
    }

    /*
    public function listaProcesos()
    { 
         
        $funcionMonitor = new MonitorPerformanceFunctions;
        
        $retornoMonitor =  $funcionMonitor->getListaMonitorSQLProcesos();
                
        if ($retornoMonitor == "error") {
            return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
        } 

        $performancesqlResult = $funcionMonitor->getProcesarMonitoreoSQL($retornoMonitor);
 
        return datatables($performancesqlResult)->toJson();
                
            #END
        //}
        //return abort(404); 
   
    }
    */

    /*
    public function listaColector()
    { 
         
        $funcionMonitor = new MonitorPerformanceFunctions;
        
        $retornoMonitor =  $funcionMonitor->getListaMonitorSQLColector();
                
        if ($retornoMonitor == "error") {
            return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
        } 

        $performancesqlResult = $funcionMonitor->getProcesarMonitoreoSQL($retornoMonitor);
 
        return datatables($performancesqlResult)->toJson();
                
            #END
        //}
        //return abort(404); 
   
    }
    */


    public function graficoPerformanceApache()
    { 
          //if($request->ajax()){
               #INICIO
                    
                    $coloresMonitorApache = ParametroColores::getMonitorApacheParametros();
                    $colorMonitorApache = $coloresMonitorApache->COLORES->monitorApache->colores;
                    
                    $funcionMonitor = new MonitorPerformanceFunctions;
                    
                    $estado= TRUE;
            
                    $resultDataMonitorApache = $funcionMonitor->getDataHistoricoApache();

                    if (count($resultDataMonitorApache) == 0) {
                        $estado = FALSE;
                    }
                    
                    return $this->resultData(["data"=>$resultDataMonitorApache,"colorMonitorApache"=>$colorMonitorApache,"estado"=>$estado]);
                    
               #END
          //}
          //return abort(404); 
  
    }


    public function eliminarProceso(Request $request)
    {
        if($request->ajax()){
            #INICIO
            $id = $request->id;
            $tipoServer = $request->tipoServer;

            $funcionMonitor = new MonitorPerformanceFunctions;

            $resultEliminarProcess = $funcionMonitor->procesarEliminacion($id,$tipoServer);

            return $resultEliminarProcess;

        }

        return abort(404); 

    }


    public function listaGuardian()
   { 
         
        $funcionMonitor = new MonitorPerformanceFunctions;
        
        $retornoMonitorGuardian =  $funcionMonitor->getListaGuardian();
                
        if ($retornoMonitorGuardian == "error") {
            return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
        } 

        $performanceGuardianResult = $funcionMonitor->getProcesarGuardian($retornoMonitorGuardian);
 
        return datatables($performanceGuardianResult)->toJson();
                
            #END

        //}
        //return abort(404); 
   
   }




   

   

}