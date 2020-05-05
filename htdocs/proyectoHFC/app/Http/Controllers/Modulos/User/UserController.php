<?php

namespace App\Http\Controllers\Modulos\User;

use UserFunctions;
use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Empresa;
use App\Administrador\Permiso;
use App\Administrador\Parametro;
use App\Functions\LogsFunctions;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\GeneralController;

class UserController extends GeneralController
{
    public function index()
    {
        return view('administrador.modulos.user.index');
    }
 
    public function lista(Request $request)
    {

      if($request->ajax()){

        $usuarioAuth = Auth::user();
        if ($usuarioAuth->tienePermisoEspecial()) {
              return datatables()
                ->eloquent(User::query())
                ->only(['id','nombre','apellidos','dni','username','email','btn'])
                ->addColumn('btn', 'administrador.modulos.user.partials.acciones')
                ->rawColumns(['btn'])
                ->toJson();
        }
        #Filtrando lista de usuarios segun los subroles del admin o subadmin
          $subroles = Role::getSubRolesByRolUser($usuarioAuth)->toJson();
          
          $array_lista_usuario = array_map(function($element){
              return $element->id;
          },json_decode($subroles));
          
          $dataListReturn = datatables()
                            ->eloquent(User::filterByRole($array_lista_usuario));
        
            if( $usuarioAuth->HasPermiso('submodulo.usuario.show') || 
                $usuarioAuth->HasPermiso('submodulo.usuario.edit')  ||
                $usuarioAuth->HasPermiso('submodulo.usuario.delete')
              ){
                
                $dataListReturn = $dataListReturn
                                  ->only(['id','nombre','apellidos','dni','username','email','btn'])
                                  ->addColumn('btn', 'administrador.modulos.user.partials.acciones')
                                  ->rawColumns(['btn'])
                                  ->toJson();
                
              }else{
                $dataListReturn = $dataListReturn
                                  ->only(['id','nombre','apellidos','dni','username','email'])
                                  ->toJson();
              }  
                
              return $dataListReturn;
          
            
          
        #End Filtro
        
           
      }
      return abort(404); 
    }

    public function show(User $usuario)
    { 
        $permisosGenerales = Permiso::getAllPermisosByUser($usuario);
  
        return view('administrador.modulos.user.detalle',[
            "usuario"=>$this->showModJsonOne($usuario),
            "permisos"=>$this->showContJsonAll($permisosGenerales)
        ]);
    }

    public function edit(User $usuario)
    {
        $usuarioAuth = Auth::user();

        $empresas = Empresa::all(); 
        $roles = Role::getSubRolesByRolUser($usuarioAuth);
       // $modulos_permisos = Permiso::all();//arma el esquema de modulos
        $permisosBloqueadosRolAuth = Permiso::getPermisosRolBloqueadosSpecialByUser($usuarioAuth)->pluck('id')->all();//Permisos Bloqueados del autenticado
 
       //Armar esquema de Modulos y permisos segùn disponga el admin o subadmin 
        $modulos = User::getModulosByUserAuth($usuarioAuth);//arma el esquema de modulos
        $permisos_role = Permiso::getPermisosRoleByUser($usuarioAuth)->whereNotIn('id', $permisosBloqueadosRolAuth)->values();//arma permisos segun rol en el esquema de modulos
        $permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos

        //Checked de permisos de usuario a editar
        //$permisosBloqueadosRol = $usuario->permisos()->wherePivot('tipo','Retirado')->get()->pluck('id')->values()->all(); //Permisos Bloqueados
        $permisosBloqueadosRol = Permiso::getPermisosRolBloqueadosSpecialByUser($usuario)->pluck('id')->all();
        $permisosCheckedRol = Permiso::getPermisosRoleByUser($usuario)->whereNotIn('id', $permisosBloqueadosRol)->values(); //Quitando los bloqueados
        $permisosCheckedUser = Permiso::getPermisosSpecialByUser($usuario);
        
        //dd($permisosBloqueadosRol);
       
 
        
        return view('administrador.modulos.user.edit',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos"=>$this->showContJsonAll($modulos),
            "permisosRol"=>$this->showContJsonAll($permisos_role),
            "permisosEspeciales"=>$this->showContJsonAll($permisos_user),
            "permisosCheckedRol"=>$this->showContJsonAll($permisosCheckedRol),
            "permisosBloqueados"=>$this->resultData($permisosBloqueadosRol),
            "permisosCheckedUser"=>$this->showContJsonAll($permisosCheckedUser),
            "usuario"=>$this->showModJsonOne($usuario)
        ]);
    }

    public function update(User $usuario, Empresa $empresa, Role $rol, UserUpdateRequest $request)
    { 
  
        
        $userFunctions = new UserFunctions;
        $logsFunctions = new LogsFunctions;
        $usuarioAuth = Auth::user();

        if($request->filled('password')){
            //Verifica que haya pasado x tiempo necesario de su ultimo cambio para un update
            $tiempoCloqueoCambioPassword = (int)Parametro::getMinutosInhabilitarCambioPassword();
            if($userFunctions->CambioDePasswordBloqueadoPorTiempo($usuario->username,$tiempoCloqueoCambioPassword)){//valida password
                return $this->errorMessage("No puede actualizar la contraseña del usuario, deben pasar los $tiempoCloqueoCambioPassword minutos de su último cambio.",422);
            }
           $userFunctions->esValidoNuevoPassword($usuario,$request->password);//valida password
        }

        
        $registroActionAdmin = false;
        $oldEmpresa = $usuario->empresa_id != $empresa->id ? $usuario->empresa->nombre : "";
        $oldRol = $usuario->role_id != $rol->id ? $usuario->role->nombre : "";

        if ($usuario->empresa_id != $empresa->id || $usuario->role_id != $rol->id) {
            $registroActionAdmin = true;
        }
 

        #GET IP
            $estacionUsuario = $userFunctions->get_ip_address();
        #END
        
         
        try {
            DB::beginTransaction();
              #begin Transaction Update User
                //insertamos el password actual en el log
                if($request->filled('password')){
                    $userFunctions->registrarPasswordOld($request->password,$usuario->username);
                    $logsFunctions->registroLog($logsFunctions::LOG_PASSWORD,array(
                                    "usuario"=>$usuario->username,
                                    "rol"=>$usuario->role->nombre,
                                    "newPassword"=>$request->password
                                ));
                }

                if($request->filled('estado')){
                    if ($request->estado == User::ESTADO_ACTIVO) {
                        $userFunctions->limpiarLogAccesosPorUsuario($usuario->username);
                        $userFunctions->limpiarLogPasswordPorUsuario($usuario->username);
                    }
                }
                  
                $usuario->empresa_id = $empresa->id;
                $usuario->role_id = $rol->id;
                

                if($request->filled('nombre')){ //preguntamos si mando un campo nombre y no esta vacio
                    $usuario->nombre = $request->nombre;
                }
        
                if($request->filled('apellidos')){
                    $usuario->apellidos = $request->apellidos;
                }

                if($request->filled('dni')){
                    $usuario->dni = $request->dni;
                }

                if($request->filled('telefono')){
                    $usuario->telefono = $request->telefono;
                }

                if($request->filled('email') && $usuario->email != $request->email){
                    $usuario->email = $request->email;
                }

                if($request->filled('password')){ 
                    $usuario->password = bcrypt($request->password);
                }

                if($request->filled('estado')){ 
                    $usuario->estado = $request->estado;
                }
    
                if($request->filled('role_id')){
                    
                    $usuario->role_id = $request->role_id;//actualza el rol signado
                }
                
                $usuario->save();

                #INI PERMISOS
                    $collectionPermisosRequest = Collection::make($request->permisos);
                    $permisosRol = $rol->permisos;
                    $idsPermisosRol = $permisosRol->pluck("id");
                
                    $permisosEspeciales = $collectionPermisosRequest->diff($idsPermisosRol)->all();//permisos usuarios agregados
                    $permisosEspeciales = array_fill_keys($permisosEspeciales, array("tipo"=>"Asignado"));
                    $permisosRetiradosRol =  $idsPermisosRol->diff($collectionPermisosRequest)->all();//Permisos rol retirados
                    $permisosRetiradosRol = array_fill_keys($permisosRetiradosRol, array("tipo"=>"Retirado"));
                    //$permisosTotales = array_merge($permisosEspeciales,$permisosRetiradosRol);//No conserva las Keys
                    $permisosTotales =  $permisosEspeciales + $permisosRetiradosRol;//Anidamos conservando su key
                    
                   // dd($permisosTotales); 
                    //dd($permisosTotales); 
                #END PERMISOS
 
                $usuario->permisos()->sync($permisosTotales);//actualiza los permisos por usuario 
                
                //$usuario->permisos()->sync($request->get('permisos'));//actualiza los permisos por usuario 
            
                

                //limpia ultimos registros password manteniendo solo los ultimos 8 "8 es variable"
                $cantidad_historial = $userFunctions->cantidadHistorialPasswordByIdUser($usuario->username);
                $cantidadHistorialPasswordConservar = (int) Parametro::getCantidadHistorialPassword();
                if ($cantidad_historial > $cantidadHistorialPasswordConservar) { //si excede los 8 registros "8 es variable"
                    $limit = $cantidad_historial - $cantidadHistorialPasswordConservar; //conservamos los 8 ultimos "8 es variable"
                    $userFunctions->eliminarUltimoPasswordHistorial($usuario->username,$limit);
                }

                //Registrar Acciones Update del Usuario 
                if ($registroActionAdmin) { //si hubo cambios de administracion o rol y debe registrarse
                    $newEmpresa = $oldEmpresa == "" ? "" : $empresa->nombre;
                    $newRol = $oldRol == "" ? "" : $rol->nombre;
                    //$userFunctions->logActionUpdateByAdmin($usuario->username,$oldEmpresa,$oldRol,$newEmpresa,$newRol);
                  
                    $logsFunctions->registroLog($logsFunctions::LOG_USUARIO,array(
                                                                                "accion"=>"update",
                                                                                "usuarioAuth"=>$usuarioAuth->username,
                                                                                "perfil"=>$usuarioAuth->role->nombre,
                                                                                "ipChanging"=>$estacionUsuario,
                                                                                "oldUsuario"=>$usuario->username,
                                                                                "oldEmpresa"=>$oldEmpresa,
                                                                                "oldRol"=>$oldRol,
                                                                                "newEmpresa"=>$newEmpresa,
                                                                                "newRol"=>$newRol
                                                                            ));
                }
 
              #End Begin Transaction update User
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

        $permisosBloqueadosRol = Permiso::getPermisosRolBloqueadosSpecialByUser($usuario)->pluck('id')->all();
        $permisosCheckedRol = Permiso::getPermisosRoleByUser($usuario)->whereNotIn('id', $permisosBloqueadosRol)->values(); //Quitando los bloqueados
        $permisosCheckedUser = Permiso::getPermisosSpecialByUser($usuario);
 

       /* return $this->resultData(array(
            "permisosBloqueadosRol"=>$permisosBloqueadosRol,
            "permisosCheckedRol"=>$permisosCheckedRol,
            "permisosCheckedUser"=>$permisosCheckedUser
        ));*/
           
           //return response()->json(['data'=>$user],200); 
            return $this->showModJsonOne($usuario);

    }

    public function create()
    {
        $usuarioAuth = Auth::user();

        $empresas = Empresa::all(); 
        $roles = Role::getSubRolesByRolUser($usuarioAuth);

        $permisosBloqueadosRolAuth = Permiso::getPermisosRolBloqueadosSpecialByUser($usuarioAuth)->pluck('id')->all();//Permisos Bloqueados del autenticado

        //Armar esquema de Modulos y permisos segùn disponga el admin o subadmin 
        $modulos = User::getModulosByUserAuth($usuarioAuth);//arma el esquema de modulos
        $permisos_role = Permiso::getPermisosRoleByUser($usuarioAuth)->whereNotIn('id', $permisosBloqueadosRolAuth)->values();//arma permisos segun rol en el esquema de modulos
        $permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos


        $modulos_permisos = Permiso::all();
  
        return view('administrador.modulos.user.create',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos"=>$this->showContJsonAll($modulos),
            "permisosRol"=>$this->showContJsonAll($permisos_role),
            "permisosEspeciales"=>$this->showContJsonAll($permisos_user),
        ]);
    }

    public function store(Empresa $empresa, Role $rol, UserRequest $request)
    {
        
        $usuario = new User;
        $userFunctions = new UserFunctions;
        $logsFunctions = new LogsFunctions;

        $usuarioAuth = Auth::user();
 
        #Generando usuario
            $nombre=strtolower($request->nombre);
            $apellidos = strtolower($request->apellidos);

            $primeraletraNombre = substr($nombre,0,1);

            $array_apellidos=explode(" ",$apellidos);
            $apellidos_letras = $array_apellidos[0];
            
            if (count($array_apellidos) > 1) {
                for ($i=1; $i < count($array_apellidos); $i++) { 
                    $apellidos_letras .= substr($array_apellidos[$i],0,1);
                }
            }
             
            $usuario_nuevo = $primeraletraNombre.$apellidos_letras;
            // dd($usuario_nuevo );
        #End Usuarios

        #Generando Password
            $charsPwd = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*+";
            $lengthPwd = 8;
            $nuevaPassword = substr( str_shuffle( $charsPwd ), 0, $lengthPwd );
        #End Password

        $permisosTotales = array();
 
        #GET IP
            $estacionUsuario = $userFunctions->get_ip_address();
        #END
             
        try { 

            //generando Usuario y Password

            DB::beginTransaction();

            $campos = $request->all();
            $campos['usuario'] = $usuario_nuevo;    
            $campos['password'] = bcrypt($nuevaPassword);
                
            // $usuario = User::create($campos); 
              
              $usuario->empresa_id = $empresa->id;
              $usuario->role_id = $rol->id;
              $usuario->nombre = $request->nombre;
              $usuario->apellidos = $request->apellidos;
              $usuario->dni = $request->dni;
              $usuario->telefono = $request->telefono;
              $usuario->email = $request->email;
              $usuario->username = $campos['usuario'];
              $usuario->password = $campos['password']; 
              $usuario->estado = User::ESTADO_ACTIVO;
              $usuario->save();

               #INI PERMISOS
                    $collectionPermisosRequest = Collection::make($request->permisos);
                    $permisosRol = $rol->permisos;
                    $idsPermisosRol = $permisosRol->pluck("id");
                
                    $permisosEspeciales = $collectionPermisosRequest->diff($idsPermisosRol)->all();//permisos usuarios agregados
                    $permisosEspeciales = array_fill_keys($permisosEspeciales, array("tipo"=>"Asignado"));
                    $permisosRetiradosRol =  $idsPermisosRol->diff($collectionPermisosRequest)->all();//Permisos rol retirados
                    $permisosRetiradosRol = array_fill_keys($permisosRetiradosRol, array("tipo"=>"Retirado"));
                    //$permisosTotales = array_merge($permisosEspeciales,$permisosRetiradosRol);//No conserva las Keys
                    $permisosTotales =  $permisosEspeciales + $permisosRetiradosRol;//Anidamos conservando su key
                     
                    //dd($permisosTotales); 
                #END PERMISOS
 
              $usuario->permisos()->sync($permisosTotales);//crea vinculo al id del permiso
   
                //Registro de accion Store del Admin
                //$userFunctions->logActionStoreByAdmin($usuario->username,$usuario->empresa->nombre,$usuario->role->nombre);
                $logsFunctions->registroLog($logsFunctions::LOG_USUARIO,array(
                                                                                "accion"=>"store",
                                                                                "usuarioAuth"=>$usuarioAuth->username,
                                                                                "perfil"=>$usuarioAuth->role->nombre,
                                                                                "ipChanging"=>$estacionUsuario,
                                                                                "usuario"=>$usuario->username,
                                                                                "empresa"=>$usuario->empresa->nombre,
                                                                                "rol"=>$usuario->role->nombre
                                                                            )
                                            );
               
                
              DB::commit();
        }catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en el registro, intente nuevamente verificando que los campos estén completos!.",422);
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente verificando que los campos estén completos!!.",422);
        }

        return response()->json(["error"=>false,"data"=>array(
            "usuario"=>$usuario_nuevo,
            "clave"=>$nuevaPassword
        )]);

    }  
    
    public function delete(User $usuario)
    {
        $usuario->permisos()->sync([]);//elimina vinculo

        $usuario->delete();

        return $this->showModJsonOne($usuario);
    }
}
