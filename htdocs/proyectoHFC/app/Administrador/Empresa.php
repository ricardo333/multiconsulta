<?php

namespace App\Administrador;

use App\Administrador\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\EmpresaTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    public $transformer = EmpresaTransformer::class;

    protected $connection = 'mysql';
    
    protected $table = 'empresas';

    protected $fillable = [
        'nombre'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function setNombreAttribute($nombre)
    {
        $this->attributes['nombre'] = strtoupper($nombre);
    }

    public static function permisosGenerales()
    {
     //verifica permisos para el listado de acciones en front end
      $instanciaPermiso =  Auth::user();
      $permiso_listar = $instanciaPermiso->HasPermiso('submodulo.empresa.list');
      $permiso_crear = $instanciaPermiso->HasPermiso('submodulo.empresa.store');
      $permiso_ver = $instanciaPermiso->HasPermiso('submodulo.empresa.show');
      $permiso_editar = $instanciaPermiso->HasPermiso('submodulo.empresa.edit');
      $permiso_actualizar = $instanciaPermiso->HasPermiso('submodulo.empresa.update');
      $permiso_eliminar = $instanciaPermiso->HasPermiso('submodulo.empresa.delete');
        

      return [
        "list"=>$permiso_listar,
        "store"=>$permiso_crear,
        "show"=>$permiso_ver,
        "edit"=>$permiso_editar,
        "update"=>$permiso_actualizar,
        "delete"=>$permiso_eliminar
      ];
    }
    
}
