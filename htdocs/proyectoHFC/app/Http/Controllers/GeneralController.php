<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SystemResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Functions\peticionesGeneralesFunctions;

class GeneralController extends Controller {
     use SystemResponser;

     public function historicoNodoTroba(Request $request)
     {
          if($request->ajax()){
               
               #INICIO

                         $validator = Validator::make($request->all(), [
                         "nodo" => "required|regex:/^[a-zA-Z0-9]+$/",
                         "troba" => "required|regex:/^[a-zA-Z0-9]+$/" 
                         ]);
                    
                         if ($validator->fails()) {    
                         // return response()->json(["error"=>true,"message"=>$validator->errors()->all()]);
                         //  {"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":[],"input":{"nodo":"FD"}}
                         return $this->errorDataTable($validator->errors()->all(),402);
                         } 

                         $nodo=$request->nodo;
                         $troba=$request->troba;
                         
                         $functionsGeneral = new peticionesGeneralesFunctions; 
                         $listaHistorico = $functionsGeneral->getHistorialNodoTroba($nodo,$troba);
     
     
                         return datatables($listaHistorico)->toJson();
                         
               #END
          }

          return abort(404); 
     }
     
 
}