<?php

use App\Administrador\Role;
use App\Administrador\User;
use App\Administrador\Empresa;
use App\Administrador\Permiso;
use Illuminate\Database\Seeder;
use App\Administrador\Parametro;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
       /*
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
            
            Empresa::truncate();
            Permiso::truncate();
            Role::truncate();
            User::truncate();
            Parametro::truncate();
            DB::table('permiso_role')->truncate();
            DB::table('permiso_user')->truncate();

            Schema::dropIfExists('parametros');
            Schema::dropIfExists('migrations');
            Schema::dropIfExists('password_resets');
            Schema::dropIfExists('empresas');
            Schema::dropIfExists('permisos');
            Schema::dropIfExists('roles');
            Schema::dropIfExists('users');
            Schema::dropIfExists('permiso_role');
            Schema::dropIfExists('permiso_user');
            

            DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); // Reactivamos la revisión de claves foráneas
        */ 
       

        $this->call(ParametroSeeder::class);
        $this->call(EmpresasSeeder::class);
        $this->call(PermisosSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class); 
    }
}
