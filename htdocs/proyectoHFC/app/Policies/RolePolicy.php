<?php

namespace App\Policies;

use App\Administrador\Role;
use App\Administrador\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization,AdminActions;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function userStore(User $user, Role $rol)
    {
        return $user->role->id === $rol->referencia;
    }

    public function show(User $user, Role $rol)
    {
        return $user->role_id === $rol->referencia;
    }

    public function edit(User $user, Role $rol)
    {
        return $user->role_id === $rol->referencia;
    }

    public function update(User $user, Role $rol)
    {
        return $user->role_id === $rol->referencia;
    }

    public function delete(User $user, Role $rol)
    {
        return $user->role_id === $rol->referencia;
    }

}
