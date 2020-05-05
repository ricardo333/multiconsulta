<?php

namespace App\Http\Controllers\Modulos\Gestion;

use Illuminate\Http\Request;
use App\Functions\GestionFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class GestionController extends GeneralController
{

    public function view()
    { 
        $gestionesF = new GestionFunctions;
        $tecnicosGestion = $gestionesF->getTecnicosGestion();
        $estados = $gestionesF->getEstadoAlertas();
        $causas = $gestionesF->getCausaGestion();
        $areasResponsables = $gestionesF->getAreasResponsablesGestion();
        $nodoTrobasList = $gestionesF->getNodoTrobas();

        return view('administrador.modulos.gestion.create',[
            "tecnicos"=>$tecnicosGestion,
            "estados"=>$estados,
            "causas"=>$causas,
            "areasR"=>$areasResponsables,
            "listaNodoTrobas"=>$nodoTrobasList
        ]);

    }

    public function requiresLoad(Request $request)
    {
        if($request->ajax()){
           // dd("aqui");
            #INICIO
                $gestionesF = new GestionFunctions;

                $numRequ = $request->numRequ;
                $nodo = $request->nodo;
                $troba = $request->troba;
                $estado = $request->estado;
                $idtp = "";
                
                if (isset($request->modulo)) {
                    if ($request->modulo == "TrabajosProgramados") {
                        $estados = $gestionesF->getEstadoAlertas(" and idestado in (42,43) ");
                        $idtp = $request->idttpp;
                    }else{
                        $estados = $gestionesF->getEstadoAlertas();
                    }
                   
                }else{
                    $estados = $gestionesF->getEstadoAlertas();
                }
                

                $tecnicosGestion = $gestionesF->getTecnicosGestion();
                //$estados = $gestionesF->getEstadoAlertas();
                $causas = $gestionesF->getCausaGestion();
                $areasResponsables = $gestionesF->getAreasResponsablesGestion();
        
                return $this->resultData([
                    "tecnicos"=>$tecnicosGestion,
                    "estados"=>$estados,
                    "causas"=>$causas,
                    "areasR"=>$areasResponsables,
                    "idTrabajoProg"=>$idtp,
                    "detalleParams"=>array(
                        "numRequ" => $numRequ,
                        "nodo" => $nodo,
                        "troba" => $troba,
                        "estado" => $estado
                    )
                ]);
            #END
        }
        return abort(404); 
       
    }

    public function lista(Request $request)
    {

     if($request->ajax()){
        
          #INICIO
                $gestionesF = new GestionFunctions;
                //dd($request->all());
                
                $validator = Validator::make($request->all(), [
                    "nodo" => "nullable|regex:/^[a-zA-Z0-9]+$/",
                    "troba" => "nullable|regex:/^[a-zA-Z0-9]+$/" 
                ]);
            
                if ($validator->fails()) {    
                   // return response()->json(["error"=>true,"message"=>$validator->errors()->all()]);
                  //  {"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":[],"input":{"nodo":"FD"}}
                    return $this->errorDataTable($validator->errors()->all(),402);
                } 

                $nodo = isset($request->nodo) ? $request->nodo : "";
                $troba = isset($request->troba)? $request->troba : "";

                $queryWhere = "";
                if ($nodo != "") {
                    $queryWhere = " where nodo='$nodo' ";
                }
                if ($troba != "") {
                    if ($queryWhere == "") {
                        $queryWhere .= " where troba='$troba' ";
                    }else{ 
                        $queryWhere .= " and troba='$troba' ";
                    }
                }

                if ($queryWhere == "") {
                    return $this->errorDataTable("El nodo y troba no pueden estar vacios.",402);
                }
                
                $listaGestiones = $gestionesF->gestListaRegistros($queryWhere);
                //dd($listaGestiones);
                
                //dd($listaGestiones);
                $dataJson = datatables($listaGestiones)->toJson();

               // dd($dataJson);
                
                return $dataJson;
      
              
          #END
      }
     return abort(404); 
    }

    public function storeIndividual(Request $request)
    {
        // dd($request->all());

        $validaEstado = Validator::make($request->all(), [
            "estado" => "required|not_in:seleccionar,Seleccionar"
        ]);

        if ($validaEstado->fails()) {   
            return $this->errorMessage($validaEstado->errors()->all(),422);
        } 

        $gestionesF = new GestionFunctions;
        $usuarioAuth = Auth::user(); 
        $usuario = $usuarioAuth->username; 

        if ($request->estado == "Enviada:ATENTO para liquidar" || $request->estado == "Enviada:COT para liquidar") {
            
            if (empty($request->causa) || trim($request->causa) == "") {
                return $this->errorMessage("¡El campo Causa es requerido, intente nuevamente!.",402);
            }
            if (empty($request->areaResponsable) || trim($request->areaResponsable) == "") {
                return $this->errorMessage("¡El campo Área encargada es requerido, intente nuevamente!.",402);
            }

            if(trim($request->numRequerimiento) != "" && $request->numRequerimiento != 0){

				if(empty($request->codtecliq) || trim($request->codtecliq) == "" ){
                         return $this->errorMessage("¡El campo Cod. Tec. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->codtecliq)) {
                         return $this->errorMessage("¡El campo Cod. Tec. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->codtecliq) > 6) {
                         return $this->errorMessage("¡La longitud del campo Cod. Tec. Liq. no deben superar los 6 digitos!.",402);
				}

				if(empty($request->codliq) || trim($request->codliq) == "" ){
                         return $this->errorMessage("¡El campo Cod. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->codliq)) {
                         return $this->errorMessage("¡El campo Cod. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->codliq) > 2) {
                         return $this->errorMessage("¡La longitud del campo Cod. Liq. no deben superar los 2 digitos!.",402);
				}

				if(empty($request->detliq) || trim($request->detliq) == "" ){
                         return $this->errorMessage("¡El campo Det. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->detliq)) {
                         return $this->errorMessage("¡El campo Det. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->detliq) > 2) {
                         return $this->errorMessage("¡La longitud del campo Det. Liq. no deben superar los 2 digitos!.",402);
				}
				if(empty($request->afectacion) || trim($request->afectacion) == "" ){
                         return $this->errorMessage("¡El campo Afectación es requerida, intente nuevamente!.",402);
				}
				if(empty($request->contrata) || trim($request->contrata) == "" ){
                         return $this->errorMessage("¡El campo Contrata es requerida, intente nuevamente!.",402);
				}
				if(empty($request->nombretecnico) || trim($request->nombretecnico) == "" ){
                         return $this->errorMessage("¡El campo Nombre técnico es requerido, intente nuevamente!.",402);
                    } 
                    if(!preg_match("/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/",$request->nombretecnico)) {
                         return $this->errorMessage("¡El campo Nombre técnico no tiene el formato correcto.!.",402);
                    }
			}

        }
         
        $parametros = $request->all();
        $parametros["usuario"] = $usuario;

        $gestionesF->registroGestionIndividual($parametros);

        return $this->mensajeSuccess("La gestión se registro correctamente.");
 
    }

    public function storeMasiva(Request $request)
    {
         //dd($request->all());

        $validaEstado = Validator::make($request->all(), [
            "estado" => "required|not_in:seleccionar,Seleccionar"
        ]);

        if ($validaEstado->fails()) {   
            return $this->errorMessage($validaEstado->errors()->all(),422);
        } 

        $gestionesF = new GestionFunctions;
        $usuarioAuth = Auth::user(); 
        $usuario = $usuarioAuth->username;

        if ($request->estado == "Enviada:ATENTO para liquidar" || $request->estado == "Enviada:COT para liquidar") {
            
            if (empty($request->causa) || trim($request->causa) == "") {
                return $this->errorMessage("¡El campo Causa es requerido, intente nuevamente!.",402);
            }
            if (empty($request->areaResponsable) || trim($request->areaResponsable) == "") {
                return $this->errorMessage("¡El campo Área encargada es requerido, intente nuevamente!.",402);
            }
 
				if(empty($request->codtecliq) || trim($request->codtecliq) == "" ){
                         return $this->errorMessage("¡El campo Cod. Tec. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->codtecliq)) {
                         return $this->errorMessage("¡El campo Cod. Tec. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->codtecliq) > 6) {
                         return $this->errorMessage("¡La longitud del campo Cod. Tec. Liq. no deben superar los 6 digitos!.",402);
				}

				if(empty($request->codliq) || trim($request->codliq) == "" ){
                         return $this->errorMessage("¡El campo Cod. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->codliq)) {
                         return $this->errorMessage("¡El campo Cod. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->codliq) > 2) {
                         return $this->errorMessage("¡La longitud del campo Cod. Liq. no deben superar los 2 digitos!.",402);
				}

				if(empty($request->detliq) || trim($request->detliq) == "" ){
                         return $this->errorMessage("¡El campo Det. Liq. es requerida, intente nuevamente!.",402);
				}
				if(!preg_match("/^[a-zA-Z0-9]+$/",$request->detliq)) {
                         return $this->errorMessage("¡El campo Det. Liq. no tiene el formato alfanumérico correcto.!.",402);
				}
				if(strlen($request->detliq) > 2) {
                         return $this->errorMessage("¡La longitud del campo Det. Liq. no deben superar los 2 digitos!.",402);
				}
				if(empty($request->afectacion) || trim($request->afectacion) == "" ){
                         return $this->errorMessage("¡El campo Afectación es requerida, intente nuevamente!.",402);
				}
				if(empty($request->contrata) || trim($request->contrata) == "" ){
                         return $this->errorMessage("¡El campo Contrata es requerida, intente nuevamente!.",402);
				}
				if(empty($request->nombretecnico) || trim($request->nombretecnico) == "" ){
                         return $this->errorMessage("¡El campo Nombre técnico es requerido, intente nuevamente!.",402);
                } 
                if(!preg_match("/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/",$request->nombretecnico)) {
                        return $this->errorMessage("¡El campo Nombre técnico no tiene el formato correcto.!.",402);
                }
		  
        }
        
        $parametros = $request->all();
        $parametros["usuario"] = $usuario;

        $gestionesF->registroGestionMasiva($parametros);

        return $this->mensajeSuccess("La gestión se registro correctamente.");
 
    }

    public function detalleMasiva(Request $request)
    {
        //dd($request->all());
        if($request->ajax()){
 
            #INICIO
                

                if(!$request->filled('codigoRequerimiento')){ //preguntamos si mando un campo nombre y no esta vacio
                    return $this->errorMessage("¡El campo codigo de requerimiento no existe!.",402);
                }

                $gestionesF = new GestionFunctions;

                $codigoRequerimiento = $request->codigoRequerimiento;

                $masiva = $gestionesF->detalleMasiva($codigoRequerimiento);

                return $this->resultData(array(
                        "data"=>$masiva
                )); 
 
            #END
        }
        return abort(404); 
    }

     


}
