<?php

namespace App\Transformers;

use App\Administrador\Empresa;
use League\Fractal\TransformerAbstract;

class EmpresaTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Empresa $empresa)
    {
        return [
            'identificador' => (int)$empresa->id,
            'empresa' => (string)$empresa->nombre,
            'fechaCreacion' => isset($empresa->created_at)? (string)$empresa->created_at : null,
            'fechaActualizacion' => isset($empresa->updated_at)? (string)$empresa->updated_at : null,
            'fechaEliminacion' => isset($empresa->deleted_at)? (string)$empresa->deleted_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'empresa' =>'nombre',
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
            'nombre' => 'empresa',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',
            '_method' => '_method',
      ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

     
}
