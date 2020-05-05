<?php

namespace App\Administrador;

use App\Administrador\Role;
use App\Administrador\User;
use App\Administrador\Permiso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\PermisoTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permiso extends Model
{
    use SoftDeletes;

    const TIPO_MODULO = "Modulo";
    const TIPO_SUBMODULO = "Submodulo";

    public $transformer = PermisoTransformer::class;

    protected $connection = 'mysql';
    
    protected $table = 'permisos';
    
    protected $fillable = [
        'nombre',
        'slug',
        'ruta',
        'imagen',
        'tipo',
        'orden',
        'referencia',
        'descripcion',
    ];

    
   public function roles(){
       return $this->belongsToMany(Role::class);
   }

   public function users(){
       return $this->belongsToMany(User::class);
   }

   public static function getPermisosRoleByUser(User $usuario)
   {
        if($usuario->tienePermisoEspecial()){
            return Permiso::all();
        }
 
        return $usuario->role->permisos;
   }

   public static function getPermisosSpecialByUser(User $usuario)
   {
       //return $usuario->permisos;
        return $usuario->permisos()->wherePivot('tipo','Asignado')->get()->values();
   }

   public static function getPermisosRolBloqueadosSpecialByUser(User $usuario)
   {
       //return $usuario->permisos;
        return $usuario->permisos()->wherePivot('tipo','Retirado')->get()->values();
   }

   public static function getAllPermisosByUser(User $usuario)
   { 
       $rol_permisos = $usuario->role->permisos;
       $usuario_permisos = $usuario->permisos;
        
        $permisosGenerales =  $rol_permisos->merge($usuario_permisos)->unique('id')->values();

        return $permisosGenerales;
   }
 

}
