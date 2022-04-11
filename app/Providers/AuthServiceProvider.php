<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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

        // Permissões de acesso ao painel administrativo
        Gate::define('admin-panel-access', function (User $user) {
            return $user->level > 1;
        });

        // Permissões de usuários sobre outros usuários
        Gate::define("user-to-user-permissions", function (User $logged, User $user) {
            return $logged->level > $user->level;
        });
    }
}
