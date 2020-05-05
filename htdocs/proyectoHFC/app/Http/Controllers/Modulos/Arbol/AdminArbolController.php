<?php

namespace App\Http\Controllers\Modulos\Arbol;

use Illuminate\Http\Request;
use App\Administrador\PasosArbol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Functions\ArbolDecisionesFunctions;
use App\Http\Controllers\GeneralController;

class AdminArbolController extends GeneralController
{
    public function index()
    {
         return view('administrador.modulos.arbolDecisiones.index');
    }
  
    public function listaPasos(Request $request)
    {
       if($request->ajax()){

            #INICIO

                $usuarioAuth = Auth::user();

                if ($usuarioAuth->tienePermisoEspecial()) {
                    return datatables()
                        ->eloquent(PasosArbol::query())
                        ->only(['id','detalle','btn'])
                        ->addColumn('btn', 'administrador.modulos.arbolDecisiones.partials.acciones')
                        ->rawColumns(['btn'])
                        ->toJson();
                }

                #Filtrando Permisos

                $dataListReturn = datatables()
                                ->eloquent(PasosArbol::query());

                if( $usuarioAuth->HasPermiso('submodulo.arbol-decision.pasos.show')){
                    
                    $dataListReturn = $dataListReturn
                                ->only(['id','detalle','btn'])
                                ->addColumn('btn', 'administrador.modulos.arbolDecisiones.partials.acciones')
                                ->rawColumns(['btn'])
                                ->toJson();
                
                }else{
                    $dataListReturn = $dataListReturn
                                    ->only(['id','detalle'])
                                    ->toJson();
                }  
                
                return $dataListReturn;
            #END
  
        }
  
        return abort(404); 
       
    }

    public function showPaso(Request $request, PasosArbol $paso )
    { 
        $arbolDecisionesFunction = new ArbolDecisionesFunctions;

        $nombreTabla = $paso->nombre; 
        $tablaAnterior =  $paso->tablaAnterior;

        
        $dataPasoTabla = $arbolDecisionesFunction->getListTable($nombreTabla);
        
 
            return view('administrador.modulos.arbolDecisiones.show',[
                                    "cantidad"=>$dataPasoTabla["cantidad"],
                                    "list"=>$dataPasoTabla["listado"],
                                    "nombreTabla"=>$nombreTabla,
                                    "tablaAnterior"=>$tablaAnterior,
                                    "paso"=>$paso->id
                                ]);

       

    }

    public function showPasoAnterior(Request $request, PasosArbol $paso )
    {

        if($request->ajax()){

            $arbolDecisionesFunction = new ArbolDecisionesFunctions;

            $nombreTabla = $paso->nombre;  
            $tablaAnterior =  $paso->tablaAnterior;
     

            $dataPasoTablaAnterior = $arbolDecisionesFunction->getListTable($tablaAnterior);
            
            return $this->resultData(
                                        [
                                            "cantidad"=>$dataPasoTablaAnterior["cantidad"],
                                            "list"=>$dataPasoTablaAnterior["listado"],
                                            "tablaAnterior"=>$tablaAnterior
                                        ]
                                    ); 

        }

    }

    public function estructuraRama(Request $request)
    {
        if($request->ajax()){
            #INICIO
                $tablaName = $request->tabla;
                $idSelected = $request->identificador;

                $arbolDecisionesFunction = new ArbolDecisionesFunctions;
        
                $tablasGenerales = $arbolDecisionesFunction->getTablaDecisionesGeneral();
                
                #CONSTRUCTOR GENERAL DE QUERY
                    $constructorQuery = "SELECT p0.* FROM arboldecisiones.`paso00` p0 ";

                    for ($i=0; $i < count($tablasGenerales["listado"]); $i++) {//recorre lista de tablas decisiones totales
                    
                        //armando query para ver el inicio de todo
                        $nameTable = $tablasGenerales["listado"][$i]->nombre;
                        $namePasoAnterior =$tablasGenerales["listado"][$i]->pasoAnterior;
                        $cant = $i-1;

                        if($namePasoAnterior != null || strlen(trim($namePasoAnterior)) > 0){
                            $constructorQuery .= " INNER JOIN arboldecisiones.$nameTable p$i ON p$cant.`id`= p$i.$namePasoAnterior ";
                        }
            
                        if ($tablasGenerales["listado"][$i]->nombre == $tablaName) { 
                            $constructorQuery .=" WHERE p$i.id=$idSelected LIMIT 1";
                            break;
                        } 

                    }
                #END CONSTRUCCIÓN
                
                #EJECUCIÓN DE QUERY GENERADO
                    $resultArbolRamaCompleta = $arbolDecisionesFunction->executeQueryGeneral($constructorQuery);
                    
                    if($resultArbolRamaCompleta["cantidad"] == 0){
                        return $this->errorMessage("No se encontró el esquema de decisiones.",500); 
                    }
                #END QUERY EJECUCIÓN
                    
                $idSeguimientos = array($resultArbolRamaCompleta["data"][0]->id);
                
                $datosPasosPosteriores[] = array(
                    "tabla"=>$tablasGenerales["listado"][0]->nombre,
                    "tablaAnterior"=>"",
                    "pasoAnterior"=>"",
                    "datos"=>array(
                        "identificadoranterior"=>"",
                        "decisiones"=>$resultArbolRamaCompleta["data"]
                    )
                );
        
                for ($i=1; $i < count($tablasGenerales["listado"]); $i++) {//recorre lista de tablas decisiones totales
        

                        $tabla = $tablasGenerales["listado"][$i]->nombre;
                        $pasoAnterior = $tablasGenerales["listado"][$i]->pasoAnterior;
                        $tablaAnterior = $tablasGenerales["listado"][$i]->tablaAnterior;
                        
                        
                        $preparandoIdSeguimiento = array();
                        $preprandoDatosTablaDecisiones = array();
        
                        for ($j=0; $j < count($idSeguimientos) ; $j++) { 
                            
                        $tablaDatos = $arbolDecisionesFunction->getTablaDecisionesGeneralPorPasoAnterior($tabla,$pasoAnterior,$idSeguimientos[$j]);
                        
                            $preprandoDatosTablaDecisiones[] = array(
                                "identificadoranterior"=>$idSeguimientos[$j],
                                "decisiones"=>$tablaDatos
                            );
                            
                            for ($k=0; $k < count($tablaDatos) ; $k++) { 
                                $preparandoIdSeguimiento[] = $tablaDatos[$k]->id;
                            }
                            
                        }

                        
                        
                        if(count($idSeguimientos)>0){
                            $datosPasosPosteriores[] = array(
                                    "tabla"=>$tabla,
                                    "tablaAnterior"=>$tablaAnterior,
                                    "pasoAnterior"=>$pasoAnterior,
                                    "datos"=>$preprandoDatosTablaDecisiones
                            );
                        }
                        
                        $idSeguimientos = $preparandoIdSeguimiento;
                        $preparandoIdSeguimiento = [];
                        $preprandoDatosTablaDecisiones =[];

                    
                }
        
                #TRANSFORMACION CORRECTA DE DECISIONES SEGUN ARRAY FORMADO
                    $nivelProfundidad = 0;
                    for ($i=count($datosPasosPosteriores)-1; $i > 1 ; $i--) { 
                        //print_r($datosPasosPosteriores[$i]);
                        #Datos Internos 
                            $datosInternosPaso = $datosPasosPosteriores[$i]["datos"];
                            for ($j=0; $j < count($datosInternosPaso); $j++) { 
                                $identificadorPasoAnterior = $datosInternosPaso[$j]["identificadoranterior"];
                                
                                
                                #Recorrer datos adelantados en uno
                                    $datoPasosAdelantadoEnUno = $datosPasosPosteriores[$i-1]["datos"];
        
                                
                                    for ($k=0; $k < count($datoPasosAdelantadoEnUno) ; $k++) { 
                                        #Recorrer Decisiones internas
                                            $decisionesPasosAdelantados = $datoPasosAdelantadoEnUno[$k]["decisiones"];
                                        
                                            for ($l=0; $l < count($decisionesPasosAdelantados); $l++) { 

                                                
                                                if ($identificadorPasoAnterior == $decisionesPasosAdelantados[$l]->id) {
                                                    
                                                    $nivelProfundidad++;
                                                    
                                                    if (isset($datosPasosPosteriores[$i-1]["datos"][$k])) {
        
                                                        
                                                        $datosPasosPosteriores[$i-1]["datos"][$k]["decisiones"][$l]->decisionesGroup = array(
                                                                            "tabla"=>$datosPasosPosteriores[$i]["tabla"],
                                                                            "tablaAnterior"=>$datosPasosPosteriores[$i]["tablaAnterior"],
                                                                            "pasoAnterior"=>$datosPasosPosteriores[$i]["pasoAnterior"],
                                                                            "Alternativas"=> $datosPasosPosteriores[$i]["datos"][$j],
                                                                            "profundidad"=>$nivelProfundidad
                                                                            );
                                                        
                                                    }
                                                    
                                                    
                                                    
                                                }

                                            }
                                        #End Recorrido Decisiones internas
                                    }
                                #End Recorrido datos adelantados en uno
                                

                            }
                        #End Datos internos

                        //eliminando array pasos posteriores ya recorridos
                        unset($datosPasosPosteriores[$i]);
                            
                    }
                #END
                
            
                $datosPasosPosteriores[0]["datos"]["decisiones"][0]->decisionesGroup = array(
                    "tabla"=>$datosPasosPosteriores[1]["tabla"],
                    "tablaAnterior"=>$datosPasosPosteriores[1]["tablaAnterior"],
                    "pasoAnterior"=>$datosPasosPosteriores[1]["pasoAnterior"],
                    "Alternativas"=>$datosPasosPosteriores[1]["datos"][0]
                );
                unset($datosPasosPosteriores[1]);
        
                return $this->resultData([ 
                        "arbol"=>$datosPasosPosteriores
                ]);
            #END
        }
        return abort(404); 
   
    }

    public function storeRama(Request $request)
    {
        //dd($request->all());
        $tablaName = $request->tb;
        $detalle = $request->detalle;
        $idDecision = $request->idDecision;

       // dd($request->all());
        #PROCESAMOS LAS IMAGENES
            $nombreImagenTotal = "sinimagen.png";
            $nombreImagenNegocio = "sinimagen.png";
            $nombreImagenmasiva = "sinimagen.png";
        

            if($request->hasFile('imagen_total')){ //valida que exista la imagen
                if ($request->file('imagen_total')->isValid()) { //valida que se haya cargado el archivo correctamente
                    $image = $request->file('imagen_total');
                    $filename = $image->getClientOriginalName();  
                    Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen

                    $nombreImagenTotal = $filename; 
                } 
            } 
            if($request->hasFile('imagen_negocio')){ //valida que exista la imagen
                if ($request->file('imagen_negocio')->isValid()) {//valida que se haya cargado el archivo correctamente
                    $image = $request->file('imagen_negocio');
                    $filename = $image->getClientOriginalName();  
                    Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen

                    $nombreImagenNegocio = $filename; 
                }  
            } 
            if($request->hasFile('imagen_masiva')){ //valida que exista la imagen
                if ($request->file('imagen_masiva')->isValid()) {//valida que se haya cargado el archivo correctamente
                    $image = $request->file('imagen_masiva');
                    $filename = $image->getClientOriginalName();  
                    Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen

                    $nombreImagenmasiva = $filename; 
                }   
            } 

        #END

        $arbolDecisionesFunction = new ArbolDecisionesFunctions;

        $dataTablaPorNombre = $arbolDecisionesFunction->getTablaPorNombre($tablaName);

        if (isset($idDecision)) {
 
            $getDataTablaPorNombreAdelantado = $arbolDecisionesFunction->getTablaPorNombre($dataTablaPorNombre->tablaSiguiente); 
             
            if(trim($getDataTablaPorNombreAdelantado->tablaSiguiente) == "" || $getDataTablaPorNombreAdelantado->tablaSiguiente == null){
                //Al no contener una tabla siguiente, se creará una nueva tabla 
                // de la misma forma se registrará esta tabla en pasosArbol
                $arbolDecisionesFunction->storeNewTableTree($getDataTablaPorNombreAdelantado->posicion,$getDataTablaPorNombreAdelantado->nombre);
               
            }

            $storeRamaNueva = $arbolDecisionesFunction->arbolStoreDecision(
                                            $getDataTablaPorNombreAdelantado->nombre,
                                            $detalle,
                                            $getDataTablaPorNombreAdelantado->pasoAnterior,$idDecision,
                                            $nombreImagenTotal,$nombreImagenNegocio,$nombreImagenmasiva);

            $dataPasoTabla = $arbolDecisionesFunction->getListTable($getDataTablaPorNombreAdelantado->nombre);

            return $this->resultData(
                                                [ 
                                                    "nuevaRama"=>array(
                                                        "idStore" =>$storeRamaNueva,
                                                        "tabla"=>$getDataTablaPorNombreAdelantado->nombre,
                                                        "pasoAnterior"=>$getDataTablaPorNombreAdelantado->pasoAnterior
                                                    ),
                                                    "ramaPadre"=>array(
                                                        "idDecision"=>$idDecision,
                                                        "tablaPadre"=>$tablaName
                                                    ),
                                                    "mensaje"=>"se registó la decisión correctamente.",
                                                    "cantidad"=>$dataPasoTabla["cantidad"],
                                                    "list"=>$dataPasoTabla["listado"],
                                                    "nombreTabla"=>$getDataTablaPorNombreAdelantado->nombre

                                                ]
                                            );

            
        }
 

        #EN CASO NO TENGA UN PADRE RAMA REFERENCIAL
            $storeRamaNueva = $arbolDecisionesFunction->arbolStoreDecision(
                                $tablaName,
                                $detalle,
                                null,null,
                                $nombreImagenTotal,$nombreImagenNegocio,$nombreImagenmasiva);

            
            $dataPasoTabla = $arbolDecisionesFunction->getListTable($tablaName);
    
    
            return $this->resultData(
                                    [ 
                                        "mensaje"=>"se registó la decisión correctamente.",
                                        "cantidad"=>$dataPasoTabla["cantidad"],
                                        "list"=>$dataPasoTabla["listado"],
                                        "nombreTabla"=>$tablaName
                                    ]
            ); 
            
           
        #END

         

    }

   
    public function updateRama(Request $request)
    {
       // dd($request->all());

       if($request->ajax()){
           
           #INICIO

                $newtext = $request->newText;
                $table = $request->pasoDecision;
                $idEdit = $request->idEdit;
        
                $arbolDecisionesFunction = new ArbolDecisionesFunctions;
        
                $nombreImagenTotal = "";
                $nombreImagenNegocio = "";
                $nombreImagenmasiva = "";
        
                if($request->hasFile('imagen_total')){ //valida que exista la imagen
                    if ($request->file('imagen_total')->isValid()) { //valida que se haya cargado el archivo correctamente
                        $image = $request->file('imagen_total');
                        $filename = $image->getClientOriginalName();  
                        Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen
        
                        $nombreImagenTotal = $filename; 
                    } 
                }
                if($request->hasFile('imagen_negocio')){ //valida que exista la imagen
                    if ($request->file('imagen_negocio')->isValid()) {//valida que se haya cargado el archivo correctamente
                        $image = $request->file('imagen_negocio');
                        $filename = $image->getClientOriginalName();  
                        Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen
        
                        $nombreImagenNegocio = $filename; 
                    }  
                } 
                if($request->hasFile('imagen_masiva')){ //valida que exista la imagen
                    if ($request->file('imagen_masiva')->isValid()) {//valida que se haya cargado el archivo correctamente
                        $image = $request->file('imagen_masiva');
                        $filename = $image->getClientOriginalName();  
                        Storage::disk('arbol')->put($filename, file_get_contents($image)); // almacenamos la nueva imagen
        
                        $nombreImagenmasiva = $filename; 
                    }   
                } 
        
                $arbolDecisionesFunction->updateArbolDecision($table,$idEdit,$newtext,$nombreImagenTotal,
                                                            $nombreImagenNegocio,$nombreImagenmasiva);
        
                $listTablaUpdate = $arbolDecisionesFunction->getListTable($table);
        
                
        
                return $this->resultData(
                    [ 
                        "mensaje"=>"se actualizaron los cambios correctamente.",
                        "cantidad"=>$listTablaUpdate["cantidad"],
                        "list"=>$listTablaUpdate["listado"],
                        "idUpdate"=>$idEdit,
                        "nombreTabla"=>$table //Para el result en update table
                        ]
                );
           #END
       }
       return abort(404); 
 
        

    }

    public function deleteRama(Request $request)
    {
        $datosEliminar = $request->arrayDelete;
        $tablaName = $request->tbNamePage;

        $arbolDecisionesFunction = new ArbolDecisionesFunctions;

        $resultEliminacion = $arbolDecisionesFunction->deletesChildsArrayTree($datosEliminar);

        if($resultEliminacion){

            $dataPasoTabla = $arbolDecisionesFunction->getListTable($tablaName);

           // return $this->mensajeSuccess("se eliminaron los datos correctamente."); 

            return $this->resultData(
                                    [ 
                                        "mensaje"=>"se eliminaron los datos correctamente.",
                                        "cantidad"=>$dataPasoTabla["cantidad"],
                                        "list"=>$dataPasoTabla["listado"],
                                        "nombreTabla"=>$tablaName
                                    ]
                    ); 

        }

        return $this->errorMessage("Se generó un error en el proceso con la BD, intente nuevamente.",409);
       
    }


}
