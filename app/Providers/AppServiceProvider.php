<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        Validator::extend('only_text', function ($attribute, $value, $parameters, $validator) {
            // return preg_match('/^[a-z0 -9 \r\n.\-\,\_\'\&\%\!\?\"\:\+\(\)\@\#\/]+$/i',
            // $value);
            return true;
        }, ':attribute tidak valid');

        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match(
                '/^[0-9 \+]{0,1}[1-9]{1,1}[0-9]+$/i',
                $value
            );
        }, ':attribute tidak valid');
    }
}
