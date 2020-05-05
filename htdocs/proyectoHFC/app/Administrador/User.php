<?php

namespace App\Administrador;

use App\Administrador\Role;
use App\Administrador\Permiso;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $connection = 'mysql';
 
    protected $table = 'users';

    public $transformer = UserTransformer::class;

    const ESTADO_ACTIVO = "A";
    const ESTADO_INACTIVO = "I";

    protected $fillable = [
        'empresa_id',
        'role_id',
        'nombre',
        'apellidos',
        'dni',
        'telefono',
        'email',
        'username',
        'password',
        'estado',
        'migrado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function role(){
        return $this->belongsTo(Role::class)->withTrashed();
    }

    public function permisos(){
        return $this->belongsToMany(Permiso::class)->withPivot('tipo');
    }

  
 
    public function tienePermisoEspecial(){
      
       return $this->role->especial == Role::CON_PERMISOS_TOTAL;
       
    }

    public function HasPermiso($permiso){

        if($this->tienePermisoEspecial()){//Si tiene permisos especiales de administrador
           return true;  
        }
       

        $resultado_permiso = false;

         //Permisos Bloqueados
         $permisosBloqueadosRol = Permiso::getPermisosRolBloqueadosSpecialByUser($this)->pluck('id')->all();

        // dd($permisosBloqueadosRol);

 
        if (isset($this->role->permisos)) { //verifica si tiene permisos para evaluar

            $resultado_permiso = $this->role->permisos()->where('slug','=',$permiso)->whereNotIn('permisos.id',$permisosBloqueadosRol)->first() != null;

        }
        if(!$resultado_permiso){ //si continua en false
            $resultado_permiso = $this->permisos()->where('slug','=', $permiso)->wherePivot('tipo',"Asignado")->first() != null; 
        }
         return $resultado_permiso;
      
    }
  
    public static function getModulosByUserAuth(User $user, $search=""){
      
        if($user->tienePermisoEspecial()){//Si tiene permisos especiales de administrador
           
            $modulos = Permiso::where('tipo',Permiso::TIPO_MODULO)
                                ->where('slug','like','%'.$search.'%')
                                ->orderBy('orden','asc')
                                ->get();
            return $modulos;
         }

         //Permisos Bloqueados
         $permisosBloqueadosRol = Permiso::getPermisosRolBloqueadosSpecialByUser($user)->pluck('id')->all();

         //permisos por rol 

         //dd($permisosBloqueadosRol);   
         $permisos_rol = $user->role
                        ->permisos()
                        ->where('tipo',Permiso::TIPO_MODULO)
                        ->whereNotIn('permisos.id',$permisosBloqueadosRol)
                        ->where('slug','like','%'.$search.'%')
                        ->orderBy('orden','asc')
                        ->get();
          //dd($permisos_rol);          
         //  dd( $user->permisos()->where('permisos.tipo',Permiso::TIPO_MODULO)->get());
         $permisos_user = $user->permisos()
                        ->where('permisos.tipo',Permiso::TIPO_MODULO)
                        ->wherePivot('tipo',"Asignado")
                        ->where('slug','like','%'.$search.'%')
                        ->orderBy('orden','asc')
                        ->get();
                        
         // dd($permisos_user);             
         $modulos = $permisos_rol->merge($permisos_user)->unique('id')->values();

         return $modulos;
    }
 
    public function scopefilterData($query)
    {
      //dd(request()->all());
      //dd($this);
      foreach (request()->all() as $key => $value) {
         //dd($key."--".$value); 
        $attribute = $this->transformer::originalAttribute($key);
         //dd($attribute."--".$value);
        if(isset($attribute,$value)){
          $query->where($attribute,"like","%$value%");
        }
      }

      return $query;
    }

    public function scopesortData($query)
    {
      if (request()->filled('sort')) {
          $attribute  = $this->transformer::originalAttribute(request()->sort);
          if(isset($attribute)){
            $query->orderBy($attribute,'asc');
          } 
      }
      return $query;
    }

    public function scopefilterByRole($query,$roles)
    { 
      if(count($roles) > 0){
        return $query->whereIn('role_id',$roles);
      }
        
      return  $query->where('role_id',0);
    }

    public static function permisosGenerales(User $usuario)
    {
     //verifica permisos para el listado de acciones en front end 
      $permiso_modulo = $usuario->HasPermiso('submodulo.usuario.index');
      $permiso_crear = $usuario->HasPermiso('submodulo.usuario.store');
      $permiso_ver = $usuario->HasPermiso('submodulo.usuario.show');
      $permiso_editar = $usuario->HasPermiso('submodulo.usuario.edit');
      $permiso_eliminar = $usuario->HasPermiso('submodulo.usuario.delete');
        

      return [
        "modulo"=>$permiso_modulo,
        "store"=>$permiso_crear,
        "show"=>$permiso_ver,
        "edit"=>$permiso_editar,
        "delete"=>$permiso_eliminar
      ];
    }
 
}
