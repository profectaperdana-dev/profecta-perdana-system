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
        Gate::define('isAdmin', function (User $user) {
            return $user->role_id === 2;
        });
        Gate::define('isSuperAdmin', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('isSalesMan', function (User $user) {
            return $user->role_id === 3;
        });
        Gate::define('isTeknisi', function (User $user) {
            return $user->role_id === 4;
        });
        Gate::define('isVerificator', function (User $user) {
            return $user->role_id === 5;
        });
        Gate::define('isWarehouseKeeper', function (User $user) {
            return $user->role_id === 6;
        });

        Gate::define('customerAuthorization', function ($user, $customer) {
            return $user->id === $customer->created_by;
        });
    }
}
