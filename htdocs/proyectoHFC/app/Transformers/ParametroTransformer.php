<?php

namespace App\Transformers;

use App\Administrador\Parametro;
use League\Fractal\TransformerAbstract;

class ParametroTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Parametro $parametro)
    {
        return [
            'identificador' => (int)$parametro->id,
            'periodo' => (int)$parametro->period,
            'tiempo' => (string)$parametro->time,
            'descripcion' => (string)$parametro->description,
            'fechaCreacion' => isset($parametro->created_at)? (string)$parametro->created_at : null,
            'fechaActualizacion' => isset($parametro->updated_at)? (string)$parametro->updated_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'periodo' =>'period',
            'tiempo' =>'time',
            'descripcion' =>'description',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            '_method' => '_method',
            '_token' => '_token',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    public static function transformedAttribute($index)
    {
      $attributes = [
            'id' => 'identificador',
            'period' => 'periodo',
            'nombre' => 'time',
            'descripcion' => 'description',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            '_method' => '_method',
      ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
