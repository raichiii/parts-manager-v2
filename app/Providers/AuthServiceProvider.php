<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //userがSYSTEM_ADMINロールを所持していればtrue
        Gate::define('isSystemAdmin', function ($user) {
            return self::hasRole($user, SYSTEM_ADMIN);
        });

        //userがSYSTEM_USERロールを所持していればtrue
        Gate::define('isServiceUser', function ($user) {
            return self::hasRole($user, SERVICE_USER);
        });
    }

    public static function hasRole(User $user, $roleId)
    {
        return $user->roles()->where('role_id', $roleId)->exists();
    }
}
