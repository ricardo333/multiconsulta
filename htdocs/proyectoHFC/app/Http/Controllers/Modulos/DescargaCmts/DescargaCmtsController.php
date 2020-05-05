<?php

namespace App\Http\Controllers\Modulos\DescargaCmts;

use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;
use App\Functions\DescargaCmtsFunctions;
use App\Functions\peticionesGeneralesFunctions;

class DescargaCmtsController extends GeneralController
{

    public function view()
    {                                                                                                 
        return view('administrador.modulos.descargaCmts.index');
    }


    public function lista(Request $request)
    { 

        if($request->ajax()){
            #INICIO
                $funcionDescargaCmts = new DescargaCmtsFunctions;
                $retornoDescargaCmts =  $funcionDescargaCmts->getListaDescargaCmts();
                //Depuracion de errores
                
                if ($retornoDescargaCmts == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }

                //dd($retornoDescargaCmts);

                $estadoDescargaCmtsResult = $funcionDescargaCmts->getProcesarDescargaCmts($retornoDescargaCmts);
                //dd($estadoDescargaCmtsResult);
                if ($estadoDescargaCmtsResult == "error") {
                    return $this->errorDataTable("Se generó un conflicto con los datos, intente dentro de un minuto por favor.",500);
                }
 
                return datatables($estadoDescargaCmtsResult)->toJson();
                //dd($caidasMasivas);

            #END

        }
        return abort(404); 
   
    }


    public function descargarArchivos(Request $request)
    {

        $archivo = $request->archivo;

        $origen = '/tftpboot/'.$archivo;
        $destino = '/temp/'.$archivo;

        $url = Storage::disk('sftp')->download($origen);

        return $url;

    }










}