<?php

namespace App\Providers;
 
use App\Administrador\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

       
        Blade::directive('routeIs', function ($expression) {
            return  "<?php if (Request::url() == route($expression)): ?>"; 
        });

        view()->composer('layouts.master', function($view) {
            $user = Auth::user();
            $resultado_modulos = User::getModulosByUserAuth($user);
            $view->with('menus', $resultado_modulos);
        });

    }
}
