<?php

namespace App\Transformers;

use App\Administrador\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
 
    public function transform(User $user)
    { 
        return [
            'identificador' => (int)$user->id,
            'idenfiticadorEmpresa' => isset($user->empresa_id)? (int)$user->empresa_id : 0,
            'identificadorRol' => isset($user->role_id)? (int)$user->role_id : 0,
            'nombre' => (string)$user->nombre,
            'apellidos' => (string)$user->apellidos,
            'documento' => (string)$user->dni,
            'celular' => (string)$user->telefono,
            'correo' => (string)$user->email,
            'usuario' => (string)$user->username,
           // 'clave' => (string)$user->password,
            'estado' => (string)$user->estado,
            'empresa'=> isset($user->empresa) ? (string) $user->empresa->nombre : "",
            'rol'=> isset($user->role) ? (string) $user->role->nombre: "",
            'permisosEspeciales'=>$user->permisos,
            'fechaCreacion' => isset($user->created_at)? (string)$user->created_at : null,
            'fechaActualizacion' => isset($user->updated_at)? (string)$user->updated_at : null,
            'fechaEliminacion' => isset($user->deleted_at)? (string)$user->deleted_at : null,
            
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'idenfiticadorEmpresa' =>'empresa_id',
            'identificadorRol' => 'role_id',
            'nombre' => 'nombre',
            'apellidos' => 'apellidos',
            'documento' => 'dni',
            'celular' => 'telefono',
            'correo' => 'email',
            'usuario' => 'username',
            'clave' => 'password',
            'estado' => 'estado',
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
            'empresa_id' => 'idenfiticadorEmpresa',
            'role_id' => 'identificadorRol',
            'nombre' => 'nombre',
            'apellidos' => 'apellidos',
            'dni' => 'documento',
            'telefono' => 'celular',
            'email' => 'correo',
            'username' => 'usuario',
            'password' => 'clave',
            'estado' => 'estado',
            'permisos' => 'permisos',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',
            '_method' => '_method',
      ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
