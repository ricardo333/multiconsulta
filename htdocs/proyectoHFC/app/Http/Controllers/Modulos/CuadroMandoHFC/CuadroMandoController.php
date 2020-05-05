<?php

namespace App\Http\Controllers\Modulos\CuadroMandoHFC;

use Illuminate\Http\Request;
use App\Functions\CuadroMandoFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class CuadroMandoController extends GeneralController
{

   public function view()
   {

        $funcionCuadroMando = new CuadroMandoFunctions;

        $categorias = $funcionCuadroMando->getCategorias();

        $resultado = array();
        $resultado["categorias"] = $categorias;

        return view('administrador.modulos.cuadroMando.index',$resultado);
   }

   public function lista(Request $request)
   {
        
        if($request->ajax()){

            $filtroCategoria = "";

            
            if ($request->filtroCategoria!="seleccionar") {
                $filtroCategoria = " WHERE categoria='".$request->filtroCategoria."'";
            }


            #INICIO
            $funcionCuadroMando = new CuadroMandoFunctions;

            $cuadroMando =  $funcionCuadroMando->getCuadroMandoList($filtroCategoria);
            $listaCuadroMando =  $funcionCuadroMando->procesoListaCuadroMando($cuadroMando);

            if ($listaCuadroMando == "error") {
                return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
            }

            return datatables($listaCuadroMando)->toJson();

        }

        return abort(404); 
        
    }

   


}