<?php

namespace App\Console;

use App\Console\Commands\Triage;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\retornoVelocidadCM;
use App\Console\Commands\GeneradorDeCuarentenas;
use App\Console\Commands\generadorGraficaAverias;
use App\Console\Commands\deleteExpiresCuarentenas;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //\App\Console\Commands\retornoVelocidadCM::class
        //\App\Console\Commands\deleteExpiresCuarentenas::class
       retornoVelocidadCM::class,
       deleteExpiresCuarentenas::class,
       GeneradorDeCuarentenas::class,
       generadorGraficaAverias::class,
       Triage::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* $schedule->command('cm:retorno_velocidad')
                ->cron('01 03 * * *');*/ //Decomentar luego
                //->cron('* * * * *'); //es para pruebas en local , lo ejecuta solo una vez
              
        $schedule->command('cuarentena:delete_expirados')
                    ->cron('*/5 * * * *');
                    //->cron('* * * * *'); //es para pruebas en local , lo ejecuta solo una vez

        $schedule->command('cuarentena:generador')
                    ->cron('*/23 * * * *');
                    //->cron('* * * * *'); //es para pruebas en local , lo ejecuta solo una vez

        $schedule->command('grafica:generador')
                    ->cron('*/6 * * * *');
                    //->cron('* * * * *'); //es para pruebas en local , lo ejecuta solo una vez

        // $schedule->command('triage')
         //           ->cron('*/30 * * * *');  Descomentar luego
                    //->cron('* * * * *'); //es para pruebas en local , lo ejecuta solo una vez
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
