<?php

namespace App\Http\Controllers\Modulos\Cuarentenas;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Administrador\TipoCuarentenas;
use App\Functions\CuarentenaFunctions;
use App\Administrador\GestionCuarentena;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController; 
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Reportes\Excel\Cuarentenas\ExcelAveriasCuarentenas;
use App\Reportes\Excel\Cuarentenas\ExcelCriticosCuarentenas;
 

class CuarentenasExcelController extends GeneralController
{

    public function TotalCuarentenas(Request $request, GestionCuarentena $cuarentena)
    {

        if($request->ajax()){
            #INICIO
                $cuarentenasFunction = new CuarentenaFunctions;
        
        
                /* $validaIdCuarentena = Validator::make($request->all(), [
                    "idCuarentena" => "required|regex:/^[0-9]+$/"
                ]);
        
                if ($validaIdCuarentena->fails()) {   
                    throw new HttpException(422,"Avería no encontrada, intente nuevamente actualizando la web.");
                } */
        
        
                $validarJefatura = Validator::make($request->all(), [
                    "averiasp" => "nullable|regex:/^[a-zA-Z\-_.:]+$/"
                ]);
        
                $validarJefatura = Validator::make($request->all(), [
                    "filtroJefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);
                $validarReiteradas = Validator::make($request->all(), [
                    "reiteradas" => "nullable|in:SI|regex:/^[a-zA-Z]+$/"
                ]);
                $validarCodMotv = Validator::make($request->all(), [
                    "codmotv" => "nullable|regex:/^[a-zA-Z\-_.:]+$/"
                ]);
                $validarTipoEstado = Validator::make($request->all(), [
                    "tipoEstado" => "nullable|regex:/^[a-zA-Z\-_.:]+$/"
                ]);
                $validasegunColor = Validator::make($request->all(), [
                    "segunColor" => "nullable|regex:/^[a-zA-Z\-_#]+$/"
                ]);
        
                $identificadorCuerentena = $cuarentena->id;
                $preguntaHoy = "";
                $filtroJefatura = "";
                $averiaReiteradaPendiente = "";
                $codmotv = "";
                $tipoEstado = "";
                $valorTipoEstado = "";
                $segunColor = "";
        
                if (!$validarJefatura->fails()) {
                    if (isset($request->averiasp)) {   
                        $preguntaHoy = trim($request->averiasp) != "" ? " and rm.codreq>0 " : "";
                    }  
                }
        
                if (!$validarJefatura->fails()) {
                    if (isset($request->filtroJefatura)) {   
                        $filtroJefatura = trim($request->filtroJefatura) != "" ? " and b.jefatura='".$request->filtroJefatura."' " : "";
                    }  
                }
                if (!$validarReiteradas->fails()) {
                    if (isset($request->reiteradas)) {   
                        $averiaReiteradaPendiente = trim($request->reiteradas) != "" ? " and a.codreq >0 " : "";
                    }  
                }
                if (!$validarCodMotv->fails()) {
                    if (isset($request->codmotv)) {   
                        $codmotv = trim($request->codmotv) != "" ? " and a.codmotv='".$request->codmotv."' " : "";
                    }  
                }
                if (!$validarTipoEstado->fails()) {
                    if (isset($request->tipoEstado)) {   
                        $tipoEstado = trim($request->tipoEstado) != "" ? " and a.status='".$request->tipoEstado."' " : "";
                        $valorTipoEstado = trim($request->tipoEstado) != "" ? $request->tipoEstado : "";
                    }  
                }
                if (!$validasegunColor->fails()) {
                    if (isset($request->segunColor)) {  
                        if ( trim($request->segunColor) != "") {
                            if($request->segunColor=='red'){
                                $segunColor=" and a.tipoaveria in ('MASIVA','PUNTUAL','') ";
                            }
                            
                            if($request->segunColor=='808000' && $valorTipoEstado =='2.- Offline - NO OK') {
                                $segunColor=" and a.tipoaveria in ('NO DESEA ATENCION','Apaga Modem','INUBICABLE','TRATAMIENTO COMERCIAL') ";
                            }
                            if($request->segunColor=='808000' && $valorTipoEstado =='1.-Niveles NO OK' ) {
                                $segunColor=" and a.tipoaveria in ('NO DESEA ATENCION','Apaga Modem','INUBICABLE','TRATAMIENTO COMERCIAL') ";
                            }
                            if($request->segunColor=='orange' && $valorTipoEstado =='2.- Offline - NO OK' ) {
                                $segunColor=" and a.tipoaveria not in ('NO DESEA ATENCION','Apaga Modem','INUBICABLE','TRATAMIENTO COMERCIAL') ";
                            }
                        } 
                        
                    }  
                }
        
        
                $fecha=date('YmdHis');
        
                if ($cuarentena->tipo == TipoCuarentenas::TIPO_AVERIAS) {
        
                    $archivo="averias_cuarentenas".$fecha.".xlsx";
        
                    $output = Excel::download(new ExcelAveriasCuarentenas($identificadorCuerentena,$preguntaHoy,$averiaReiteradaPendiente,
                                                                        $filtroJefatura,$codmotv,$tipoEstado,$segunColor), $archivo);
        
                }elseif ($cuarentena->tipo == TipoCuarentenas::TIPO_CRITICOS) {
                    $archivo="criticos_cuarentenas".$fecha.".xlsx";
                    $output = Excel::download(new ExcelCriticosCuarentenas($identificadorCuerentena,$averiaReiteradaPendiente,$filtroJefatura), $archivo);
        
                }else{
                    throw new HttpException(409,"No se reconoce el tipo de cuarentena, verifique que la web se encuentre actualizada.");
                }
                
        
                return $output;
            #END
        }
        return abort(404); 
       
    }

}