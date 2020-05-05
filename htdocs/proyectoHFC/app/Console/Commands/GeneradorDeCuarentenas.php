<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GeneradorDeCuarentenas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuarentena:generador';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera las cuarentenas generales por nodo, trobas y codigo de cliente';

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

            DB::select("call zz_new_system.sp_cuarentenas");
 
            \Log::error("Sin errores, se actualizarón las cuarentenas por sp_cuarentenas.");  

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());
            \Log::error("Problemas al actualizar cuarentenas sp_cuarentenas: ".$ex->getMessage());  
             
         }catch(\Exception $e){
            // dd($e->getMessage()); 
            \Log::error("Problemas al actualizar cuarentenas sp_cuarentenas: ".$e->getMessage());   
        }

    }
}
