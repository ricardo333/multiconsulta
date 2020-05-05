<?php

use App\Administrador\Role;
use App\Administrador\User;
use App\Administrador\Empresa;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = new User;
        $usuario->empresa_id = 1;
        $usuario->role_id = 1;
        $usuario->nombre = 'Jhon';
        $usuario->apellidos = 'Yupanqui Cusihuamán';
        $usuario->dni = '48483283';
        $usuario->telefono = '1547895';
        $usuario->email = 'jhonjy95@gmail.com';
        $usuario->username = 'jyupanqui';
        $usuario->password = bcrypt('123456789');
        $usuario->estado = 'A';
        $usuario->save();

        $role_id = Role::all()->random()->id;

        $empresas = Empresa::all();
        foreach ($empresas as $empresa) {
            $empresa_id = $empresa->id;
           // factory(User::class,500)->create([
            factory(User::class,50)->create([
                'empresa_id' =>$empresa_id,
                'role_id' =>$role_id
            ]);
        }
        
    }
}
