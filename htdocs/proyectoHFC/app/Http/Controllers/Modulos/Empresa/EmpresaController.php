<?php

namespace App\Http\Controllers\Modulos\Empresa;

use Illuminate\Http\Request;
use App\Administrador\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;

class EmpresaController extends GeneralController
{
    public function index(){
        return view('administrador.modulos.empresa.index');
    }

    public function lista(Request $request){
        if($request->ajax()){

            $usuarioAuth = Auth::user();
            
            #Filtrando lista de Empresas
              $empresa = Empresa::all();
             
              $dataListReturn = datatables()
                                ->collection($empresa);
            
                if( $usuarioAuth->HasPermiso('submodulo.empresa.show') || 
                    $usuarioAuth->HasPermiso('submodulo.empresa.edit')  ||
                    $usuarioAuth->HasPermiso('submodulo.empresa.delete')
                  ){
                    
                    $dataListReturn = $dataListReturn
                                      ->only(['id','nombre','btn'])
                                      ->addColumn('btn', 'administrador.modulos.empresa.partials.acciones')
                                      ->rawColumns(['btn'])
                                      ->toJson();
                    
                  }else{
                    $dataListReturn = $dataListReturn
                                      ->only(['id','nombre'])
                                      ->toJson();
                  }  
                    
                  return $dataListReturn;
               
            #End Filtro
             
         }
        return abort(404); 
       
    }

    public function show(Empresa $empresa){
        return view('administrador.modulos.empresa.detalle',
            [
                "empresa"=>$this->showModJsonOne($empresa)
            ]);
    }

    public function edit(Empresa $empresa){
        return view('administrador.modulos.empresa.edit',
        [
            "empresa"=>$this->showModJsonOne($empresa)
        ]);
    }

    public function create()
    {
        return view('administrador.modulos.empresa.create');
    }

    public function store(EmpresaRequest $request)
    {
          
        $empresa = new Empresa;
         
         try {  
           
            DB::beginTransaction();
     
              $empresa->nombre = $request->nombre;
     
              $empresa->save();
     
            DB::commit();
        }catch(QueryException $ex){ 
           // dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en el registro, intente nuevamente verificando que los campos estén completos!.",402);
        }catch(\Exception $e){
           // dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente verificando que los campos estén completos!!.",402);
        }
        return $this->showModJsonOne($empresa);
    }

    public function update(Empresa $empresa,EmpresaRequest $request){
 
        try {
            DB::beginTransaction();
                #begin Transaction Update Rol
             
                if($request->filled('nombre')){ //preguntamos si mando un campo nombre y no esta vacio
                    $empresa->nombre = $request->nombre;
                }
  
                $empresa->save();

                #End Begin Transaction update Rol
            DB::commit();

        }catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
        }catch(\Exception $e){
            //dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
        }

        return $this->showModJsonOne($empresa);
    }

    public function delete(Empresa $empresa){
        $empresa->delete();

        return $this->showModJsonOne($empresa);
    }
}
