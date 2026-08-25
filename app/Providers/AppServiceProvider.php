<?php

namespace App\Providers;

use App\View\Composers\AuthRegionsComposer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer(['auth.login', 'auth.register'], AuthRegionsComposer::class);
    }
}