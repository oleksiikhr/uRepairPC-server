<?php

namespace App\Providers;

use App\Http\Kernel;
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
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @param  Kernel  $kernel
     * @return void
     */
    public function boot(Kernel $kernel)
    {
//        \Illuminate\Support\Facades\DB::listen(function ($query) {
//            var_dump($query->sql);
//        });
    }
}
