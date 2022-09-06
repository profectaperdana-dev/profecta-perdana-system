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

        Gate::define('superadmin', function (User $user) {
            return $user->job_id === 4;
        });
        Gate::define('finance', function (User $user) {
            return $user->job_id === 3;
        });
        Gate::define('verificator', function (User $user) {
            return $user->job_id === 1;
        });
        Gate::define('sales', function (User $user) {
            return $user->job_id === 5;
        });
        Gate::define('warehouse_keeper', function (User $user) {
            return $user->job_id === 6;
        });
    }
}
