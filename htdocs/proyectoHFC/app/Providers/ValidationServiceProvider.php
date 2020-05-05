<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validator\PersonalizateValidator;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    { 

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         Validator::resolver(
            function ( $translator, $data, $rules, $messages, $customAttributes ) {
                return new PersonalizateValidator( $translator, $data, $rules, $messages, $customAttributes );
            }
        );
    }
}
