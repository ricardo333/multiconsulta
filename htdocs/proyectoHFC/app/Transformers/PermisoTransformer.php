<?php

namespace App\Transformers;

use App\Administrador\Permiso;
use League\Fractal\TransformerAbstract;

class PermisoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Permiso $permiso)
    {
        return [
            'identificador' => (int)$permiso->id,
            'nombre' => (string)$permiso->nombre,
            'permiso' =>  (string)$permiso->slug,
            'url' =>  url($permiso->ruta),
            'imagen' => url("images/modulos/{$permiso->imagen}"),
            'tipo' => (string)$permiso->tipo,
            'identificadorModulo' => (int)$permiso->referencia,
            'descripcion' => (string)$permiso->descripcion,
            'fechaCreacion' => isset($permiso->created_at)? (string)$permiso->created_at : null,
            'fechaActualizacion' => isset($permiso->updated_at)? (string)$permiso->updated_at : null,
            'fechaEliminacion' => isset($permiso->deleted_at)? (string)$permiso->deleted_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'nombre',
            'permiso' => 'slug',
            'url' => 'ruta',
            'imagen' => 'imagen',
            'tipo' => 'tipo',
            'identificadorModulo' => 'email',
            'usuario' => 'referencia',
            'descripcion' => 'descripcion',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
            '_method' => '_method',
            '_token' => '_token',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    public static function transformedAttribute($index)
    {
      $attributes = [
                'id' => 'identificador',
                'nombre' => 'nombre',
                'slug' => 'permiso',
                'ruta' => 'url',
                'imagen' => 'imagen',
                'tipo' => 'tipo',
                'email' => 'identificadorModulo',
                'referencia' => 'usuario',
                'descripcion' => 'descripcion',
                'created_at' => 'fechaCreacion',
                'updated_at' => 'fechaActualizacion',
                'deleted_at' => 'fechaEliminacion',
                '_method' => '_method',
      ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
