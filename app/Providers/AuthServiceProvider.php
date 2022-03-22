<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Access\Response;


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

        Gate::define('isAdmin', function ($user) {
            return $user->level==='Admin' ? Response::allow() : Response::deny('You must be an administrator.');
        });

        Gate::define('isWFC', function ($user) {
            return $user->level=='WFC' ? Response::allow() : Response::deny('You must be a WFC.');
        });

        Gate::define('isAnalyst', function ($user) {
            return $user->level==='Analyst' || $user->level==='Swing Capacity' ? Response::allow() : Response::deny('You must be an Analyst.');
        });

        Gate::define('isTeamLead', function ($user) {
            return $user->level==='Supervisor' ? Response::allow() : Response::deny('You must be a Team Lead.');
        });

        $this->registerPolicies();

        //
    }
}
