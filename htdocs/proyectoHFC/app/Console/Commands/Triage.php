<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class Triage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Procedure que ejecuta el triage automático';

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
           /* DB::update(
                 "update catalogos.`excepciones` a  INNER JOIN  multiconsulta.`nclientes` b
                 ON a.`idventa`=b.`idventa`  set a.devuelto='S'  WHERE a.fecha_fin<NOW() AND a.nvel=b.`SERVICEPACKAGECRMID` AND a.idventa>0  AND devuelto='N'"
            );*/
            DB::select("call triaje.sp_triaje_01");
           

         }catch(QueryException $ex){ 
            // dd($ex->getMessage());
            \Log::error("Problemas al actualizar el triage automático :".$ex->getMessage());  
             
         }catch(\Exception $e){
            // dd($e->getMessage()); 
            \Log::error("Problemas al actualizar el triage automático: ".$e->getMessage());  
             
        }
    }
}
