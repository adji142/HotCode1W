<?php

namespace App\Providers;

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
        
        $permissions = \App\Models\Permission::where('groupapps','TRADING')->get();
        foreach($permissions as $permission) {
            Gate::define($permission->slug, function($user) use ($permission) {
                $return = false;
                foreach ($permission->role as $role) {
                    $return = $user->hasRole($role->name);
                    if($return) break; 
                }
                return $return;
            });
        }
    }
}
