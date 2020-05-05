<?php

namespace App\Http\Controllers\Modulos\Rol;

use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Permiso;
use App\Functions\LogsFunctions;
use App\Http\Requests\RolRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;

class RolController extends GeneralController
{
 
  public function index()
  {
 
    return view('administrador.modulos.rol.index');
  }

  public function lista(Request $request)
  {
     if($request->ajax()){
      $usuarioAuth = Auth::user();
      
      #Filtrando lista de Roles segun los subroles del admin o subadmin
        $roles = Role::getSubRolesByRolUser($usuarioAuth);
        $dataListReturn = datatables()
                          ->collection($roles);
      
          if( $usuarioAuth->HasPermiso('submodulo.rol.show') || 
              $usuarioAuth->HasPermiso('submodulo.rol.edit')  ||
              $usuarioAuth->HasPermiso('submodulo.rol.delete')
            ){
              
              $dataListReturn = $dataListReturn
                                ->only(['id','nombre','estado','btn'])
                                ->addColumn('btn', 'administrador.modulos.rol.partials.acciones')
                                ->rawColumns(['btn'])
                                ->toJson();
              
            }else{
              $dataListReturn = $dataListReturn
                                ->only(['id','nombre','estado'])
                                ->toJson();
            }  
              
            return $dataListReturn;
         
      #End Filtro
       
    }
    return abort(404); 
  }

  public function permisos(Role $rol,Request $request){
    if ($request->ajax()) {
  
        if($rol->esRolEspecial()){
            $permisos = Permiso::all();
            return $this->showContJsonAll($permisos);
        }

        $permisos = $rol->permisos;
        //dd($permisos);
        return $this->showContJsonAll($permisos);
    }
    return abort(404);
  }

  
  public function show(Role $rol)
  {
    return view('administrador.modulos.rol.detalle',
      [
        "rol"=>$this->showModJsonOne($rol),
        "permisos"=>$this->showContJsonAll($rol->permisos)
      ]
    );
  }

  public function edit(Role $rol)
  {
    $usuarioAuth = Auth::user();
    
    $rolesReferencia = Role::getSubRolesByRolUser($usuarioAuth);//Lista de roles según sus referencias
    $rolesReferencia = $rolesReferencia->where('id','!=',$rol->id)->values()->unique();//retiramos el id editado para que no se referencie como padre
      
    if ($usuarioAuth->tienePermisoEspecial() && isset($rol->referencia)) { 
        $modulos = Role::find($rol->referencia)->permisos()->where('tipo',Permiso::TIPO_MODULO)->get();
    }else{
      if (isset($rol->referencia)) {  
        $modulos  = Role::find($rol->referencia)->permisos()->where('tipo',Permiso::TIPO_MODULO)->get();
      }else{

        $modulos  = Permiso::where('tipo',Permiso::TIPO_MODULO)->get(); 
         
      }
    }

 
    $permisosSegunUser = Permiso::getPermisosRoleByUser($usuarioAuth);//arma permisos segun rol en el esquema de modulos
    //$permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos
     
    $permisosSegunRol = $rol->permisos; //Permisos de rol editado
    
     
    return view('administrador.modulos.rol.edit',[
        "rol"=>$this->showModJsonOne($rol),
        "roles"=>$this->showContJsonAll($rolesReferencia),
        "modulos"=>$this->showContJsonAll($modulos),
        "permisos"=>$this->showContJsonAll($permisosSegunUser),
        //"permisosUser"=>$this->showContJsonAll($permisos_user),
        "permisosChecked"=>$this->showContJsonAll($permisosSegunRol)
    ]);
  }

  public function update(Role $rol, RolRequest $request)
  {

      $usuarioAuth = Auth::user();
      $logsFunctions = new LogsFunctions;
 
      $oldRol = $rol->nombre;
      $oldEstado = $rol->estado;
      $oldAccesoTotal = $rol->especial;
      $oldRolpadre = $rol->referencia;
      
      $rolPadre = $oldRolpadre;
       
      try {
        DB::beginTransaction();
          #begin Transaction Update Rol
         
            if($request->filled('nombre')){ //preguntamos si mando un campo nombre y no esta vacio
                $rol->nombre = $request->nombre;
            }

            if($request->filled('estado')){
                $rol->estado = $request->estado;
            }

            if($request->filled('especial')){
                $rol->especial = $request->especial;
            }
    
            if ($usuarioAuth->tienePermisoEspecial()) {//(si es admin)
              if($request->filled('referencia')){ // (si mando un campo referencia y no esta vacio)
                  $rol->referencia = $request->referencia;
                  $rolPadre = $request->referencia;
              } 
            }else{
              $rol->referencia = $usuarioAuth->role_id;
              $rolPadre = $usuarioAuth->role_id;
            }
   
            $rol->permisos()->sync($request->get('permisos'));//actualiza los permisos por usuario 
        
            $rol->save();

            $logsFunctions->registroLog($logsFunctions::LOG_ROLES,array(
                                          "usuario" => $usuarioAuth->username,
                                          "perfil" => $usuarioAuth->role->nombre,
                                          "accion" => "update",
                                          "rol" => $oldRol,
                                          "estado" =>  $oldEstado,
                                          "acceso_total" => $oldAccesoTotal,
                                          "rol_padre" => $oldRolpadre,
                                          "new_rol" => $rol->nombre,
                                          "new_estado" => $rol->estado,
                                          "new_acceso_total" => $request->filled('especial') ? $rol->especial : $oldAccesoTotal,
                                          "new_rol_padre" => $rolPadre
                                    )
                            );
 

          #End Begin Transaction update Rol
        DB::commit();

      }catch(QueryException $ex){ 
         // dd($ex->getMessage()); 
          DB::rollback();
          return $this->errorMessage("Hubo un problema en la actualización, intente nuevamente!.",402);
      }catch(\Exception $e){
         // dd($e->getMessage()); 
          DB::rollback();
          return $this->errorMessage("Hubo un error inesperado!, intente nuevamente!.",402);
      }

      return $this->showModJsonOne($rol);
  }


  public function create()
  {
    $usuarioAuth = Auth::user();
    $roles = Role::getSubRolesByRolUser($usuarioAuth);//Lista de roles según sus referencias

    $permisosBloqueadosRol = Permiso::getPermisosRolBloqueadosSpecialByUser($usuarioAuth)->pluck('id')->all();//Permisos Bloqueados

    //Armar esquema de Modulos y permisos segùn disponga el admin o subadmin 
    $modulos = User::getModulosByUserAuth($usuarioAuth);//arma el esquema de modulos
    $permisos_role = Permiso::getPermisosRoleByUser($usuarioAuth)->whereNotIn('id', $permisosBloqueadosRol)->values();//arma permisos segun rol en el esquema de modulos
    $permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos

    //dd($permisos_user);
    return view('administrador.modulos.rol.create',[
        "rolesDisponibles"=>$this->showContJsonAll($roles),
        "modulos"=>$this->showContJsonAll($modulos),
        "permisosRol"=>$this->showContJsonAll($permisos_role),
        "permisosUser"=>$this->showContJsonAll($permisos_user),
    ]);

  }

  public function store(RolRequest $request)
  {
     
  
    $rol = new Role;
    $logsFunctions = new LogsFunctions;
    
    $usuarioAuth = Auth::user();
      
     try {  
       
        DB::beginTransaction();
 
          $rol->nombre = $request->nombre;
          $rolPadre = "";

          if($request->filled('especial')){ //preguntamos si mando un campo expecial y no esta vacio
              $rol->especial = $request->especial;
          }
          if($request->filled('estado')){ //preguntamos si mando un campo estado y no esta vacio
              $rol->estado = $request->estado;
          }
         
          if ($usuarioAuth->tienePermisoEspecial()) {//(si es admin)
             
            if($request->filled('referencia')){ // (si mando un campo referencia y no esta vacio)
              $rol->referencia = $request->referencia;
              $rolPadre = $request->referencia;
            } 
          }else{
            $rol->referencia = $usuarioAuth->role_id;
            $rolPadre = $usuarioAuth->role_id;
          }

         // dd("paso tiene permiso");
 
 
          $rol->save();

          $rol->permisos()->sync($request->permisos);//crea vinculo al id del permiso
          
          $logsFunctions->registroLog($logsFunctions::LOG_ROLES,array(
                                                        "usuario" => $usuarioAuth->username,
                                                        "perfil" => $usuarioAuth->role->nombre,
                                                        "accion" => "store",
                                                        "rol" => $rol->nombre,
                                                        "estado" =>  $rol->estado,
                                                        "acceso_total" => $request->filled('especial') ? $rol->especial : "NO",
                                                        "rol_padre" => $rolPadre,
                                                        "new_rol" => "",
                                                        "new_estado" => "",
                                                        "new_acceso_total" => "",
                                                        "new_rol_padre" => ""
                                                  )
                                      );
 
        DB::commit();
    }catch(QueryException $ex){ 
         // dd($ex->getMessage()); 
        DB::rollback();
        return $this->errorMessage("Hubo un problema en el registro, intente nuevamente verificando que los campos estén completos!.",402);
    }catch(\Exception $e){
          //dd($e->getMessage()); 
        DB::rollback();
        return $this->errorMessage("Hubo un error inesperado!, intente nuevamente verificando que los campos estén completos!!.",402);
    }
    return $this->showModJsonOne($rol);
     
  } 
  
  public function delete(Role $rol)
  {
    $usuarioAuth = Auth::user();
    $logsFunctions = new LogsFunctions;

    $rol->permisos()->sync([]);//elimina vinculo

    $rol->delete();

    $logsFunctions->registroLog($logsFunctions::LOG_ROLES,array(
                                                                "usuario" => $usuarioAuth->username,
                                                                "perfil" => $usuarioAuth->role->nombre,
                                                                "accion" => "delete",
                                                                "rol" => $rol->nombre,
                                                                "estado" =>  $rol->estado,
                                                                "acceso_total" => $rol->especial,
                                                                "rol_padre" => $rol->referencia,
                                                                "new_rol" => "",
                                                                "new_estado" => "",
                                                                "new_acceso_total" => "",
                                                                "new_rol_padre" => ""
                                                          )
                              );

    return $this->showModJsonOne($rol);
  }

  
 

}
