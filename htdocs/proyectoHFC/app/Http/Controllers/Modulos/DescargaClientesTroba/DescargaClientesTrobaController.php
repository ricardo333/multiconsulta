<?php

namespace App\Http\Controllers\Modulos\DescargaClientesTroba;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\peticionesGeneralesFunctions;

class DescargaClientesTrobaController extends GeneralController
{
    public function view()
    {

        $generalFunctions = new peticionesGeneralesFunctions;

        $trobas = $generalFunctions->getTrobasTotales();
       // $interfaces = $generalFunctions->getInterfaces();
        $nivelesPuerto = $generalFunctions->getNivelesPorPuerto();

        return view('administrador.modulos.descargaClientesTroba.index',
            [
                "trobas"=>$trobas,
                // "interfaces"=>$interfaces,
                "nivelesPuerto"=>$nivelesPuerto
            ]
        );
    }

    public function interfacesLista(Request $request)
    {
        if($request->ajax()){
            #INICIO
                $generalFunctions = new peticionesGeneralesFunctions;
                $interfaces = $generalFunctions->getInterfaces();
        
                return $this->resultData(array( 
                    "cantidad"=>count($interfaces),
                    "lista" =>$interfaces
                    ) 
                );
            #END
        }
        return abort(404); 
        
    }

    public function filtro(Request $request)
    {

        if($request->ajax()){
            #INICIO
                //dd($request->all());
                $interfaceValida = Validator::make($request->all(), [
                    "interfaces" => "nullable|not_in:seleccionar,Seleccionar|array"
                ]);
                $trobasValida = Validator::make($request->all(), [
                    "trobas" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9]+$/"
                ]);
                $NivelesTrobaValida = Validator::make($request->all(), [
                    "nivelesPorPuerto" => "nullable|not_in:seleccionar,Seleccionar|regex:/^[a-zA-Z0-9_\/\.\-]+(\s*[a-zA-Z0-9_\/\.\-]*)*[a-zA-Z0-9_\/\.\-]+$/"
                ]);

                $interfaces = [];
                $trobas = "";
                $nivelesPorPuerto = "";

                //$generalFunctions = new peticionesGeneralesFunctions;


                if (!$interfaceValida->fails()) {
                    if (isset($request->interfaces)) $interfaces = count($request->interfaces) > 0 ? $request->interfaces : [];  
                }
                if (!$trobasValida->fails()) { 
                    if (isset($request->trobas))   $trobas = trim($request->trobas) != "" ? $request->trobas : "";   
                }
                if (!$NivelesTrobaValida->fails()) {
                    if (isset($request->nivelesPorPuerto))  $nivelesPorPuerto = trim($request->nivelesPorPuerto) != "" ? $request->nivelesPorPuerto : "";  
                }
                
                if (count($interfaces) == 0 && $trobas == "" && $nivelesPorPuerto == "" ) {
                     return $this->errorMessage("No se puede procesar el filtro al no enviar datos válidos, intente nuevamente.",402);
                } 

                #Procesar Interfaces
                    $queryInterfaces = "";
                    $cantidadTrobasList = array();
                    if (count($interfaces)> 0) {
                        $concat='';
                        for ($i=0;$i<count($interfaces);$i++)    
                        {      
                            $concat .= $interfaces[$i]; 
                            if(count($interfaces) > $i +1)  $concat .= "','";
                        } 
                        $queryInterfaces="'".$concat."'";
                        //$cantidadTrobasList = $generalFunctions->getCantidadTrobasByInterfaces($queryInterfaces); 
                    }   
                        
                #END

                return $this->resultData(array(
                    "hayFiltroTrobas"=> $trobas != "",
                    "dataTroba"=> $trobas == "" ? [] : array(
                                                            "nodo" => substr($trobas,0,2),
                                                            "troba" => substr($trobas,2,4)
                                                        ),
                    "hayFiltroInterfaces" => count($interfaces) > 0,
                    "dataInterfaces"=> count($interfaces) == 0 ? [] : array(
                                                                "cantidad"=> count($interfaces),
                                                                "interfaces"=>  $request->interfaces,  
                                                                "data"=> $queryInterfaces    
                                                        ),
                    "hayFiltroNiveles" =>  $nivelesPorPuerto != "",
                    "dataNiveles"=> $nivelesPorPuerto == "" ? [] : array(
                                                            "puerto" => $nivelesPorPuerto
                                                        ), 
                ));
            #END
        }
        return abort(404); 
        
    }

    public function cantidadTrobasPorInterface(Request $request)
    {
        if($request->ajax()){
            #INICIO
                $interfaceValida = Validator::make($request->all(), [
                    "interfaces" => "required"
                ]);
        
                if ($interfaceValida->fails()) {
                    return $this->errorMessage("Se requiere de interfaces para procesar la data.",402);
                }
        
                //dd($request->all());
        
                $generalFunctions = new peticionesGeneralesFunctions;
        
                $cantidadTrobasList = $generalFunctions->getCantidadTrobasByInterfaces($request->interfaces); 
        
                return $this->resultData(array(
                                            "cantidad" => count($cantidadTrobasList),
                                            "lista" =>  $cantidadTrobasList
                                            ) 
                                    );
            #END
        }
        return abort(404); 
         
    }

    public function promedioNivelesPorPuerto(Request $request)
    {
        if($request->ajax()){
            #INICIO
                //dd($request->all());

                $generalFunctions = new peticionesGeneralesFunctions;

                $listaNiveles = $generalFunctions->getPromediosNivelesCmtsPorPuertos($request->puerto);

                if ($listaNiveles == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 

                if (count($listaNiveles) == 0) {
                    return datatables($listaNiveles)->toJson();
                }

                $nivelesResult = $generalFunctions->getProcesarNivelesCmtsPuertos($listaNiveles);

                return datatables($nivelesResult)->toJson();
            #END
        }
        return abort(404); 
        
 
    }

    public function historicoNivelesCmtsPorPuerto(Request $request)
    {
        if($request->ajax()){
            #INICIO 
                //dd($request->all());
                $generalFunctions = new peticionesGeneralesFunctions;
        
                $listaNiveles = $generalFunctions->getHistoricoNivelesCmtsPorPuertos($request->puerto);
                
                if ($listaNiveles == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                    
                }
                if (count($listaNiveles) == 0) {
                    return datatables($listaNiveles)->toJson();
                }
        
                $nivelesResult = $generalFunctions->getProcesarNivelesCmtsPuertos($listaNiveles);
        
                return datatables($nivelesResult)->toJson();
            #END
        }
        return abort(404); 

    }
}
