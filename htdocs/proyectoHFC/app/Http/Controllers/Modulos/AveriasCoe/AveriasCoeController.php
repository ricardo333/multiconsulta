<?php

namespace App\Http\Controllers\Modulos\AveriasCoe;

use Illuminate\Http\Request;
use App\Administrador\Parametrosrf;
use Illuminate\Support\Facades\Auth;
use App\Functions\AveriasCoeFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class AveriasCoeController extends GeneralController
{

    public function view()
    {
        $functionPeticionesGenerales = new peticionesGeneralesFunctions;
        

        $jefaturas =  $functionPeticionesGenerales->getJefaturas();
        $trobas =  $functionPeticionesGenerales->getTrobas();

        return view('administrador.modulos.averiasCoe.index',[
            "jefaturas"=>$jefaturas,
            "trobas"=>$trobas
        ]);

    }

    public function lista(Request $request){
        
         if($request->ajax()){

            #INICIO
 
                $filtroJefatura = "";
                $filtroEstado = "";
                $filtroTroba = "";

                
                $validarJefatura = Validator::make($request->all(), [
                    "jefatura" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z\-_]+$/"
                ]); 
                $validarEstado = Validator::make($request->all(), [ //Validando texto con caracteres y espacios.
                    "estado" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z-_:.]+(\s*[a-zA-Z-_:.]*)*[a-zA-Z-_:.]+$/"
                ]);  
                $validarTroba = Validator::make($request->all(), [
                    "troba" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_-]+$/"
                ]);  


                if (!$validarJefatura->fails()) {
                    if (isset($request->jefatura)) {   
                        $filtroJefatura = trim($request->jefatura) != "" ? " and ar.zonal='".$request->jefatura."' " : "";
                    }  
                }
                if (!$validarEstado->fails()) {
                    if (isset($request->estado)) {   
                        $filtroEstado = trim($request->estado) != "" ? "  and tg.EstadoDelCaso='".$request->estado."' " : "";
                        if ( trim($request->estado) ==  "SIN_ESTADO") {
                            $filtroEstado =  "and tg.EstadoDelCaso is null";
                        }
                    }  
                    
                }
                
                if (!$validarTroba->fails()) {
                    //dd($request->troba);
                    if (isset($request->troba)) {   
                        $nodoTroba = explode("_",$request->troba);
                        $filtroTroba = " and ar.nodohfc= '".$nodoTroba[0]."' and ar.trobahfc='".$nodoTroba[1]."' ";
                    }  
                }

                $functionAveriaCoe = new AveriasCoeFunctions;

                $listaAveriaCoe =  $functionAveriaCoe->getListaCoe($filtroJefatura,$filtroTroba,$filtroEstado);
                //dd($listaAveriaCoe);
            
                if ($listaAveriaCoe == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 

                return datatables($listaAveriaCoe)->toJson();

            #END
        }
        return abort(404); 
    }

    public function ruidosInterfaz(Request $request)
    {

       if ($request->ajax()) {
            #INICIO
                if (!isset($request->interface)) {
                    return $this->errorDataTable("No se pudo identificar la interface, intente con una interfaz válida.",422);
                }

                $peticionesGeneralesFunction = new peticionesGeneralesFunctions;

                $listaHistorico = $peticionesGeneralesFunction->getHistoricoInterface($request->interface);

                if ($listaHistorico == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 

                $parametrosRF = Parametrosrf::getHistoricoRuidosRF();
                //dd($parametrosRF);

                //dd($listaHistorico);
                return datatables($listaHistorico)
                        ->with([
                            'niveles' => $parametrosRF
                        ])
                        ->toJson();
            #END
       }
       return abort(404);  
    }

    public function gestViewGestion(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            #INICIO
                $codigosReq = $request->codigosRequerimientos;
                $cantidad = count($codigosReq);
        
                $queryCodigosReq = "(";
                for ($i=0; $i < $cantidad; $i++) {
                    $queryCodigosReq .= "$codigosReq[$i]";  
                    if ($i + 1 < $cantidad)  $queryCodigosReq .= ",";  
                }
                $queryCodigosReq .= ")"; 
        
                $functionAveriaCoe = new AveriasCoeFunctions;

                //dd($queryCodigosReq);
        
                $listaClientes =  $functionAveriaCoe->getDataClientByIn($queryCodigosReq);
        
                if ($listaClientes == "error") {
                    return $this->errorMessage("Se generó un problema interno al traer los datos. intente nuevamente dentro de un minuto.",500); 
                }
        
                return $this->resultData( 
                        array( 
                            "clientes"=>$listaClientes,
                            'detalleView' => json_encode(view(
                                                'administrador.modulos.averiasCoe.partials.gestion',
                                                [
                                                    "clientes"=>$listaClientes
                                                ]
                                                )->render(),JSON_UNESCAPED_UNICODE),
                        )
                );
            #END
        }
        return abort(404);    
    }

    public function storeGestion(Request $request)
    {
        if ($request->ajax()) {
            #INICIO
                if (!isset($request->dataClientes)) {
                    return $this->errorMessage("Los datos de Clientes a procesar es requerido. intente enviar la data correctamente.",422); 
                }

                $dataClientes = $request->dataClientes;
                $cantidad = count($dataClientes);

                if ($cantidad == 0) {
                    return $this->errorMessage("No hay data de clientes enviado. Intente enviar la data correctamente",422);  
                }

                if (!isset($request->segundaLinea)) {
                    return $this->errorMessage("El campo Segunda Linea es requerido.",422);
                }
                if (!isset($request->resultadoSegundaLinea)) {
                    return $this->errorMessage("El campo Resultado es requerido.",422);
                }
                if (!isset($request->detalleResultado)) {
                    return $this->errorMessage("El campo Detalle Resultado es requerido.",422); 
                }
                if (!isset($request->personaContacto)) {
                    return $this->errorMessage("El campo Persona de Contacto es requerido.",422); 
                }
                if (!isset($request->numeroContacto)) {
                    return $this->errorMessage("El campo Número de Contacto es requerido.",422); 
                }

                for ($k=0; $k < $cantidad; $k++) { 
                    if (    ($dataClientes[$k]['codcli'] == "" || $dataClientes[$k]['codcli'] == null) && 
                            ($dataClientes[$k]['codreq'] == "" || $dataClientes[$k]['codreq'] == null) &&
                            ($dataClientes[$k]['macaddress'] == "" || $dataClientes[$k]['macaddress'] == null) &&
                            ($dataClientes[$k]['codsrv'] == "" || $dataClientes[$k]['codsrv'] == null) &&
                            ($dataClientes[$k]['nodohfc'] == "" || $dataClientes[$k]['nodohfc'] == null) &&
                            ($dataClientes[$k]['trobahfc'] == "" || $dataClientes[$k]['trobahfc'] == null)
                
                        ) {
                            return $this->errorMessage("No se puede procesar porque hace falta uno de estos datos: Codigo del cliente, 
                                                        Codigo de requerimiento, MacAddress, Codigo de Servicio, Nodo, Troba.",422); 

                    }
                }

                $usuarioAuth = Auth::user();

                //dd($usuarioAuth);
        
                $segundaLinea = $request->segundaLinea;
                $resultadoSegundaLinea = $request->resultadoSegundaLinea;
                $detalleResultado = $request->detalleResultado;
                $personaContacto = $request->personaContacto;
                $numeroContacto = $request->numeroContacto;
                $observacionResultado = $request->observacionResultado;
                $EstadoDelCaso = $request->EstadoDelCaso;
                $ResultadoVisita = $request->ResultadoVisita;
                $observacionVisitaTecnica = $request->observacionVisitaTecnica;

                $idUsuario = $usuarioAuth->id;
                $username = $usuarioAuth->nombre;
                $idArea = $usuarioAuth->role->nombre;
                

                $fecha = date("Y-m-d H:i:s");

                $queryInsert = "insert ignore triaje.gestion_triaje values ";

                for ($m=0; $m < $cantidad; $m++) { 

                    $codigoCiente = $dataClientes[$m]['codcli'];
                    $codigoRequerimiento = $dataClientes[$m]['codreq'];
                    $mac = $dataClientes[$m]['macaddress'];
                    $codigoServicio = $dataClientes[$m]['codsrv'];
                    $nodo = $dataClientes[$m]['nodohfc'];
                    $troba = $dataClientes[$m]['trobahfc'];

                    $queryInsert .= " 
                                    ( '".$nodo."','".$troba."',".$codigoCiente.",'".$mac."',
                                        ".$codigoServicio.",'".$codigoRequerimiento."','".$username."',
                                        '".$segundaLinea."','".$resultadoSegundaLinea."','".$detalleResultado."',
                                        '".$personaContacto."','".$numeroContacto."','".$observacionResultado."',
                                        '".$EstadoDelCaso."','".$ResultadoVisita."','".$observacionVisitaTecnica."',
                                        '".$fecha."'
                                        )
                                    ";
                    if ($m + 1 < $cantidad) $queryInsert .= " , " ;
                }

                $averiasCoeFunction = new AveriasCoeFunctions;

                // dd($queryInsert);
                $resultadoGestion = $averiasCoeFunction->registroGestionCoe($queryInsert);
                // dd($resultadoGestion);

                if ($resultadoGestion == "error") {
                    return $this->errorMessage("Se generó un problema interno al registrar los datos. intente nuevamente dentro de un minuto.",500); 
                }

                return $this->mensajeSuccess("La gestión se registró correctamente.");
            #END
        }
        return abort(404);    
 
    }

    public function historicoGestion(Request $request)
    {
        if ($request->ajax()) {
            #INICIO
                if (!isset($request->codigoCliente)) {
                    return $this->errorMessage("El campo Código del Cliente es requerido. Verifique la data enviada.",422);
                }
                if ( trim($request->codigoCliente) == "" || (int)$request->codigoCliente == 0) {
                    return $this->errorMessage("El Código del cliente enviado no es válido. Verifique la data enviada",422);
                }

                $averiasCoeFunction = new AveriasCoeFunctions;

                $resultadoHistorico = $averiasCoeFunction->getDetailsByClienteCode($request->codigoCliente);

                if ($resultadoHistorico == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }

                return datatables($resultadoHistorico)->toJson();
            #END
        }
        return abort(404);    
  
    }

 

}
