<?php

namespace App\Http\Controllers\Modulos\Cuarentenas;

use Illuminate\Http\Request;
use App\Functions\GestionFunctions;
use Illuminate\Support\Facades\Auth;
use App\Administrador\TipoCuarentenas;
use App\Functions\CuarentenaFunctions;
use App\Administrador\GestionCuarentena;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\GestionCuarentenaFunctions;
use App\Functions\peticionesGeneralesFunctions;

class CuarentenasController extends GeneralController
{
    public function index(Request $request)
    {
         
        $cuarentenasFunction = new CuarentenaFunctions;
        $peticionesGeneralesF = new peticionesGeneralesFunctions;

        $resultado = array();

        if (isset($request->motivo)){
            $resultado["motivo"] = $request->motivo;
            $resultado["nodo"] = $request->nodo;
            $idCuarentena = $request->nodo;
            $nombres= $cuarentenasFunction->nombres($idCuarentena);
        } else {
            $idCuarentena = "";
            $nombres= $cuarentenasFunction->nombres($idCuarentena);
        }

        //$nombres= $cuarentenasFunction->nombres();

        $jefaturas = $peticionesGeneralesF->getJefaturas();

        //$resultado = array();
        $resultado["nombres"] = $nombres;
        $resultado["jefaturas"] = $jefaturas;
         
        if (isset($request->codmotv)) $resultado["codmotv"] = $request->codmotv; 
        if (isset($request->tipoEstado)) $resultado["tipoEstado"] = $request->tipoEstado; 
        if (isset($request->segunColor)) $resultado["segunColor"] = $request->segunColor; 
        if (isset($request->averiasp)) $resultado["averiasp"] = $request->averiasp; 

 
        return view('administrador.modulos.cuarentenas.index',$resultado);

    }

    public function lista(Request $request, GestionCuarentena $cuarentena)
    {
        if($request->ajax()){
            #INICIO
                $cuarentenasFunction = new CuarentenaFunctions;

                $validarAveriasp = Validator::make($request->all(), [
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
        
                
                $preguntaHoy = "";
                $filtroJefatura = "";
                $averiaReiteradaPendiente = "";
                $codmotv = "";
                $tipoEstado = "";
                $valorTipoEstado = "";
                $segunColor = "";
        
                if (!$validarAveriasp->fails()) {
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
                
                // dd($request->all());
                $identificadorCuerentena = $cuarentena->id;
        
                if ($cuarentena->tipo == TipoCuarentenas::TIPO_AVERIAS) {
        
                    $lista = $cuarentenasFunction->getlistaAveriasCuarentenas($identificadorCuerentena,$preguntaHoy,$averiaReiteradaPendiente,
                                                                        $filtroJefatura,$codmotv,$tipoEstado,$segunColor);
                    if ($lista == "error") {
                        return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    }
                    
                    $listaProcesada = $cuarentenasFunction->procesarListaAveriasCuarentenas($lista);
        
                }elseif ($cuarentena->tipo == TipoCuarentenas::TIPO_CRITICOS) {
            
                    $lista = $cuarentenasFunction->getlistaCriticosCuarentenas($identificadorCuerentena,$averiaReiteradaPendiente,$filtroJefatura);
        
                    if ($lista == "error") {
                        return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    }
        
                    $listaProcesada = $cuarentenasFunction->procesarListaCriticosCuarentenas($lista);
        
                }else{
                    return $this->errorDataTable("No se puede identificadar a la Cuarentena, verifique sus datos enviados.",500);
                }
        
                //dd($listaProcesada);
        
                return datatables($listaProcesada)->toJson();
            #END
        }
        return abort(404); 
 
    }

    public function storeGestionIndividual(Request $request)
    {
        
        $valida = Validator::make($request->all(), [
            "tipoDeAveria" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_ ]+$/",
            "idClienteCRM" => "required"
        ]);

        if ($valida->fails()) {   
            return $this->errorMessage($valida->errors()->all(),422);
        } 
 
        $usuarioAuth = Auth::user();
        $gestionF = new GestionFunctions;

        $parametros = $request->all();
        $parametros["usuario"] = $usuarioAuth->nombre;
          
        $gestionF->registroGestionCuarentenaIndividual($parametros);

        return $this->mensajeSuccess("El cliente se registró correctamente en Gestión Cuerentena.");

    }

    public function listaGestionIndividual(Request $request)
    {

        if($request->ajax()){
            #INICIO
                if (!isset($request->idClienteCrm)) {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
        
                $gestionF = new GestionFunctions;
        
                $listaHistoricoClienteCuarentena = $gestionF->getGestionCuarentenasByIdClienteCrm($request->idClienteCrm);
        
                return datatables($listaHistoricoClienteCuarentena)->toJson();
            #END
        }
        return abort(404); 
       
          
    }

}
