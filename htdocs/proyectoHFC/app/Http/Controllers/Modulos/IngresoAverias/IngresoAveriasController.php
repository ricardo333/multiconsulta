<?php

namespace App\Http\Controllers\Modulos\IngresoAverias;

use Illuminate\Support\Facades\Storage;
use App\Administrador\ParametroColores;
use App\Functions\peticionesGeneralesFunctions;
use Illuminate\Http\Request;
use App\Functions\IngresoAveriasFunctions;
use App\Http\Controllers\GeneralController;

class IngresoAveriasController extends GeneralController
{

     public function view(Request $request)
     {

        $functionesPeticionesGenerales = new peticionesGeneralesFunctions;
        $jefaturas = $functionesPeticionesGenerales->getJefaturas();
        $trobas = $functionesPeticionesGenerales->getTrobas();

        $resultado = array();
        $resultado["trobas"] = $trobas;
        $resultado["jefaturas"] = $jefaturas;

        //$request->motivo = "cuadroMando";

        if (isset($request->motivo)) $resultado["motivo"] = $request->motivo;
          
        /*
        return view('administrador.modulos.ingresoAverias.index',[
            "listaJefaturas"=>$listaJefaturas,
            "listaTrobas"=>$listaTrobas
        ]);
        */
        return view('administrador.modulos.ingresoAverias.index',$resultado);

     }      


    public function graficoAveriasMotivos(Request $request)
    { 
          if($request->ajax()){
               #INICIO

                    $jefatura = trim($request->jefatura) != "" ? $request->jefatura : "";
                    $troba = trim($request->troba) != "" ? $request->troba : "";
                    $jefaturax='';
                    $troba_ll='';
                    $estado='true';

                    if ( $jefatura!=='' || $troba!=='' ) {
                         if( $jefatura!=='' ){
                              $jefatura = " and jj.jefatura='".$jefatura."' ";
                              $inner=" inner join catalogos.jefaturas jj on a.codnod=jj.nodo ";
                              $troba = $troba;
                              $troba_ll = $troba_ll;
                              $jefaturax = "<br><h2>Zonal ".$request->jefatura."</h2>";
                         }
                         if( $troba!=='' ){
                              $jefatura = $jefatura;
                              $inner=" inner join catalogos.jefaturas jj on a.codnod=jj.nodo ";
                              $troba=" and concat(a.codnod,a.nroplano)='".$troba."' ";
                              $troba_ll="and concat(a.codnod,a.troba)='$request->troba'";
                              $jefaturax=$jefaturax;
                         }
                    }else{
                         $jefatura = "";
                         $inner = "";
                         $troba = '';
                         $jefaturax='';
                    }
                    
                    $colorestIngresoAveriasJefaturas = ParametroColores::getIngresoAveriasParametros();
                    $colorAveriasMotivos = $colorestIngresoAveriasJefaturas->COLORES->averiasMotivos->colores;
                    
                    $ingresoAveriasFuntions = new IngresoAveriasFunctions;
                    
                    //Seleccionar los filtros $jefatura='LIMA-EST' y $troba='LAR005' para mirar la grafica
                    //$resultDataAveriasMotivos = $ingresoAveriasFuntions->getDataHistoricoAveriasMotivos($jefatura, $inner);
                    $resultDataAveriasMotivos = $ingresoAveriasFuntions->getDataHistoricoAveriasMotivos($jefatura, $inner, $troba);
                    
                    $resultFechaAverias = $ingresoAveriasFuntions->getFechaDiaAverias($troba,$jefatura);
                    $resultTotalAverias = $ingresoAveriasFuntions->getTotalAverias($troba,$jefatura);
                    $resultResumenAverias = $ingresoAveriasFuntions->getResumenIngresosAverias($troba,$jefatura);
                    //return ["data"=>$resultDataAveriasMotivos];

                    if (count($resultDataAveriasMotivos) == 0) {
                         //return $this->errorMessage("No se encontró data histórica de averías por motivos.",400);
                         $estado = 'false';
                    }
                    
                    return $this->resultData(["data"=>$resultDataAveriasMotivos,"coloresNiveles"=>$colorAveriasMotivos,"param"=>$resultFechaAverias,"total"=>$resultTotalAverias,"resultResumenAverias"=>$resultResumenAverias,"jefaturax"=>$jefaturax,"estado"=>$estado]);
                    
               #END
          }
          return abort(404); 
  
    }

    public function graficoAveriasJefatura(Request $request)
    { 
          if($request->ajax()){
               #INICIO

                    $jefaturaFiltro = $jefatura = trim($request->jefatura) != "" ? $request->jefatura : "";
                    $trobaFiltro = $troba = trim($request->troba) != "" ? $request->troba : "";
                    $jefaturax='';
                    $estado='true';

                    if ( $jefatura!=='' || $troba!=='' ) {
                         if( $jefatura!=='' ){
                              $jefatura = " and jj.jefatura='".$jefatura."' ";
                              $troba = $troba;
                              $jefaturax = "<br><h2>Zonal ".$request->jefatura."</h2>";
                         }
                         if( $troba!=='' ){
                              $jefatura = $jefatura;
                              $troba=" and concat(a.codnod,a.nroplano)='".$troba."' ";
                              $jefaturax=$jefaturax;
                         }
                    }else{
                         $jefatura = "";
                         $troba = '';
                         $jefaturax='';
                    }
                    
                    $colorestIngresoAveriasJefaturas = ParametroColores::getIngresoAveriasParametros();
                    $colorAveriasJefaturas = $colorestIngresoAveriasJefaturas->COLORES->averiasJefatura->colores;
                   
                    $ingresoAveriasFuntions = new IngresoAveriasFunctions;
                    
                    $resultFechaAverias = $ingresoAveriasFuntions->getFechaDiaAverias($troba,$jefatura);
                    //return ["data"=>$resultFechaAverias];

                    $resultTotalAverias = $ingresoAveriasFuntions->getTotalAverias($troba,$jefatura);
                    $resultResumenAverias = $ingresoAveriasFuntions->getResumenIngresosAverias($troba,$jefatura);
                    $aniomes = date("Y-m");
                    //$xdia = ($resultFechaAverias[0]->numdia!= "")? $resultFechaAverias[0]->numdia: '';
                    
                    $resultDataAveriasJefaturas = $ingresoAveriasFuntions->getDataHistoricoAveriasJefaturas($jefaturaFiltro,$trobaFiltro);
                    //return ["data"=>$resultDataAveriasJefaturas];
                    
                    if (count($resultDataAveriasJefaturas) == 0) {
                         //return $this->errorMessage("No se encontró data histórica de averías por jefaturas.",500);
                         $estado = 'false';
                    }
                   
                    
                    return $this->resultData(["data"=>$resultDataAveriasJefaturas,"coloresNiveles"=>$colorAveriasJefaturas,"param"=>$resultFechaAverias,"total"=>$resultTotalAverias,"resultResumenAverias"=>$resultResumenAverias,"aniomes"=>$aniomes,"jefaturax"=>$jefaturax,"estado"=>$estado]);
                    
               #END
          }
          return abort(404); 
  
    }

    public function descargarArchivos(Request $request)
    {

        $archivo = $request->archivo;
        $ruta = $request->ruta;
        //$extension = $request->extension;

        $origen = $ruta.$archivo;
        $url = Storage::disk('sftpServer')->download($origen);

        return $url;

    }

    public function trobasPorjefatura(Request $request)
    {
            
        if($request->ajax()){

            #INICIO
               /*
               // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
                $valida = Validator::make($request->all(), [
                    "jefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z_-]+$/"
                ]);
                   
                if ($valida->fails()) {   
                    return $this->errorMessage($valida->errors()->all(),422);
                } 
                */
                $peticionesGFunctions = new peticionesGeneralesFunctions;

                $jefatura = $request->jefatura;
                if($jefatura ==''){
                    $trobas = $peticionesGFunctions->getTrobas();
                }else{
                    $trobas = $peticionesGFunctions->getTrobasByJefaturaJoin($jefatura);
                }
        
                return $this->resultData(array(
                    //"interfaces"=>$interfaces,
                    "trobas"=>$trobas
                ));
            #END

        }

        return abort(404); 
        

    }
    
}

