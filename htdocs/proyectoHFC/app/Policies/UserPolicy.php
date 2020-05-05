<?php

namespace App\Policies;

use App\Administrador\Role;
use App\Administrador\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization,AdminActions;
  
     
    public function show(User $user, User $usuario)
    {
        return  $user->role_id === $usuario->role->referencia;
    }

    public function edit(User $user, User $usuario)
    { 
        return  $user->role_id === $usuario->role->referencia;
    }

    public function update(User $user, User $usuario, Role $rol)
    { 
        return $user->role->id === $rol->referencia && $user->role_id === $usuario->role->referencia;
    }

    public function delete(User $user, User $usuario)
    {
        return  $user->role_id === $usuario->role->referencia;
    }

    public function detallePerfil(User $user, User $usuario)
    {
       return $user->id === $usuario->id;
    }
 
}
