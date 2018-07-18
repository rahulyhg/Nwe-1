<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::resolveUsersUsing(function ($guard = null) {
            if( is_null($guard) ){
                if( Auth::guard('employers')->check()) return Auth::guard('employers')->user();
                else if( Auth::guard('web')->check()) return Auth::guard('web')->user();
            }
            return Auth::guard($guard)->user();
        });

        //
    }
}
