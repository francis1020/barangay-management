<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register permissions as gates
        if (Schema::hasTable('permissions')) {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->slug, function (User $user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });
            });
        }
    }
}
