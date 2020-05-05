<?php

namespace App\Administrador;

use App\Administrador\User;
use App\Administrador\Permiso;
use App\Transformers\RolTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
   use SoftDeletes;


   const SIN_PERMISOS_TOTAL = "NO";
   const CON_PERMISOS_TOTAL = "SI";
   
   public $transformer = RolTransformer::class;

   protected $connection = 'mysql';
   
   protected $table = 'roles';


    protected $fillable = [
        'nombre',
        'especial',
        'referencia',
        'estado'
    ];

    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = strtoupper($nombre);
    }
     
    public function permisos(){
        return $this->belongsToMany(Permiso::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public static function getSubRolesByRolUser(User $usuario)
    { 
        if($usuario->tienePermisoEspecial()){
            return Role::all();
        }
        return Role::where('referencia','=',$usuario->role_id)->get();
    }
 
    public function esRolEspecial()
    { 
        return  $this->especial == Role::CON_PERMISOS_TOTAL;
    }

}
