<?php

namespace App\Providers;

use Illuminate\Support\Facades\View,
    Illuminate\Support\ServiceProvider,
    App\Http\ViewSwitchers\AgentViewSwitcher;

class AgentViewSwitcherServiceProvider extends ServiceProvider
{
    /**
     * 全アプリケーションサービスの初期起動処理
     *
     * @return void
     */
    public function boot()
    {
        View::creator('*', AgentViewSwitcher::class);
    }

    /**
     * コンテナ結合の登録
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
