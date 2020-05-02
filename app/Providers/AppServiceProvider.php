<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Common\Code;
use Illuminate\Support\Facades\DB;
use App\Http\Common\ConstMap;
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
        DB::listen(function ($query) {
            // echo $query->sql;
            // $query->bindings
            // $query->time
        });
    }

}
