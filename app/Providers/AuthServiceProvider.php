<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Question::class => QuestionPolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return ($user->is_admin || $user->is_moderator);
        });

        
    }
}
