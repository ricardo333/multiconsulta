<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class deleteExpiresCuarentenas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuarentena:delete_expirados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambia el estado Activo a Inactivo todas las cuarentenas en gestión, donde su fecha final ya termino.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {

            $listaEliminar = DB::select("select * FROM zz_new_system.`gestion_cuarentena` c WHERE DATEDIFF(NOW(),c.`fechaFin`) > 0 and c.estado='Activo' ");

            if (count($listaEliminar) > 0) {
               for ($i=0; $i < count($listaEliminar); $i++) { 
                    //DB::delete("delete from zz_new_system.`gestion_cuarentena` WHERE id = ?", [$listaEliminar[$i]->id]);
                    DB::update("update zz_new_system.`gestion_cuarentena` set estado='Inactivo' WHERE id = ?", [$listaEliminar[$i]->id]);
               }
            }

           // \Log::error("Sin errores, se actualizarón correctamente las gestion de cuarentenas con fechas finales antiguas.");  

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());
            \Log::error("Problemas al eliminar gestion en cuarentenas con fechas finales expiradas: ".$ex->getMessage());  
             
         }catch(\Exception $e){
            // dd($e->getMessage()); 
            \Log::error("Problemas al eliminar gestion en cuarentenas con fechas finales expiradas: ".$e->getMessage());   
        }
      
       
    }
}
