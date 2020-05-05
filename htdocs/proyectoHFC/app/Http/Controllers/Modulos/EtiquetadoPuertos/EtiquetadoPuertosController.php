<?php

namespace App\Http\Controllers\Modulos\EtiquetadoPuertos;

use Illuminate\Http\Request;
use App\Functions\EtiquetadoPuertosFunctions;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class EtiquetadoPuertosController extends GeneralController
{
    public function view()
   {
      
          $functionesEtiquetadoPuertos = new EtiquetadoPuertosFunctions;
          $listaCmts = $functionesEtiquetadoPuertos->getListCmts();
          return view('administrador.modulos.etiquetadoPuertos.index',[
            "listaCmts"=>$listaCmts
          ]);
          
   }

   public function lista(Request $request)
   { 

        if($request->ajax()){

            #INICIO
                
                $validarCmts = Validator::make($request->all(), [
                    "filtroCmts" => "nullable"
                ]);
                
                $filtroCmts = "";
                $cmtsfil = 'Todo';
                  
                if (!$validarCmts->fails()) {
                    if (isset($request->filtroCmts)) {                 
                        $filtroCmts = trim($request->filtroCmts) != "" ? " AND a.cmts='".$request->filtroCmts."' " : "";
                        $cmtsfil = $request->filtroCmts;
                    }
                }
                
                //$filtroJefatura = "";
                $functionesEtiquetadoPuertos = new EtiquetadoPuertosFunctions;
                $retornoEtiquetadoPuertos =  $functionesEtiquetadoPuertos->getListCmtsxFiltro($filtroCmts);
                //Depuracion de errores
                //dd($retornoEtiquetadoPuertos);
                if ($retornoEtiquetadoPuertos == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                } 
                $etiquetadoPuertosResult = $functionesEtiquetadoPuertos->getProcesarEtiquetadoPuertos($retornoEtiquetadoPuertos,$cmtsfil);
                //dd($etiquetadoPuertosResult);
                if ($etiquetadoPuertosResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
                //dd($etiquetadoPuertosResult);
                return datatables($etiquetadoPuertosResult)->toJson();
            #END

        }
        return abort(404); 
   
   }

   public function actualizar(Request $request)
   { 
        
        $functionesEtiquetadoPuertos = new EtiquetadoPuertosFunctions;
        $retornoActualizarEtiquetadoPuertos =  $functionesEtiquetadoPuertos->actualizarEtiquetadoPuertos($request);

        return $this->resultData(array(
            "t"=>$request->t,
            "r"=>$request->r,
            "n"=>$request->n,
            "resultado"=>'ok',
            "type"=>"intraway"
       ));
   
   }
     
}