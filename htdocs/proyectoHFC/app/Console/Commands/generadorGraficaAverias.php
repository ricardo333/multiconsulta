<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class generadorGraficaAverias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grafica:generador';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los gráficos para Averías por jefatura y troba';

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

            DB::select("call zz_new_system.sp_grafica_averias_x_jefatura_troba");
            
            \Log::error("Sin errores, se actualizarón los gráficos por sp_grafica_averias_x_jefatura_troba.");  

        } catch(QueryException $ex){ 
            // dd($ex->getMessage());
            \Log::error("Problemas al actualizar los gráficos sp_grafica_averias_x_jefatura_troba: ".$ex->getMessage());  
             
         }catch(\Exception $e){
            // dd($e->getMessage()); 
            \Log::error("Problemas al actualizar los gráficos sp_grafica_averias_x_jefatura_troba: ".$e->getMessage());   
        }

    }
}
