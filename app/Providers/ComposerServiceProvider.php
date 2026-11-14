<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $routeCompany = (Request::route('company') != null) ? Request::route('company') : null;
            $routeOffice = (Request::route('office') != null) ? Request::route('office') : null;
            $routePrintCircuitBoard = (Request::route('print_circuit_board') != null) ? Request::route('print_circuit_board') : null;
            $routeJob = (Request::route('job') != null) ? Request::route('job') : null;
            $view->with(compact('routeCompany', 'routeOffice', 'routePrintCircuitBoard', 'routeJob'));
        });
    }
}
