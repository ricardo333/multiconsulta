<?php

namespace App\Http\Controllers\Modulos\TrabajosProgramados;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Administrador\ParametroColores;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\MonitoreoAveriasFunctions;
use App\Functions\TrabajosProgramadosFunctions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TrabajosProgramadosController extends GeneralController
{

    public function index(Request $request)
    {

        $trabajosProgFunction = new TrabajosProgramadosFunctions;
        $functionesMonitoreoAv = new MonitoreoAveriasFunctions;
        $usuarioAuth = Auth::user();

        $arrayReturn = array();
       // $microzonas = $trabajosProgFunction->getMicrozonas();
        $jefaturas = $functionesMonitoreoAv->getJefaturasAverias();
        $estadosTP = $trabajosProgFunction->getEstadosTP();

        $nodoTrobas = array();
        $permisoStore = false;

        $arrayReturn["jefaturas"] = $jefaturas;
        $arrayReturn["estados"] = $estadosTP;

        if ($usuarioAuth->HasPermiso('submodulo.trabajos-programados.store')) {
            $nodoTrobas = $trabajosProgFunction->getNodoTrobas();
            $tipoTrabajo = $trabajosProgFunction->getTipoTrabajoProgramado();
            $supervisorTDP = array();
            $fecha_inicio_enviar = "";
            if (count($tipoTrabajo)>0) {
                $supervisorTDP = $trabajosProgFunction->getSupervisorTDPByTipoTrabajoId($tipoTrabajo[0]->id);
                $fecha_inicio_enviar = $trabajosProgFunction->getFechaSegunTipoTrabajo($tipoTrabajo[0]->id);
            }
            
            $arrayReturn["nodoTrobas"] = $nodoTrobas;
            $arrayReturn["nodoTrobasJson"] = json_encode($nodoTrobas,true);
            $arrayReturn["tipoTrabajo"] = $tipoTrabajo;
            $arrayReturn["supervisorTDP"] = $supervisorTDP;
            $arrayReturn["supervisorTDPJson"] = json_encode($supervisorTDP,true);
            $arrayReturn["fechaInicio"] = $fecha_inicio_enviar;
        }

        if ($usuarioAuth->HasPermiso('submodulo.trabajos-programados.mantenimiento')) {
            $supervisoresGenerales = $trabajosProgFunction->getSupervisorGeneral();
            $arrayReturn["supervisoresGenerales"] = $supervisoresGenerales;
        }
 
        return view('administrador.modulos.trabajosProgramados.index',$arrayReturn);
    }

    public function lista(Request $request)
    {
 
       // if($request->ajax()){
            #INICIO
                $filtroJefatura = "";
                $filtroEstado = "";
                $joinJefatura = "";
        
                $validarJefatura = Validator::make($request->all(), [
                    "jefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]); 
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "estado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]); 
        
                if (!$validarJefatura->fails()) {  
                    if (isset($request->jefatura)) {   
                        $filtroJefatura = trim($request->jefatura) != "" ? " where jef.jefatura='".$request->jefatura."' " : "";
                    }   
                }  
                if ($filtroJefatura != "") {
                    $joinJefatura =  "  inner join catalogos.jefaturas jef on tp.nodo=jef.nodo ";
                }

                if (!$validarEstado->fails()) {
                    if (isset($request->estado)) {  
                        if (trim($filtroJefatura) == "") {
                            $filtroEstado = trim($request->estado) != "" ? " where tp.estado ='".trim($request->estado)."' " : "";
                        } else{ 
                            $filtroEstado = trim($request->estado) != "" ? " and tp.estado ='".trim($request->estado)."' " : "";
                        } 
                    } 
                }

               // dd($filtroJefatura."----".$filtroEstado);

                 

               // dd($filtroJefatura );
        
        
                $trabajosProgFunction = new TrabajosProgramadosFunctions;
        
                $listaTrabajosProg = $trabajosProgFunction->getTrabajosProgramadosList($filtroJefatura,$filtroEstado,$joinJefatura);
                
                if ($listaTrabajosProg == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
        
                $listaProcesoTrabProg = $trabajosProgFunction->procesarTrabajoProg($listaTrabajosProg);
            
                    
                return datatables($listaProcesoTrabProg)->toJson();
            #END
       // }
//
       // return abort(404); 

         
    }

    public function detallePorNodoTroba(Request $request)
    {
        if($request->ajax()){
           #INICIO
                $validaNodoTroba = Validator::make($request->all(), [
                    "nodo" => "required|regex:/^[a-zA-Z0-9]+$/",
                    "troba" => "required|regex:/^[a-zA-Z0-9]+$/"
                ]);


                if ($validaNodoTroba->fails()) {   
                    return $this->errorDataTable($validaNodoTroba->errors()->all(),422);
                } 

                $trabajoProgFunction = new TrabajosProgramadosFunctions;

                $nodo = $request->nodo;
                $troba = $request->troba;

            
                $detalleTrab = $trabajoProgFunction->detailsByNodoTroba($nodo,$troba);
                // dd($detalleTrab);
                return $this->resultData(array(
                    "data"=>$detalleTrab
                ));
           #END
       }
       
       return abort(404); 
 
      
    }

    public function detallePorItem(Request $request, $item)
    {
        if($request->ajax()){
            #INICIO
            
                 $valida = Validator::make(array("item"=>$item), [
                     "item" => "required|regex:/^[0-9]+$/",
                 ]);
  
                 if ($valida->fails()) {   
                     return $this->errorMessage($valida->errors()->all(),422);
                 } 
 
                $trabajoProgFunction = new TrabajosProgramadosFunctions;
  
                

                $resultadoDataFinal = array();

                

                if($request->filled('formulario')){ //preguntamos no mando un campo  y no esta vacio
                       
                    if ($request->formulario == "APERTURA") {

                        $detalleTrab = $trabajoProgFunction->detailsPendientesByItem($item);

                        if (count($detalleTrab) == 0) {
                            return $this->errorMessage("No se encontraron datos del trabajo Programado Indicado, verifique que exista la data.",422);
                        }

                        $resultadoDataFinal["data"]=$detalleTrab[0];

                        $fecha_actual = date("Y-m-d");
                        $hora_anterior = date("H", (strtotime ("-1 Hours")));
                        $tecnicos= $trabajoProgFunction->getTecnicos();
                        $contratas= $trabajoProgFunction->getContratas();

                        $resultadoDataFinal["dataApertura"]= array(
                            "fechaApertura"=>$fecha_actual,
                            "hora"=>$hora_anterior,
                            "tecnicos"=>$tecnicos,
                            "contratas"=>$contratas
                        );

                    }
                    if ($request->formulario == "CIERRE") { 

                        $detalleTrab = $trabajoProgFunction->detailsEnProcesoByItem($item);

                        if (count($detalleTrab) == 0) {
                            return $this->errorMessage("No se encontraron datos del trabajo Programado Indicado, verifique que exista la data.",422);
                        }

                        $resultadoDataFinal["data"]=$detalleTrab[0];

                        $fecha_actual = date("Y-m-d");
                        $hora_anterior = date("H", (strtotime ("-1 Hours")));
                        $trabajos= $trabajoProgFunction->getTipoTrabajoGeneral();
                        $tecnicos= $trabajoProgFunction->getTecnicos();
                        $contratas= $trabajoProgFunction->getContratas();

                        $resultadoDataFinal["dataCierre"]= array(
                            "fechaCierre"=>$fecha_actual,
                            "hora"=>$hora_anterior,
                            "trabajos"=>$trabajos,
                            "tecnicos"=>$tecnicos,
                            "contratas"=>$contratas
                        );

                    }
                }else{

                    $detalleTrab = $trabajoProgFunction->detailsPendientesByItem($item);

                    if (count($detalleTrab) == 0) {
                        return $this->errorMessage("No se encontraron datos del trabajo Programado Indicado, verifique que exista la data.",422);
                    }

                    $resultadoDataFinal["data"]=$detalleTrab[0];

                }
 
                     
                return $this->resultData($resultadoDataFinal);
               
            #END
        }
        
        return abort(404); 
    }

    public function detallesTipoTrabajo($tipoTrabajo)
    {
        $validar = Validator::make(array("tipoDeTrabajo"=>$tipoTrabajo), [
            "tipoDeTrabajo" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
        ]); 

        if ($validar->fails()) { 
           return $this->errorMessage($validar->errors()->all(),422);
        } 

        $trabajosProgFunction = new TrabajosProgramadosFunctions;

        $id_trabajo = (int)$tipoTrabajo;
    
        $supervisorTDP = $trabajosProgFunction->getSupervisorTDPByTipoTrabajoId($id_trabajo);
        $fecha_inicio_enviar = $trabajosProgFunction->getFechaSegunTipoTrabajo($id_trabajo);

        return $this->resultData(
            array(
                "supervisorTDP"=>$supervisorTDP,
                "fechaInicio"=>$fecha_inicio_enviar
            )
        );
       
    }

    public function store(Request $request)
    {   
        //dd($request->all());

        $validar = Validator::make($request->all(), [
            "nodoPlano" => "required|array|min:1",
            "amplificador" => "required",
            "remedy" => "nullable|min:8",
            "supervisor" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/",
            "celularSupervisorTDP" => "nullable|regex:/^[0-9]+$/",
            "fechaMinima" => "required|date|date_format:Y-m-d",
            "fechaInicio" => "required|date|date_format:Y-m-d|after_or_equal:fechaMinima",
            "HoraInicio" => "required|date_format:H:i|before:HoraTermino",
            "HoraTermino" => "required|date_format:H:i|after:HoraInicio",
            "corteServicio" => "required"
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        } 
        if(!$request->filled('supervisorText')){ //preguntamos no mando un campo  
            return $this->errorMessage("No se reconoce al supervisor, intente nuevamente.",422);
        }
       /* if(!$request->filled('fechaMinima')){ //preguntamos no mando un campo  
            return $this->errorMessage("No se reconoce la fecha de Inicio, intente nuevamente.",422);
        }*/

        #INICIO
            $nodo_plano = $request->nodoPlano;
            $amplificador = $request->amplificador;
            $tipo_Trabajo_select_text = $request->tipoTrabajoText;
            $id_tipo_trabajo =$request->tipoTrabajo;
            $remedy = $request->remedy;
            $supervisor = $request->supervisorText;
            $fecha_minima = $request->fechaMinima;
            $fecha_inicio = $request->fechaInicio;
            $h_inicio = $request->HoraInicio;
            $h_termino = $request->HoraTermino;
            $corte_sev = $request->corteServicio;
            $afectacion =  $request->afectacion;
            $celsuptdp =  $request->celularSupervisorTDP; 
        #END

        $trabajosProgFunction = New TrabajosProgramadosFunctions;
        $fecha_minima = $trabajosProgFunction->getFechaSegunTipoTrabajo($id_tipo_trabajo);
        $fecha_inicio= strtotime($fecha_inicio);
        $fecha_valida = strtotime($fecha_minima);

        if($fecha_inicio < $fecha_valida){
            return $this->errorMessage("La fecha de inicio debe ser despues o igual a la fecha mostrada.",422); 
        }
         //"REPARACION EDIFICIO" - "TRABAJOS DE EMERGENCIA"
        if( ($id_tipo_trabajo != 22 && $id_tipo_trabajo != 23) && trim($remedy) == "" ){  //"REPARACION EDIFICIO" - "TRABAJOS DE EMERGENCIA"
            return $this->errorMessage("El Remedy es requerido para este tipo de trabajo seleccionado!.",422); 
        }  
 
        $usuario = Auth::user()->nombre;
        $horario = ($h_inicio >= '00:00' && $h_inicio <='06:00') ? 'MADRUGADA' :'DIA';

        $data = $request->all();
        $data["horario"] = $horario;
        $data["usuario"] = $usuario; 
        $data["tipo_Trabajo_select"] = $request->tipo_Trabajo_select_text;
        $trabajosProgFunction->SetRegisterTrabProg($data);

          
        return $this->mensajeSuccess("El trabajo programado se guardo correctamente.");
        
    }

    public function cancelarTP(Request $request,$item)
    {

        $valida = Validator::make(array("item"=>$item), [
            "item" => "required|regex:/^[0-9]+$/",
        ]);

        if ($valida->fails()) {   
            return $this->errorMessage($valida->errors()->all(),422);
        } 

        $trabajoProgFunction = new TrabajosProgramadosFunctions;

        $observaciones = "";

        if($request->filled('observaciones')){ //preguntamos no mando un campo  ¿y no esta vacio
            $observaciones = $request->observaciones;
        }

         
        $trabajoProgFunction->cancelarTPPendienteByItem($item,$observaciones);

        return $this->mensajeSuccess("El trabajo Programado se canceló correctamente.");


    }

    public function aperturarTP(Request $request,$item)
    {
 
        $validaItem = Validator::make(array("item"=>$item), [
            "item" => "required|regex:/^[0-9]+$/",
        ]);

        if ($validaItem->fails()) {   
            return $this->errorMessage($validaItem->errors()->all(),422);
        } 

        $validaGeneral = Validator::make($request->all(), [
            "fechaDeApertura" => "required",
            "hora" => "required",
            "tecnico" => "required|not_in:seleccionar,Seleccionar",
            "celSupContrata" => "nullable|max:10|min:7|regex:/^[0-9]+$/",
            "telefono" => "nullable|max:10|min:7|regex:/^[0-9]+$/",
            "contrata" => "required|not_in:seleccionar,Seleccionar",
            'imagenEstado' => 'mimes:jpeg,jpg,png|required' // max 10000kb => |max:10000
        ]);
        if ($validaGeneral->fails()) {   
            return $this->errorMessage($validaGeneral->errors()->all(),422);
        } 

        if($request->file('imagenEstado')->isValid()){
            $fechaImagen=date('YmdHis'); 
            $image = $request->file('imagenEstado');
            $nombreArhivo = $fechaImagen."_".$image->getClientOriginalName();
            Storage::disk('trabajosProgramados')->put($nombreArhivo, file_get_contents($image)); // almacenamos la nueva imagen

            #PROCESO
                $fecha = $request->fechaDeApertura;
                $hora = $request->hora;
                $tecnico = $request->tecnico;
                $contrata = $request->contrata;

                $fecha_actual = date("Y-m-d");
                $fecha_enviada= strtotime($fecha);
                $fecha_hoy = strtotime($fecha_actual);
                //validando fecha
                if($fecha_enviada < $fecha_hoy){
                    return $this->errorMessage("La fecha enviada no puede ser menor a: $fecha_actual",422); 
                }

                $hora_anterior = date("H", (strtotime ("-1 Hours")));//una hora menos
                $hora_enviada = strtotime($hora);//hora enviada
                $una_hora_antes = strtotime(date($hora_anterior.":00"));

                //Validando hora
                if($hora_enviada < $una_hora_antes){
                    return $this->errorMessage("La hora enviada no puede ser menor a: $hora_anterior  H.",422); 
                } 

                $fapertura=date("Y-m-d H:i:s");
                
                $trabajosProgFunction = New TrabajosProgramadosFunctions;
                $usuario = Auth::user()->nombre;

                $dataEnviar = $request->all();
                $dataEnviar["usuario"] = $usuario;
                $dataEnviar["item"] = $item;
                $dataEnviar["nombreImagen"] = $nombreArhivo;

                $trabajosProgFunction->updateTrabajoProgramadoApertura($dataEnviar);

                return $this->mensajeSuccess("Se aperturo el trabajo correctamente.");
            #END

        }else{
            return $this->errorMessage("La imagen no se cargo correctamente. Intente nuevamente",500);
        } 
 
          
    }

    public function cerrarTP(Request $request,$item)
    {
        
        $validaItem = Validator::make(array("item"=>$item), [
            "item" => "required|regex:/^[0-9]+$/",
        ]);

        if ($validaItem->fails()) {   
            return $this->errorMessage($validaItem->errors()->all(),422);
        } 

        $validaGeneral = Validator::make($request->all(), [
            "fechaDeCierre" => "required|date|date_format:Y-m-d",
            "horaDeCierre" => "required",
            "horaDeInicio" => "required|date_format:H:i|before:horaDeCierre",
            "horaDeCierre" => "required|date_format:H:i|after:horaDeInicio",
            "trabajo" => "required|not_in:seleccionar,Seleccionar",
            "tecnico" => "required|not_in:seleccionar,Seleccionar",
            "telefonoTecnico" => "required|max:10|min:7|regex:/^[0-9]+$/",
            "contrata" => "required|not_in:seleccionar,Seleccionar",
            'imagenEstado' => 'mimes:jpeg,jpg,png|required' // max 10000kb => |max:10000
        ]);
        if ($validaGeneral->fails()) {   
            return $this->errorMessage($validaGeneral->errors()->all(),422);
        } 

        if($request->file('imagenEstado')->isValid()){
            $fechaImagen=date('YmdHis'); 
            $image = $request->file('imagenEstado');
            $nombreArhivo = $fechaImagen."_".$image->getClientOriginalName();
            Storage::disk('trabajosProgramados')->put($nombreArhivo, file_get_contents($image)); // almacenamos la nueva imagen

            #INICIO PROCESO
                $fechaDeCierre = $request->fechaDeCierre; 
                $hora_inicio = $request->horaDeInicio; 
                $hora_final = $request->horaDeCierre; 
                $tecnico = $request->tecnico;
                $contrata = $request->contrata;
        
                $fecha_actual = date("Y-m-d");
                $fecha_enviada= strtotime($fechaDeCierre);
                $fecha_hoy = strtotime($fecha_actual);
                //validando fecha
                if($fecha_enviada < $fecha_hoy){ 
                    return $this->errorMessage("La fecha de cierre enviada no puede ser menor a: $fecha_actual",422); 
                }
            
        
                $hora_anterior = date("H", (strtotime ("-1 Hours")));//una hora menos
                $hora_enviada_final = strtotime($hora_final);//hora enviada
                $una_hora_antes = strtotime(date($hora_anterior.":00"));
        
                //Validando hora inicio
                if($hora_enviada_final < $una_hora_antes){
                    return $this->errorMessage("La hora de inicio enviada no puede ser menor a: $hora_anterior",422); 
                } 
                
        
                $trabajosProgFunction = New TrabajosProgramadosFunctions;
                $usuario = Auth::user()->nombre;
                $fcierre=date("Y-m-d H:i:s");
        
                $data_update = $request->all();
                $data_update["fcierre"] = $fcierre;
                $data_update["usuario"] = $usuario;
                $data_update["item"] = $item;
                $data_update["nombreImagen"] = $nombreArhivo;
        
                $trabajosProgFunction->updateTrabajoProgramadoCierre($data_update);
        
                return $this->mensajeSuccess("Se cerró el trabajo correctamente.");
            #END

        }else{
            return $this->errorMessage("La imagen no se cargo correctamente. Intente nuevamente",500);
        }
         
    }

    public function MantenimientoTrobas(Request $request)
    {

        $validar = Validator::make($request->all(), [
            "nodo" => "required|regex:/^[a-zA-Z0-9]+$/",
            "troba" => "required|regex:/^[0-9]+$/"
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        }  
        
        $nodo=strtoupper($request->nodo);
        $troba="R".$request->troba;
        
    
        $trabajosProgFunction = New TrabajosProgramadosFunctions;
 
        $trabajosProgFunction->insertNodoTroba($nodo,$troba);
        
        return $this->mensajeSuccess("Los datos se guardarón corectamente.");
        
    }

    public function MantenimientoTipoTrabajo(Request $request)
    {
 
        $validar = Validator::make($request->all(), [
            "tipoDeTrabajo" => "required|regex:/^[a-zA-Z0-9 _-]+$/",
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        }  

       
        $trabajo=strtoupper($request->tipoDeTrabajo);
    
        $trabajo2=str_replace(" ","_",$trabajo);
           
        $trabajosProgFunction = New TrabajosProgramadosFunctions;
            
        $trabajosProgFunction->insertTipoTrabajo($trabajo2,$trabajo);
             
        return $this->mensajeSuccess("Los datos se guardarón corectamente.");
        
    }

    public function MantenimientoSupervisor(Request $request)
    {
 
        $validar = Validator::make($request->all(), [
            "supervisor" => "required|regex:/^[a-zA-Z ]+$/"
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        }  
  
        $super= strtoupper($request->supervisor);
        $super2=str_replace(" ","_",$super);

        $trabajosProgFunction = New TrabajosProgramadosFunctions;
        
        $trabajosProgFunction->insertSupervisor($super2,$super);
        
        return $this->mensajeSuccess("Los datos se guardarón corectamente.");
        
    }

    public function listaTipoTrabajoBySupervisor(Request $request, $supervisor)
    {

        //dd($supervisor);

        $validar = Validator::make(array("supervisor"=>$supervisor), [
            "supervisor" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        } 
            
        $id_supervisor = $supervisor;

        $trabajosProgFunction = New TrabajosProgramadosFunctions;
        
        $listaTrabajosNoAsig =  $trabajosProgFunction->tipoTrabajoNoAsignadoSupervisorById($id_supervisor);
        $listaTrabajosAsig =  $trabajosProgFunction->tipoTrabajoAsignadoSupervisorById($id_supervisor);
        
        
        return $this->resultData(array(
            "listadoSinAsignar"=>$listaTrabajosNoAsig,
            "listadoAsignados"=>$listaTrabajosAsig
        ));
            
    }

    public function updateTipoTrabajoBySupervisor(Request $request, $supervisor)
    {

        $validar = Validator::make(array("supervisor"=>$supervisor), [
            "supervisor" => "required|not_in:seleccionar,Seleccionar|regex:/^[0-9]+$/"
        ]); 

        if ($validar->fails()) { 
            return $this->errorMessage($validar->errors()->all(),422);
        }

        $validarTrabajos = Validator::make($request->all(), [
            "trabajos" => "nullable|array"
        ]); 

        if ($validarTrabajos->fails()) { 
            return $this->errorMessage($validarTrabajos->errors()->all(),422);
        }
        
        $trabajosProgFunction = New TrabajosProgramadosFunctions;
        $trabajosProgFunction->insertTrabajoSupervisor($supervisor,$request->trabajos);

        return $this->mensajeSuccess("Los datos se actualizarón corectamente.");


    }

    public function graficaLlamadasTroba(Request $request)
    {
       
        $nodo = $request->nodo;
        $troba = $request->troba;

        $trabajosProgFunction = New TrabajosProgramadosFunctions;

        $cantidad = $trabajosProgFunction->cantidadmaximaLlamada($nodo,$troba);
        
        if ($cantidad[0]->total <= 1) {
            return $this->errorMessage("No hay llamadas en esta troba.",500);
        }

        $dataGrafico = $trabajosProgFunction->getDataGraficoLlamadas($nodo,$troba);

        //segunGraficoLlamadas

        $parametrosTTPP = ParametroColores::getTrabajosProgramadosParametros();
        $coloresGrafico = $parametrosTTPP->COLORES->segunGraficoLlamadas->colores;
       

        return $this->resultData(array(
            "data"=>$dataGrafico,
            "colores"=>$coloresGrafico,
            "total"=>$cantidad[0]->total,
            "hora"=>$cantidad[0]->hora,
            "nodo"=>$nodo,
            "troba"=>$troba
        ));

    }


}
