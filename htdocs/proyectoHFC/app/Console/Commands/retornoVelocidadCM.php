<?php

namespace App\Console\Commands;

use DB; 
use Illuminate\Console\Command;
use App\Functions\IntrawayFunctions;
use App\Functions\MulticonsultaFunctions;

class retornoVelocidadCM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cm:retorno_velocidad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retorna la Velocidad del CM según el vencimiento en su fecha de cambio.';

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
         $multiconsultaFuntions = new MulticonsultaFunctions;
         $tramas = $multiconsultaFuntions->velocidadCMRetorno();

         \Log::error("Se inicia el retorno de Velocidades...");  

          
         if (count($tramas) > 0) {
            foreach ($tramas as $item) {
                  
               $iwAction = "activar";
               $codCliente = $item->idclientecrm ;
               $idProducto = $item->idProdVenta;
               $idServicio = $item->idservicio;
               $serviceP = $item->velocidadInicial;
               $idISPCRM = $item->scopesgroup;
               $ispMtaCrmId = $item->mta;

               $intrawayPeticion = new IntrawayFunctions;
               $resultadoITW = $intrawayPeticion->ActiveOrChangeCM($iwAction,$codCliente,$idProducto,$idServicio,$serviceP,$idISPCRM,$ispMtaCrmId);
               
               if ($resultadoITW == "error") {
                     \Log::error("Error en cambio de velocidad del cliente $codCliente. Problema de conetividad con Intraway. => ".$resultadoITW);
               }
                  
            } 
         }

         try {
               DB::update(
                    "update catalogos.`excepciones` a  INNER JOIN  multiconsulta.`nclientes` b
                    ON a.`idventa`=b.`idventa`  set a.devuelto='S'  WHERE a.fecha_fin<NOW() AND a.nvel=b.`SERVICEPACKAGECRMID` AND a.idventa>0  AND devuelto='N'"
               );

               /*DB::update("
                    UPDATE catalogos.`excepciones` a  INNER JOIN  multiconsulta.`nclientes` b  
                    ON a.`idproducto`=b.`idproducto`  SET a.devuelto='S'   WHERE a.fecha_fin<NOW() AND a.nvel=b.`SERVICEPACKAGECRMID` AND a.idproducto>0  AND a.devuelto='N'
               ");*/

            }catch(QueryException $ex){ 
               // dd($ex->getMessage());
               \Log::error("Problemas al actualizar catalogos.excepciones para los clientes quienes retoman su velocidad original por cron :".$ex->getMessage());  
                
            }catch(\Exception $e){
               // dd($e->getMessage()); 
               \Log::error("Problemas al actualizar catalogos.excepciones para los clientes quienes retoman su velocidad original por cron: ".$e->getMessage());  
                
         }

            \Log::error("Termino el retorno de Velocidades..."); 
   }

   
}
