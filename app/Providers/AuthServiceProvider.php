<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('level1', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('level2', function (User $user) {
            return $user->role_id === 2;
        });
        Gate::define('level3', function (User $user) {
            return $user->role_id === 3;
        });

        Gate::define('isVerificator', function (User $user) {
            return $user->job_id === 1;
        });
        Gate::define('isFinance', function (User $user) {
            return $user->job_id === 2;
        });
        Gate::define('isSuperAdmin', function (User $user) {
            return $user->job_id === 3;
        });
        Gate::define('isSales', function (User $user) {
            return $user->job_id === 4;
        });
        Gate::define('isWarehouseKeeper', function (User $user) {
            return $user->job_id === 5;
        });
    }
}
