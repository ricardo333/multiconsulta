<?php

namespace App\Transformers;

use App\Administrador\Role;
use League\Fractal\TransformerAbstract;

class RolTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $rol)
    {
        return [
            'identificador' => (int)$rol->id,
            'rol' => (string)$rol->nombre,
            'esAdministrador' => (string) $rol->especial == Role::CON_PERMISOS_TOTAL,
            'rolPadre' => (int)$rol->referencia,
            'estado' =>  ((int)$rol->estado == 1)? "Activo":"Inactivo",
            'fechaCreacion' => isset($rol->created_at)? (string)$rol->created_at : null,
            'fechaActualizacion' => isset($rol->updated_at)? (string)$rol->updated_at : null,
            'fechaEliminacion' => isset($rol->deleted_at)? (string)$rol->deleted_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'rol' =>'nombre',
            'esAdministrador' =>'especial',
            'rolPadre' =>'referencia',
            'estado' =>'estado',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
            'permisos' => 'permisos',
            '_method' => '_method',
            '_token' => '_token',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    public static function transformedAttribute($index)
    {
      $attributes = [
            'id' => 'identificador',
            'nombre' => 'rol',
            'especial' => 'esAdministrador',
            'referencia' => 'rolPadre',
            'estado' => 'estado',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',
            'permisos' => 'permisos',
            '_method' => '_method',
      ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
