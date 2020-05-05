<?php

namespace App\Http\Controllers\Modulos\Cuarentenas;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Administrador\TipoCuarentenas;
use App\Administrador\TrobasCuarentena;
use Illuminate\Database\QueryException;
use App\Administrador\GestionCuarentena;
use App\Administrador\ClientesCuarentena;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\GestionCuarentenaFunctions;
use App\Functions\peticionesGeneralesFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GestionCuarentenasController extends GeneralController
{
    public function index(Request $request)
    {
        
        $usuarioAuth = Auth::user();
        $peticionesGFunctions = new peticionesGeneralesFunctions;
        $cuarentenasGFunctions = new GestionCuarentenaFunctions;

         
        $resultadoFinal = array();

        $resultadoFinal["jefaturas"] = $peticionesGFunctions->getJefaturas();
        $resultadoFinal["tipoCuarentenas"] = $cuarentenasGFunctions->tipo();

        if ($usuarioAuth->HasPermiso('submodulo.gestion-cuarentena.store') || $usuarioAuth->HasPermiso('submodulo.gestion-cuarentena.edit')) {
          
            $fecha_actual = date("Y-m-d");  
           // $resultadoFinal["cmts"] = $peticionesGFunctions->getCmts();
           
            
            $resultadoFinal["servicepackageCrmid"] = $peticionesGFunctions->getServicepackageCRMID();
            $resultadoFinal["scopeGroup"] = $peticionesGFunctions->getScopeGroup();
            $resultadoFinal["fechaInicio"] = $fecha_actual; 
        } 
        return view('administrador.modulos.gestionCuarentenas.index',$resultadoFinal);
    }

    public function lista(Request $request)
    {

        // if($request->ajax()){

            #INICIO
                $validarJefatura = Validator::make($request->all(), [
                    "jefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]);
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "estado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z]+$/"
                ]); 
        
                $filtroJefatura = "";
                $filtroEstado = "";
        
                if (!$validarJefatura->fails()) {
                    if (isset($request->jefatura)) {   
                        $filtroJefatura = trim($request->jefatura) != "" ? " where a.jefatura='".$request->jefatura."' " : "";
                    }  
                }
                if (!$validarEstado->fails()) {
                    if (isset($request->estado)) {   
                        if ($filtroJefatura == "") {
                            $filtroEstado =  trim($request->estado) != "" ? " where a.estado = '".trim($request->estado)."' " : "";
                        }else{
                            $filtroEstado =  trim($request->estado) != "" ? " and a.estado = '".trim($request->estado)."' " : "";
                        } 
                    } 
                }
        
                // dd($filtroJefatura."-------".$filtroEstado);
        
        
                $gestionCuarentenasGestionF = new GestionCuarentenaFunctions;
        
                $lista =  $gestionCuarentenasGestionF->listaPrincipal($filtroJefatura,$filtroEstado);
        
                return datatables($lista)->toJson();
            #END

        // }

      //  return abort(404); 
         
        
    }

    public function listaClientesPorCuarentena(Request $request, GestionCuarentena $cuarentena)
    {
        if($request->ajax()){
            #INICIO
                $listaClientes = $cuarentena->clientesCuarentenas;        
                $dataListReturn = datatables()
                                ->collection($listaClientes)->toJson();
                return $dataListReturn;
            #END
        }
        return abort(404); 
    }

    public function listaTrobasPorCuarentena(Request $request, GestionCuarentena $cuarentena)
    {
        if($request->ajax()){
            #INICIO
                $listaTrobas = $cuarentena->trobasCuarentenas;        
                $dataListReturn = datatables()
                                ->collection($listaTrobas)->toJson();
                return $dataListReturn;
            #END
        }
        return abort(404);  
    }

    public function detalles(Request $request, GestionCuarentena $cuarentena)
    {

        if($request->ajax()){
            #INICIO
                $peticionesGFunctions = new peticionesGeneralesFunctions;

                $trobas = $peticionesGFunctions->getTrobasByJefatura($cuarentena->jefatura);
        
                $trobasCuarentena = $cuarentena->trobasCuarentenas;
        
                $arrayTrobas = array();
                foreach ($trobasCuarentena as $trob) {
                    $arrayTrobas[] = $trob->nodo."-".$trob->troba;
                }
        
                $trobasCollection = Collection::make($trobas); //Convertimos en Collection para hacer el filtro rapido
                
                $trobasSinUtilizar = $trobasCollection->whereNotIn('nodotroba', $arrayTrobas)->values();
               // dd($trobasSinUtilizar);
                return $this->resultData(array(
                    "trobasCuarentenas"=>$trobasCuarentena,
                    "trobas"=>$trobasSinUtilizar
                ));
            #END
        }
        return abort(404); 
        

    }

    public function trobasPorjefatura(Request $request)
    {
            
        if($request->ajax()){

            #INICIO

               // throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
                $valida = Validator::make($request->all(), [
                    "jefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z_-]+$/"
                ]);
        
                if ($valida->fails()) {   
                    return $this->errorMessage($valida->errors()->all(),422);
                } 
        
                $peticionesGFunctions = new peticionesGeneralesFunctions;
        
                $jefatura = $request->jefatura;
        
                $trobas = $peticionesGFunctions->getTrobasByJefatura($jefatura);
        
                return $this->resultData(array(
                    //"interfaces"=>$interfaces,
                    "trobas"=>$trobas
                ));
            #END

        }

        return abort(404); 
        

    }

    public function store(Request $request)
    {
         //dd($request->all());
        $valida = Validator::make($request->all(), [
            "jefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z_-]+$/",
            "nombre" => "required|regex:/^[a-zA-Z0-9_-]+(\s*[a-zA-Z0-9_-]*)*[a-zA-Z0-9_-]+$/",
            "trobas" => "required|array|min:1",
            //"servicePackage" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_-]+$/",
            //"scopeGroup" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_-]+$/",
            "tipoDeCuarentena" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z]+$/",
            "estado" => "required",
            "cuadroDeMando" => "required",
            "fechaInicio" => "required|date|date_format:Y-m-d|before:fechaFin",
            "fechaFin" => "required|date|date_format:Y-m-d|after:fechaInicio",
        ]);

        if ($valida->fails()) {   
            return $this->errorMessage($valida->errors()->all(),422);
        } 

        $hoy = date("Y-m-d");
        if ($hoy > $request->fechaFin) {
            return $this->errorMessage("La fecha Final no puede ser menor a la fecha actual : $hoy .",422);
        }

        $fRegistro=date("Y-m-d H:i:s");

        $servicePackage = "";
        $scopeGroup = "";

        if (isset($request->servicePackage)) {
            if (strtolower($request->servicePackage) != "seleccionar") {
                $servicePackage = $request->servicePackage;
            }
        }
        if (isset($request->scopeGroup)) {
            if (strtolower($request->scopeGroup) != "seleccionar") {
                $scopeGroup = $request->scopeGroup;
            }
        }
   
     
        try {

            DB::beginTransaction(); 
                #INICIO
                    
                    $gestionCuarentena = new GestionCuarentena();
                    $gestionCuarentena->nombre = $request->nombre;
                    //$gestionCuarentena->nodo = $nodo;
                    //$gestionCuarentena->troba = $troba;
                    $gestionCuarentena->jefatura = $request->jefatura;
                    $gestionCuarentena->clientes = 0;
                    $gestionCuarentena->trobas = count($request->trobas);
                    $gestionCuarentena->estado = $request->estado;
                    $gestionCuarentena->cuadroMando = $request->cuadroDeMando;
                    $gestionCuarentena->tipo = $request->tipoDeCuarentena;
                    $gestionCuarentena->servicePackageCrmid = $servicePackage;
                    $gestionCuarentena->scopesGroup = $scopeGroup;
                    $gestionCuarentena->fechaInicio = $request->fechaInicio;
                    $gestionCuarentena->fechaFin = $request->fechaFin;
                    $gestionCuarentena->fechaRegistro = $fRegistro;
                    $gestionCuarentena->save();

                    
 
                    $valuesInsert = "";
                    $cantidadTrobas = count($request->trobas);
                    for ($i=0; $i < $cantidadTrobas; $i++) { 
                        
                        $nodo=substr($request->trobas[$i],0,2);
                        $troba=substr($request->trobas[$i],3,5); 

                        $valuesInsert .= "('null',".$gestionCuarentena->id.",'$nodo','$troba')";
                        if ($i+1 < $cantidadTrobas) {
                            $valuesInsert .=", ";
                        }
                    
                    }

                    DB::insert("INSERT INTO zz_new_system.trobas_cuarentenas VALUES $valuesInsert");
                    
                #END
            
                DB::commit();
        }catch(\Exception $e){
            DB::rollback();
          //  dd($e->getMessage()); 
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente verificando que los campos estén completos!!.",409);
        } catch(QueryException $ex){ 
            DB::rollback();
           // dd($ex->getMessage()); 
            return $this->errorMessage("Hubo un problema en el registro, intente nuevamente verificando que los campos estén completos!.",409);
        } 

      

        return $this->mensajeSuccess("Se registrarón los datos correctamente.");

       

    }

    public function update(Request $request, GestionCuarentena $cuarentena)
    {
        $valida = Validator::make($request->all(), [
            "jefatura" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z_-]+$/",
            "nombre" => "required|regex:/^[a-zA-Z0-9_-]+(\s*[a-zA-Z0-9_-]*)*[a-zA-Z0-9_-]+$/",
            "trobas" => "required|array|min:1",
            //"servicePackage" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_-]+$/",
            //"scopeGroup" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_-]+$/",
            "estado" => "required",
            "cuadroDeMando" => "required",
            "tipoDeCuarentena" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z]+$/",
            "fechaInicio" => "required|date|date_format:Y-m-d|before:fechaFin",
            "fechaFin" => "required|date|date_format:Y-m-d|after:fechaInicio",
        ]);

        if ($valida->fails()) {   
            return $this->errorMessage($valida->errors()->all(),422);
        } 
        
       // dd($request->all());

        try {
            DB::beginTransaction();
                #begin Transaction Update Cuarentena

                //dd($cuarentena->trobasCuarentenas->findOrFail($cuarentena->id));
                TrobasCuarentena::where('idCuarentenas', $cuarentena->id)->delete();
                //DB::delete("delete from ");
            
               // $nodo=substr($request->trobas,0,2);
               // $troba=substr($request->trobas,3,5); 

                    
                $servicePackage = "";
                $scopeGroup = "";

                if (isset($request->servicePackage)) {
                        if (strtolower($request->servicePackage) != "seleccionar") {
                        $servicePackage = $request->servicePackage;
                        }
                }
                if (isset($request->scopeGroup)) {
                        if (strtolower($request->scopeGroup) != "seleccionar") {
                        $scopeGroup = $request->scopeGroup;
                        }
                }
 

                $cuarentena->nombre =$request->nombre;
                //$cuarentena->nodo =$nodo;
                //$cuarentena->troba =$troba;
                $cuarentena->jefatura = $request->jefatura;
                $cuarentena->clientes = 0;
                $cuarentena->trobas = count($request->trobas);
                $cuarentena->estado = $request->estado;
                $cuarentena->cuadroMando = $request->cuadroDeMando;
                $cuarentena->tipo = $request->tipoDeCuarentena;
                $cuarentena->servicePackageCrmid = $servicePackage;
                $cuarentena->scopesGroup = $scopeGroup;
                $cuarentena->fechaInicio =$request->fechaInicio;
                $cuarentena->fechaFin =$request->fechaFin;
                $cuarentena->save();

               /* for ($i=0; $i < count($request->trobas); $i++) { 
                        
                    $nodo=substr($request->trobas[$i],0,2);
                    $troba=substr($request->trobas[$i],3,5); 

                     

                    $trobasCuarentena = new TrobasCuarentena;
                    $trobasCuarentena->idCuarentenas = $cuarentena->id;
                    $trobasCuarentena->nodo = $nodo;
                    $trobasCuarentena->troba = $troba;
                    //$trobasCuarentena->servicePackageCrmid = $servicePackage;
                    //$trobasCuarentena->scopesGroup = $scopeGroup;
                    $trobasCuarentena->save();
                     
                }*/

                $valuesInsert = "";
                $cantidadTrobas = count($request->trobas);
                for ($i=0; $i < $cantidadTrobas; $i++) { 
                    
                    $nodo=substr($request->trobas[$i],0,2);
                    $troba=substr($request->trobas[$i],3,5); 

                    $valuesInsert .= "('null',".$cuarentena->id.",'$nodo','$troba')";
                    if ($i+1 < $cantidadTrobas) {
                        $valuesInsert .=", ";
                    }
                
                }

                DB::insert("INSERT INTO zz_new_system.trobas_cuarentenas VALUES $valuesInsert");
                 

                #End Begin Transaction update Rol
            DB::commit();

        }catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
        }catch(\Exception $e){
              //dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
        }

        return $this->mensajeSuccess("Se actualizarón los datos correctamente.");
           
    }

    public function delete(GestionCuarentena $cuarentena)
    { 
        if ($cuarentena->clientes > 0) {
            ClientesCuarentena::where('idCuarentenas', $cuarentena->id)->delete();
        }else{
            TrobasCuarentena::where('idCuarentenas', $cuarentena->id)->delete();
        }
        $cuarentena->delete();

        return $this->mensajeSuccess("la cuarentena se eliminó correctamente.");

    }

    public function saveFile(Request $request)
    {
        
        $valida = Validator::make($request->all(), [
            "estadoDeGuardado" => "required|in:true,false"
        ]);


        if ($valida->fails()) {   
            return $this->errorMessage($valida->errors()->all(),422);
        }
 

        $usuarioAuth = Auth::user();
        $idClienteActivo = $usuarioAuth->id; 
        $gestionCuarentenasGestionF = new GestionCuarentenaFunctions;

        if ($request->estadoDeGuardado == "false") {
             //dd($request->all());
            $gestionCuarentenasGestionF->limpiaCodClientesTemporales($idClienteActivo);//Limpia C. T. anteriores del usuario

            if($request->hasFile('archivo')){ //valida que exista el archivo
                if ($request->file('archivo')->isValid()) { //valida que se haya cargado el archivo correctamente

                    $validaRequerimientos = Validator::make($request->all(), [
                        "nombre" => "required|regex:/^[a-zA-Z0-9_-]+(\s*[a-zA-Z0-9_-]*)*[a-zA-Z0-9_-]+$/",
                        "estado" => "required",
                        "cuadroDeMando" => "required",
                        "tipoDeCuarentena" => "required|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z]+$/",
                        "fechaInicio" => "required|date|date_format:Y-m-d|before:fechaFin",
                        "fechaFin" => "required|date|date_format:Y-m-d|after:fechaInicio"
                    ]);

                    $hoy = date("Y-m-d");
                    if ($hoy > $request->fechaFin) {
                        return $this->errorMessage("La fecha Final no puede ser menor a la fecha actual : $hoy .",422);
                    }
              

                    $nombreCuarentena = htmlspecialchars($request->nombre);
                    $estadoCuarentena =  htmlspecialchars($request->estado);
                    $publicacionCuarentena = htmlspecialchars($request->cuadroDeMando);
                    $tipo = htmlspecialchars($request->tipoDeCuarentena);
                    $fechaInicio = htmlspecialchars($request->fechaInicio);
                    $fechaFin = htmlspecialchars($request->fechaFin);

                    if ($validaRequerimientos->fails()) {   
                        return $this->errorMessage($validaRequerimientos->errors()->all(),422);
                    }
             
                    $archivo = $request->file('archivo');
                    $nombreArhivo = $archivo->getClientOriginalName();  
                    $sizeArchivo = $archivo->getSize(); 
                    
                        if ($sizeArchivo < 70000){ // 20 KB
                            //$temp_file =  tempnam(sys_get_temp_dir(), $nombreArhivo);
                            $content = File::get($archivo); 
                            $arrayClientes = explode("\r\n",$content);   //por saltos de linea    
                            
                            if ($tipo == TipoCuarentenas::TIPO_AVERIAS) {
                        
                                $clientesErrados = array();
                                $clientesNoErrados = array();

                                $armandoQuery = "";
        
                                for ($i=0; $i < count($arrayClientes) ; $i++) { 
            
                                    if (trim($arrayClientes[$i]) != "") {
        
                                        $resultV =  $this->validaClienteCodigos($arrayClientes[$i]);
                                            
                                        if ($resultV["error"]) {
                                            $clientesErrados[] = $arrayClientes[$i] ." : ".$resultV["mensaje"];
                                        }else{
                                            $clientesNoErrados[] = $arrayClientes[$i];

                                            if ($i+1 < $arrayClientes && $i != 0) {
                                                $armandoQuery .=", ";
                                            }
                                            $clientesNoErrados[] = $arrayClientes[$i];
                                            $armandoQuery .= "('".$arrayClientes[$i]."',".$idClienteActivo.",'".$nombreCuarentena."','".$estadoCuarentena."','".$publicacionCuarentena."','".$tipo."','','".$fechaInicio."','".$fechaFin."')"; 
                                              
                                        } 
                                    
                                    }  
                                } 
        
                                if (count($clientesErrados) == count($arrayClientes)) { 
                                    return $this->errorMessage("Todos los datos enviados son inválidos!.",402);
                                } 
        
                                if (count($arrayClientes) > 5000) { 
                                    return $this->errorMessage("Está superando los 5000 clientes a procesar, intente con una cantidad menor.",402);
                                }
        
                                //Guardando en los datos de las consultas en BD 
                                $resultadoInsert = $gestionCuarentenasGestionF->registroCodClientesTemporales(utf8_encode($armandoQuery));
        
                                if (!$resultadoInsert) {
                                    return $this->errorMessage("Se generó un problema en el servidor, intente nuevamente.",402);
                                }
        
                                if (count($clientesErrados) > 0 ) {
                                    return $this->resultData(array(
                                        "procesoResult"=>false,
                                        "cantidadErrores"=>count($clientesErrados),
                                        "errores"=>$clientesErrados,
                                       // "dataProcesar"=>$clientesNoErrados
                                    )); 
                                }
        
                                //En caso de no existir errores
                                return $this->resultData(array(
                                    "procesoResult"=>false,
                                    "cantidadErrores"=>0,
                                    "errores"=>[],
                                   // "dataProcesar"=>$clientesNoErrados
                                )); 

                            }elseif ($tipo == TipoCuarentenas::TIPO_CRITICOS) {
                                 
                           
                               // dd($arrayClientes);
                                $clientesErrados = array();
                                $clientesNoErrados = array();

                                $detalleArrayCli = array();

                                $armandoQueryCriticos = "";
                                    
                                for ($i=0; $i < count($arrayClientes) ; $i++) {

                                   // $claves = preg_split("/[,]+/", $arrayClientes[$i]);
                                    
                                    
                                    //$detalleArrayCli[$i] = preg_split("/[,]+/", $arrayClientes[$i]);
                                    
                                   
                                    //dd($detalleArrayCli[$i]);
                                     $detalleArrayCli[$i] = explode(",",$arrayClientes[$i]);

                                    
 
                                    if (trim($detalleArrayCli[$i][0]) != "") {
        
                                        $resultV =  $this->validaClienteCodigos($detalleArrayCli[$i][0]);
                                         
                                            
                                        if ($resultV["error"]) {
                                            $clientesErrados[] = $detalleArrayCli[$i][0] ." : ".$resultV["mensaje"];
                                        }else{
                                            if ($i+1 < $arrayClientes && $i != 0) {
                                                $armandoQueryCriticos .=", ";
                                            }
                                            //$detalleArrayCli[$i][1] = utf8_encode($detalleArrayCli[$i][1]);
                                           /* if ($i == 50) { 
                                                
                                                dd($arrayClientes[$i], $detalleArrayCli[$i][0], );
                                             }*/
                                            $clientesNoErrados[] = $detalleArrayCli[$i];
                                            $armandoQueryCriticos .= "('".$detalleArrayCli[$i][0]."',".$idClienteActivo.",'".$nombreCuarentena."','".$estadoCuarentena."','".$publicacionCuarentena."','".$tipo."','".strtoupper($detalleArrayCli[$i][1])."','".$fechaInicio."','".$fechaFin."')"; 
                                           
                                        } 
 
                                    }

                                    /*if ($i == 50) {
                                       dd($armandoQueryCriticos);
                                    }*/
                                     
                                     
                                }
                               //dd(str_replace("(", "CADENA", $armandoQueryCriticos););
                              //dd($armandoQueryCriticos);

                                if (count($clientesErrados) == count($arrayClientes)) { 
                                    return $this->errorMessage("Todos los datos enviados son inválidos!.",402);
                                } 
        
                                if (count($arrayClientes) > 5000) { 
                                    return $this->errorMessage("Está superando los 5000 clientes a procesar, intente con una cantidad menor.",402);
                                }

                                 //Guardando en los datos de las consultas en BD 
                                 //para acceder a ello se debe agregar un campo a temporal cuarentena llamado entidad
                                  $resultadoInsert = $gestionCuarentenasGestionF->registroCodClientesTemporales(utf8_encode($armandoQueryCriticos)); 

                                
                                if (!$resultadoInsert) {
                                    return $this->errorMessage("Se generó un problema en el servidor, intente nuevamente.",402);
                                }
                                
        
                                if (count($clientesErrados) > 0 ) {
                                    return $this->resultData(array(
                                        "procesoResult"=>false,
                                        "cantidadErrores"=>count($clientesErrados),
                                        "errores"=>$clientesErrados,
                                        //"dataProcesar"=>$clientesNoErrados
                                    )); 
                                } 
                               // dd($clientesNoErrados);
                                //En caso de no existir errores
                                return $this->resultData(array(
                                    "procesoResult"=>false,
                                    "cantidadErrores"=>0,
                                    "errores"=>[],
                                   // "dataProcesar"=> $clientesNoErrados
                                )); 

                                 

                            }else{
                                return $this->errorMessage("No se encontro el tipo de Cuarentena Indicado.",402);
                            } 
                            
                             
   
                        }else{
                           return $this->errorMessage("La longitud del archivo es superior a 70 KB",402);
                        } 
                   
                   
                }else{
                    return $this->errorMessage("El archivo no se cargo correctamente. Intente nuevamente",402);
                } 
            }else{
                return $this->errorMessage("No existe un archivo que procesar. Intente nuevamente",422);
            }
        }elseif ($request->estadoDeGuardado == "true") {
            // dd("es true, se supone que ya tiene una session de data para exportar el usuario solo los validos");
             
            $clientesCuarentenas = $gestionCuarentenasGestionF->getClientesCuarentenasParaRegistro($idClienteActivo);
            //dd($clientesCuarentenas);
            try {
                DB::beginTransaction(); 

                    #INICIO
                        if (count($clientesCuarentenas)> 0) {

                            $fRegistro=date("Y-m-d H:i:s");

                              
                            $gestionCuarentena = new GestionCuarentena;
                            $gestionCuarentena->nombre = $clientesCuarentenas[0]->nombre;
                            $gestionCuarentena->jefatura = "SIN-JEFATURA";
                            $gestionCuarentena->clientes = count($clientesCuarentenas);
                            $gestionCuarentena->trobas = 0;
                            $gestionCuarentena->estado = $clientesCuarentenas[0]->estado;
                            $gestionCuarentena->cuadroMando = $clientesCuarentenas[0]->cuadroMando;
                            $gestionCuarentena->tipo = $clientesCuarentenas[0]->tipo;
                            $gestionCuarentena->servicePackageCrmid = "";
                            $gestionCuarentena->scopesGroup = "";
                            $gestionCuarentena->fechaInicio = $clientesCuarentenas[0]->fechaInicio;
                            $gestionCuarentena->fechaFin = $clientesCuarentenas[0]->fechaFin;
                            $gestionCuarentena->fechaRegistro = $fRegistro;
                            $gestionCuarentena->save(); 
 

                            $valuesInsert = "";
                            $cantidadClientes = count($clientesCuarentenas);
                            for ($i=0; $i < $cantidadClientes; $i++) { 

                                $idCuarentenas = $gestionCuarentena->id;
                                $idCliente = $clientesCuarentenas[$i]->codcli;
                                $entidad = $clientesCuarentenas[$i]->entidad;
                                $jefatura =  strlen($clientesCuarentenas[$i]->jefatura) == 0 ? "SIN-JEFATURA" : $clientesCuarentenas[$i]->jefatura;
                                $nodo = $clientesCuarentenas[$i]->NODO; 
                                $troba = $clientesCuarentenas[$i]->TROBA; 
                                $servicePackageCrmid = $clientesCuarentenas[$i]->SERVICEPACKAGECRMID;
                                $scopesGroup = $clientesCuarentenas[$i]->SCOPESGROUP;

                                 
                                
                                $valuesInsert .= "('null',".$idCuarentenas.",$idCliente,'$entidad','$jefatura','$nodo','$troba','$servicePackageCrmid','$scopesGroup')";
                                if ($i+1 < $cantidadClientes) {
                                    $valuesInsert .=", ";
                                }
                            
                            }
  
                            DB::insert("INSERT INTO zz_new_system.clientes_cuarentenas VALUES $valuesInsert");

                        }
                    #END
               DB::commit();
            } catch(QueryException $ex){ 
                DB::rollback();
                //dd($ex->getMessage());  
                DB::delete("delete from zz_new_system.gestion_cuarentena where id=$gestionCuarentena->id");

                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
        
            }catch(\Exception $e){
                DB::rollback();
                //dd($e->getMessage());  
                DB::delete("delete from zz_new_system.gestion_cuarentena where id=$gestionCuarentena->id");
                throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
                
            } 
 
           
            return $this->resultData(array(
                "procesoResult"=>true,
                "cantidadErrores"=>0,
                "errores"=>[],
               // "dataProcesar"=>[]
            )); 
             

        }else{
            return $this->errorMessage("No se está indicando el estado adecuado de la cuarentena.",402);
        }
        
    }

    private function  validaClienteCodigos($codigo)
    { 
        $mensaje = "";
        $error = false;
        
            if (preg_match("/^[0-9\.]+$/", $codigo) != 1) {
                $error = true;
                $mensaje = "El codigo del cliente no tiene un formato válido";
            }
            if (substr($codigo,0,1) == 0) {
                $error = true;
                $mensaje = "El codigo del cliente no tiene un formato válido";
            }
               

        return array(
            "error"=>$error,
            "mensaje"=>$mensaje
        );

    }
  
}
