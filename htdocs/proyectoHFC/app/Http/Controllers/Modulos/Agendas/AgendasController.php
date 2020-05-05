<?php

namespace App\Http\Controllers\Modulos\Agendas;

use Illuminate\Http\Request;
use App\Functions\AgendaFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class AgendasController extends GeneralController
{
    public function view()
    {
        $agendasFunction = new AgendaFunctions;
        $usuarioAuth = Auth::user();

        $arrayResultados = array();

        $filtroEstadoSWGestion = "";

        $estados = $agendasFunction->getEstadosAgenda($filtroEstadoSWGestion);

        $arrayResultados["estados"]= $estados;

        if ($usuarioAuth->HasPermiso('submodulo.agendas.gestion.store')) {
            $filtroEstadoSWGestion = " where sw=1 ";
            $estadosGestionAgenda = $agendasFunction->getEstadosAgenda($filtroEstadoSWGestion);
            $quiebreAgenda = $agendasFunction->getQuiebreAgenda();
            $arrayResultados["estadosGestionAgenda"] = $estadosGestionAgenda;
            $arrayResultados["quiebres"] = $quiebreAgenda;
        }
 

        return view('administrador.modulos.agenda.index',$arrayResultados);
    }

    public function lista(Request $request)
    {
        if($request->ajax()){
            #INICIO
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "estado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]);  
        
                $validarCodCliente = Validator::make($request->all(), [
                    "codigoCliente" => "nullable|regex:/^[0-9]+$/"
                ]);
        
                $filtroEstado = "";
                $filtroCodCli = "";
                //dd($request->all());
        
                if (!$validarEstado->fails()) {
                    if (isset($request->estado)) {   
                        $filtroEstado = trim($request->estado) != "" ? " and a.estado='".$request->estado."' " : "";
                    }  
                }
        
                if (!$validarCodCliente->fails()) {
                    if (isset($request->codigoCliente)) {   
                        $filtroCodCli = trim($request->codigoCliente) != "" ? " and a.codcli='".$request->codigoCliente."' " : "";
                    }  
                }
        
                $agendasFunction = new AgendaFunctions;
                $listaAgendas = $agendasFunction->getListaAgenda($filtroEstado,$filtroCodCli);
                if ($listaAgendas == "error") {
                    return $this->errorDataTable("Ocurrio un problema al traer los datos, intente nuevamente.",500);
                }
                return datatables($listaAgendas)->toJson();
            #END
        }
       
        return abort(404); 
        //dd($listaAgendas);
    }

    public function storeGestionAgenda(Request $request)
    {
            $validar = Validator::make($request->all(), [
                "idAgenda" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/",
                "estado" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/",
                "quiebre" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
            ]); 
  
            if ($validar->fails()) { 
                 return $this->errorMessage($validar->errors()->all(),422);
            } 

            $agendasFunction = new AgendaFunctions;
            $usuarioAuth = Auth::user();
            $usuario = $usuarioAuth->username;

            $agendasFunction->registroGestionAgenda($request->all(),$usuario);

            return $this->mensajeSuccess("Se registró correctamente la gestión");
              
    }

    public function listaGestionAgenda(Request $request)
    {
        if($request->ajax()){
           #INICIO
                $validar = Validator::make($request->all(), [
                    "idAgenda" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
                ]); 

                if ($validar->fails()) { 
                    return $this->errorMessage($validar->errors()->all(),422);
                } 

                $agendasFunction = new AgendaFunctions;

                $listaMovimientosGestion = $agendasFunction->getGestionMovByCodCli($request->idAgenda);

                if ($listaMovimientosGestion == "error") {
                    return $this->errorDataTable("Ocurrio un problema al traer los datos, intente nuevamente.",500);
                }
        
                return datatables($listaMovimientosGestion)->toJson();
           #END
        }

        return abort(404); 
 
    }


}
