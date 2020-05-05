<?php

namespace App\Functions\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceAutenticacionFunctions
{

    public function validaUsuario($user)
    {
        $queryUser = DB::select("SELECT user FROM zz_new_system.api_services WHERE user='$user'");

        return $queryUser;
    }


    public function validaPassword($pass)
    {
        $queryUser = DB::select("SELECT password FROM zz_new_system.api_services WHERE password=MD5('$pass')");

        return $queryUser;
    }


    public function validaUserToken($user,$token)
    {
        $queryUser = DB::select("SELECT user FROM zz_new_system.api_services WHERE user='$user' AND token='$token'");

        return $queryUser;
    }


    public function generarToken($user)
    {
        $token = Str::random(60);
        $time = Carbon::now();

        $queryToken = DB::update("UPDATE zz_new_system.api_services SET token='$token', time='$time' WHERE user='$user'");

        $getToken = DB::select("SELECT token FROM zz_new_system.api_services WHERE user='$user'");

        return $getToken;
    }


    public function tiempoiniToken($token)
    {
        $tiempoini = DB::select("SELECT time FROM zz_new_system.api_services WHERE token='$token'");

        return $tiempoini;
    }




}