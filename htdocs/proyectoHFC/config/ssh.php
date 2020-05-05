<?php 

return [

   // 'one_conexion' => env('USER_ONE_CONEXION','ehuertasf')

     'one_conexion' => [
        'user' => env('ONE_CONEXION_USER',''), //ehuertasf
        'pass' => env('ONE_CONEXION_PASS',''), //Santana
        'ip' => env('ONE_CONEXION_IP', ''), //190.234.74.6
        'puerto' => env('ONE_CONEXION_PUERTO', '') //9561
    ], 
        
     'webserver_conexion' => [
        'user' => env('WEB_CONEXION_USER',''),
        'pass' => env('WEB_CONEXION_PASS',''),
        'ip' => env('WEB_CONEXION_IP', ''), 
        'puerto' => env('WEB_CONEXION_PUERTO', '')
    ], 

    'procesos_conexion' => [
        'user' => env('PROCESS_CONEXION_USER',''),
        'pass' => env('PROCESS_CONEXION_PASS',''),
        'ip' => env('PROCESS_CONEXION_IP', ''), 
        'puerto' => env('PROCESS_CONEXION_PUERTO', '')
    ],

];