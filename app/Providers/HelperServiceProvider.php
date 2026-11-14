<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * ヘルパー関数を読み込む
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path().'/Helpers/*.php') as $filename){
            require_once($filename);
        }

        // const ディレクトリ
        foreach (glob(app_path().'/Helpers/const/*.php') as $filename){
            require_once($filename);
        }
    }
}
